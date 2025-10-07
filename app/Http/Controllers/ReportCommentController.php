<?php

    namespace App\Http\Controllers;

    use App\Models\FacilityReport;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class ReportCommentController extends Controller
    {
        // ... di dalam method store() ...
        public function store(Request $request, FacilityReport $report)
        {
        // ... (validasi)

        $report->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        // LOGIKA PENGIRIMAN NOTIFIKASI
        if (Auth::user()->role->name == 'admin_sarpras') {
            // Jika admin yang berkomentar, kirim notif ke user
            $report->reporter->notify(new \App\Notifications\NewReportComment($report));
        } else {
            // Jika user yang berkomentar, kirim notif ke admin
            // (Asumsi hanya ada 1 admin)
            $admin = \App\Models\User::where('role_id', 2)->first();
            if ($admin) {
                $admin->notify(new \App\Notifications\NewReportComment($report));
            }
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
        }
    }