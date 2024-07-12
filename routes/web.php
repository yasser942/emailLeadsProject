<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\MailtrapSettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Leads
Route::resource('leads', LeadsController::class)->middleware('auth');
Route::post('leads/import', [LeadsController::class, 'import'])->name('leads.import')->middleware('auth');
Route::post('leads/send-emails', [LeadsController::class, 'sendEmails'])->name('leads.sendEmails')->middleware('auth');

// Email templates
Route::resource('email-templates', EmailTemplateController::class)->middleware('auth');

// Mailtrap settings
Route::get('mailtrap-settings', [MailtrapSettingsController::class, 'index'])->name('mailtrap-settings.index')->middleware('auth');
Route::get('mailtrap-settings/edit', [MailtrapSettingsController::class, 'edit'])->name('mailtrap-settings.edit')->middleware('auth');
Route::put('mailtrap-settings/update', [MailtrapSettingsController::class, 'update'])->name('mailtrap-settings.update')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';