<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', fn () => response(
    "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml'),
    200,
    ['Content-Type' => 'text/plain']
))->name('robots');

Route::post('/contact', ContactController::class)->name('contact.store');
