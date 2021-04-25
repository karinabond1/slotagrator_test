<?php

namespace App\Http\Controllers;

use App\Contracts\IOperation;
use App\Models\Money;
use App\Models\MoneyOperation;
use App\Models\MoneyPointsOperation;
use App\Models\Operation;
use App\Models\PointOperation;
use App\Models\Points;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MoneyOperationController extends Controller implements IOperation
{


    /**
     * Make operation for type money
     *
     * @param User $user
     * @return array
     */
    public function makeOperation(User $user)
    {
        /**
         * @var Money $allAmountObject
         */
        $allAmountObject = Money::getAllMoney();
        $allAmount = $allAmountObject->all_amount ?? false;
        if(!$allAmount && $allAmount !== 0)
        {
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }
        $amountForPrize = $allAmount > 0 ? rand(100, $allAmount) : false;
        if(!$amountForPrize || $amountForPrize > $allAmount)
        {
            return [
                'error' => 'Sorry, but we do not have enough money! Please, come back later and try your luck again!'
            ];
        }

        $newAmount = $allAmount - $amountForPrize;

        DB::beginTransaction();

        $updateNewAmount = Money::updateAllMoney($allAmountObject->id, $newAmount);
        if(!$updateNewAmount)
        {
            DB::rollBack();
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }

        $operation = MoneyOperation::create($amountForPrize, $user->id, Operation::PRIZE_TYPE_MONEY);
        if(!$operation)
        {
            DB::rollBack();
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }

        $amountForPoints = $this->getConvertedAmountToPoints($amountForPrize);
        if(!$amountForPoints)
        {
            DB::rollBack();
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }

        $amountForPrize /= 100;
        $amountForPoints /= 100;

        $points = Points::getPointsByUserId($user->id) / 100;

        DB::commit();

        return [
            'amountForPrize'  => $amountForPrize,
            'amountForPoints' => $amountForPoints,
            'operationId'     => $operation->id,
            'points'          => $points,
        ];
    }

    /**
     * Operation converting money to points and add to user balance
     *
     * @param int $operationId
     * @return Application|Factory|View|JsonResponse
     */
    public function convertToPoints(int $operationId)
    {
        $moneyInfo = $this->getMoneyOperation($operationId);
        /**
         * @var MoneyOperation $moneyOperation
         */
        $moneyOperation = $moneyInfo['moneyOperation'] ?? false;
        $user = $moneyInfo['user'] ?? false;
        $points = Points::getPointsByUserId($user->id) / 100;
        if((!$moneyOperation || !$user) && isset($moneyInfo['error']))
        {
            DB::rollBack();
            $result['error'] = $moneyInfo['error'];
            return view('home', compact('result', 'points'));
        }

        $moneyOperation->status = MoneyOperation::CONVERT_IN_POINTS;
        $moneyOperation->save();

        $amountInMoney = $moneyOperation->amount ?? 0;
        if($amountInMoney === 0)
        {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later2!';
            return view('home', compact('result', 'points'));
        }

        $amountForPoints = $this->getConvertedAmountToPoints($amountInMoney);
        if(!$amountForPoints)
        {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later3!';
            return view('home', compact('result', 'points'));
        }

        $pointsOperation = PointOperation::create($amountForPoints, $user->id, Operation::PRIZE_TYPE_POINTS);
        if(!$pointsOperation)
        {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later4!';
            return view('home', compact('result', 'points'));
        }

        $moneyPointOperation = MoneyPointsOperation::create($moneyOperation->id, $pointsOperation->id);
        if(!$moneyPointOperation)
        {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later5!';
            return view('home', compact('result', 'points'));
        }

        $userAddPoints = Points::addPointsForUser($amountForPoints, $user->id);
        if(!$userAddPoints)
        {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later6!';
            return view('home', compact('result', 'points'));
        }

        DB::commit();

        $result['amountForPointsConvert'] = $amountForPoints / 100;
        $points = Points::getPointsByUserId($user->id) / 100;

        return view('home', compact('result', 'points'));
    }


    /**
     * Get converted money in points
     *
     * @param int $amount
     * @return false|float|int
     */
    public function getConvertedAmountToPoints(int $amount)
    {
        $cross_rate = Money::getCrossRate();
        if(!$cross_rate)
        {
            return false;
        }
        return round($amount * ($cross_rate / 100), 2);
    }


    /**
     * Make transaction to bank
     *
     * @param int $operationId
     * @return array|Application|Factory|View
     */
    public function makeTransactionToBank(int $operationId)
    {
        $result = [
            'send' => false
        ];

        $moneyInfo = $this->getMoneyOperation($operationId);
        /**
         * @var MoneyOperation $moneyOperation
         */
        $moneyOperation = $moneyInfo['moneyOperation'] ?? false;
        $user = $moneyInfo['user'] ?? false;
        $points = Points::getPointsByUserId($user->id) / 100;
        if(!$moneyOperation || !$user)
        {
            DB::rollBack();
            $result['error'] = $moneyInfo['error'] ?? 'Something went wrong. Please, try again later!';
            return view('home', compact('result', 'points'));
        }
        $moneyOperation->status = MoneyOperation::NEED_SEND_TO_BANK_CARD;
        $operationSave = $moneyOperation->save();

        if(!$operationSave)
        {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later!';
            return view('home', compact('result', 'points'));
        }

        DB::commit();
        $result['send'] = true;

        return view('home', compact('result', 'points'));
    }


    /**
     * Find money operation and check user
     *
     * @param int $operationId
     * @return array|JsonResponse
     */
    private function getMoneyOperation(int $operationId)
    {
        if(!$operationId)
        {
            return [
                'error' => 'There is no such operation!'
            ];
        }

        /**
         * @var User $user
         */
        $user = Auth::user();
        if(!$user)
        {
            return [
                'error' => 'You need to log in or register!'
            ];
        }

        DB::beginTransaction();

        /**
         * @var MoneyOperation $moneyOperation
         */
        $moneyOperation = MoneyOperation::getOperationByOperationId($operationId);
        if(!$moneyOperation)
        {
            DB::rollBack();
            return [
                'error' => 'There is no such operation!'
            ];
        }

        return [
            'moneyOperation' => $moneyOperation,
            'user' => $user
        ];
    }
}
