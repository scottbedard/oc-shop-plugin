<?php namespace Bedard\Shop\Classes;

use Backend\Classes\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->addJs('/plugins/bedard/shop/assets/dist/vendor.js');
        $this->addJs('/plugins/bedard/shop/assets/dist/main.js');
    }
}
