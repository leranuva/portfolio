<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CalendlyWebhookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::view('/recursos/auditoria', 'pages.lead-magnet')->name('lead-magnet.auditoria');

Route::post('/webhooks/calendly', CalendlyWebhookController::class)->name('webhooks.calendly')->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', fn () => response(
    "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml'),
    200,
    ['Content-Type' => 'text/plain']
))->name('robots');

Route::post('/contact', ContactController::class)->name('contact.store');
