<?php namespace ThanhVoCSE\History\Models;

use Model;

/**
 * Model
 */
class HistorySetting extends Model
{
    /**
     * @var array Behaviors implemented by this model.
     */
    public $implement = [
        \System\Behaviors\SettingsModel::class
    ];

    public $settingsCode = 'thanhvocse_history_settings';

    public $settingsFields = 'fields.yaml';

}
