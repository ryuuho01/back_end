<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'reservation_date' => 'date',
            'reservation_time' => 'regex:/^[0-9:]+$/',
            'num_members'      => 'regex:/^[0-9名様]+$/',
        ];
    }

    public function messages()
    {
        return [
            'reservation_date.date' => '予約日は必須項目です',
            'reservation_time.regex' => '予約時間は必須項目です',
            'num_members.regex'      => '予約人数は必須項目です',
        ];
    }
}

