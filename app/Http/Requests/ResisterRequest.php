<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Contracts\Validation\Validator;
// use Illuminate\Http\Exceptions\HttpResponseException;

class ResisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
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
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            // 'token'     => 'required',
        ];
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     $res = response()->json([
    //         'status' => 400,
    //         'errors' => $validator->errors(),
    //     ], 400);
    //     throw new HttpResponseException($res);
    // }

    public function messages()
    {
        return [
            'name.required'     => 'ユーザー名は必須項目です',
            'email.required'    => 'メールアドレスは必須項目です',
            'email.email'       => '有効なメールアドレスを入力して下さい',
            'email.unique'       => 'そのメールアドレスはすでに登録されています',
            'password.required' => 'パスワードは必須項目です',
            'password.min'      => 'パスワードは6文字以上でなければなりません',
            // 'token.required' => '認証コードが必要です',
        ];
    }
}
