<?php

namespace App\Services\Debtors;

use App\DebtorAccount;
use App\Debtor;
use Carbon\Carbon;

class Analyzer
{
    protected $account;
    
    public function __construct(DebtorAccount $account)
    {
        $this->account = $account;
    }
    
    protected function calculateAmountAccruals(array $servies, bool $onlyOwner = null, Carbon $onDate = null) : float
    {
        $balance = 0;
        
        $koef = $onDate ? (intval($onDate->format('d')) / intval($onDate->endOfMonth()->format('d'))) : 1;
        
        foreach ($servies as $service) {
            if ($service['onlyOwner'] === $onlyOwner) $balance += round(($service['tariff'] * $service['amount'] * $koef), 2);
        }
        
        return $balance;
    }
    
    public function getBalance(bool $onlyOwner) : float
    {
        $inputBalance = $this->account->periods[0]['incoming_balance'];
        
        $balance = $inputBalance ?? 0;
        
        foreach($this->account->periods as $period) {
            $month = Carbon::parse($period['month'])->endOfMonth();
            
            $balance += $this->calculateAmountAccruals($period['services'], $onlyOwner);
            $balance += $period['recosting'] - $period['paid'];
        }
        
        return $balance;
    }
    
    protected function getServices(array $services, bool $onlyOwner) : array
    {
        $results = [];
        
        foreach ($services as $service) {
            if ($service['onlyOwner'] === $onlyOwner) $results[] = $service;
        }
        return $results;
    }
    
    public function getDebtPeriods(bool $onlyOwner) : array
    {
        $balance = $this->getBalance($onlyOwner);
        $periods = [];
        
        if ($balance <= 0) return [];
        
        foreach(array_reverse($this->account->periods) as $period) {
            $accrual = $this->calculateAmountAccruals($period['services'], $onlyOwner);
            $period['services'] = $this->getServices($period['services'], $onlyOwner);
            $periods[] = [
                'period' => $period,
                'amountAccruals' => $accrual,
                'inputBalance' => $balance,
                'outputBalance' => $balance -= $accrual,
            ];
            
            if ($balance <= 0) return [
                'balance' => $balance,
                'periods' => $periods,
            ];
        }
        
        return [
            'balance' => $balance,
            'periods' => $periods,
        ];
    }
    
    protected function dateIn(Carbon $date, Carbon $start, Carbon $end) : bool
    {
        return $date >= $start && $date <= $end;
    }
    
    protected function getDatesFromInterval(array $dates, Carbon $start, Carbon $end) : array
    {   
        $results = [];
        foreach ($dates as $date) {
            if ($date) {
                $date = Carbon::parse($date);
                if ($this->dateIn($date, $start, $end)) $results[] = $date;
            }
        }
        
        return $results;
    }
    
    protected function getResponderDates(Debtor $debtor, Carbon $start, Carbon $end) : array
    {
        $dates = [];
        
        foreach ($debtor->residencePeriods as $period) {
            $dates = array_merge($dates, $this->getDatesFromInterval(collect($period)->values()->all(), $start, $end));
        }
        
        foreach ($debtor->property as $property) {
            $dates = array_merge($dates, $this->getDatesFromInterval([$property['start'], $property['end']], $start, $end));
        }
        
        $puberty = $debtor->birthday ? $debtor->birthday->addYears(18)->addDay() : Carbon::parse('1900-01-01');
        if ($this->dateIn($puberty, $start, $end)) $dates[] = $puberty;
        
        return $dates;
    }
    
    public function getDates(array $periods) : array
    {
        $dates = [];
        
        $end = Carbon::parse($periods['periods'][0]['period']['month'])->endOfMonth();
        $start = Carbon::parse(end($periods['periods'])['period']['month']);
        
        $dates[] = $start;
        $dates[] = $end;
        
        foreach ($this->account->responders as $responder) {
            $dates = array_merge($dates, $this->getResponderDates($responder, $start, $end));
        }
        
        $dates = collect($dates)->sort()->unique()->values()->all();
        
        return $dates;
    }
    
    protected function isResponderAdult(Debtor $person, Carbon $date) : bool
    {   
        $puberty = $person->birthday ? $person->birthday->addYears(18)->addDay() : Carbon::parse('1900-01-01');
        return  $puberty <= $date;
    }
    
    protected function isResponderAlive(Debtor $person) : bool
    {   
        if (! $person->deathday) return true;
        
        return false;
    }
    
    protected function isResponderLive(Debtor $person, Carbon $date) : bool
    {   
        if (! $person->residencePeriods) return false;
        
        foreach ($person->residencePeriods as $residencePeriod) {
            if ($this->dateIn($date, Carbon::parse($residencePeriod['arrival']), Carbon::parse($residencePeriod['departure']))) return true;
        }
        
        return false;
    }
    
    protected function isResponderOwner(Debtor $person, Carbon $date) : bool
    {   
        if (! $person->property) return false;
        
        foreach ($person->property as $propertyPeriod) {
            if ($this->dateIn($date, Carbon::parse($propertyPeriod['start']), Carbon::parse($propertyPeriod['end']))) return true;
        }
        
        return false;
    }
    
    protected function getResponders(Carbon $date) : array
    {
        $responders = [];
        $execptions = [];
        
        foreach ($this->account->responders as $person) {
            $isOwner = $this->isResponderOwner($person, $date);
            $isCohabitant = $this->isResponderLive($person, $date);
            if ($isOwner || $isCohabitant) {
                if ($this->isResponderAlive($person) && $this->isResponderAdult($person, $date)) $responders[($isOwner ? 'owner' : 'cohabitant')][] = $person;
                elseif (! $this->isResponderAdult($person, $date)) $execptions[($isOwner ? 'owner' : 'cohabitant')]['notAdult'][] = $person;
                elseif (! $this->isResponderAlive($person)) $execptions[($isOwner ? 'owner' : 'cohabitant')]['notAlive'][] = $person;
            }
        }
        
        return [
            'responders' => $responders,
            'execptions' => $execptions,
        ];
    }
    
    protected function getBalanceOnDate(array $periods, Carbon $onDate) : float
    {
        $onDateMonth = Carbon::parse($onDate->format('Y-m-d'))->startOfMonth();
        $saldoInput = 0;
        
        foreach ($periods as $period) {
            $datePeriod = Carbon::parse($period['period']['month']);
            if ($datePeriod == $onDateMonth) {
                //$saldoInput = $period
            }
            
        }
        
        return 1;
    }
    
    public function getCalculation()
    {
        $calculations = [];
        $ownerPeriods = $this->getDebtPeriods(true);
        $otherPeriods = $this->getDebtPeriods(false);
        $ownerDates = $this->getDates($ownerPeriods);
        $otherDates = $this->getDates($otherPeriods);
        
        dd($ownerPeriods, $otherPeriods);
        $start = array_shift($ownerPeriods);
        
        foreach ($ownerDates as $key => $date) {
            if ($key < (count($ownerDates) - 1)) $date->subDay();
            
            $calculations[] = [
                'start' => $start->format('d.m.Y'),
                'end' => $date->format('d.m.Y'),
                'responders' => $this->getResponders($start),
                'calculation' => [
                    'owner' => 1,
                    'all' => 2,
                ],
            ];
            $start = $date->addDay();
        }
        return $calculations;
    }
}