<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidateAuthRequest extends FormRequest
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
            'name'                  => 'required|between:2,100',
            'email'                 => 'required|email|unique:users,email|max:50',
            'phone'                 => 'required|unique:users,phone|numeric',
            'address'               => 'required',
            'password'              => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required'                  => 'Họ và tên không được để trống',
            'email.required'                 => 'Email không được để trống',
            'email.email'                    => 'Bạn nhập sai định dạng email',
            'email.unique'                   => 'Email này đã tồn tại',
            'password.confirmed'             => 'Xác nhận mật khẩu không khớp',
            'password.min'                   => 'Nhập khẩu lớn hơn 6 ký tự',
            'password.required'              => 'Mật khẩu không được để trống',
            'phone.required'                 => 'Số điện thoại không được để trống',
            'phone.unique'                   => 'Số điện thoại đã tồn tại',
            'address.required'               => 'Địa chỉ không được để trống',
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
