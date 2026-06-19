<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use OpenApi\Attributes as OA;

class AuditLogController extends Controller
{

#[OA\Get(
    path: "/api/v1/audit-logs",
    summary: "Audit Trail Logs",
    tags: ["Collaboration"],
    responses: [
        new OA\Response(
            response: 200,
            description: "List of audit logs"
        )
    ]
)]
  public function index()
{
    return response()->json([
        'status' => 'success',
        'data' => AuditLog::latest()->get()
    ]);
}
}
