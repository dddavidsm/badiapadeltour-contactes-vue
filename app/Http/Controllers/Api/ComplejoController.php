<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complejo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComplejoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);

        $complejos = Complejo::with('pistas')
            ->when($request->filled('activo'), function ($query) use ($request) {
                $query->where('activo', $request->boolean('activo'));
            })
            ->paginate(max($perPage, 1));

        return response()->json($complejos);
    }

    public function show(Complejo $complejo): JsonResponse
    {
        return response()->json($complejo->load('pistas'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateData($request, false);

        $complejo = Complejo::create($data);

        return response()->json($complejo->load('pistas'), 201);
    }

    public function update(Request $request, Complejo $complejo): JsonResponse
    {
        $data = $this->validateData($request, true);

        $complejo->update($data);

        return response()->json($complejo->load('pistas'));
    }

    public function destroy(Complejo $complejo): JsonResponse
    {
        $complejo->delete();

        return response()->json(null, 204);
    }

    private function validateData(Request $request, bool $isUpdate): array
    {
        $rules = [
            'nombre' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'direccion' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
            'hora_apertura' => ['sometimes', 'nullable', 'date_format:H:i'],
            'hora_cierre' => ['sometimes', 'nullable', 'date_format:H:i'],
            'imagen' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];

        $data = $request->validate($rules);

        if (! $isUpdate) {
            $data['activo'] = $data['activo'] ?? true;
        }

        return $data;
    }
}
