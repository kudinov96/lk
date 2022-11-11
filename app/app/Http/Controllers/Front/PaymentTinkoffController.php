<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class PaymentTinkoffController extends Controller
{
    public function callback()
    {

    }

    public function success()
    {
        return "SUCCESS TINKOFF";
    }

    public function fail()
    {
        return "FAIL TINKOFF";
    }
}
