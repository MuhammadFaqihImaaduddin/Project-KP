<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitKerjaController extends Controller
{
            // Method ini akan digunakan untuk mengambil data unit kerja
            public function getUnitKerjaOptions()
            {
                // Ambil data unit kerja dari database menggunakan model
                $unitKerjaOptions = UnitKerja::pluck('nama', 'id');

                // Kirim data sebagai JSON response
                return response()->json($unitKerjaOptions);
            }

            public function getRoles()
            {
                $roleOptions = Role::whereIn('name', ['Visitor', 'Karyawan'])->pluck('name', 'id');

                return response()->json($roleOptions);
            }
}
