<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MoneyOperation
 * @property int id
 * @property int amount
 * @property int status
 * @property int operation_id
 * @package App\Models
 * @method static where(string $string, int $operation_id)
 */
class MoneyOperation extends Model
{
    use HasFactory;

    public const TO_BANK_CARD = 1;
    public const CONVERT_IN_POINTS = 2;
    public const NEED_SEND_TO_BANK_CARD = 3;
    public const SENT_TO_BANK_CARD = 4;

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
        $money->status = self::TO_BANK_CARD;
        $money->operation_id = $operation->id;
        return $money->save() ? $money : false;
    }

    public static function getOperationByOperationId(int $operation_id)
    {
        return self::where('id', $operation_id)->first();
    }

    public static function getAllOperationThatNeedToSend()
    {
        return self::where('status', self::NEED_SEND_TO_BANK_CARD)->get();
    }
}
