<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\URL;

class AccountingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Accounting/Browse', ['prev_url' => URL::previous(), 'items' => []]);
    }
}
