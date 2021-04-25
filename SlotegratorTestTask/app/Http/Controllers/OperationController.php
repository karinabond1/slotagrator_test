<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{

    /**
     * Get type object
     *
     * @param int $type
     * @return MoneyOperationController|ObjectOperationController|PointOperationController|false
     */
    public function getTypeObject(int $type)
    {
        switch ($type)
        {
            case Operation::PRIZE_TYPE_MONEY:
                return new MoneyOperationController();

            case Operation::PRIZE_TYPE_OBJECT:
                return new ObjectOperationController();

            case Operation::PRIZE_TYPE_POINTS:
                return new PointOperationController();

        }
        return false;
    }
}
