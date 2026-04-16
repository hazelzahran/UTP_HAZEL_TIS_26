<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContainerController extends Controller
{
    // JSON Dummy Data
    private static $containers = [
        [
            'container_id' => 'GD12345',
            'waste_type' => 'Chemical',
            'weight_kg' => 500,
            'status' => 'Active',
            'tracking_logs' => [
                ['location' => 'Gudang A', 'timestamp' => '2026-04-16 10:00', 'description' => 'Masuk gudang']
            ]
        ]
    ];

    public function index() {
        return response()->json(self::$containers);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'container_id' => 'required|regex:/^[A-Z]{2}[0-9]{5}$/',
            'waste_type' => 'required',
            'weight_kg' => 'required|numeric|min:10|max:5000',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->waste_type == 'Chemical' && $request->weight_kg > 1000) {
                $validator->errors()->add('weight_kg', 'Limbah Chemical tidak boleh lebih dari 1000kg.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Push data ke array dummy agar tabel langsung update
        self::$containers[] = [
            'container_id' => $request->container_id,
            'waste_type' => $request->waste_type,
            'weight_kg' => (int)$request->weight_kg,
            'status' => 'Active',
            'tracking_logs' => []
        ];

        return response()->json(['message' => 'Data berhasil disimpan'], 201);
    }

    public function search(Request $request) {
        return response()->json(self::$containers);
    }

    public function getLogs($id) {
        return response()->json(self::$containers[0]['tracking_logs']);
    }
}