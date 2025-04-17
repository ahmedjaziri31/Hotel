<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Task::with(['complaint.qrCode.room', 'assignedUser', 'approver']);

        // Filter by assigned user if specified
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by status if specified
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // For maintenance agents, only show tasks assigned to them
        if (Auth::user()->role === 'maintenance_agent') {
            $query->where('assigned_to', Auth::id());
        }

        $tasks = $query->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complaint_id' => 'required|exists:complaints,id',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,resolved,out_of_stock,blocked',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if a task already exists for this complaint
        $existingTask = Task::where('complaint_id', $request->complaint_id)->first();
        if ($existingTask) {
            return response()->json(['message' => 'A task already exists for this complaint'], 400);
        }

        $task = Task::create($request->all());
        return response()->json($task, 201);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = Task::with(['complaint.qrCode.room', 'assignedUser', 'approver'])->findOrFail($id);

        // Check if user has permission to view this task
        if (Auth::user()->role === 'maintenance_agent' && $task->assigned_to !== Auth::id()) {
            return response()->json(['message' => 'You do not have permission to view this task'], 403);
        }

        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Check if user has permission to update this task
        if (Auth::user()->role === 'maintenance_agent' && $task->assigned_to !== Auth::id()) {
            return response()->json(['message' => 'You do not have permission to update this task'], 403);
        }

        $validator = Validator::make($request->all(), [
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,resolved,out_of_stock,blocked',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldStatus = $task->status;
        $task->update($request->all());

        // If status changed to resolved, update completed_at
        if ($request->has('status') && $request->status === 'resolved' && $oldStatus !== 'resolved') {
            $task->update(['completed_at' => now()]);
        }

        // Update the complaint status to match
        if ($request->has('status') && $oldStatus !== $request->status) {
            $task->complaint->update(['status' => $request->status]);
        }

        return response()->json($task);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Only admin or maintenance_chief can delete tasks
        if (!in_array(Auth::user()->role, ['admin', 'maintenance_chief'])) {
            return response()->json(['message' => 'You do not have permission to delete tasks'], 403);
        }

        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

    /**
     * Assign a task to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(Request $request, $id)
    {
        // Only admin, maintenance_chief or receptionist can assign tasks
        if (!in_array(Auth::user()->role, ['admin', 'maintenance_chief', 'receptionist'])) {
            return response()->json(['message' => 'You do not have permission to assign tasks'], 403);
        }

        $task = Task::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress',
        ]);

        // Update the complaint status to match
        $task->complaint->update(['status' => 'in_progress']);

        return response()->json($task);
    }

    /**
     * Mark a task as complete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Check if user has permission to complete this task
        if (Auth::user()->role === 'maintenance_agent' && $task->assigned_to !== Auth::id()) {
            return response()->json(['message' => 'You do not have permission to complete this task'], 403);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task->update([
            'status' => 'resolved',
            'completed_at' => now(),
            'notes' => $request->notes ?: $task->notes,
            'approved_by' => in_array(Auth::user()->role, ['admin', 'maintenance_chief']) ? Auth::id() : null,
        ]);

        // Update the complaint status to match
        $task->complaint->update(['status' => 'resolved']);

        return response()->json($task);
    }
}
