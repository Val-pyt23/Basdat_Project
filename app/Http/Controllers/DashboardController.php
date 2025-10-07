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
        $userRole = $user->role->name;
        $adminRoles = ['superadmin', 'admin_instansi'];

        if (in_array($userRole, $adminRoles)) {
            $reportsQuery = FacilityReport::with(['reporter', 'category', 'instansi'])->latest();

            if ($userRole == 'admin_instansi') {
                $reportsQuery->where('instansi_id', $user->instansi_id);
            }

            $reports = $reportsQuery->paginate(10);
            return view('admin.dashboard', compact('reports'));

        } else {
            $userId = $user->id;
            $pendingCount = FacilityReport::where('user_id', $userId)->where('status', 'pending')->count();
            $inProgressCount = FacilityReport::where('user_id', $userId)->where('status', 'in_progress')->count();
            $completedCount = FacilityReport::where('user_id', $userId)->where('status', 'completed')->count();

            return view('dashboard', compact('pendingCount', 'inProgressCount', 'completedCount'));
        }
    }
}