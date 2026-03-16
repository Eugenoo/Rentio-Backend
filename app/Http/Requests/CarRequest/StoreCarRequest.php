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
            "brand" => "required|string|max:255",
            "model" => "required|string|max:255",
            "registration_number" => "nullable|string|max:50",
            "year" => "nullable|integer|min:1900|max:2100",
            "price_per_day" => "nullable|numeric|min:0",
            "available" => "nullable|boolean",
            "car_category_id" => "nullable|integer|exists:car_categories,id",
            "status" => "nullable|string|max:50",
            "photo" => "nullable|image|mimes:jpeg,jpg,png,webp|max:2048",
        ];
    }
}
