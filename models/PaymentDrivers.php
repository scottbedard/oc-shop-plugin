<?php namespace Bedard\Shop\Models;

use Bedard\Shop\Models\DriverConfig;
use Model;

/**
 * Payment Driverz Model.
 */
class PaymentDrivers extends Model
{
    /**
     * @var array   Behaviors
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string  Settings code
     */
    public $settingsCode = 'bedard_shop_payment_drivers';

    /**
     * @var string  Settings fields
     */
    public $settingsFields = 'fields.yaml';

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->saveDriverConfigs();
    }

    /**
     * Save driver configs.
     *
     * @return void
     */
    protected function saveDriverConfigs()
    {
        $drivers = json_decode(self::get('payment'), true);

        foreach ($drivers as $driver) {
            $config = DriverConfig::firstOrNew(['class' => $driver['class']]);
            $config->is_enabled = $driver['isEnabled'];
            $config->save();
        }
    }
}
