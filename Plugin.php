<?php namespace ThanhVoCSE\History;

use Backend;
use Backend\Behaviors\RelationController;
use Illuminate\Support\Str;
use ThanhVoCSE\History\Behaviors\HistoryController;
use ThanhVoCSE\History\Behaviors\HistoryModel;
use System\Classes\PluginBase;
use Event;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'History',
            'description' => 'Model history for OctoberCMS',
            'author' => 'ThanhVoCSE',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        //
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        Event::listen('backend.form.extendFields', function ($form) {
            if (!$form->isNested) {
                $model = $form->model;
                $controller = $form->getController();
                if ($controller->isClassExtendedWith(RelationController::class)
                    && $controller->isClassExtendedWith(HistoryController::class)) {
                    $model->extendClassWith(HistoryModel::class);

                    $options = [];
                    foreach ($form->getFields() as $key => $field) {
                        $options[$key] = $field->options();
                    }

                    $model->addDynamicMethod('getOptionLabel', function ($fieldName, $value) use ($options) {
                        return __($options[$fieldName][$value] ?? $value);
                    });

                    $model->addDynamicMethod('getHistoryLabel', function ($fieldName) use ($model, $form) {
                        return __($form->getField($fieldName)->label ?? $fieldName);
                    });
                }
            }
        });
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return [
            'thanhvocse.history.access' => [
                'label' => 'Access model history records',
                'tab' => 'Model History',
                'order' => 100,
            ],
            'thanhvocse.history.configure' => [
                'label' => 'Configure model history function',
                'tab' => 'Model History',
                'order' => 200,
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate
    }

    /**
     * registerPageSnippets used by the backend.
     */
    public function registerPageSnippets()
    {
        return []; // Remove this line to activate
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'Model History Settings',
                'description' => 'Manage model history settings.',
                'category'    => \System\Classes\SettingsManager::CATEGORY_BACKEND,
                'icon' => 'icon-history',
                'class' => \ThanhVoCSE\History\Models\HistorySetting::class,
                'permissions' => ['thanhvocse.history.configure']
            ]
        ];
    }
}
