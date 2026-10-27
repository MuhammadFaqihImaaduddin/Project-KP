<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\DataSubmission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class SubRegistrationController extends Controller
{
    public function lookForm($submissionId)
    {
        $submission = Submission::findOrFail($submissionId);
        return view('Pemohon.Sub-registration', compact('submissionId', 'submission'));
    }

    public function DaftarTamu($submissionId)
    {
        $submission = Submission::with('dataSubmission.fileUploads', 'dataSubmission.role')->findOrFail($submissionId);
        return view('Pemohon.daftar_tamu', compact('submissionId', 'submission'));
    }

    public function submitSubForm(Request $request, $submissionId)
    {
        $submission = Submission::with('role')->findOrFail($submissionId);
        $parentRoleId = optional($submission->role)->id;

        $request->validate([
            'nama_tamu' => 'required|string|max:255',
            'file1' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file2' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $dataSubmission = DataSubmission::create([
                'submission_id' => $submissionId,
                'role_id' => $parentRoleId,
                'nama_tamu' => $request->input('nama_tamu'),
            ]);

            $storedFiles = [];

            if ($parentRoleId == 5) {
                $storedFiles[] = ['file' => $request->file('file1'), 'tipe' => 'ktp'];
                $storedFiles[] = ['file' => $request->file('file2'), 'tipe' => 'booster'];
            } elseif ($parentRoleId == 6) {
                $storedFiles[] = ['file' => $request->file('file1'), 'tipe' => 'induction'];
                $storedFiles[] = ['file' => $request->file('file2'), 'tipe' => 'pdpe'];
            } else {
                throw new \Exception('Role ID tidak dikenal untuk pengajuan file.');
            }

            foreach ($storedFiles as $fileData) {
                if ($fileData['file']) {
                    $path = $fileData['file']->store('uploads', 'public');

                    FileUpload::create([
                        'data_submission_id' => $dataSubmission->id,
                        'file_name' => $fileData['file']->getClientOriginalName(),
                        'file_path' => $path,
                        'tipe' => $fileData['tipe'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('daftar-tamu', ['submissionId' => $submissionId])
                ->with('success', 'Data tamu berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting sub-form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan data tamu: ' . $e->getMessage())->withInput();
        }
    }
}
