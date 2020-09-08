<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{   
    public \App\Google\Calendar $calendar;
    public function __construct(\App\Google\Calendar $calendar) {
        $this->calendar = $calendar;
    }

    public function show(Request $request) {
        
        return response()->json($this->calendar->show($request->input("year"), $request->input("week")), 200, [], JSON_UNESCAPED_UNICODE);
    }
}
