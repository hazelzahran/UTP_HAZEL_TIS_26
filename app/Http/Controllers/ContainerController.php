<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Container;
use OpenApi\Attributes as OA;

class ContainerController extends Controller
{
    /**
     * Ambil semua data kontainer dengan fitur search, filter, sort, dan pagination.
     */
    #[OA\Get(
        path: '/api/v1/gateway/containers',
        summary: 'Daftar semua kontainer',
        description: 'Mengambil daftar kontainer dengan fitur pencarian, filter status, sorting, dan pagination.',
        tags: ['Containers'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, description: 'Cari berdasarkan kode atau lokasi', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, description: 'Filter status', schema: new OA\Schema(type: 'string', enum: ['Active', 'Maintenance', 'Full', 'Archived'])),
            new OA\Parameter(name: 'jenis_limbah', in: 'query', required: false, description: 'Filter jenis limbah', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, description: 'Kolom sorting', schema: new OA\Schema(type: 'string', default: 'created_at')),
            new OA\Parameter(name: 'sort_order', in: 'query', required: false, description: 'Arah sorting', schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'], default: 'desc')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, description: 'Jumlah data per halaman', schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Daftar kontainer berhasil diambil'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function index(Request $request)
    {
        $query = Container::withCount('trackingLogs');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_container', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('jenis_limbah', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter jenis limbah
        if ($request->has('jenis_limbah') && $request->jenis_limbah) {
            $query->where('jenis_limbah', $request->jenis_limbah);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['kode_container', 'jenis_limbah', 'kapasitas', 'lokasi', 'status', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $containers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $containers->items(),
            'pagination' => [
                'current_page' => $containers->currentPage(),
                'last_page' => $containers->lastPage(),
                'per_page' => $containers->perPage(),
                'total' => $containers->total(),
            ],
        ], 200);
    }

    /**
     * Detail satu kontainer.
     */
    #[OA\Get(
        path: '/api/v1/gateway/containers/{id}',
        summary: 'Detail kontainer',
        description: 'Mengambil detail satu kontainer beserta tracking logs terbaru.',
        tags: ['Containers'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID kontainer', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Detail kontainer'),
            new OA\Response(response: 404, description: 'Kontainer tidak ditemukan'),
        ]
    )]
    public function show($id)
    {
        $container = Container::with(['trackingLogs' => function ($q) {
            $q->orderBy('tanggal', 'desc');
        }])->withCount('trackingLogs')->find($id);

        if (!$container) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $container,
        ], 200);
    }

    /**
     * Menyimpan kontainer baru.
     */
    #[OA\Post(
        path: '/api/v1/gateway/containers',
        summary: 'Tambah kontainer baru',
        description: 'Membuat kontainer baru. Hanya admin yang boleh.',
        tags: ['Containers'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['kode_container', 'jenis_limbah', 'kapasitas', 'lokasi'],
                properties: [
                    new OA\Property(property: 'kode_container', type: 'string', example: 'WC-B3-009'),
                    new OA\Property(property: 'jenis_limbah', type: 'string', example: 'Chemical'),
                    new OA\Property(property: 'kapasitas', type: 'integer', example: 500),
                    new OA\Property(property: 'lokasi', type: 'string', example: 'Gudang Utama - Zona A'),
                    new OA\Property(property: 'status', type: 'string', example: 'Active'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Kontainer berhasil dibuat'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_container' => 'required|string|max:50|unique:containers,kode_container',
            'jenis_limbah' => 'required|string|in:Chemical,Medical,Electronic,Radioactive',
            'kapasitas' => 'required|integer|min:10|max:10000',
            'lokasi' => 'required|string|max:255',
            'status' => 'nullable|string|in:Active,Maintenance,Full,Archived',
        ]);

        // Conditional Validation: Chemical max 1000kg, Radioactive max 200kg
        $validator->after(function ($validator) use ($request) {
            if ($request->jenis_limbah === 'Chemical' && $request->kapasitas > 1000) {
                $validator->errors()->add('kapasitas', 'Limbah Chemical tidak boleh melebihi kapasitas 1000 kg.');
            }
            if ($request->jenis_limbah === 'Radioactive' && $request->kapasitas > 500) {
                $validator->errors()->add('kapasitas', 'Limbah Radioactive tidak boleh melebihi kapasitas 500 kg.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $container = Container::create([
            'kode_container' => $request->kode_container,
            'jenis_limbah' => $request->jenis_limbah,
            'kapasitas' => (int) $request->kapasitas,
            'lokasi' => $request->lokasi,
            'status' => $request->get('status', 'Active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Container berhasil ditambahkan!',
            'data' => $container,
        ], 201);
    }

    /**
     * Update data kontainer.
     */
    #[OA\Put(
        path: '/api/v1/gateway/containers/{id}',
        summary: 'Update kontainer',
        description: 'Mengupdate data kontainer. Hanya admin.',
        tags: ['Containers'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'kode_container', type: 'string'),
                    new OA\Property(property: 'jenis_limbah', type: 'string'),
                    new OA\Property(property: 'kapasitas', type: 'integer'),
                    new OA\Property(property: 'lokasi', type: 'string'),
                    new OA\Property(property: 'status', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Kontainer berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tidak ditemukan'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(Request $request, $id)
    {
        $container = Container::find($id);

        if (!$container) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'kode_container' => 'sometimes|string|max:50|unique:containers,kode_container,' . $id,
            'jenis_limbah' => 'sometimes|string|in:Chemical,Medical,Electronic,Radioactive',
            'kapasitas' => 'sometimes|integer|min:10|max:10000',
            'lokasi' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|in:Active,Maintenance,Full,Archived',
        ]);

        $validator->after(function ($validator) use ($request) {
            $jenis = $request->jenis_limbah ?? '';
            $kapasitas = $request->kapasitas ?? 0;
            if ($jenis === 'Chemical' && $kapasitas > 1000) {
                $validator->errors()->add('kapasitas', 'Limbah Chemical tidak boleh melebihi kapasitas 1000 kg.');
            }
            if ($jenis === 'Radioactive' && $kapasitas > 500) {
                $validator->errors()->add('kapasitas', 'Limbah Radioactive tidak boleh melebihi kapasitas 500 kg.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $container->update($request->only(['kode_container', 'jenis_limbah', 'kapasitas', 'lokasi', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Container berhasil diupdate!',
            'data' => $container->fresh(),
        ], 200);
    }

    /**
     * Archive kontainer.
     */
    #[OA\Patch(
        path: '/api/v1/gateway/containers/{id}/archive',
        summary: 'Archive kontainer',
        description: 'Mengubah status kontainer menjadi Archived. Hanya admin.',
        tags: ['Containers'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Kontainer berhasil di-archive'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tidak ditemukan'),
        ]
    )]
    public function archive($id)
    {
        $container = Container::find($id);

        if (!$container) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak ditemukan.',
            ], 404);
        }

        if ($container->status === 'Archived') {
            return response()->json([
                'success' => false,
                'message' => 'Container sudah dalam status Archived.',
            ], 400);
        }

        $container->update(['status' => 'Archived']);

        return response()->json([
            'success' => true,
            'message' => 'Container berhasil di-archive!',
            'data' => $container->fresh(),
        ], 200);
    }

    /**
     * Hapus kontainer.
     */
    #[OA\Delete(
        path: '/api/v1/gateway/containers/{id}',
        summary: 'Hapus kontainer',
        description: 'Menghapus kontainer beserta tracking logs-nya. Hanya admin.',
        tags: ['Containers'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Kontainer berhasil dihapus'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tidak ditemukan'),
        ]
    )]
    public function destroy($id)
    {
        $container = Container::find($id);

        if (!$container) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak ditemukan.',
            ], 404);
        }

        $container->delete();

        return response()->json([
            'success' => true,
            'message' => 'Container berhasil dihapus!',
        ], 200);
    }
}