<?php

namespace App\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as User;

interface IOperation
{
    public function makeOperation(User $user);
}
