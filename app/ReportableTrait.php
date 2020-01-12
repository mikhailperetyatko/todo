<?php

namespace App;

trait ReportableTrait
{
    public $tables = [];
    public $attach;
    public $timeBeforeDelete;
    
    public function setDataFromReportableDataHandler(\App\ReportableDataHandler $dataHandler)
    {
        $this->tables = $dataHandler->tables;
        $this->attach = $dataHandler->attach;
        $this->timeBeforeDelete = $dataHandler->timeBeforeDelete;
        return $this;
    }
}
