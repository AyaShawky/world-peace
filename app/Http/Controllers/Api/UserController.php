<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\changeRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\resetRequest;
use App\Http\Requests\User\updateRequest;
use App\Models\User;
use App\Reposotry\Classes\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{


    protected $user;
    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }

    public function index()
    {
        return $this->user->getAll();
    }

    public function show($id)
    {
        return $this->user->getById($id);
    }

    public function putUser(Request $request)
    {
        return $this->user->update($request->all());
    }

    public function destroyUser(Request $request)
    {
        return $this->user->delete($request);

    }
    // public function postUser(Request $request)
    // {
    //     return $this->user->create($request->all());
    // }

    public function login()
    {
        return $this->user->login();
    }
    public function register(RegisterRequest $request){
        return $this->user->register($request->all());
    }

    public function getUser(){
        return $this->user->getUser();
    }

    public function logout(Request $request){
        return $this->user->logout($request->all());

    }

    public function Join(Request $request){
        return $this->user->Join($request->all());
    }

    public function getfriend(){
        return $this->user->getfriend();
    }

    public function deleteFriend($id)
    {
        return $this->user->deleteFriend($id);

    }

    public function changePassword(changeRequest $request)
    {
        return $this->user->changePassword($request->all());
    }

    public function search(Request $request){
        return $this->user->search($request->all());
    }
    
}
