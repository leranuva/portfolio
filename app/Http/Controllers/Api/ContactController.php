<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;
use App\Services\PublicSubmission;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __invoke(ContactFormRequest $request): JsonResponse
    {
        $data = $request->validated();
        PublicSubmission::once('message', $data, fn () => ContactMessage::create($data));

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully. We\'ll get back to you soon.',
        ]);
    }
}
