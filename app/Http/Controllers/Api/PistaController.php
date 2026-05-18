<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pista;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PistaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);
        $sort = $request->get('sort', 'id_desc');

        $pistas = Pista::with('complejo')
            ->when($request->filled('disponible'), function ($query) use ($request) {
                $query->where('disponible', $request->boolean('disponible'));
            })
            ->when($request->filled('tipo'), function ($query) use ($request) {
                $query->where('tipo', $request->get('tipo'));
            })
            ->when($request->filled('es_dobles'), function ($query) use ($request) {
                $query->where('es_dobles', $request->boolean('es_dobles'));
            })
            ->when($request->filled('complejo_id'), function ($query) use ($request) {
                $query->where('complejo_id', $request->integer('complejo_id'));
            })
            ->when($request->filled('precio_min'), function ($query) use ($request) {
                $query->where('precio_hora', '>=', (float) $request->get('precio_min'));
            })
            ->when($request->filled('precio_max'), function ($query) use ($request) {
                $query->where('precio_hora', '<=', (float) $request->get('precio_max'));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->get('q'));

                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('nombre', 'like', "%{$term}%")
                        ->orWhereHas('complejo', function ($complexQuery) use ($term) {
                            $complexQuery->where('nombre', 'like', "%{$term}%")
                                ->orWhere('direccion', 'like', "%{$term}%");
                        });
                });
            })
            ->when($sort === 'precio_asc', function ($query) {
                $query->orderBy('precio_hora');
            })
            ->when($sort === 'precio_desc', function ($query) {
                $query->orderByDesc('precio_hora');
            })
            ->when($sort === 'nombre_asc', function ($query) {
                $query->orderBy('nombre');
            })
            ->when($sort === 'nombre_desc', function ($query) {
                $query->orderByDesc('nombre');
            })
            ->when(! in_array($sort, ['precio_asc', 'precio_desc', 'nombre_asc', 'nombre_desc'], true), function ($query) {
                $query->orderByDesc('id');
            })
            ->paginate(max($perPage, 1));

        return response()->json($pistas);
    }

    public function show(Pista $pista): JsonResponse
    {
        return response()->json($pista->load('complejo'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateData($request, false);

        $pista = Pista::create($data);

        return response()->json($pista->load('complejo'), 201);
    }

    public function update(Request $request, Pista $pista): JsonResponse
    {
        $data = $this->validateData($request, true);

        $pista->update($data);

        return response()->json($pista->load('complejo'));
    }

    public function destroy(Pista $pista): JsonResponse
    {
        $pista->delete();

        return response()->json(null, 204);
    }

    private function validateData(Request $request, bool $isUpdate): array
    {
        $rules = [
            'complejo_id' => [$isUpdate ? 'sometimes' : 'required', 'exists:complejos,id'],
            'nombre' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'],
            'tipo' => [$isUpdate ? 'sometimes' : 'required', 'in:indoor,outdoor'],
            'es_dobles' => ['sometimes', 'boolean'],
            'precio_hora' => [$isUpdate ? 'sometimes' : 'required', 'numeric', 'min:0'],
            'disponible' => ['sometimes', 'boolean'],
            'imagen' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];

        $data = $request->validate($rules);

        if (! $isUpdate) {
            $data['es_dobles'] = $data['es_dobles'] ?? true;
            $data['disponible'] = $data['disponible'] ?? true;
        }

        return $data;
    }
}
