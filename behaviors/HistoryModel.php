<?php

namespace ThanhVoCSE\History\Behaviors;

use ThanhVoCSE\History\Models\HistoryRecord;
use ThanhVoCSE\History\Models\HistorySetting;
use October\Rain\Extension\ExtensionBase;
use BackendAuth;
use Illuminate\Support\Str;

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

                // Rotate history records
                $rotation = HistorySetting::get('history_rotation', 0);
                if ($rotation > 0) {
                    HistoryRecord::where('entity', $entity)
                        ->where('entity_id', $entityId)
                        ->where('revision', '<=', $revision - $rotation)
                        ->delete();
                }
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

        foreach ($changes as $key => $newValue) {
            $origin = $this->model->getOriginal($key);

            // Adjust float numbers comparison
            $attribute = $this->model->getAttribute($key);
            if (is_float($attribute) && bccomp($origin, $newValue, 3) == 0) {
                break;
            }

            $result[$key] = [
                $this->model->getOptionLabel($key, $origin),
                $this->model->getOptionLabel($key, $newValue),
            ];
        }

        return $result;
    }

}
