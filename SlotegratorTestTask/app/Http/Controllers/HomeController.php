<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Points;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    private $operationController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->operationController = new OperationController();
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $points = Points::getPointsByUserId($user->id) / 100;
        return view('home', compact('points'));
    }

    public function getPrize()
    {
        $prizeType = rand(1, 3);
        $getObject = $this->operationController->getTypeObject($prizeType);
        $user = Auth::user();

        $result = $getObject->makeOperation($user);

        $points = $result['points'] ?? Points::getPointsByUserId($user->id) / 100;

        return view('home', compact('result', 'points'));
    }




}
