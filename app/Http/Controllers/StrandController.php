<?php

namespace App\Http\Controllers;

use App\Http\Requests\StrandRequest;
use App\Models\Strand;
use Illuminate\Http\Request;

class StrandController extends Controller
{

    public function index(Request $request)
    {
        $pageSize = $request->input('page_size');
        $filter = $request->input('filter');
        $sortColumn = $request->input('sort_column', 'name');
        $sortDesc = $request->input('sort_desc', false) ? 'desc' : 'asc';

        $query = Strand::query();

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('name', 'like', "%{$filter}%")
                    ->orWhere('acronym', 'like', "%{$filter}%");
            });
        }

        if (in_array($sortColumn, ['name', 'acronym'])) {
            $query->orderBy($sortColumn, $sortDesc);
        }

        if ($pageSize) {
            $strands = $query->paginate($pageSize);
        } else {
            $strands = $query->get();
        }

        return response()->json([
            'status' => 'success',
            'message' => __('messages.success.fetched'),
            'data' => $strands,
        ]);
    }

    public function show(Strand $termimal)
    {
        return $this->success(['status' => true, 'data' => $termimal]);
    }

    public function store(StrandRequest $request)
    {
        $validated = $request->validated();

        $strand = Strand::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => __('messages.success.created'),
            'data' => $strand,
        ]);
    }

    public function update(StrandRequest $request, string $id)
    {
        $strand = Strand::findOrFail($id);

        $strand->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => __('messages.success.updated'),
            'data' => $strand,
        ], 200);
    }

    public function destroy(string $id)
    {
        $strand = Strand::findOrFail($id);
        $strand->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('messages.success.deleted'),
            'terminal' => $strand,
        ]);
    }
}
