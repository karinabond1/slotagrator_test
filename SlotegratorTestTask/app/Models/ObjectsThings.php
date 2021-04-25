<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ObjectThings
 * @property int id
 * @property string object_name
 * @property int status
 * @package App\Models
 * @method static where(string $string, int $STATUS_FREE)
 */
class ObjectsThings extends Model
{
    use HasFactory;

    /**
     * Object has status free
     */
    public const STATUS_FREE = 1;
    /**
     * Object has status get
     */
    public const STATUS_GET = 2;

    public static function getAllFreeObjects()
    {
        return self::where('status', self::STATUS_FREE)->get() ?: false;
    }

    public static function getObjectById(int $id)
    {
        return self::where('id', $id)->first();
    }

}
