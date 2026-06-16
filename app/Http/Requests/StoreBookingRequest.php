<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'weight_kg' => ['required', 'numeric', 'min:1', 'max:500'],
            'baggage_weight_kg' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'seat_id' => ['required', 'integer', 'exists:seats,id'],
            'add_on_ids' => ['nullable', 'array'],
            'add_on_ids.*' => ['integer', 'exists:add_ons,id'],
            'membership_code' => ['nullable', 'string', 'max:50'],
        ];
    }
}
