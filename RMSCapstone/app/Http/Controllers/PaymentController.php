<?php

namespace App\Http\Controllers;

use App\Services\PayMongoService;

class PaymentController extends Controller
{
    public function showForm()
    {
        return view('pay');
    }

    public function pay(PayMongoService $payMongo)
    {
        $amount = 100000; // 1000 PHP in centavos
        $redirect = $payMongo->createGcashPaymentIntent($amount);

        return $redirect ? redirect($redirect) : redirect()->back()->withErrors(['error' => 'Failed to initiate payment']);
    }

    public function success()
    {
        return view('success');
    }

    public function failed()
    {
        return view('failed');
    }
}
