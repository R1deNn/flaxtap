<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderPrintController extends Controller
{
    /**
     * Печать чека
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function print(Request $request)
    {
        return view('exports.order-print');
    }
}
