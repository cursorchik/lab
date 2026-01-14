<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SalaryController extends Controller
{
    public function __invoke(): Response
    {
        $mehanics = DB::table('mechanics')
            ->select(DB::raw('`id`,`name`, (SELECT `cost`*`count` FROM `works` WHERE `mid`=`mechanics`.`id`) as `salary`'))
            ->get();

        $clinics = DB::table('clinics')
            ->select(DB::raw('`id`,`name`, (SELECT `cost`*`count` FROM `works` WHERE `cid`=`clinics`.`id`) as `salary`'))
            ->get();

        return Inertia::render('Salary/Browse', ['prev_url' => URL::previous(), 'items' => [
            'mehanics' => $mehanics,
            'clinics' => $clinics
        ]]);
    }
}
