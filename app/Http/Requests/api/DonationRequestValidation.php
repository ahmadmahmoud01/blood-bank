<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequestValidation extends FormRequest
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
            'patient_name' => 'required',
            'patient_phone' => 'required',
            'patient_age' => 'required|integer',
            'hospital_name' => 'required',
            'hospital_address' => 'required',
            'bags_nums' => 'required|integer',
            'details' => 'required',
            'city_id' => 'required|exists:cities,id',
            'client_id' => 'required|exists:clients,id',
            'blood_type_id' => 'required|exists:blood_types,id',
        ];
    }
}
