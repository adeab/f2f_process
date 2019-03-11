<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Http\Resources\User as UserResource;

class PagesController extends Controller
{
    public function user_api(){
        //Get User List
        $users= User::paginate(10);
        
        //Return User collection
        return UserResource::collection($users);

        //return view('single');
    }
    public function single_peer(){
        return view('single');
    }

    public function multi(){
        return view('multi');
    }
}
