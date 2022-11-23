<?php

namespace App\Http\Controllers\Front;

use App\Helpers\PaymentHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function fullDescription(Request $request): array
    {
        return PaymentHelper::fullDescription(
            service_type: $request->service_type,
            service_id: $request->service_id,
            period_id: $request->period_id ?? null,
        );
    }
}
