<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MoneyPointsOperation
 * @property int id
 * @property int money_operation_id
 * @property int point_operation_id
 * @package App\Models
 */
class MoneyPointsOperation extends Model
{
    use HasFactory;

    public static function create(int $moneyOperationId, int $pointOperationId)
    {
        $moneyPointOperation = new self();
        $moneyPointOperation->money_operation_id = $moneyOperationId;
        $moneyPointOperation->point_operation_id = $pointOperationId;
        return $moneyPointOperation->save() ? $moneyPointOperation : false;
    }
}
