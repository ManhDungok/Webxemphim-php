<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * Nhận các quy tắc xác thực áp dụng cho yêu cầu.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'gender' => 'required',
            'birthday' => 'required|date',
            'phone_number' => 'required|size:10',
            'address' => 'required',
            'password' => 'required|confirmed|min:8|string',
            'avatar' => 'required|image',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Họ và tên là trường bắt buộc.',
            'name.max' => 'Họ và tên không được dài quá :max ký tự.',
            'gender.required' => 'Giới tính là trường bắt buộc.',
            'birthday.required' => 'Ngày sinh là trường bắt buộc.',
            'birthday.date' => 'Ngày sinh không đúng định dạng.',
            'phone_number.required' => 'Số điện thoại là trường bắt buộc.',
            'phone_number.size' => 'Số điện thoại phải là :size số.',
            'address.required' => 'Địa chỉ là trường bắt buộc.',
            'email.required' => 'Email là trường bắt buộc.',
            'email.email' => 'Email chưa đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'email.string' => 'Email phải là một chuỗi.',
            'email.max' => 'Email không được dài quá :max ký tự.',
            'password.required' => 'Mật khẩu là trường bắt buộc!',
            'password.confirmed' => 'Xác nhận mật khẩu không chính xác!',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự!',
            'password.string' => 'Mật khẩu phải là một chuỗi',
            'avatar.required' => 'Ảnh đại diện là trường bắt buộc!',
            'avatar.image' => 'Ảnh đại diện phải là tệp tin dạng ảnh!',
        ];
    }
}
