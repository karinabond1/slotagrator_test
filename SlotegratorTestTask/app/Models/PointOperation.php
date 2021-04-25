<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PointOperation
 * @property int id
 * @property int amount
 * @property int operation_id
 * @package App\Models
 */
class PointOperation extends Model
{
    use HasFactory;

    public function operation()
    {
        return $this->belongsTo(Operation::class)->with('operation');
    }


    public static function create(int $amount, int $user_id, int $prize_type_id)
    {
        $operation = Operation::create($user_id, $prize_type_id);
        if(!$operation)
        {
            return false;
        }
        $money = new self();
        $money->amount = $amount;
        $money->operation_id = $operation->id;
        return $money->save() ? $money : false;
    }
}
