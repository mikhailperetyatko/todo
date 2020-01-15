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
    protected $dataHandler;
    protected $user;
    protected $models = [];
    
    public function __construct(array $models, \App\User $user)
    {
        $this->user = $user;
        $this->models = $models;
        $this->dataHandler = app(ReportableDataHandler::class);
    }
        
    protected function addStatistics()
    {
        foreach ($this->models as $model) {
            $this->dataHandler->addStatistics([
                'name' => trans("messages.tables.$model.name"),  'data' => app(Statistics::class)->getTableCount($model)
            ]);
        }
    }
 
    public function handle()
    {
        if (empty($this->models)) {
            return;
        }
        
        $this->addStatistics();
        
        app(ReportToXLSX::class)
            ->putTitle('Отчет')->putHeader(['Таблица', 'Количество записей'])
            ->putRowsFromReportableDataHandlerWithStandartStyle($this->dataHandler)
            ->save($this->dataHandler->getFilename())
        ;
        
        \Mail::to($this->user->email)
            ->send(
                app(ReportMail::class)
                    ->setDataFromReportableDataHandler($this->dataHandler)
                    ->withDefaultTemplate()
            )
        ;
        
        event(
            app(GenerateReport::class)
                ->setDataFromReportableDataHandler($this->dataHandler)
                ->forUser($this->user)
        );
        
        ReportDelete::dispatch($this->dataHandler->getFilename())->onQueue('reports')->delay(now()->addHours(config('app.delayBeforeDeleteReportInHours')));
    }
}
