<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opportunity;
use App\Models\OpportunityTask;


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

    return response()->json([
        'status' => 'success',
        'data' => $task
    ]);
}
}
