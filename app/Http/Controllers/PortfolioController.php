<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormMail;
use App\Models\PortfolioConfig;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class PortfolioController extends Controller
{
    public function index()
    {
        $config = PortfolioConfig::first();
        $projects = Project::where('featured', true)
            ->orderBy('order')
            ->get();
        $skills = Skill::orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return view('portfolio.index', compact('config', 'projects', 'skills'));
    }

    public function sendContact(ContactFormRequest $request)
    {
        $config = PortfolioConfig::first();
        $recipientEmail = $config->email ?? config('mail.from.address');

        try {
            Mail::to($recipientEmail)->send(
                new ContactFormMail(
                    $request->name,
                    $request->email,
                    $request->subject,
                    $request->message
                )
            );

            return response()->json([
                'success' => true,
                'message' => '¡Mensaje enviado exitosamente! Te responderé pronto.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending contact form: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al enviar el mensaje. Por favor, intenta nuevamente.'
            ], 500);
        }
    }
}
