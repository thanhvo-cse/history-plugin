<?php namespace ThanhVoCSE\History;

use Backend;
use Backend\Behaviors\RelationController;
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
            'description' => 'No description provided yet...',
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
                    $model->addDynamicMethod('getHistoryLabel', function ($fieldName) use ($model, $form) {
                        return $form->getField($fieldName)->label;
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
        return []; // Remove this line to activate
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
}
