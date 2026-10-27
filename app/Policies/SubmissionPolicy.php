<?php

namespace App\Policies;

use App\Models\UserApproval;
use App\Models\Submission;

class SubmissionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    // SubmissionPolicy.php

    public function approve(UserApproval $user, Submission $submission)
    {
        // Logika otorisasi untuk persetujuan
        // Contoh: hanya pengguna tertentu yang dapat menyetujui
        return $user->hasRole('admin') && $user->can('approve-submission');
    }

    public function reject(UserApproval $user, Submission $submission)
    {
        // Logika otorisasi untuk penolakan
        // Contoh: hanya pengguna tertentu yang dapat menolak
        return $user->hasRole('admin') && $user->can('reject-submission');
    }

}
