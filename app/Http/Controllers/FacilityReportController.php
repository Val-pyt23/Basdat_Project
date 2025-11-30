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
        $user = Auth::user();
        // Super admin and admin_sarpras can see all reports
        if ($user->isGlobalAdmin()) {
            $reports = FacilityReport::latest()->paginate(10);
        }
        // Admin per instansi hanya melihat laporan untuk instansi mereka
        elseif ($user->isInstansiAdmin()) {
            $reports = FacilityReport::where('instansi_id', $user->instansi_id)
                                     ->latest()
                                     ->paginate(10);
        }
        // Default: user biasa hanya melihat laporannya sendiri
        else {
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
        // Hapus debug dump agar view render normal untuk user
        $user = Auth::user();
        // Pastikan peran user tersedia untuk logika otorisasi
        $userRole = $user->role->name ?? null;
        // Logika otorisasi (pastikan user berhak melihat laporan ini)
        if ($user->isInstansiAdmin() && $user->instansi_id != $report->instansi_id) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }
        // Pastikan menggunakan Auth::id() karena primary key user adalah `user_id`
        if ($userRole == 'mahasiswa' && Auth::id() != $report->user_id) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        // Logika untuk menandai notifikasi sebagai "telah dibaca"
        if ($request->has('notify_id')) {
            $notification = $user->notifications()->where('id', $request->query('notify_id'))->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }
        
        // --- PERBAIKAN DI SINI ---
        // Selalu cari apakah user saat ini sudah memberikan rating
        $existingRating = $report->ratings()->where('user_id', Auth::id())->first();
        
        // Kirim SEMUA data yang dibutuhkan ke view, termasuk $existingRating
        return view('reports.show', compact('report', 'existingRating'));
    }

    /**
     * Menampilkan form untuk mengedit laporan. (Update)
     */
    public function edit(FacilityReport $report)
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        // Jika bukan admin dan bukan pemilik, atau laporan sudah completed, tolak akses
        if (!$isAdmin && Auth::id() != $report->user_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }
        if (!$isAdmin && $report->status === 'completed') {
            abort(403, 'Laporan yang sudah selesai tidak dapat diedit.');
        }

        $categories = Category::all();
        $instansis = Instansi::all();
        return view('reports.edit', compact('report', 'categories', 'instansis'));
    }

    /**
     * Menyimpan perubahan pada laporan ke database. (Update)
     */
    public function update(Request $request, FacilityReport $report)
    {
        $isAdmin = Auth::user()->isAdmin();
        $originalStatus = $report->status;
        
        $dataToUpdate = [];

        // Prevent non-admins from updating completed reports
        if (!$isAdmin && $report->status === 'completed') {
            abort(403, 'Laporan yang sudah selesai tidak dapat diubah.');
        }

        if ($isAdmin) {
            // --- LOGIKA UNTUK ADMIN ---
            $rules = [
                'status' => 'required|string',
                'admin_comment' => 'nullable|string',
                // Aturan baru: completion_notes wajib jika status diubah menjadi 'completed'
                'completion_notes' => 'required_if:status,completed|nullable|string',
                'completion_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];
            
            $request->validate($rules);
            
            $dataToUpdate['status'] = $request->status;

            // Simpan data bukti jika statusnya completed
            if ($request->status == 'completed') {
                $dataToUpdate['completion_notes'] = $request->completion_notes;
                if ($request->hasFile('completion_image')) {
                    // Hapus gambar lama jika ada
                    if ($report->completion_image_path) {
                        Storage::disk('public')->delete($report->completion_image_path);
                    }
                    $dataToUpdate['completion_image_path'] = $request->file('completion_image')->store('completion_proofs', 'public');
                }
            }
            
            // Simpan komentar jika ada
            if ($request->filled('admin_comment')) {
                $report->comments()->create([
                    'user_id' => Auth::id(),
                    'body' => $request->admin_comment,
                ]);
                $report->reporter->notify(new \App\Notifications\NewReportComment($report));
            }

        } else {
            // ---- LOGIKA UNTUK USER BIASA (TETAP SAMA) ----
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
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        // Hanya admin atau pemilik yang belum completed yang bisa menghapus
        if (!$isAdmin) {
            if (Auth::id() != $report->user_id) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus laporan ini.');
            }
            if ($report->status === 'completed') {
                abort(403, 'Laporan yang sudah selesai tidak dapat dihapus.');
            }
        }

        if ($report->attachment_path) {
            Storage::disk('public')->delete($report->attachment_path);
        }

        $report->delete();

        if ($isAdmin) {
            return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus!');
        } else {
            return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus!');
        }
    }

    /**
     * Mengembalikan file lampiran untuk laporan (dengan otorisasi).
     */
    public function attachment(FacilityReport $report)
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        // Cek otorisasi: admin atau pemilik laporan
        if (!$user->isAdmin() && Auth::id() != $report->user_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat lampiran ini.');
        }

        if (!$report->attachment_path) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $report->attachment_path);
        if (!file_exists($fullPath)) {
            abort(404, 'File lampiran tidak ditemukan di server.');
        }

        return response()->file($fullPath);
    }
}