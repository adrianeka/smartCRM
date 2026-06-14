<?php

namespace App\Services\Analytics;

use App\Exports\AnalyticsReportExport;
use App\Models\AnalyticsReportDefinition;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ReportExportService
{
    public function __construct(private readonly ReportBuilderService $builder) {}

    public function download(AnalyticsReportDefinition $report, User $user, string $format): Response
    {
        $format = $this->validatedFormat($format);
        $filename = $this->filename($report, $format);

        if ($format === 'xlsx') {
            return Excel::download($this->excelExport($report, $user), $filename);
        }

        $content = $format === 'pdf'
            ? $this->pdfContent($report, $user)
            : $this->csvContent($report, $user);

        return response($content, 200, [
            'Content-Type' => $format === 'pdf' ? 'application/pdf' : 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function store(AnalyticsReportDefinition $report, User $user, string $format): string
    {
        $format = $this->validatedFormat($format);
        $path = 'analytics-reports/'.now()->format('Y/m').'/'.$this->filename($report, $format);

        if ($format === 'xlsx') {
            Excel::store($this->excelExport($report, $user), $path, 'local');

            return $path;
        }

        Storage::disk('local')->put(
            $path,
            $format === 'pdf'
                ? $this->pdfContent($report, $user)
                : $this->csvContent($report, $user),
        );

        return $path;
    }

    private function excelExport(AnalyticsReportDefinition $report, User $user): AnalyticsReportExport
    {
        $data = $this->builder->build($report, $user);

        return new AnalyticsReportExport(
            name: $report->name,
            headings: $data['headings'],
            rows: $data['rows'],
            primaryColor: $report->branding['primary_color'] ?? 'F59E0B',
        );
    }

    private function csvContent(AnalyticsReportDefinition $report, User $user): string
    {
        $data = $this->builder->build($report, $user);
        $stream = fopen('php://temp', 'w+');

        fputcsv($stream, [$report->branding['title'] ?? 'SmartCRM Analytics']);
        fputcsv($stream, [$report->name]);
        fputcsv($stream, ['Generated at', now()->toIso8601String()]);
        fputcsv($stream, []);
        fputcsv($stream, $data['headings']);

        foreach ($data['rows'] as $row) {
            fputcsv($stream, $row);
        }

        rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);

        return $content;
    }

    private function pdfContent(AnalyticsReportDefinition $report, User $user): string
    {
        $options = new Options;
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('reports.analytics', [
            'report' => $report,
            'data' => $this->builder->build($report, $user),
        ])->render());
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }

    private function validatedFormat(string $format): string
    {
        abort_unless(in_array($format, ['csv', 'xlsx', 'pdf'], true), 404);

        return $format;
    }

    private function filename(AnalyticsReportDefinition $report, string $format): string
    {
        return str($report->name)
            ->slug()
            ->append('-'.now()->format('Ymd-His').'.'.$format)
            ->toString();
    }
}
