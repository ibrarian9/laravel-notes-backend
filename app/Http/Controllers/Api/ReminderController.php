<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $reminders = Reminder::with('task')->get();
        return response()->json($reminders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'task_id' => 'required|exists:tasks,task_id',
                'tanggal_pengingat' => 'required|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        $reminder = Reminder::create($validatedData);

        return response()->json($reminder, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $reminder = Reminder::with('task')->find($id);

        if (!$reminder) {
            return response()->json(['message' => 'Reminder not found'], 404);
        }

        return response()->json($reminder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reminder = Reminder::find($id);

        if (!$reminder) {
            return response()->json(['message' => 'Reminder not found'], 404);
        }

        try {
            $validatedData = $request->validate([
                'task_id' => 'sometimes|exists:tasks,task_id',
                'tanggal_pengingat' => 'sometimes|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        $reminder->update($validatedData);

        return response()->json($reminder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $reminder = Reminder::find($id);

        if (!$reminder) {
            return response()->json(['message' => 'Reminder not found'], 404);
        }

        $reminder->delete();

        return response()->json(['message' => 'Reminder deleted successfully'], 204);
    }
}
