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
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'token',
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'cartItems' => [
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
     * Generate a unique token.
     *
     * @return void
     */
    protected function generateToken()
    {
        do {
            $this->token = str_random(40);
        } while (self::whereToken($this->token)->exists());
    }
}
