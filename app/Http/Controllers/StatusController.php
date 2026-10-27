<?php

namespace App\Http\Controllers;
use App\Models\Status;
use App\Models\Submission;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class StatusController extends Controller
{
    public function showStatus()
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Get all submissions for the authenticated user
        $submissions = Submission::with('unitKerja')
            ->where('user_id', $userId)
            ->get();

        return view('Pemohon.status', compact('submissions'));
    }
}
