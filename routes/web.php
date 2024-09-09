<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DialogflowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {
    return view('welcome'); // Return the chat view
})->name('merit.chat');

Route::post('/detect-intent', [DialogflowController::class, 'detectIntent'])->name('detect.intent');;
