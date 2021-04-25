<?php

namespace App\Http\Controllers;

use App\Contracts\IOperation;
use App\Models\Operation;
use App\Models\PointOperation;
use App\Models\Points;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PointOperationController extends Controller implements IOperation
{

    /**
     * Make operation for type points
     *
     * @param User $user
     * @return string[]
     */
    public function makeOperation(User $user)
    {
        $points = rand(100, 10000);

        DB::beginTransaction();

        $pointsOperation = PointOperation::create($points, $user->id, Operation::PRIZE_TYPE_POINTS);
        if(!$pointsOperation)
        {
            DB::rollBack();
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }

        $userAddPoints = Points::addPointsForUser($points, $user->id);
        if(!$userAddPoints)
        {
            DB::rollBack();
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }
        DB::commit();
        $result['add'] = true;
        $result['points_earn'] = $points / 100;
        $result['points'] = Points::getPointsByUserId($user->id) / 100;

        return $result;
    }
}
