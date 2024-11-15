<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            "first_name" => "sometimes|required",
            "middle_name" => "sometimes|required",
            "last_name" => "sometimes|required",
            "gender" => "sometimes|required|in:male,female",
            "mobile_number" => [
                "unique:users,mobile_number," . $this->route()->user,
                "regex:/^\+63\d{10}$/",
            ],
            "email" => [
                "sometimes",
                "required",
                "unique:users,email," . $this->route()->user,
            ],
            "username" => [
                "sometimes",
                "required",
                "min:3",
                "unique:users,username," . $this->route()->user,
            ],
            "password" => "sometimes|required|min:3",
        ];
    }
}
