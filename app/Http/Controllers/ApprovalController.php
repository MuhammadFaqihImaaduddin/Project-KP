<?php

namespace App\Http\Controllers;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;


class ApprovalController extends Controller
{
    public function approveSubmissionKantor($submissionId, Request $request)
    {
        try {
            $submission = Submission::findOrFail($submissionId);
            $keterangan = $request->input('keterangan_approval_approve');

            $submission->status_id = 2;
            $submission->keterangan_approval = $keterangan;
            $submission->save();

            return redirect()->route('Approval.DashboardKantor')->with('success', 'Pengajuan disetujui oleh Kantor.');
        } catch (\Exception $e) {
            Log::error('Error approving submission Kantor: ' . $e->getMessage());
            return redirect()->route('Approval.DashboardKantor')->with('error', 'Error approving submission Kantor: ' . $e->getMessage());
        }
    }

    public function rejectSubmissionKantor($submissionId, Request $request)
    {
        $request->validate([
            'keterangan_approval_reject' => 'required|string|min:10',
        ], [
            'keterangan_approval_reject.required' => 'Alasan penolakan wajib diisi.',
            'keterangan_approval_reject.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        try {
            $submission = Submission::findOrFail($submissionId);
            $keterangan = $request->input('keterangan_approval_reject');

            $submission->status_id = 3;
            $submission->keterangan_approval = $keterangan;
            $submission->save();

            return redirect()->route('Approval.DashboardKantor')->with('success', 'Pengajuan ditolak oleh Kantor.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Validasi gagal.');
        } catch (\Exception $e) {
            Log::error('Error rejecting submission Kantor: ' . $e->getMessage());
            return redirect()->route('Approval.DashboardKantor')->with('error', 'Error rejecting submission Kantor: ' . $e->getMessage());
        }
    }

    public function approveSubmissionPabrik($submissionId, Request $request)
    {
        try {
            $submission = Submission::findOrFail($submissionId);
            $keterangan = $request->input('keterangan_approval_approve');

            $submission->status_id = 2;
            $submission->keterangan_approval = $keterangan;
            $submission->save();

            return redirect()->route('Approval.DashboardPabrik')->with('success', 'Pengajuan disetujui oleh Pabrik.');
        } catch (\Exception $e) {
            Log::error('Error approving submission Pabrik: ' . $e->getMessage());
            return redirect()->route('Approval.DashboardPabrik')->with('error', 'Error approving submission Pabrik: ' . $e->getMessage());
        }
    }

    public function rejectSubmissionPabrik($submissionId, Request $request)
    {
        $request->validate([
            'keterangan_approval_reject' => 'required|string|min:10',
        ], [
            'keterangan_approval_reject.required' => 'Alasan penolakan wajib diisi.',
            'keterangan_approval_reject.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        try {
            $submission = Submission::findOrFail($submissionId);
            $keterangan = $request->input('keterangan_approval_reject');

            $submission->status_id = 3;
            $submission->keterangan_approval = $keterangan;
            $submission->save();

            return redirect()->route('Approval.DashboardPabrik')->with('success', 'Pengajuan ditolak oleh Pabrik.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Validasi gagal.');
        } catch (\Exception $e) {
            Log::error('Error rejecting submission Pabrik: ' . $e->getMessage());
            return redirect()->route('Approval.DashboardPabrik')->with('error', 'Error rejecting submission Pabrik: ' . $e->getMessage());
        }
    }
}
