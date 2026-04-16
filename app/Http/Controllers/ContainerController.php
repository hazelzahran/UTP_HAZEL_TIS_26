<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContainerController extends Controller
{
    // Path file JSON dummy
    private $jsonPath = 'containers.json';

    // Ambil semua data dari JSON
    private function getData(): array
    {
        if (!Storage::exists($this->jsonPath)) {
            Storage::put($this->jsonPath, json_encode([]));
        }
        return json_decode(Storage::get($this->jsonPath), true) ?? [];
    }

    // Simpan data ke JSON
    private function saveData(array $data): void
    {
        Storage::put($this->jsonPath, json_encode($data, JSON_PRETTY_PRINT));
    }

    // GET /api/containers - Ambil semua kontainer
    public function index()
    {
        $containers = $this->getData();
        return response()->json($containers, 200);
    }

    // POST /api/containers - Tambah kontainer baru
    public function store(Request $request)
    {
        $rules = [
            'container_id' => ['required', 'regex:/^[A-Z]{2}[0-9]{5}$/'],
            'waste_type'   => ['required', 'string', 'in:Chemical,Organic,Radioactive,General'],
            'weight_kg'    => ['required', 'numeric', 'min:10', 'max:5000'],
            'status'       => ['required', 'in:Active,Archived'],
        ];

        $messages = [
            'container_id.required' => 'container_id wajib diisi.',
            'container_id.regex'    => 'Format container_id tidak valid. Harus 2 huruf kapital + 5 angka. Contoh: GD00001.',
            'waste_type.required'   => 'waste_type wajib diisi.',
            'waste_type.in'         => 'waste_type harus salah satu dari: Chemical, Organic, Radioactive, General.',
            'weight_kg.required'    => 'weight_kg wajib diisi.',
            'weight_kg.numeric'     => 'weight_kg harus berupa angka.',
            'weight_kg.min'         => 'weight_kg minimal 10 kg.',
            'weight_kg.max'         => 'weight_kg maksimal 5000 kg.',
            'status.required'       => 'status wajib diisi.',
            'status.in'             => 'status harus Active atau Archived.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $containers = $this->getData();

        // Cek container_id unik
        foreach ($containers as $c) {
            if ($c['container_id'] === $request->container_id) {
                return response()->json([
                    'message' => 'Validasi gagal.',
                    'errors'  => ['container_id' => ['container_id sudah digunakan, pilih ID lain.']]
                ], 422);
            }
        }

        // Conditional Validation: Chemical maks 1000 kg
        if ($request->waste_type === 'Chemical' && (float)$request->weight_kg > 1000) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => ['weight_kg' => ['Untuk waste_type Chemical, weight_kg tidak boleh lebih dari 1000 kg.']]
            ], 422);
        }

        $newContainer = [
            'container_id'  => $request->container_id,
            'waste_type'    => $request->waste_type,
            'weight_kg'     => (float)$request->weight_kg,
            'status'        => $request->status,
            'tracking_logs' => [],
        ];

        $containers[] = $newContainer;
        $this->saveData($containers);

        return response()->json([
            'message' => 'Kontainer berhasil ditambahkan.',
            'data'    => $newContainer
        ], 201);
    }

    // PATCH /api/containers/{id} - Update parsial (archive)
    public function update(Request $request, $id)
    {
        $containers = $this->getData();
        $found = false;

        foreach ($containers as &$container) {
            if ($container['container_id'] === $id) {
                if ($request->has('status')) {
                    $container['status'] = $request->status;
                }
                $found = true;
                $updatedContainer = $container;
                break;
            }
        }

        if (!$found) {
            return response()->json(['message' => 'Kontainer tidak ditemukan.'], 404);
        }

        $this->saveData($containers);

        return response()->json([
            'message' => 'Kontainer berhasil diperbarui.',
            'data'    => $updatedContainer
        ], 200);
    }

    // DELETE /api/containers/{id} - Hapus kontainer
    public function destroy($id)
    {
        $containers = $this->getData();
        $filtered = array_filter($containers, fn($c) => $c['container_id'] !== $id);

        if (count($filtered) === count($containers)) {
            return response()->json(['message' => 'Kontainer tidak ditemukan.'], 404);
        }

        $this->saveData(array_values($filtered));

        return response()->json(['message' => 'Kontainer berhasil dihapus.'], 200);
    }

    // GET /api/containers/search?type=Chemical&min_weight=100
    public function search(Request $request)
    {
        $containers = $this->getData();

        if ($request->has('type') && $request->type !== '') {
            $containers = array_filter($containers, function ($c) use ($request) {
                return strtolower($c['waste_type']) === strtolower($request->type);
            });
        }

        if ($request->has('min_weight') && $request->min_weight !== '') {
            $containers = array_filter($containers, function ($c) use ($request) {
                return $c['weight_kg'] >= (float)$request->min_weight;
            });
        }

        return response()->json(array_values($containers), 200);
    }

    // GET /api/containers/{id}/logs - Ambil tracking logs
    public function logs($id)
    {
        $containers = $this->getData();

        foreach ($containers as $container) {
            if ($container['container_id'] === $id) {
                return response()->json([
                    'container_id'  => $id,
                    'tracking_logs' => $container['tracking_logs']
                ], 200);
            }
        }

        return response()->json(['message' => 'Kontainer tidak ditemukan.'], 404);
    }
}