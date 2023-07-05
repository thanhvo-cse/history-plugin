<?php namespace ThanhVo\History;

use Backend;
use ThanhVo\History\Behaviors\HistoryModel;
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
            'description' => 'No description provided yet...',
            'author' => 'ThanhVo',
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
            $model = $form->getModel();
            if (is_subclass_of($model, \Model::class) && $model->isClassExtendedWith(HistoryModel::class)) {
                $model->addDynamicMethod('getHistoryLabel', function($fieldName) use ($model, $form) {
                    return $form->getField($fieldName)->label;
                });
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
        return []; // Remove this line to activate
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate
    }
}
