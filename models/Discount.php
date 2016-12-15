<?php namespace Bedard\Shop\Models;

use Model;
use Queue;
use Carbon\Carbon;

/**
 * Discount Model.
 */
class Discount extends Model
{
    use \Bedard\Shop\Traits\Amountable,
        \Bedard\Shop\Traits\StartEndable,
        \Bedard\Shop\Traits\Subqueryable,
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
     * @var array Attribute casting
     */
    public $casts = [
        'amount' => 'float',
        'is_percentage' => 'boolean',
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
        'end_at',
        'is_percentage',
        'name',
        'start_at',
    ];

    /**
     * @var array Purgeable vields
     */
    protected $purgeable = [];

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
        $productIds = $this->products()->lists('id');
        $categories = $this->categories()
            ->with(['products' => function ($product) {
                return $product->select('id');
            }])
            ->get(['id']);

        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                $productIds[] = $product->id;
            }
        }

        return array_unique($productIds);
    }

    /**
     * Get categories options.
     *
     * @return array
     */
    public function getCategoriesOptions()
    {
        return Category::lists('name', 'id');
    }

    /**
     * Get products options.
     *
     * @return array
     */
    public function getProductsOptions()
    {
        return Product::lists('name', 'id');
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
                    Price::create(Discount::buildPriceArray($discount, $product));
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
                $data[] = self::buildPriceArray($discount, $product);
            }
        }

        Price::whereNotNull('discount_id')->delete();
        Price::insert($data);
    }

    /**
     * Sync the prices of a single product.
     *
     * @param  Product $product
     * @param  array   $categoryIds
     * @return void
     */
    public static function syncProductPrice(Product $product, array $categoryIds)
    {
        $discounts = self::isNotExpired()
            ->whereHas('products', function ($query) use ($product) {
                return $query->where('id', $product->id);
            })
            ->orWhereHas('categories', function ($query) use ($categoryIds) {
                return $query->whereIn('id', $categoryIds);
            })
            ->get();

        $data = [];
        foreach ($discounts as $discount) {
            $data[] = self::buildPriceArray($discount, $product);
        }

        Price::whereProductId($product->id)->whereNotNull('discount_id')->delete();
        Price::insert($data);
    }

    /**
     * Build the array needed to insert price models.
     *
     * @param  \Bedard\Shop\Models\Discount $discount
     * @param  \Bedard\Shop\Models\Product  $product
     * @return array
     */
    public static function buildPriceArray(Discount $discount, Product $product)
    {
        return [
            'discount_id' => $discount->id,
            'end_at' => $discount->end_at,
            'price' => $discount->calculatePrice($product->base_price),
            'product_id' => $product->id,
            'start_at' => $discount->start_at,
        ];
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
}
