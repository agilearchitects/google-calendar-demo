<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCalendarEvent;
use App\Google\AddEvent;

class CalendarController extends Controller
{   
    public \App\Google\Calendar $calendar;
    public function __construct(\App\Google\Calendar $calendar) {
        $this->calendar = $calendar;
    }

    public function index(Request $request) {
        return response()->json($this->calendar->show($request->input("year"), $request->input("week")), 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function store(StoreCalendarEvent $request) {
        $validated = $request->validated();
        $event = new AddEvent($validated["title"], $validated["when"]);
        $this->calendar->add($event);
        return "1";
    }
}
