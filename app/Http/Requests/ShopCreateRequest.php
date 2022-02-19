<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopCreateRequest extends FormRequest
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
      'pic_path'     => 'required',
      'shop_name'     => 'required',
      'area_name'     => 'required',
      'genre_name'     => 'required',
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
      'pic_path.required'     => '店舗画像は必須項目です',
      'shop_name.required'     => '店舗名は必須項目です',
      'area_name.required'     => '地域は必須項目です',
      'genre_name.required'     => 'ジャンルは必須項目です',
      'description.required'    => '店舗詳細は必須項目です',
      'description.max'       => '店舗詳細は120文字までです',
    ];
  }
}
