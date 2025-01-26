<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size');
        $filter = $request->input('filter');
        $sortColumn = $request->input('sort_column', 'name');
        $sortDesc = $request->input('sort_desc', false) ? 'desc' : 'asc';

        $query = Student::with(['user', 'strand']);

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('student_number', 'like', "%{$filter}%")
                    ->orWhereHas('user', function ($query) use ($filter) {
                        $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$filter}%"]);
                    })
                    ->orWhereHas('strand', function ($query) use ($filter) {
                        $query->where('name', 'like', "%{$filter}%");
                    });
            });
        }

        if (in_array($sortColumn, ['student_number', 'user', 'strand'])) {
            if ($sortColumn === 'user') {
                $query->orderByRaw("CONCAT(user.first_name, ' ', user.last_name) {$sortDesc}");
            } elseif ($sortColumn === 'strand') {
                $query->orderBy('strand.name', $sortDesc);
            } else {
                $query->orderBy($sortColumn, $sortDesc);
            }
        }

        if ($pageSize) {
            $students = $query->paginate($pageSize);
        } else {
            $students = $query->get();
        }

        return response()->json([
            'status' => 'success',
            'message' => __('messages.success.fetched'),
            'data' => $students,
        ]);
    }
}
