<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;
use App\Models\FacilityReport;

class RatingController extends Controller
{
    /**
     * Store a newly created rating for a report.
     */
    public function store(Request $request, $reportId)
    {
        $request->validate([
            'rating_value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $report = FacilityReport::findOrFail($reportId);

        // Only the original reporter can rate after completion
        if ($report->status !== 'completed' || Auth::id() !== $report->user_id) {
            abort(403, 'Anda tidak berhak memberikan penilaian untuk laporan ini.');
        }

        // Prevent duplicate rating
        $existing = $report->ratings()->where('user_id', Auth::id())->first();
        if ($existing) {
            return redirect()->back()->with('info', 'Anda sudah memberikan penilaian untuk laporan ini.');
        }

        Rating::create([
            'report_id' => $report->report_id,
            'user_id' => Auth::id(),
            'rating_value' => $request->rating_value,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Terima kasih, penilaian Anda telah disimpan.');
    }
}
