<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Admin;
use App\Http\Resources\Admin as AdminResource;
use App\Http\Controllers\BaseController as BaseController;

class AdminController extends Basecontroller
{
    public function alogin(Request $request){
        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){;

            $authUser = Auth::guard('admin')->user();
            $success["token"] = $authUser->createToken("MyAuthApp", ['admin'])->plainTextToken;
            $success["name"] = $authUser->name;
            return $this->sendResponse($success, "Sikeres bejelentkezés.");
        }
        else 
        {
          return $this->sendError("Unauthorizd.".["error" => "Hibás adatok"], 401);
        }

    }

    public function aregister(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            "name" => "required",
            "email" => "required",
            "password" => "required",
            "confirm_password" => "required|same:password"
        ]);

        if($validator->fails()) 
        {
            return $this->sendError("Error validation", $validator->errors() );
        }

        $input = $request->all();
        $input["password"] = bcrypt($input["password"]);
        $user = Admin::create($input);
        $success ["name"] = $user->name;
        return $this->sendResponse($success, "Sikeres regisztráció.");

    }
}
