<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TrackingLog;
use App\Models\Container;

class TrackingLogController extends Controller
{
    /**
     * List tracking logs untuk container tertentu.
     */
    public function index($containerId)
    {
        $container = Container::find($containerId);

        if (!$container) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak ditemukan.',
            ], 404);
        }

        $logs = TrackingLog::where('container_id', $containerId)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'container' => [
                'id' => $container->id,
                'kode_container' => $container->kode_container,
                'jenis_limbah' => $container->jenis_limbah,
                'status' => $container->status,
            ],
            'data' => $logs,
        ], 200);
    }

    /**
     * Tambah tracking log baru untuk container.
     */
    public function store(Request $request, $containerId)
    {
        $container = Container::find($containerId);

        if (!$container) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:1000',
            'operator' => 'required|string|max:255',
            'status_perjalanan' => 'required|string|in:Received,In Transit,Processing,Full,Maintenance,Inspection,Monitoring,Repaired,Completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $log = TrackingLog::create([
            'container_id' => $containerId,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi,
            'catatan' => $request->catatan,
            'operator' => $request->operator,
            'status_perjalanan' => $request->status_perjalanan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tracking log berhasil ditambahkan.',
            'data' => $log,
        ], 201);
    }
}
