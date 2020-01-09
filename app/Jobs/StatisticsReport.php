<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\ReportableDataHandler;
use App\Mail\ReportMail;
use App\Services\Statistics;
use App\Services\ReportToXLSX;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ReportDelete;
use App\Events\GenerateReport;

class StatisticsReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $reportFile;
    protected $dataHandler;
    protected $filename;
    protected $user;
    protected $needTables = [];
    
    public function __construct(array $allowedTables, \App\User $user)
    {
        $this->user = $user;
        $this->filename = $this->generateFileName();
        $this->reportFile = new ReportToXLSX();
        $this->dataHandler = new ReportableDataHandler($this->filename);
        foreach ($allowedTables as $table) {
            if (request()->input($table)) $this->needTables[] = $table;
        }
    }
    
    protected function generateFileName()
    {
        return $this->user->id . '_' . md5(microtime()) . '.xlsx';
    }
    
    protected function addStatistics($name, $data)
    {
        $this->dataHandler->addStatistics([
            'name' => trans("messages.tables.$name.name"),  'data' => $data
        ]);
        $this->reportFile->putRowWithStandartStyle([
            trans("messages.tables.$name.name"), $data
        ]);
    }
 
    public function handle()
    {
        if (! empty($this->needTables)) {
        $this->reportFile->putTitle('Отчет')->putHeader(['Таблица', 'Количество записей']);
        foreach ($this->needTables as $table) {
            $this->addStatistics($table, app(Statistics::class)->getTableCount($table));
        }
        $this->reportFile->save($this->filename);
        $reportMail = (new ReportMail($this->dataHandler))->withDefaultTemplate();   
        
        \Mail::to($this->user->email)->send($reportMail);
        event((new GenerateReport($this->dataHandler))->forUser($this->user));
        ReportDelete::dispatch($this->filename)->onQueue('reports')->delay(now()->addHours(config('app.delayBeforeDeleteReportInHours')));
        }
    }
}
