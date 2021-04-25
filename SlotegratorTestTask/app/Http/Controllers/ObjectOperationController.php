<?php

namespace App\Http\Controllers;

use App\Contracts\IOperation;
use App\Models\ObjectsThings;
use App\Models\ObjectOperation;
use App\Models\Operation;
use App\Models\Points;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ObjectOperationController extends Controller implements IOperation
{

    /**
     * Find money operation and check user
     *
     * @param User $user
     * @return string[]
     */
    public function makeOperation(User $user)
    {
        $getAllFreeObjects = ObjectsThings::getAllFreeObjects();
        if(!$getAllFreeObjects || count($getAllFreeObjects) === 0)
        {
            return [
                'error' => 'Unfortunately, no prizes is left.'
            ];
        }
        /**
         * @var ObjectsThings $randomObject
         */
        $randomObjectNum = rand(1, count($getAllFreeObjects));
        if(!$randomObjectNum)
        {
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }


        DB::beginTransaction();

        $randomObject = $getAllFreeObjects[$randomObjectNum - 1];

        $randomObject->status = ObjectsThings::STATUS_GET;
        $randomObjectSave = $randomObject->save();
        if(!$randomObjectSave)
        {
            DB::rollBack();
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }

        $objectOperation = ObjectOperation::create($user->id, Operation::PRIZE_TYPE_OBJECT, $randomObject->id);
        if(!$objectOperation)
        {
            DB::rollBack();
            return [
                'error' => 'Something went wrong. Please, try again later!'
            ];
        }

        DB::commit();

        $result['operationId'] = $objectOperation->id;
        $result['name'] = $randomObject->object_name;
        $result['points'] = Points::getPointsByUserId($user->id) / 100;

        return $result;
    }


    /**
     * User refuse object
     *
     * @param int $operationId
     * @return Application|Factory|View|JsonResponse
     */
    public function refuseObject(int $operationId)
    {
        $points = Points::getPointsByUserId(Auth::id()) / 100;
        /**
         * @var ObjectOperation $operation
         */
        $operation = ObjectOperation::getOperationByOperationId($operationId);
        if(!$operation)
        {
            $result['error'] = 'Something went wrong. Please, try again later!';
            return view('home', compact('result', 'points'));
        }

        DB::beginTransaction();

        $operation->status = ObjectOperation::REFUSE_PRICE;
        $operationSave = $operation->save();
        if(!$operationSave) {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later!';
            return view('home', compact('result', 'points'));
        }

        /**
         * @var ObjectsThings $object
         */
        $object = ObjectsThings::getObjectById($operation->object_id);
        if(!$object)
        {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later!';
            return view('home', compact('result', 'points'));
        }

        $object->status = ObjectsThings::STATUS_FREE;
        $randomObjectSave = $object->save();
        if(!$randomObjectSave)
        {
            DB::rollBack();
            $result['error'] = 'Something went wrong. Please, try again later!';
            return view('home', compact('result', 'points'));
        }

        $result['refuse'] = true;
        DB::commit();

        return view('home', compact('result', 'points'));
    }


    /**
     * Send object to user
     *
     * @return Application|Factory|View|JsonResponse
     */
    public function sendObjectToUser()
    {
        $result['send_object'] = true;

        $points = Points::getPointsByUserId(Auth::id()) / 100;

        return view('home', compact('result', 'points'));
    }
}
