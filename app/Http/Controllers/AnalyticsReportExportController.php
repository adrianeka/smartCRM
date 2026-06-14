<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsReportDefinition;
use App\Models\User;
use App\Services\Analytics\ReportExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AnalyticsReportExportController extends Controller
{
    public function __invoke(
        Request $request,
        AnalyticsReportDefinition $report,
        string $format,
        ReportExportService $exporter,
    ): Response {
        Gate::authorize('view', $report);

        /** @var User $user */
        $user = $request->user();

        return $exporter->download($report, $user, $format);
    }
}
