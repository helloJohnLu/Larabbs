<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . \Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:100',
            'avatar' => 'mimes:jpeg,png,jpg|dimensions:min_width:200,min_height=200',
        ];
    }

    /**
     * 错误提示信息
     *
     * @return array
     */
    public function messages()
    {
        return [
            'avatar.mimes' => '头像必须是 jpeg, png, jpg 格式的图片',
            'avatar.dimensions' => '图片的清析度不够，宽和度需要至少 200px 以上',
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持中英文、数字、横杆和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}
