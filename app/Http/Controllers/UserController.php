<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Validator;
use Auth;
use App\Models\User;
use App\Common\Response;

class UserController extends Controller
{
    /**
     * Get Single User
     *
     * @param  integer $user_id
     * @return User::class
     */
    public function getSingleUser($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            return Response::success($user);
        } catch (\Exception $e) {
            return Response::error('There is no user with that id.');
        }
    }

    /**
     * Get all registered Users
     * 
     * @return User::class
     */
    public function getUserList()
    {
        $users = User::all();
        return Response::success($users);
    }

    /**
     * Register a new user if username is unique.
     * 
     * @param  Request $request [string username, string password]
     * @return User::class
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users,username',
            'password' => 'required',
        ]);

        $hasher = app()->make('hash');
        $username = $request->input('username');
        $password = $hasher->make($request->input('password'));
        $user = new User([
                    'username' => $username,
                    'password' => $password,
                ]);

        $user->save();

        return Response::success($user);
    }

    /**
     * Authanticates user
     * @param  Request $request [string username, string password]
     * @return string api_key
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'

        ]);
        try {
            $user = User::where('username', $request->input('username'))->firstOrFail();
            if(Hash::check($request->input('password'), $user->password)){
                $apikey = base64_encode(str_random(40));
                $user = User::where('username', $request->input('username'))->update(['api_key' => $apikey]);
                return Response::success(['api_key' => $apikey]);
            }else{
                return Response::error('Password is incorrect.'); //password is incorrect
            }
        } catch (\Exception $e) {
            return Response::error('User name is incorrect.'); //username is incorrect
        }
    }

    /**
     * Update user infos
     * @param  Request $request [string username, string password]
     * @return User::class
     */
    public function updateUser(Request $request, $user_id)
    {
        $this->validate($request, [
            'username' => 'required|unique:users,username',
            'password' => 'required',
        ]);

        $hasher = app()->make('hash');
        $username = $request->input('username');
        $password = $hasher->make($request->input('password'));
        $user = User::findOrFail($user_id);
        $data =[
            'username' => $username,
            'password' => $password,
        ];

        $user->update($data);

        return Response::success($user);
    }

    public function deleteUser($user_id)
    {
        if(Auth::user()->is_admin){
            try {
                $user = User::findOrFail($user_id);;
                $user->delete();
                return Response::success();   
            } catch (\Exception $e) {
                return Response::error();
            }
        }
    }
}
