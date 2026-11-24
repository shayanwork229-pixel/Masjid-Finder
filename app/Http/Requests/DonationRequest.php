<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'amount' => 'required|numeric|min:1',
            'category' => 'required|in:' . implode(',', array_keys(config('payments.categories'))),
            'message' => 'nullable|string|max:1000',
            'is_anonymous' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_anonymous' => $this->boolean('is_anonymous')]);
    }
}
