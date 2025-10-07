<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FacilityReport;
use App\Models\User;
use App\Notifications\ReportOverdue;
use Illuminate\Support\Facades\Notification;

class CheckOverdueReports extends Command
{
    // Nama perintah yang akan kita panggil
    protected $signature = 'reports:check-overdue';
    
    // Deskripsi perintah
    protected $description = 'Periksa laporan yang tertunda dan kirim notifikasi ke admin';

    public function handle()
    {
        $this->info('Memeriksa laporan tertunda...');

        // Cari laporan dengan status 'pending' yang dibuat lebih dari 1 hari yang lalu
        $overdueReports = FacilityReport::where('status', 'pending')
                                        ->where('created_at', '<=', now()->subDay())
                                        ->get();

        if ($overdueReports->isEmpty()) {
            $this->info('Tidak ada laporan tertunda.');
            return;
        }

        // Cari semua admin
        $admins = User::where('role_id', 2)->get();
        if ($admins->isEmpty()) {
            $this->warn('Tidak ada admin yang ditemukan untuk dikirimi notifikasi.');
            return;
        }

        // Kirim notifikasi untuk setiap laporan yang tertunda
        foreach ($overdueReports as $report) {
            Notification::send($admins, new ReportOverdue($report));
            $this->info('Notifikasi dikirim untuk laporan #' . $report->report_id);
        }

        $this->info('Selesai.');
    }
}