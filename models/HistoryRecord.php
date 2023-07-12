<?php namespace ThanhVoCSE\History\Models;

use Model;

/**
 * Model
 */
class HistoryRecord extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'thanhvo_history_record';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    protected $jsonable = ['data'];

}
