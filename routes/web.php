<?php

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

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

Route::post('/webhook/{user}', '\Socrates\Http\Controllers\WebhookReceivedController')->name('webhook');


/**
 * Admin
 */
Route::namespace('Admin')->group(
    function () {
        Route::prefix('admin')->group(
            function () {
                Route::group(
                    ['as' => 'admin.'], function () {

                        Route::prefix('activity')->group(
                            function () {
                                Route::group(
                                    ['as' => 'activity.'], function () {
                                        Route::get('/', 'ActivityController@index')->name('index');
                                    }
                                );
                            }
                        );    
                        Route::prefix('contact')->group(
                            function () {
                                Route::group(
                                    ['as' => 'contact.'], function () {
                                        Route::get('/', 'ContactController@index')->name('index');
                                    }
                                );
                            }
                        );    
                    }
                );
            }
        );    
    }
);