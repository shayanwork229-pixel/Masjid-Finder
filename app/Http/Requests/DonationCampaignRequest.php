<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'masjid_id' => 'required|exists:masjids,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'target_amount' => 'required|numeric|min:0',
            'collected_amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,paused,closed',
            'is_featured' => 'sometimes|boolean',
            'is_emergency' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_emergency' => $this->boolean('is_emergency'),
        ]);
    }
}
