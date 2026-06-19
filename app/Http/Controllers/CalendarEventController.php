<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use OpenApi\Attributes as OA;

class CalendarEventController extends Controller
{



#[OA\Get(
    path: "/api/v1/calendar/events",
    summary: "Shared Calendar Events",
    tags: ["Collaboration"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Calendar event list"
        )
    ]
)]

public function index()
{
    return response()->json([
        'status' => 'success',
        'data' => CalendarEvent::with('customer')
            ->orderBy('event_date')
            ->get()
    ]);
}





#[OA\Post(
    path: "/api/v1/calendar/events",
    summary: "Create Calendar Event",
    tags: ["Collaboration"],
    responses: [
        new OA\Response(
            response: 201,
            description: "Event created"
        )
    ]
)]

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
