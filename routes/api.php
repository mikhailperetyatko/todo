<?php

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/fastclaim/payments', function () {
    $account = [
        'uuid' => (string) Str::uuid(),
        'ls' => [
            [
                'date' => Carbon::parse('31.01.2020')->format('d.m.Y'),
                'uuid' => (string) Str::uuid(),
                'services' => [
                    [
                        'uuid' => (string) Str::uuid(),
                        'title' => 'Отопление',
                        'calculation' => [
                            'amount' => 2,
                            'tariff' => 1000,
                            'unit' => 'ГКал',
                        ],
                        'recalculation' => -200,
                        'exemption' => 0,
                    ],
                    [
                        'uuid' => (string) Str::uuid(),
                        'title' => 'Горячее водоснабжение',
                        'calculation' => [
                            'amount' => 3,
                            'tariff' => 2000,
                            'unit' => 'куб.м.',
                        ],
                        'recalculation' => 200,
                        'exemption' => 0,
                    ],
                ],
            ],
            [
                'date' => Carbon::parse('29.02.2020')->format('d.m.Y'),
                'uuid' => (string) Str::uuid(),
                'services' => [
                    [
                        'uuid' => (string) Str::uuid(),
                        'title' => 'Отопление',
                        'calculation' => [
                            'amount' => 2,
                            'tariff' => 1000,
                            'unit' => 'ГКал',
                        ],
                        'recalculation' => 0,
                        'exemption' => 0,
                    ],
                    [
                        'uuid' => (string) Str::uuid(),
                        'title' => 'Горячее водоснабжение',
                        'calculation' => [
                            'amount' => 3,
                            'tariff' => 2000,
                            'unit' => 'куб.м.',
                        ],
                        'recalculation' => 0,
                        'exemption' => 0,
                    ],
                ],
            ],
            [
                'date' => Carbon::parse('31.03.2020')->format('d.m.Y'),
                'uuid' => (string) Str::uuid(),
                'services' => [
                    [
                        'uuid' => (string) Str::uuid(),
                        'title' => 'Отопление',
                        'calculation' => [
                            'amount' => 2.5,
                            'tariff' => 1000,
                            'unit' => 'ГКал',
                        ],
                        'recalculation' => 0,
                        'exemption' => 0,
                    ],
                    [
                        'uuid' => (string) Str::uuid(),
                        'title' => 'Горячее водоснабжение',
                        'calculation' => [
                            'amount' => 3.5,
                            'tariff' => 2000,
                            'unit' => 'куб.м.',
                        ],
                        'recalculation' => 0,
                        'exemption' => 0,
                    ],
                ],
            ],
        ],
    ];
    $payments = [
        [
            'uuid' => (string) Str::uuid(),
            'date' => Carbon::parse('27.02.2020')->format('d.m.Y'),
            'amount' => 1234.56,
        ],
        [
            'uuid' => (string) Str::uuid(),
            'date' => Carbon::parse('28.02.2020')->format('d.m.Y'),
            'amount' => 567.12,
        ],
    ];
    return response()->json([
        'account' => $account,
        'payments' => $payments,
    ]);
})->middleware('cors');
