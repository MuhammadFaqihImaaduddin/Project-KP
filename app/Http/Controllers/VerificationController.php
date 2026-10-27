<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($token)
    {
        $submission = Submission::with([
            'user', 'unitKerja', 'role', 'status',
            'dataSubmission.role', 'dataSubmission.ktpFile',
            'dataSubmission.boosterFile', 'dataSubmission.safetyInductionFile', 'dataSubmission.pdpeFile'
        ])->where('verification_token', $token)->first();

        if (!$submission) {
            abort(404, 'Dokumen tidak ditemukan atau token tidak valid.');
        }

        return view('public_verify', compact('submission')); // Nama view ini
    }
}
