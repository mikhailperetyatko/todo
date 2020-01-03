<?php

namespace App;

class ReportableDataHandler
{
    public $tables = [];
    public $attach;
    public $timeBeforeDelete;
    
    public function __construct(string $file)
    {
        $this->attach = $file;
        $this->timeBeforeDelete = now()->addHours(config('app.delayBeforeDeleteReportInHours'))->toDayDateTimeString();
        return $this;
    }
    
    public function addStatistics(array $table)
    {
        array_push($this->tables, $table);
        return $this;
    }

}