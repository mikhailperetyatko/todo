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
        $userId = auth()->user()->id;
        
        return view('admin.reports', compact('userId'));
    }
    
    public function job()
    {
        $needModels = [];
        
        if (count(request()->input()) > 1) {
            foreach (config('app.reportableTables') as $model) {
                if (request()->input($model)) $needModels[] = $model;
            }
            StatisticsReport::dispatch($needModels, auth()->user())->onQueue('reports');
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
