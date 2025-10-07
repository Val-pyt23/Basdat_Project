<?php

namespace App\Http\Controllers;

use App\Models\FacilityReport;
use App\Models\Category;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReportStatusUpdated;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewReportSubmitted;

class FacilityReportController extends Controller
{
    /**
     * Menampilkan daftar semua laporan. (Read)
     */
    public function index()
    {
        if (Auth::user()->role->name == 'admin_sarpras') {
            $reports = FacilityReport::latest()->paginate(10);
        } else {
            $reports = FacilityReport::where('user_id', Auth::id())
                                     ->latest()
                                     ->paginate(10);
        }
        return view('reports.index', compact('reports'));
    }

    /**
     * Menampilkan form untuk membuat laporan baru. (Create)
     */
    public function create()
    {   
        $categories = Category::all();
        $instansis = Instansi::all();
        return view('reports.create', compact('categories', 'instansis'));
    }

    /**
     * Menyimpan laporan baru ke database. (Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'instansi_id' => 'required|exists:instansi,instansi_id',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('attachments', 'public');
        }

        // --- PERBAIKAN DI SINI ---
        // Simpan hasil pembuatan laporan ke dalam variabel $newReport
        $newReport = FacilityReport::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'instansi_id' => $request->instansi_id,
            'description' => $request->description,
            'location' => $request->location,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'attachment_path' => $filePath,
        ]);
        // --- AKHIR PERBAIKAN ---

        // Sekarang $newReport sudah ada dan bisa digunakan
        $admins = User::where('role_id', 2)->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewReportSubmitted($newReport));
        }
    
    // Logika redirect yang benar setelah membuat laporan
    if (Auth::user()->role->name == 'admin_sarpras') {
        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat!');
    } else {
        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat!');
    }
}

    /**
     * Menampilkan detail satu laporan. (Read)
     */
    public function show(Request $request, FacilityReport $report)
    {
        if ($request->has('notify_id')) {
            $notification = Auth::user()->notifications()->where('id', $request->query('notify_id'))->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }
        return view('reports.show', compact('report'));
    }

    /**
     * Menampilkan form untuk mengedit laporan. (Update)
     */
    public function edit(FacilityReport $report)
    {
        $categories = Category::all();
        $instansis = Instansi::all();
        return view('reports.edit', compact('report', 'categories', 'instansis'));
    }

    /**
     * Menyimpan perubahan pada laporan ke database. (Update)
     */
    public function update(Request $request, FacilityReport $report)
    {
        // Cek apakah pengguna adalah admin
        $isAdmin = in_array(Auth::user()->role->name, ['superadmin', 'admin_instansi']);
        $originalStatus = $report->status;
        $dataToUpdate = [];

        if ($isAdmin) {
            // ---- LOGIKA UNTUK ADMIN ----
            // 1. Aturan validasi HANYA untuk admin
            $rules = [
                'status' => 'required|string',
                'admin_comment' => 'nullable|string',
            ];
            
            $request->validate($rules);
            
            // 2. Data yang akan diupdate HANYA status
            $dataToUpdate['status'] = $request->status;

            // 3. Simpan komentar jika ada
            if ($request->filled('admin_comment')) {
                $report->comments()->create([
                    'user_id' => Auth::id(),
                    'body' => $request->admin_comment,
                ]);
                // Kirim notifikasi komentar ke user
                $report->reporter->notify(new \App\Notifications\NewReportComment($report));
            }

        } else {
            // ---- LOGIKA UNTUK USER BIASA ----
            // Aturan validasi untuk user biasa (tidak ada status)
            $rules = [
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,category_id',
                'instansi_id' => 'required|exists:instansi,instansi_id',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ];
            
            $request->validate($rules);
            $dataToUpdate = $request->except(['attachment', '_token', '_method']);
        }

        // Logika untuk menangani update file lampiran (hanya untuk user)
        if (!$isAdmin && $request->hasFile('attachment')) {
            if ($report->attachment_path) {
                Storage::disk('public')->delete($report->attachment_path);
            }
            $filePath = $request->file('attachment')->store('attachments', 'public');
            $dataToUpdate['attachment_path'] = $filePath;
        }

        $report->update($dataToUpdate);

        // Kirim notifikasi HANYA jika admin mengubah status
        if ($isAdmin && $request->has('status') && $originalStatus !== $request->status) {
            $report->reporter->notify(new \App\Notifications\ReportStatusUpdated($report));
        }

        // Redirect ke halaman yang sesuai
        if ($isAdmin) {
            return redirect()->route('dashboard')->with('success', 'Laporan berhasil diperbarui!');
        } else {
            return redirect()->route('reports.index')->with('success', 'Laporan berhasil diperbarui!');
        }
    }

    /**
     * Menghapus laporan dari database. (Delete)
     */
    public function destroy(FacilityReport $report)
    {
        if ($report->attachment_path) {
            Storage::disk('public')->delete($report->attachment_path);
        }

        $report->delete();
        
        if (Auth::user()->role->name == 'admin_sarpras') {
            return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus!');
        } else {
            return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus!');
        }
    }
}