<?php namespace Bedard\Shop\Classes;

use Backend\Classes\Controller as BaseController;

class BackendController extends BaseController
{
    use \Bedard\Shop\Traits\LangJsonable;

    public function __construct()
    {
        parent::__construct();

        $this->addJs('/plugins/bedard/shop/assets/dist/vendor.js');
    }
}
