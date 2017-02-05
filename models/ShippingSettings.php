<?php namespace Bedard\Shop\Models;

use Flash;
use Lang;
use Model;
use October\Rain\Exception\ValidationException;

/**
 * Cart Settings Model.
 */
class ShippingSettings extends Model
{
    /**
     * @var array   Behaviors
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string  Settings code
     */
    public $settingsCode = 'bedard_shop_settings_shipping';

    /**
     * @var string  Settings fields
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->validateDrivers(self::get('enabled_drivers'));
    }

    /**
     * Ensure that only one driver is enabled.
     *
     * @param   array|null                              $drivers
     * @throws  \October\Rain\Database\ModelException
     * @return  void
     */
    protected function validateDrivers(array $drivers = null)
    {
        if (! $drivers || ! is_array($drivers)) {
            Flash::error(Lang::get('bedard.shop::lang.shipping.form.validation_none'));
            throw new ValidationException(null);
        }

        // @todo: allow more than one shipping driver to be enabled
        if (count($drivers) !== 1) {
            Flash::error(Lang::get('bedard.shop::lang.shipping.form.validation_multiple'));
            throw new ValidationException(null);
        }
    }
}
