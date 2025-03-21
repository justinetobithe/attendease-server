<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRecordRequest;
use App\Models\AttendanceRecord;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size');
        $filter = $request->input('filter');
        $sortColumn = $request->input('sort_column', 'date');
        $sortDesc = $request->input('sort_desc', false) ? 'desc' : 'asc';

        $query = AttendanceRecord::with(['student.user']);

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->whereHas('student.user', function ($query) use ($filter) {
                    $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$filter}%"]);
                })
                    ->orWhere('date', 'like', "%{$filter}%")
                    ->orWhere('time_in', 'like', "%{$filter}%")
                    ->orWhere('time_out', 'like', "%{$filter}%");
            });
        }

        if (in_array($sortColumn, ['date', 'time_in', 'time_out'])) {
            $query->orderBy($sortColumn, $sortDesc);
        }

        if ($pageSize) {
            $attendanceRecords = $query->paginate($pageSize);
        } else {
            $attendanceRecords = $query->get();
        }

        return response()->json([
            'status' => 'success',
            'message' => __('messages.success.fetched'),
            'data' => $attendanceRecords,
        ]);
    }

    public function store(AttendanceRecordRequest $request)
    {
        $validated = $request->validated();

        $student = Student::where('student_number', $validated['student_number'])->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.error.student_not_found'),
            ]);
        }

        $existingAttendanceRecord = AttendanceRecord::where('student_id', $student->id)
            ->whereDate('date', Carbon::today()->toDateString())
            ->first();

        if ($existingAttendanceRecord) {
            if ($existingAttendanceRecord->time_in && !$existingAttendanceRecord->time_out) {
                $existingAttendanceRecord->update([
                    'time_out' => Carbon::now()->format('H:i:s'),
                ]);

                $existingAttendanceRecord->load('student.user');

                return response()->json([
                    'status' => 'success',
                    'message' => __('messages.success.time_out_updated'),
                    'data' => $existingAttendanceRecord,
                ]);
            }

            if ($existingAttendanceRecord->time_in && $existingAttendanceRecord->time_out) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('messages.errors.already_checked_in_and_out_today'),
                ]);
            }
        } else {
            $attendanceRecord = AttendanceRecord::create([
                'student_id' => $student->id,
                'date' => Carbon::today()->toDateString(),
                'time_in' => Carbon::now()->format('H:i:s'),
                'time_out' => null,
            ]);

            $attendanceRecord->load('student.user');

            return response()->json([
                'status' => 'success',
                'message' => __('messages.success.attendance_record_created'),
                'data' => $attendanceRecord,
            ]);
        }

        $attendanceCountToday = AttendanceRecord::where('student_id', $student->id)
            ->whereDate('date', Carbon::today()->toDateString())
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->count();

        if ($attendanceCountToday >= 3) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.errors.max_checkins_today'),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('messages.errors.unknown_error'),
        ]);
    }

    public function getByStudentNumber(Request $request, $studentNumber)
    {
        $student = Student::where('student_number', $studentNumber)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found.',
            ], 404);
        }

        $attendanceRecords = AttendanceRecord::with(['student.user'])
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance records retrieved successfully.',
            'data' => $attendanceRecords,
        ]);
    }
}
