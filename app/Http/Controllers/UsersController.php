<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }


    /**
     * 个人中心页面渲染
     *
     * @param User $user
     * @return
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 编辑页面渲染
     *
     * @param User $user
     * @return
     */
    public function edit(User $user)
    {
        // 权限
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * 更新个人资料
     *
     * @param UserRequest $request
     * @param User $user
     * @return
     */
    public function update(UserRequest $request, User $user, ImageUploadHandler $uploadHandler)
    {
        // 权限
        $this->authorize('update', $user);

        $data = $request->all();

        if ($request->avatar) {
            $result = $uploadHandler->save($request->avatar, 'avatars', $user->id, 362);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功');
    }
}
