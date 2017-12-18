<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Tools\ImageUploadTool;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'showByName']]);
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, ImageUploadTool $upload, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            if ($result = $upload->save($request->avatar, 'avatars', $user->id, 400)) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }

    public function showByName($name)
    {
        $user = User::where('name', $name)->first();

        return view('users.show', compact('user'));
    }
}
