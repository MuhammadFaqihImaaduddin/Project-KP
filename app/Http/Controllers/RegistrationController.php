<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->load('unitKerja');

        $tujuanUnitKerja = optional($user->unitKerja);

        return view('Pemohon.registration', compact('tujuanUnitKerja'));
    }

    public function submitForm(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->loadMissing('unitKerja');

        $loggedInUserUnitKerjaId = optional($user->unitKerja)->id;

        $request->validate([
            'roleSelection' => 'required',
            'nama' => 'required',
            'npk' => 'required|numeric',
            'contactPerson' => 'required',
        ]);

        if (is_null($loggedInUserUnitKerjaId)) {
            Log::error('User (ID: ' . $user->id . ') tidak memiliki Unit Kerja terkait saat mencoba membuat pengajuan.');
            return redirect()->route('registration.form')->with('error', 'Anda tidak memiliki Unit Kerja terkait untuk membuat pengajuan. Silakan hubungi admin.');
        }

        try {
            DB::beginTransaction();

            $verificationToken = Str::uuid()->toString();

            $submission = new Submission([
                'user_id' => $user->id,
                'unit_kerja' => $loggedInUserUnitKerjaId,
                'nama' => $request->input('nama'),
                'npk' => $request->input('npk'),
                'contact_person' => $request->input('contactPerson'),
                'status_id' => 1,
                'verification_token' => $verificationToken,
            ]);

            $role = Role::findOrFail($request->input('roleSelection'));
            $submission->role()->associate($role);
            $submission->save();

            DB::commit();

            Log::info('Submission Data:', $submission->toArray());

            return redirect()->route('Pemohon.DashboardPemohon')->with('success', 'Pendaftaran berhasil!');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error submitting form: ' . $e->getMessage());
            Log::error('NPK Value: ' . $request->input('npk'));
            Log::error($e);

            return redirect()->route('registration.form')->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. Pesan: ' . $e->getMessage());
        }
    }
}
