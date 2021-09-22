<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidateChangePasswordRequest extends FormRequest
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
            'password_old'          => 'required',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required'
            //
        ];
    }

    public function messages()
    {
        return [
            'password_old.required' => 'Mật khẩu cũ không được để trống',
            'password.required'     => 'Mật khẩu mới không được để trống',
            'password_confirmation' => 'Nhập lại mật khẩu mới không được để trống',
            'password.confirmed'    => 'Xác nhận mật khẩu không khớp'
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([$validator->errors()], 422));
    }
}
