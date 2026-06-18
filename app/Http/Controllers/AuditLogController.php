<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
  public function index()
{
    return response()->json([
        'status' => 'success',
        'data' => AuditLog::latest()->get()
    ]);
}
}
