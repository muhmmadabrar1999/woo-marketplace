<?php

Route::group(['namespace' => 'Woo\LanguageAdvanced\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'language-advanced'], function () {
            Route::post('save/{id}', [
                'as' => 'language-advanced.save',
                'uses' => 'LanguageAdvancedController@save',
                'permission' => false,
            ])->where('id', '[0-9]+');
        });
    });
});
