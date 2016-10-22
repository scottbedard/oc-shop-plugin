<?php namespace Bedard\Shop\Classes;

use Backend\Classes\Controller as BaseController;
use Lang;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->addJs('/plugins/bedard/shop/assets/dist/vendor.js');
        $this->addJs('/plugins/bedard/shop/assets/dist/main.js');
    }

    /**
     * Get a set of language strings and convert them to JSON.
     *
     * @param  array    $keys
     * @return string
     */
    public function getLangJson($keys)
    {
        $lang = [];

        foreach ($keys as $key => $value) {
            $isFiltered = gettype($value) === 'array';

            if (!$isFiltered) {
                $key = $value;
            }

            $lang[$key] = $isFiltered
                ? array_intersect_key(Lang::get($key), array_flip($value))
                : Lang::get($key);
        }

        return json_encode($lang);
    }
}
