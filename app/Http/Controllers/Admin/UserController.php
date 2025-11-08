<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;

class UserController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function index()
    {
        $all = $this->users->listAll();
        return view('users', ['users' => $all]);
    }
}



