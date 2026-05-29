<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Container;
use App\Models\TrackingLog;

class StatsController extends Controller
{
    /**
     * Dashboard statistics.
     */
    public function index()
    {
        $total = Container::count();
        $active = Container::where('status', 'Active')->count();
        $maintenance = Container::where('status', 'Maintenance')->count();
        $full = Container::where('status', 'Full')->count();
        $archived = Container::where('status', 'Archived')->count();

        // Distribusi jenis limbah
        $jenisDistribusi = Container::selectRaw('jenis_limbah, COUNT(*) as total')
            ->groupBy('jenis_limbah')
            ->pluck('total', 'jenis_limbah');

        // Aktivitas tracking 7 hari terakhir
        $trackingActivity = TrackingLog::selectRaw('DATE(tanggal) as date, COUNT(*) as total')
            ->where('tanggal', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Recent tracking logs
        $recentLogs = TrackingLog::with('container')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'container_kode' => $log->container->kode_container ?? 'N/A',
                    'lokasi' => $log->lokasi,
                    'operator' => $log->operator,
                    'status_perjalanan' => $log->status_perjalanan,
                    'tanggal' => $log->tanggal->format('Y-m-d'),
                    'catatan' => $log->catatan,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_containers' => $total,
                'active' => $active,
                'maintenance' => $maintenance,
                'full' => $full,
                'archived' => $archived,
                'status_distribution' => [
                    'Active' => $active,
                    'Maintenance' => $maintenance,
                    'Full' => $full,
                    'Archived' => $archived,
                ],
                'jenis_distribution' => $jenisDistribusi,
                'tracking_activity' => $trackingActivity,
                'recent_logs' => $recentLogs,
            ],
        ], 200);
    }
}
