<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Cart Model.
 */
class Cart extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_carts';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'id' => null,
        'created_at' => null,
        'item_count' => 0,
        'item_total' => 0,
        'token' => null,
        'updated_at' => null,
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'item_count' => 'integer',
        'item_total' => 'float',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'items' => [
            'Bedard\Shop\Models\CartItem',
        ],
    ];

    /**
     * Before create.
     *
     * @return void
     */
    public function beforeCreate()
    {
        $this->generateToken();
    }

    /**
     * Generate a random token.
     *
     * @return void
     */
    protected function generateToken()
    {
        $this->token = str_random(40);
    }
}
