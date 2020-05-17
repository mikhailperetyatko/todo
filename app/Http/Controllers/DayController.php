<?php

namespace App\Http\Controllers;

use App\Day;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize(Day::class);
        $attr = $request->validate([
            'year' => 'integer',
        ]);
        $year = isset($attr['year']) ? $attr['year'] : Carbon::now()->format('Y');
        
        $from = Carbon::parse('01.01.' . $year);
        $to = Carbon::parse('31.12.' . $year);
        $dateRange = [];
        $days = auth()->user()->days()->whereYear('date', $year)->get();
        
        foreach (generateDateRange($from, $to, 'Y-m-d', false) as $day) {
            $isWeekend = $day->dayOfWeekIso == 6 || $day->dayOfWeekIso == 7;
            
            $key = $days->search(function ($item, $key) use ($day) {
                return $item->date == $day;
            });
            
            if ($key !== false) $isWeekend = $days[$key]->is_weekend;
            
            $dateRange[$day->month][$day->weekNumberInMonth][$day->dayOfWeekIso] = [
                'day' => $day->day,
                'is_holiday' => $isWeekend,
                'date' => $day->format('Y-m-d'),
            ];
        }

        return view('home.days.index', [
            'dateRange' => $dateRange,
            'year' => $year,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize(Day::class);
        $user = auth()->user();
        $attr = $request->validate([
            'days' => 'array|required',
            'days.*.date' => 'date|required',
            'days.*.weekend' => ['regex:/^(true|false)$/'],
            'teams' => 'array|nullable',
            'teams.*' => 'integer',
        ]);
        
        $teamsForSync = [];
        $teamsAlreadyExists = [];
        
        foreach ($user->teams()->whereIn('id', $attr['teams'] ?? [])->get() as $team) {
            if ($team->usersWithDays->isEmpty() || $team->usersWithDays->first()->id == $user->id) $teamsForSync[] = $team->id;
            else $teamsAlreadyExists[] = $team->name . ' (добавил пользователь - ' . $team->usersWithDays->first()->name . ')';
        }
        
        $user->teamsHasDays()->sync($teamsForSync);
        
        foreach ($attr['days'] as $day) {
            $date = parseDate($day['date']);
            $savedDay = Day::firstOrNew(['owner_id' => $user->id, 'date' => $date->format('Y-m-d')]);
            $savedDay->is_weekend = $day['weekend'] == 'true';
            if ($savedDay->id) {
                if (($date->dayOfWeekIso >= 6 && $savedDay->is_weekend) || ($date->dayOfWeekIso < 6 && ! $savedDay->is_weekend)) $savedDay->delete();
            } else {
                if (($date->dayOfWeekIso >= 6 && ! $savedDay->is_weekend) || ($date->dayOfWeekIso < 6 && $savedDay->is_weekend)) {
                    $savedDay->owner()->associate($user);
                    $savedDay->date = $date->format('Y-m-d');
                    $savedDay->save();
                }
            }
            
        }
        if (! $teamsAlreadyExists) flash('success');
        else flash('danger', 'Настройки сохранены, однако возникла ошибка добавления следующих команд, уже имеющих настройки календаря: ' . implode(', ', $teamsAlreadyExists));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function show(Day $day)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function edit(Day $day)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Day $day)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function destroy(Day $day)
    {
        //
    }
}
