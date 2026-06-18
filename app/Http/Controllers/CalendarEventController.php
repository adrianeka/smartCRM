<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;

class CalendarEventController extends Controller
{


public function index()
{
    return response()->json([
        'status' => 'success',
        'data' => CalendarEvent::with('customer')
            ->orderBy('event_date')
            ->get()
    ]);
}


    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'event_date' => 'required|date'
    ]);

    $event = CalendarEvent::create([
        'title' => $request->title,
        'description' => $request->description,
        'event_date' => $request->event_date,
        'type' => $request->type ?? 'Meeting',
        'customer_id' => $request->customer_id
    ]);

    return response()->json([
        'status' => 'success',
        'data' => $event
    ]);
}
}
