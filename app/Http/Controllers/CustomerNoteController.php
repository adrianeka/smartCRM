<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\AuditLog;

class CustomerNoteController extends Controller
{
public function index($id)
{
    $customer = Customer::findOrFail($id);

    $notes = $customer->notes()
        ->whereNull('parent_id')
        ->with(['user','replies.user'])
        ->latest()
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $notes
    ]);
}

public function store(Request $request, $id)
{
    $customer = Customer::findOrFail($id);

    $request->validate([
    'note' => 'required|string',
    'parent_id' => 'nullable|exists:customer_notes,id'
]);

$note = CustomerNote::create([
    'customer_id' => $customer->id,
    'user_id' => 1,
    'parent_id' => $request->parent_id,
    'note' => $request->note
]);

AuditLog::create([
    'user_id' => 1,
    'action' => 'created note',
    'entity_type' => 'CustomerNote',
    'entity_id' => $note->id
]);

preg_match_all('/@(\w+)/', $request->note, $matches);

foreach ($matches[1] as $mentionedName) {

    $user = \App\Models\User::whereRaw(
        'LOWER(name) LIKE ?',
        ['%' . strtolower($mentionedName) . '%']
    )->first();

    if ($user) {

            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'UserMentioned',
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'You were mentioned',
                    'message' => 'Super Admin mentioned you in a customer note',
                    'customer_id' => $customer->id,
                    'note_id' => $note->id
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }
}

    return response()->json([
        'status' => 'success',
        'data' => $note
    ]);
}
}
