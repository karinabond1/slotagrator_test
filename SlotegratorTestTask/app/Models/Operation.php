<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Operation
 * @property int id
 * @property int prize_type_id
 * @property int user_id
 * @package App\Models
 */
class Operation extends Model
{
    use HasFactory;

    /**
     * Prize has type - money
     */
    public const PRIZE_TYPE_MONEY = 1;
    /**
     * Prize has type - object
     */
    public const PRIZE_TYPE_OBJECT = 2;
    /**
     * Prize has type - points
     */
    public const PRIZE_TYPE_POINTS = 3;

    /**
     * Prize names
     */
    public const PRIZE_TYPES_NAME = [
        self::PRIZE_TYPE_MONEY  => 'money',
        self::PRIZE_TYPE_OBJECT => 'object',
        self::PRIZE_TYPE_POINTS => 'points',
    ];

    public function moneyOperation()
    {
        return $this->hasOne(MoneyOperation::class);
    }

    public function objectOperation()
    {
        return $this->hasOne(ObjectOperation::class);
    }

    public function pointOperation()
    {
        return $this->hasOne(PointOperation::class);
    }


    /**
     * @param int $user_id
     * @param int $prize_type_id
     * @return Operation|false
     */
    public static function create(int $user_id, int $prize_type_id)
    {
        $operation = new self();
        $operation->user_id = $user_id;
        $operation->prize_type_id = $prize_type_id;
        return $operation->save() ? $operation : false;
    }
}
