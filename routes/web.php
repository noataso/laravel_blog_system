<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');



Route::get('/blogs','App\Http\Controllers\BlogController@index')->name('blogs.index');
Route::get('/blogs/create','App\Http\Controllers\BlogController@create')->name('blog.create')->middleware('auth');
Route::post('/blogs/store/','App\Http\Controllers\BlogController@store')->name('blog.store')->middleware('auth');
Route::get('/blogs/edit/{blog}','App\Http\Controllers\BlogController@edit')->name('blog.edit')->middleware('auth');
Route::put('/blogs/edit/{blog}','App\Http\Controllers\BlogController@update')->name('blog.update')->middleware('auth');
Route::delete('/blogs/{blog}','App\Http\Controllers\BlogController@destroy')->name('blog.destroy')->middleware('auth');

require __DIR__.'/auth.php';