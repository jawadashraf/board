<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

Route::get('/', 'HomeController@index')->name('home.index');
Route::post('home/dump', 'HomeController@dump')->name('home.dump');

Route::get('cards', 'CardController@index')->name('cards.index');
Route::post('cards', 'CardController@store')->name('cards.store');
Route::put('cards/sync', 'CardController@sync')->name('cards.sync');
//Route::put('cards/{card}', 'CardController@update')->name('cards.update');
Route::put('cards/update', 'CardController@update')->name('cards.update');


Route::post('columns', 'ColumnController@store')->name('columns.store');
Route::put('columns', 'ColumnController@update')->name('columns.update');
Route::delete('columns/{id}', 'ColumnController@delete')->name('columns.delete');
