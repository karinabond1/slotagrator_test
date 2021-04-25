<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ObjectThingsOperation
 * @property int id
 * @property int operation_id
 * @property int object_id
 * @property int status
 * @package App\Models
 * @method static where(string $string, int $operation_id)
 */
class ObjectOperation extends Model
{
    use HasFactory;

    public const RECEIVE_PRICE = 1;
    public const REFUSE_PRICE = 2;

    public function operation()
    {
        return $this->belongsTo(Operation::class)->with('operation');
    }

    public static function create(int $user_id, int $prize_type_id, int $object_id)
    {
        $operation = Operation::create($user_id, $prize_type_id);
        if(!$operation)
        {
            return false;
        }
        $money = new self();
        $money->object_id = $object_id;
        $money->status = self::RECEIVE_PRICE;
        $money->operation_id = $operation->id;
        return $money->save() ? $money : false;
    }

    public static function getOperationByOperationId(int $operation_id)
    {
        return self::where('id', $operation_id)->first();
    }
}
