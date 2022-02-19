<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopEditRequest extends FormRequest
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
      'shop_name'     => 'required',
      'description'    => 'required|max:120',
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
      'shop_name.required'     => '店舗名は必須項目です',
      'description.required'    => '店舗詳細は必須項目です',
      'description.max'       => '店舗詳細は120文字までです',
    ];
  }
}
