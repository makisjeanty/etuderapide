<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageLeads() ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:new,read,replied,archived'],
            'internal_notes' => ['nullable', 'string'],
            'payment_link' => ['nullable', 'url'],
            'quoted_value' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
