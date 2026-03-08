<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __invoke(ContactFormRequest $request): JsonResponse
    {
        ContactMessage::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully. We\'ll get back to you soon.',
        ]);
    }
}
