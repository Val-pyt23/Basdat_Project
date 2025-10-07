<?php

namespace App\Notifications;

use App\Models\FacilityReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportStatusUpdated extends Notification
{
    use Queueable;

    public $report;

    public function __construct(FacilityReport $report)
    {
        $this->report = $report;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Kirim notifikasi ke database
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Status laporan "' . $this->report->title . '" telah diubah menjadi ' . $this->report->status . '.',
            'url' => route('reports.show', $this->report->report_id),
        ];
    }
}