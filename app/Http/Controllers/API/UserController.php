<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //untuk login
    public function login(Request $request){
        try{
            //form validation
            $request->validate(
                ['email' => 'email|required',
                'password'=> 'required|min:6'
                ]
            );

            //memeriksa credentials login
            $credentialas = request(['email', 'password']);

            //jika email/password salah
            if(!Auth::attempt($credentialas)){
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            //password tidak sesuai
            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }

            //jika berhasil login
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        }
        //jika proses login gagal
        catch (Exception $error){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);

        }
    }

    public function register (Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'string|nullable',
                'picture_path' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
                'address' => 'nullable',
                'houseNumber' => 'nullable',
                'contact' => 'nullable',
                'city' => 'nullable'
            ]);


            if($request->file('picture_path')){
                $file = $request->file('picture_path');
                $file_name = 'profile-photo-'.$request->name.".".$file->extension();
                $save_file = $file->storeAs('assets/user', $file_name, 'public');
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'picture_path' => $save_file,
                'address' => $request->address,
                'houseNumber' => $request->houseNumber,
                'contact' => $request->contact,
                'city' => $request->city
            ]);


            $user = User::where('email', $request->email)->first();

            $token = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }
        catch (Exception $error){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Berhasil dihapus');
    }

    public function fetch(Request $request){
        return ResponseFormatter::success($request->user(), 'Data profile user');
    }

    public function updateProfile(Request $request)
    {
        

        // print_r($request->houseNumber);

        // $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|string|min:6',
        //     'role' => 'string|nullable',
        //     'picture_path' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
        //     'address' => 'nullable',
        //     'houseNumber' => 'nullable',
        //     'contact' => 'nullable',
        //     'city' => 'nullable'
        // ]);

        $data = $request->all();

        $data['password'] = Hash::make($request->password);

        $user = $request->user();

        if($request->file('picture_path')){
            if($user->picture_path && file_exists(storage_path('app/public/'.$user->picture_path))){
                Storage::delete('public/'.$user->picture_path);
            }
            $data['picture_path'] =$request->file('picture_path')
            ->storeAs('assets/user', 'profile-photo-'.$request->name.".".$request->file('picture_path')->extension(), 'public');
        }
        $user->update($data);
        
        return ResponseFormatter::success($user, 'Profile Updated');
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048'
        ]);

        if($validator->fails())
        {
            return ResponseFormatter::error([
                'error' => $validator->errors()
            ], 'Update foto gagal', 401);
        }

        if($request->file('file')){
            $file = $request->file('file');
                $file_name = 'profile-photo-'.$request->name.".".$file->extension();
                $save_file = $file->storeAs('assets/user', $file_name, 'public');

                $user = $request->user();
                $user->profile_photo_path = $save_file;
                $user->update();

                return ResponseFormatter::success([$file], 'File sukses diupdate');
        }
    }
}
