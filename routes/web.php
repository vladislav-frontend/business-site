<?php

use Illuminate\Support\Facades\Route;
use App\Services\Locale;

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

Route::group(['prefix' => Locale::setLocale()], function()
{
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('index');

    Route::get('user/{id}', [\App\Http\Controllers\UserController::class, 'show'])->name('user.show');

    Route::get('about-us', [\App\Http\Controllers\AboutUsController::class, 'index'])->name('about-us.index');

    Route::get('contacts', [\App\Http\Controllers\ContactsController::class, 'index'])->name('contacts.index');

    Route::get('categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');

    Route::get('{category_slug?}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('category.show');

    Route::get('{category_slug?}/{post_slug?}', [\App\Http\Controllers\PostController::class, 'show'])->name('post.show');

    Route::post('{category_slug?}/{post_slug?}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
});

Route::post('order-service', [\App\Http\Controllers\OrderServiceController::class, 'send'])->name('order-service.send');

Route::post('feedback', [\App\Http\Controllers\FeedbackController::class, 'send'])->name('feedback.send');
