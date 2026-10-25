<?php

use App\Http\Controllers\AdminLocaleController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (Request $request) => redirect('/'.SetLocale::preferred($request)));
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/company-profile.pdf', [PageController::class, 'profile'])->name('profile.download');

Route::prefix('{locale}')
    ->whereIn('locale', SetLocale::LOCALES)
    ->middleware(SetLocale::class)
    ->group(function () {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('/about', [PageController::class, 'about'])->name('about');
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/contact', [InquiryController::class, 'create'])->name('contact');
        Route::post('/contact', [InquiryController::class, 'store'])->middleware('throttle:5,10')->name('contact.store');
    });

Route::get('/robots.txt', fn () => response("User-agent: *\nDisallow: /admin\n\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain']));

Route::get('/admin-language/{locale}', AdminLocaleController::class)->name('admin.locale');
