<?php

Route::group(['namespace' => 'Woo\Paypal\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::get('payment/paypal/status', 'PaypalController@getCallback')->name('payments.paypal.status');
});
