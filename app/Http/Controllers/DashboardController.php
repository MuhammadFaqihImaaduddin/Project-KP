<?php

namespace App\Http\Controllers;

use App\Models\AreaKerja;
use App\Models\UnitKerja;
use App\Models\FileUpload;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\DataSubmission;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{
    public function user1() // Dashboard VP Kamtib (Kantor)
    {
        Log::info('[DASHBOARD_KANTOR] - Entered user1() method.'); // <<< TAMBAHKAN INI
        $user = auth()->user();
        $areaKerjaApprover = $user->areaKerja;

        if ($areaKerjaApprover) {
            $unitKerjaIdsInArea = UnitKerja::where('area_kerja_id', $areaKerjaApprover->id)->pluck('id');
            $query = Submission::with(['user', 'role', 'unitKerja', 'status'])
                                ->whereIn('unit_kerja', $unitKerjaIdsInArea);
            $search = Request()->get('search');
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')->orWhere('NPK', 'like', '%' . $search . '%');
                });
            }
            $submissions = $query->paginate(10);
        } else {
            $submissions = collect();
        }
        return view('Approval.DashboardKantor', ['submissions' => $submissions]);
    }


    public function user2() // Dashboard VP Operasi 1 (Pabrik)
    {
        Log::info('[DASHBOARD_PABRIK] - Entered user2() method.'); // <<< TAMBAHKAN INI
        $user = auth()->user();
        $areaKerjaApprover = $user->areaKerja;

        if ($areaKerjaApprover) {
            $unitKerjaIdsInArea = UnitKerja::where('area_kerja_id', $areaKerjaApprover->id)->pluck('id');
            $query = Submission::with(['user', 'role', 'unitKerja', 'status'])
                                ->whereIn('unit_kerja', $unitKerjaIdsInArea);
            $search = Request()->get('search');
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')->orWhere('NPK', 'like', '%' . $search . '%');
                });
            }
            $submissions = $query->paginate(10);
        } else {
            $submissions = collect();
        }
        return view('Approval.DashboardPabrik', ['submissions' => $submissions]);
    }

    // public function user3() { ... } // Hapus jika tidak terpakai

    public function showDashboardPemohon() // Dashboard Unit Kerja (Pemohon)
    {
        Log::info('[DASHBOARD_PEMOHON] - Entered showDashboardPemohon() method.'); // <<< TAMBAHKAN INI
        $userId = Auth::id();
        $submissions = Submission::with(['unitKerja', 'dataSubmission.role', 'dataSubmission.fileUploads', 'status'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('Pemohon.DashboardPemohon', compact('submissions'));
    }

    public function cek() // Pengalihan utama setelah login
    {
        $user = auth()->user();
        $userRole = $user->role_id;
        Log::info('[DASHBOARD_CEK] - User ' . $user->username . ' (ID: ' . $user->id . ', Role ID: ' . $userRole . ') is in DashboardController@cek.');

        if ($userRole === 1) { // Admin
            Log::info('[DASHBOARD_CEK] - Redirecting Admin (Role ID 1) to admin.dashboard.');
            return redirect()->route('admin.admin.dashboard');
        } elseif ($userRole === 2) { // VP Kamtib (Kantor)
            Log::info('[DASHBOARD_CEK] - Redirecting VP Kamtib (Role ID 2) to Approval.DashboardKantor.');
            return redirect()->route('Approval.DashboardKantor');
        } elseif ($userRole === 3) { // VP Operasi 1 (Pabrik)
            Log::info('[DASHBOARD_CEK] - Redirecting VP Operasi 1 (Role ID 3) to Approval.DashboardPabrik.');
            return redirect()->route('Approval.DashboardPabrik');
        } elseif ($userRole === 4) { // Unit Kerja (Pemohon)
            Log::info('[DASHBOARD_CEK] - Redirecting Unit Kerja (Role ID 4) to Pemohon.DashboardPemohon.');
            return redirect()->route('Pemohon.DashboardPemohon');
        } else {
            Log::warning('[DASHBOARD_CEK] - User role ' . $userRole . ' is UNKNOWN. Redirecting to login.');
            return redirect()->route('login');
        }
    }


// App\Http\Controllers\DashboardController.php

    public function CheckSubmission($submissionId)
    {
        // Ambil data submission berdasarkan submissionId
        // Pastikan relasi ktpFile, boosterFile, safetyInductionFile, pdpeFile memuat kolom yang diperlukan
        $submission = DataSubmission::with([
            'ktpFile',
            'boosterFile',
            'safetyInductionFile',
            'pdpeFile',
            'role', // Pastikan role juga dimuat
            'submission.user', // Muat relasi user dari submission parent
            'submission.unitKerja', // Muat relasi unitKerja dari submission parent
            'submission.status',
            'submission.role'
        ])->where('submission_id', $submissionId)->get();

        // Pastikan $submission tidak kosong sebelum mencoba mengakses first()
        if ($submission->isEmpty()) {
            // Handle jika tidak ada data submission ditemukan
            return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        // Tampilkan halaman Check Submission dengan data submission
        return view('Approval.CheckSubmission', ['submission' => $submission]);
    }

        public function showApprovalConfirmation($submissionId) // Mengganti nama metode
    {
        $submission = Submission::with([
            'user', 'unitKerja', 'role', 'status',
            'dataSubmission.role', 'dataSubmission.ktpFile',
            'dataSubmission.boosterFile', 'dataSubmission.safetyInductionFile', 'dataSubmission.pdpeFile'
        ])->findOrFail($submissionId);

        // Opsional: Pastikan hanya pengajuan yang disetujui yang bisa dicetak dokumennya
        if ($submission->status_id !== 2 && $submission->status_id !== 4) { // ID 2: Approved Kantor, ID 4: Approved Pabrik
            return redirect()->back()->with('error', 'Dokumen konfirmasi hanya bisa dicetak untuk pengajuan yang sudah disetujui.');
        }

        return view('Pemohon.konfirmasi', compact('submission')); // Mengganti nama Blade view juga
    }
}
