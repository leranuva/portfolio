<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function getRedirectUrl(): string
    {
        return url()->previous() . '#contact';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'project_type' => ['required', 'string', 'max:255'],
            'what_to_automate' => ['nullable', 'string', 'max:1000'],
            'budget_range' => ['required', 'string', 'in:bajo,medio,alto'],
            'urgency' => ['required', 'string', 'in:flexible,pronto,inmediato'],
            'message' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'project_type.required' => 'Project type is required.',
            'budget_range.required' => 'Please select a budget range.',
            'urgency.required' => 'Please select urgency.',
        ];
    }
}
