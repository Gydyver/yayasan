<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginManualController extends Controller
{
    public function __construct()
    {
        session_start();
    }

    public function show()
    {
        return view('auth.login-new');
    }

    public function notAllowed()
    {
        return view('auth.notAllowed');
    }

    public function login(Request $request)
    {
        $username = \VCHelper::instance()->VigenereEncrypt($request->username);
        $password = \VCHelper::instance()->VigenereEncrypt($request->password);
        $search = User::where('username', $username)->where('password', $password)->whereNull('deleted_at')->get();
        // dd($search);
        if (isset($search[0])) {
            // $password_inputed = hash('sha256', $request->password . $search[0]->salt);
            // $password_existing = $search[0]->password;

            // if ($password_existing == $password_inputed) {

            $_SESSION["data"] = (object) [
                'id' => $search[0]->id,
                'name' => $search[0]->name,
                'usergroup_id' => $search[0]->usergroup_id,
                "chapter_id" => $search[0]->chapter_id,
                "class_id" => $search[0]->class_id,
                "phone" => $search[0]->phone,
                "username" => $search[0]->username,
                "password" => $search[0]->password,
                "monthly_fee" => $search[0]->monthly_fee,
                "gender" => $search[0]->gender,
                "birth_date" => $search[0]->birth_date,
                "join_date" => $search[0]->join_date,
                "remember_token" => $search[0]->remember_token,
                "deleted_at" => $search[0]->deleted_at,
                "created_at" => $search[0]->created_at,
                "updated_at" => $search[0]->updated_at,
                "latest_hapalan" => $search[0]->latest_hapalan,
                "latest_halaman" => $search[0]->latest_halaman
            ];
            return true;
        } else {
            unset($_SESSION['data']);
            session_destroy();
            return false;
        }
    }
}
