<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UnitKerjaController;
use App\Models\DataSubmission;
use App\Models\FileUpload;


class EditController extends Controller
{
    public function edit($submissionId, $dataSubmissionId)
    {
        $submission = Submission::with('dataSubmission')->findOrFail($submissionId);
        $dataSubmission = DataSubmission::with(['ktpFile', 'boosterFile', 'safetyInductionFile', 'pdpeFile', 'role'])->findOrFail($dataSubmissionId);

        return view('Pemohon.edit', compact('submission', 'dataSubmission'));
    }

    // Handle Update
    public function update(Request $request, $submissionId, $dataSubmissionId)
    {
        $request->validate([
            'nama_tamu' => 'required|string|max:255',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'booster' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'induction' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'pdpe' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $dataSubmission = DataSubmission::findOrFail($dataSubmissionId);
        $dataSubmission->nama_tamu = $request->input('nama_tamu');
        $dataSubmission->save();

        $this->handleFileUpload($request, $dataSubmission, 'ktp');
        $this->handleFileUpload($request, $dataSubmission, 'booster');
        $this->handleFileUpload($request, $dataSubmission, 'induction');
        $this->handleFileUpload($request, $dataSubmission, 'pdpe');

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    private function handleFileUpload(Request $request, DataSubmission $dataSubmission, $tipe)
    {
        if ($request->hasFile($tipe)) {
            $file = $request->file($tipe);
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $fileName, 'public');

            // Cek apakah ada file lama
            $existingFile = $dataSubmission->fileUploads()->where('tipe', $tipe)->first();

            if ($existingFile) {
                if ($existingFile->file_path && Storage::disk('public')->exists($existingFile->file_path)) {
                    Storage::disk('public')->delete($existingFile->file_path);
                }
                $existingFile->delete(); // hapus record-nya dari DB
            }

            // Simpan file baru
            FileUpload::create([
                'data_submission_id' => $dataSubmission->id,
                'tipe' => $tipe,
                'file_name' => $fileName,
                'file_path' => $path,
            ]);
        }
    }


    public function deleteForm($submissionId)
    {
        // Pastikan submission dengan ID yang diberikan ada
        $submission = Submission::find($submissionId);

        if (!$submission) {
            return redirect()->route('DashboardPemohon')->with('error', 'Submission not found.');
        }

        // Hanya izinkan penghapusan jika status submission adalah 1 (Pending) atau 3 (Rejected)
        if ($submission->status->id != 1 && $submission->status->id != 3) {
            return redirect()->route('DashboardPemohon')->with('error', 'You cannot delete this submission.');
        }

        // Hapus formulir dan file terkait (sesuaikan dengan logika penghapusan Anda)
        $submission->delete();

        // Redirect ke DashboardPemohon dengan pesan sukses
        return redirect()->route('Pemohon.DashboardPemohon')->with('success', 'Form deleted successfully.');
    }
}
