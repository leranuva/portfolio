<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadContactFormRequest;
use App\Services\ContactSubmissionService;
use Illuminate\Http\RedirectResponse;

class ContactFormController extends Controller
{
    public function __invoke(LeadContactFormRequest $request, ContactSubmissionService $submissions): RedirectResponse
    {
        $submissions->submit($request->validated());

        return redirect()->to(route('home').'#contact')->with('contact_success', true);
    }
}
