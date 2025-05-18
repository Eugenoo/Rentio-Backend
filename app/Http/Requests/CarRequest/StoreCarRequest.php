<?php

namespace App\Http\Requests\CarRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "brand" => "required",
            "model" => "required",
            "registration_number" => "nullable",
            "year" => "nullable",
            "price_per_day" => "nullable",
            "available" => "nullable",
            "car_category_id" => "nullable",
            "status" => "nullable",
            "photo" => "nullable",
        ];
    }
}
