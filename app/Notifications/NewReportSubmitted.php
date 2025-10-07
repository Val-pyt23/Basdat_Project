<?php

namespace App\Notifications;

use App\Models\FacilityReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReportSubmitted extends Notification
{
    use Queueable;

    public $report;

    public function __construct(FacilityReport $report)
    {
        $this->report = $report;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $reporterName = $this->report->reporter->username;
        return [
            'message' => 'Laporan baru "' . $this->report->title . '" telah diajukan oleh ' . $reporterName . '.',
            'url' => route('reports.show', $this->report->report_id),
        ];
    }
}