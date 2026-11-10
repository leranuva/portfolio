<?php

namespace App\Http\Requests;

use App\Rules\TurnstileToken;
use App\Services\PublicSubmission;
use Illuminate\Foundation\Http\FormRequest;

class LeadContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function getRedirectUrl(): string
    {
        return route('home').'#contact';
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => PublicSubmission::emailRules(),
            'project_type' => ['required', 'string', 'max:255'],
            'what_to_automate' => ['nullable', 'string', 'max:1000'],
            'budget_range' => ['required', 'string', 'in:bajo,medio,alto'],
            'urgency' => ['required', 'string', 'in:flexible,pronto,inmediato'],
            'message' => ['nullable', 'string', 'max:5000'],
            'company_website' => ['prohibited'],
            ...array_fill_keys(PublicSubmission::TRACKING_FIELDS, ['nullable', 'string', 'max:255']),
        ];

        if (config('contact.turnstile.enabled')) {
            $rules['cf-turnstile-response'] = ['bail', 'required', 'string', 'max:2048', new TurnstileToken];
        }

        return $rules;
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
            'cf-turnstile-response.required' => 'Please complete the security check.',
        ];
    }
}
