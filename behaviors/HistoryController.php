<?php

namespace ThanhVoCSE\History\Behaviors;

use Backend\Behaviors\RelationController;
use Backend\Classes\ControllerBehavior;
use Backend\Behaviors\FormController;
use ThanhVoCSE\History\Models\HistoryRecord;
use October\Rain\Extension\ExtensionBase;
use BackendAuth;

class HistoryController extends ControllerBehavior
{
    /**
     * @var Controller controller is a reference to the extended object.
     */
    protected $controller;

    /**
     * __construct
     */
    public function __construct($controller)
    {
        $this->controller = $controller;

        if ($controller->isClassExtendedWith(RelationController::class)) {
            $relation = $controller->asExtension(RelationController::class);
            $historiesConfig = $relation->getConfig('histories');
            $historiesConfig['view']['list'] = '$/thanhvocse/history/models/historyrecord/columns.yaml';
            $relation->config->histories = array_replace(
                [
                    'label' => 'History',
                    'readOnly' => 'true',
                    'view' => [
                        'list' =>  '$/thanhvocse/history/models/historyrecord/columns.yaml',
                        'recordsPerPage' => 10,
                        'defaultSort' => [
                            'column' => 'revision',
                            'direction' => 'desc',
                        ]
                    ],
                ],
                $historiesConfig);
        }
    }

    /**
     * @param null $recordId
     * @param null $context
     * @return mixed
     */
    public function update_onSave($recordId = null, $context = null)
    {
        $this->controller->asExtension(FormController::class)->update_onSave($recordId, $context);

        return $this->controller->relationRefresh('histories');
    }
}
