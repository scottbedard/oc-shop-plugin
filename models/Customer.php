<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Customer Model.
 */
class Customer extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_customers';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'email',
        'name',
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:bedard_shop_customers',
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'addresses' => [
            'Bedard\Shop\Models\Address',
        ],
    ];

    public $belongsTo = [
        'user' => [
            'RainLab\User\Models\User',
        ],
    ];
}
