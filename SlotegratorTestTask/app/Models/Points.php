<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Points
 * @property int id
 * @property int amount
 * @property int user_id
 * @package App\Models
 */
class Points extends Model
{
    use HasFactory;

    public static function create(int $amount, int $userId)
    {
        $points = new self();
        $points->amount = $amount;
        $points->user_id = $userId;
        return $points->save() ? $points : false;
    }

    public static function addPointsForUser(int $amount, int $userId)
    {
        /**
         * @var Points $points
         */
        $points = self::where('user_id', $userId)->first();

        if($points)
        {
            $points = self::where('user_id', $userId)->update([
                'amount' => $points->amount + $amount
            ]);
            return $points ? true : false;
        }

        $points = self::create($amount, $userId);
        return $points ? true : false;
    }


    public static function getPointsByUserId(int $userId)
    {
        $points = self::where('user_id', $userId)->first();
        if(!$points)
        {
            $points = self::create(0, $userId);
        }
        return $points->amount ?? 0;
    }

}
