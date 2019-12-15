<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\StatisticsReport;

class ReportsController extends Controller
{
    public function show()
    {
        return view('admin.reports');
    }
    
    public function job()
    {
        StatisticsReport::dispatchNow(['posts', 'informations', 'tags', 'comments', 'users', 'commentables']);
        //StatisticsReport::dispatchNow()->onQueue('reports');
    }
}
