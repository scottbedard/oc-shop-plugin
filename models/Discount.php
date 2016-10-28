<?php namespace Bedard\Shop\Models;

use Carbon\Carbon;
use Flash;
use Lang;
use Model;
use October\Rain\Database\ModelException;
use Queue;

/**
 * Discount Model.
 */
class Discount extends Model
{
    use \Bedard\Shop\Traits\Subqueryable,
        \Bedard\Shop\Traits\Timeable,
        \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_discounts';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'amount' => 0,
        'is_percentage' => true,
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'amount',
        'amount_exact',
        'amount_percentage',
        'end_at',
        'is_percentage',
        'name',
        'start_at',
    ];

    /**
     * @var array Purgeable vields
     */
    protected $purgeable = [
        'amount_exact',
        'amount_percentage',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'categories' => [
            'Bedard\Shop\Models\Category',
            'table' => 'bedard_shop_category_discount',
        ],
        'products' => [
            'Bedard\Shop\Models\Product',
            'table' => 'bedard_shop_discount_product',
        ],
    ];

    public $hasMany = [
        'prices' => [
            'Bedard\Shop\Models\Price',
            'delete' => true,
        ],
    ];

    /**
     * @var  array Validation rules
     */
    public $rules = [
        'end_at' => 'date',
        'name' => 'required',
        'start_at' => 'date',
        'amount_exact' => 'numeric|min:0',
        'amount_percentage' => 'integer|min:0',
    ];

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->savePrices();
    }

    /**
     * After validate.
     *
     * @return void
     */
    public function afterValidate()
    {
        $this->validateDates();
    }

    /**
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->setAmount();
    }

    /**
     * Calculate the discounted price.
     *
     * @param  float $basePrice
     * @return float
     */
    public function calculatePrice($basePrice)
    {
        $value = $this->is_percentage
            ? $basePrice - ($basePrice * ($this->amount / 100))
            : $basePrice - $this->amount;

        if ($value < 0) {
            $value = 0;
        }

        return round($value, 2);
    }

    /**
     * Filter form fields.
     *
     * @param  object   $fields
     * @return void
     */
    public function filterFields($fields)
    {
        $fields->amount_exact->hidden = $this->is_percentage;
        $fields->amount_percentage->hidden = ! $this->is_percentage;
    }

    /**
     * Fetch all of the products within the scope of this discount.
     *
     * @return array
     */
    public function getAllProductIds()
    {
        $categoryProductIds = $this->categories()
            ->select('id')
            ->with(['products' => function ($products) {
                return $products->select('id');
            }])
            ->lists('categories.products.id');

        $productIds = $this->products()->lists('id');

        return array_unique(array_merge($productIds, $categoryProductIds));
    }

    /**
     * Save the prices created by this discount.
     *
     * @return void
     */
    public function savePrices()
    {
        $id = $this->id;
        Queue::push(function ($job) use ($id) {
            $discount = Discount::findOrFail($id);
            $productIds = $discount->getAllProductIds();
            $products = Product::whereIn('id', $productIds)->select('id', 'base_price')->get();
            $discount->prices()->delete();

            foreach ($productIds as $productId) {
                $product = $products->find($productId);
                if ($product) {
                    Price::create([
                        'discount_id' => $discount->id,
                        'end_at' => $discount->end_at,
                        'price' => $discount->calculatePrice($product->base_price),
                        'product_id' => $productId,
                        'start_at' => $discount->start_at,
                    ]);
                }
            }

            $job->delete();
        });
    }

    /**
     * Sync the prices of all non-expired discounts.
     *
     * @return void
     */
    public static function syncAllPrices()
    {
        $data = [];
        $discounts = self::isNotExpired()->with('categories.products', 'products')->get();
        foreach ($discounts as $discount) {
            $products = $discount->products;
            foreach ($discount->categories as $category) {
                $products = $products->merge($category->products);
            }

            foreach ($products as $product) {
                $data[] = [
                    'discount_id' => $discount->id,
                    'end_at' => $discount->end_at,
                    'price' => $discount->calculatePrice($product->base_price),
                    'product_id' => $product->id,
                    'start_at' => $discount->start_at,
                ];
            }
        }

        Price::whereNotNull('discount_id')->delete();
        Price::insert($data);
    }

    /**
     * This exists to makes statuses sortable by assigning them a value.
     *
     * Expired  0
     * Running  1
     * Upcoming 2
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeSelectStatus($query)
    {
        $grammar = $query->getQuery()->getGrammar();
        $start_at = $grammar->wrap($this->table.'.start_at');
        $end_at = $grammar->wrap($this->table.'.end_at');
        $now = Carbon::now();

        $subquery = 'CASE '.
            "WHEN ({$end_at} IS NOT NULL AND {$end_at} < '{$now}') THEN 0 ".
            "WHEN ({$start_at} IS NOT NULL AND {$start_at} > '{$now}') THEN 2 ".
            'ELSE 1 '.
        'END';

        return $query->selectSubquery($subquery, 'status');
    }

    /**
     * Set the discount amount.
     *
     * @return  void
     */
    public function setAmount()
    {
        $exact = $this->getOriginalPurgeValue('amount_exact');
        $percentage = $this->getOriginalPurgeValue('amount_percentage');

        $this->amount = $this->is_percentage
            ? $percentage
            : $exact;
    }

    /**
     * Ensure the start and end dates are valid.
     *
     * @return void
     */
    public function validateDates()
    {
        // Start date must be after the end date
        if ($this->start_at !== null &&
            $this->end_at !== null &&
            $this->start_at >= $this->end_at) {
            Flash::error(Lang::get('bedard.shop::lang.discounts.form.start_at_invalid'));
            throw new ModelException($this);
        }
    }
}
