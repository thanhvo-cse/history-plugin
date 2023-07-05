<?php

namespace ThanhVoCSE\History\Behaviors;

use ThanhVoCSE\History\Models\HistoryRecord;
use October\Rain\Extension\ExtensionBase;
use BackendAuth;

class HistoryModel extends ExtensionBase
{

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;

        $this->model->hasMany += [
            'histories' => [HistoryRecord::class, 'key' => 'entity_id', 'otherKey' => 'id']
        ];

        $this->model->bindEvent('model.afterSave', function() {
            $changes = $this->getChanges();
            if ($this->model->wasRecentlyCreated || !empty($changes)) {
                $entity = $this->model->getTable();
                $entityId = $this->model->id;
                $user = BackendAuth::getUser();
                $revision = (int) HistoryRecord::where('entity', $entity)
                    ->where('entity_id', $entityId)
                    ->max('revision');

                $historyRecord = new HistoryRecord();
                $historyRecord->entity = $entity;
                $historyRecord->entity_id = $entityId;
                $historyRecord->revision = ++$revision;
                $historyRecord->data = $changes;
                $historyRecord->user_id = $user->id;
                $historyRecord->user_first_name = $user->first_name;
                $historyRecord->user_last_name = $user->last_name;
                $historyRecord->save();
            }
        });
    }

    /**
     * @return array
     */
    private function getChanges(): array
    {
        $result = [];
        $changes = $this->model->getChanges();
        unset($changes['updated_at']);

        foreach ($changes as $key => $value) {
            $result[$key] = [
                $this->model->getOriginal($key),
                $value,
            ];
        }

        return $result;
    }

}
