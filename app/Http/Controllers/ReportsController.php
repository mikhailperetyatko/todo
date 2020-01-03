<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\StatisticsReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Events\GenerateReport;

class ReportsController extends Controller
{
    public function show()
    {
        return view('admin.reports');
    }
    
    public function job()
    {
        if (count(request()->input()) > 1) {
            StatisticsReport::dispatch(config('app.reportableTables'), auth()->user())->onQueue('reports');
            flash('success');
            return back();
        } else {
            return back()->withErrors(['Не выбрана ни одна таблица']);
        }
        
    }
    
    public function download($filename)
    {
        return Storage::disk('reportsStorage')->exists($filename) ? response()->download(config('filesystems.disks.reportsStorage.root') . '/' . $filename) : abort(404);
    }
}
