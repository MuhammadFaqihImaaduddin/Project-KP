<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use App\Models\AreaKerja;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UnitKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua Unit Kerja.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Memuat relasi areaKerja dan user untuk ditampilkan
        $unitKerjaList = UnitKerja::with(['areaKerja', 'userPemohon']) // 'userPemohon' adalah relasi yang akan kita buat
                                    ->orderBy('nama')
                                    ->paginate(10);

        return view('admin.unit-kerja.index', compact('unitKerjaList'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk menambah Unit Kerja baru.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areaKerjaOptions = AreaKerja::orderBy('nama')->get(); // Ambil semua Area Kerja untuk dropdown
        return view('Admin.unit-kerja.create', compact('areaKerjaOptions'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan Unit Kerja baru dan membuat akun Pemohon terkait.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input Unit Kerja
        $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('unit_kerja', 'nama')],
            'kode' => ['required', 'string', 'digits:2', Rule::unique('unit_kerja', 'kode')], // Pastikan kode 2 digit dan unik
            'area_kerja_id' => ['required', 'exists:area_kerja,id'],
            'password' => ['nullable', 'string', 'min:8'], // Opsional jika ingin set password manual
        ]);

        DB::beginTransaction(); // Mulai transaksi database

        try {
            // 1. Buat Unit Kerja baru
            $unitKerja = UnitKerja::create([
                'nama' => $request->nama,
                'kode' => strtoupper($request->kode), // Simpan kode dalam huruf kapital
                'area_kerja_id' => $request->area_kerja_id,
            ]);

            // 2. Dapatkan role 'Unit Kerja' (Pemohon)
            $pemohonRole = Role::where('name', 'Unit Kerja')->first(); // Pastikan nama role ini ada dan ID-nya 4
            if (!$pemohonRole) {
                throw new \Exception('Role "Unit Kerja" tidak ditemukan di database. Pastikan seeder roles sudah dijalankan.');
            }

            // 3. Buat akun User untuk Pemohon terkait Unit Kerja ini
            // Generate username dan email unik dari kode unit kerja
            $defaultPassword = $request->password_pemohon ? $request->password_pemohon : 'password123'; // Password default
            $usernamePemohon = 'uk_' . strtolower($request->kode); // Contoh: uk_hrd

            $userPemohon = User::create([
                'username' => $usernamePemohon,
                'password' => Hash::make($defaultPassword),
                'role_id' => $pemohonRole->id, // ID role Unit Kerja (Pemohon)
                'unit_kerja_id' => $unitKerja->id, // Kaitkan dengan Unit Kerja yang baru dibuat
                'area_kerja_id' => $request->area_kerja_id, // Juga kaitkan dengan area kerja (opsional, tergantung desain Anda)
            ]);

            DB::commit(); // Komit transaksi jika semua berhasil

            return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja dan Akun Pemohon terkait berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error
            Log::error("Error creating Unit Kerja and Pemohon user: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan Unit Kerja. Pesan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     * Menampilkan detail Unit Kerja tertentu (opsional).
     * @param  \App\Models\UnitKerja  $unitKerja
     * @return \Illuminate\Http\Response
     */
    public function show(UnitKerja $unitKerja)
    {
        // Untuk modul sederhana, show() seringkali tidak diperlukan atau dialihkan ke edit()
        return redirect()->route('admin.unit-kerja.edit', $unitKerja);
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit Unit Kerja tertentu.
     * @param  \App\Models\UnitKerja  $unitKerja
     * @return \Illuminate\Http\Response
     */
    public function edit(UnitKerja $unitKerja)
    {
        $areaKerjaOptions = AreaKerja::orderBy('nama')->get(); // Ambil semua Area Kerja untuk dropdown
        $userPemohon = $unitKerja->userPemohon; // Dapatkan user Pemohon terkait

        return view('Admin.unit-kerja.edit', compact('unitKerja', 'areaKerjaOptions', 'userPemohon'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data Unit Kerja dan akun Pemohon terkait.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UnitKerja  $unitKerja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnitKerja $unitKerja)
    {
        // Validasi input Unit Kerja
        $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('unit_kerja', 'nama')->ignore($unitKerja->id)],
            'kode' => ['required', 'string', 'digits:2', Rule::unique('unit_kerja', 'kode')->ignore($unitKerja->id)],
            'area_kerja_id' => ['required', 'exists:area_kerja,id'],
            'password_pemohon' => ['nullable', 'string', 'min:6'], // Opsional untuk update password
        ]);

        DB::beginTransaction(); // Mulai transaksi database

        try {
            // 1. Perbarui Unit Kerja
            $unitKerja->update([
                'nama' => $request->nama,
                'kode' => strtoupper($request->kode),
                'area_kerja_id' => $request->area_kerja_id,
            ]);

            // 2. Perbarui akun User Pemohon terkait (jika ada)
            $userPemohon = $unitKerja->userPemohon; // Relasi userPemohon (akan dibuat)
            if ($userPemohon) {
                $userDataToUpdate = [
                    'username' => 'uk_' . strtolower($request->kode), // Perbarui username
                    'area_kerja_id' => $request->area_kerja_id, // Perbarui area kerja
                ];

                if ($request->filled('password_pemohon')) {
                    $userDataToUpdate['password'] = Hash::make($request->password_pemohon);
                }
                $userPemohon->update($userDataToUpdate);
            }

            DB::commit(); // Komit transaksi

            return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja dan Akun Pemohon terkait berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi
            Log::error("Error updating Unit Kerja and Pemohon user: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui Unit Kerja. Pesan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus Unit Kerja dan akun Pemohon terkait.
     * @param  \App\Models\UnitKerja  $unitKerja
     * @return \Illuminate\Http\Response
     */
    public function destroy(UnitKerja $unitKerja)
    {
        DB::beginTransaction(); // Mulai transaksi

        try {
            // Hapus akun User Pemohon terkait terlebih dahulu (jika ada)
            if ($unitKerja->userPemohon) {
                $unitKerja->userPemohon->delete();
            }

            // Hapus Unit Kerja
            $unitKerja->delete();

            DB::commit(); // Komit transaksi

            return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja dan Akun Pemohon terkait berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi
            Log::error("Error deleting Unit Kerja and Pemohon user: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus Unit Kerja. Pastikan tidak ada data pengajuan yang masih terkait dengan Unit Kerja ini. Pesan: ' . $e->getMessage());
        }
    }
}
