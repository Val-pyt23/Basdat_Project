<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FacilityReport;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Jika user adalah admin global (superadmin/admin_sarpras) atau admin instansi
        if ($user->isAdmin()) {
            $reportsQuery = FacilityReport::with(['reporter', 'category', 'instansi'])->latest();

            if ($user->isInstansiAdmin()) {
                $reportsQuery->where('instansi_id', $user->instansi_id);
            }

            $reports = $reportsQuery->paginate(10);
            return view('admin.dashboard', compact('reports'));

        } else {
            $userId = Auth::id();
            $pendingCount = FacilityReport::where('user_id', $userId)->where('status', 'pending')->count();
            $inProgressCount = FacilityReport::where('user_id', $userId)->where('status', 'in_progress')->count();
            $completedCount = FacilityReport::where('user_id', $userId)->where('status', 'completed')->count();

            return view('dashboard', compact('pendingCount', 'inProgressCount', 'completedCount'));
        }
    }
}