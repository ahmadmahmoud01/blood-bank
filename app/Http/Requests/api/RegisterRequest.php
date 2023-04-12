<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|unique,email',
            'password' => 'required|confirmed',
            'phone' => 'required',
            'city_id' => 'required',
            'blood_type_id' => 'required',
            'd_o_b' => 'required',
            'last_donation_date' => 'required',
            'pin_code' => 'nullable'
        ];
    }
}
