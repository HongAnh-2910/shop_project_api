<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidateLoginRequest extends FormRequest
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
            'loginKey'    => 'required',
            'password' => 'required',
            //
        ];
    }

    public function messages()
    {
        return [
            'loginKey.required'    => 'Địa chỉ email hoặc số điện thoại không được để trống',
            'email.email'       => 'Bạn nhập sai định dạng email',
            'password.required' => 'Mật khẩu không được để trống',
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
