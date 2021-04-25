<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Money
 * @property int id
 * @property int all_amount
 * @property int rate_money_points
 * @package App\Models
 * @method static first()
 * @method static where(string $string, int $moneyId)
 */
class Money extends Model
{
    use HasFactory;

    /**
     * @return mixed
     */
    public static function getAllMoney()
    {
        return self::first();
    }

    /**
     * @return false
     */
    public static function getCrossRate()
    {
        return self::first()->rate_money_points ?: false;
    }

    /**
     * @param int $moneyId
     * @param int $money
     * @return mixed
     */
    public static function updateAllMoney(int $moneyId,int $money)
    {
        return self::where('id', $moneyId)->update([
            'all_amount' => $money
        ]);
    }
}
