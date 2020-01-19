<?php

namespace App;

class ReportableDataHandler
{
    public $tables = [];
    public $attach;
    public $timeBeforeDelete;
    
    public function __construct()
    {
        $this->attach = $this->generateFileName();
        $this->timeBeforeDelete = now()->addHours(config('app.delayBeforeDeleteReportInHours'))->toDayDateTimeString();
        return $this;
    }
    
    protected function generateFileName()
    {
        return md5(microtime()) . '.xlsx';
    }
    
    public function addStatistics(array $table)
    {
        array_push($this->tables, $table);
        return $this;
    }
    
    public function getData()
    {
        return $this->tables;
    }
    
    public function getFilename()
    {
        return $this->attach;
    }

}