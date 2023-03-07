<?php

Route::group(['namespace' => 'Woo\Stripe\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::get('payment/stripe/success', 'StripeController@success')->name('payments.stripe.success');
    Route::get('payment/stripe/error', 'StripeController@error')->name('payments.stripe.error');
});
