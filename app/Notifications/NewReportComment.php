<?php

namespace App\Notifications;

use App\Models\FacilityReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReportComment extends Notification
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
        $commenter = auth()->user()->username;
        return [
            'message' => $commenter . ' memberikan komentar pada laporan "' . $this->report->title . '".',
            'url' => route('reports.show', $this->report->report_id),
        ];
    }
}