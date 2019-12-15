<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Statistics;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tables = [];
    protected $mailTemplate;
    protected $attachment;
    
    public function __construct(string $template = 'mail.report')
    {
        $this->mailTemplate = $template;
    }
    
    public function __call($method, $parameters)
    {
        $table = snake_case(str_replace('with', '', $method));
        $this->tables[$table] = $this->getItemsAmount($table);
        return $this;
    }
    
    protected function getItemsAmount(string $table)
    {
        return app(Statistics::class)->getTableCount($table);
    }
    
    public function build()
    {
        $generator = new \App\Services\GeneratorExcel();
        $generator->putTitle('Отчет')->putHeader(['Таблица', 'Количество записей']);
        
        foreach ($this->tables as $table => $value)
        {
            $generator->putRowWithStandartStyle([trans("messages.tables.$table.name"), $value]);
        }
        $file = time() . '.xls';
        $generator->save($file);
        
        return $this->markdown($this->mailTemplate)->attachData($file, $file, [
                'mime' => 'application/xls',
              ]);
    }
}
