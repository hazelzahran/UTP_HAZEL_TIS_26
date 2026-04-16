<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Container; // Import Model Container agar tidak error 500

class ContainerController extends Controller
{
    // Mengambil semua data dari database untuk ditampilkan di tabel web
    public function index()
    {
        $containers = Container::orderBy('created_at', 'desc')->get();
        return response()->json($containers);
    }

    // Menyimpan data kontainer baru
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'container_id' => 'required|regex:/^[A-Z]{2}[0-9]{5}$/|unique:containers,container_id',
            'waste_type'   => 'required',
            'weight_kg'    => 'required|numeric|min:10|max:5000',
        ]);

        // Aturan khusus untuk limbah Chemical
        $validator->after(function ($validator) use ($request) {
            if ($request->waste_type == 'Chemical' && $request->weight_kg > 1000) {
                $validator->errors()->add('weight_kg', 'Limbah Chemical tidak boleh lebih dari 1000kg.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Simpan ke MySQL via Model
        try {
            Container::create([
                'container_id' => $request->container_id,
                'waste_type'   => $request->waste_type,
                'weight_kg'    => (int)$request->weight_kg,
                'status'       => 'Active',
            ]);

            return response()->json(['message' => 'Data Berhasil Ditambah!'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan ke database: ' . $e->getMessage()], 500);
        }
    }
}