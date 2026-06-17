<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opportunity;
use App\Models\OpportunityTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class OpportunityTaskController extends Controller
{
    public function index($id)
{
    $opportunity = Opportunity::findOrFail($id);

    return response()->json([
        'status' => 'success',
        'data' => $opportunity->tasks
    ]);
}


public function store(Request $request, $id)
{
    $opportunity = Opportunity::findOrFail($id);

    $task = OpportunityTask::create([
        'opportunity_id' => $opportunity->id,
        'title' => $request->title,
        'due_date' => $request->due_date,
        'status' => 'Pending'
    ]);

    DB::table('notifications')->insert([
        'id' => Str::uuid(),
        'type' => 'OpportunityTaskCreated',
        'notifiable_type' => \App\Models\User::class,
        'notifiable_id' => 1,
        'data' => json_encode([
            'title' => 'New Opportunity Task',
            'message' => 'Task "' . $task->title . '" has been created',
            'opportunity_id' => $opportunity->id
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json([
        'status' => 'success',
        'data' => $task
    ]);
}



}
