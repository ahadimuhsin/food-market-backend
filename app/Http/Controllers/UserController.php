<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Actions\Fortify\PasswordValidationRules;

class UserController extends Controller
{
    use PasswordValidationRules;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = User::paginate(10);

        return view('users.index', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // $data = $request->all();
        // $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => $this->passwordRules(),
            'role' => 'string|nullable|in:USER,ADMIN',
            'picture_path' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
            'profile_photo_path' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
            'address' => 'required',
            'houseNumber' => 'nullable',
            'contact' => 'nullable',
            'city' => 'nullable'
        ]);

        $save_file = null;
        if($request->file('profile_photo_path')){
            $file = $request->file('profile_photo_path');
            $file_name = 'profile-photo-'.$request->name.".".$file->extension();
            $save_file = $file->storeAs('assets/user', $file_name, 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo_path' => $save_file,
            'address' => $request->address,
            'houseNumber' => $request->houseNumber,
            'contact' => $request->contact,
            'city' => $request->city
        ]);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);

        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        // $data = $request->all();

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:users',
            // 'password' => $this->passwordRules(),
            'role' => 'string|nullable|in:USER,ADMIN',
            'picture_path' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
            'profile_photo_path' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
            'address' => 'required',
            'houseNumber' => 'nullable',
            'contact' => 'nullable',
            'city' => 'nullable'
        ],
    ['name.required' => 'aaa',

    'email.required' => 'bbb',
    'address.required' => 'cccc']);

        $user = User::findOrFail($id);
        $save_file = null;
        if($request->file('profile_photo_path')){
            if($user->profile_photo_path && file_exists('app/public/'.$user->profile_photo_path)){
                Storage::delete('public/'.$user->profile_photo_path);
            }
            $file = $request->file('profile_photo_path');
            $file_name = 'profile-photo-'.$request->name.".".$file->extension();
            $save_file = $file->storeAs('assets/user', $file_name, 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $user->email,
            // 'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_photo_path' => $save_file,
            'address' => $request->address,
            'houseNumber' => $request->houseNumber,
            'contact' => $request->contact,
            'city' => $request->city
        ]);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::findOrFail($id);
        // $path = public_path().$user->profile_photo_path;
        // // echo $path;
        $user->delete();
        if($user->profile_photo_path && file_exists('app/public/'.$user->profile_photo_path)){
            Storage::delete('public/'.$user->profile_photo_path);
        }
        // unlink($path);

        return redirect()->route('users.index');
    }
}
