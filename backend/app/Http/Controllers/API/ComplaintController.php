<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\QrCode;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the complaints.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $complaints = Complaint::with(['qrCode.room', 'task'])->get();
        return response()->json($complaints);
    }

    /**
     * Store a newly created complaint in storage.
     * This endpoint is public and does not require authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code_id' => 'required|exists:qr_codes,id',
            'client_name' => 'required|string|max:100',
            'description' => 'required|string',
            'urgency_level' => 'required|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the QR code is active
        $qrCode = QrCode::find($request->qr_code_id);
        if (!$qrCode || $qrCode->status !== 'active') {
            return response()->json(['message' => 'Invalid QR code'], 400);
        }

        // Create the complaint
        $complaint = Complaint::create([
            'qr_code_id' => $request->qr_code_id,
            'client_name' => $request->client_name,
            'description' => $request->description,
            'urgency_level' => $request->urgency_level,
            'status' => 'pending',
        ]);

        // Automatically create a task for this complaint
        Task::create([
            'complaint_id' => $complaint->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Complaint submitted successfully',
            'complaint' => $complaint
        ], 201);
    }

    /**
     * Display the specified complaint.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $complaint = Complaint::with(['qrCode.room', 'task'])->findOrFail($id);
        return response()->json($complaint);
    }

    /**
     * Update the specified complaint in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'client_name' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'urgency_level' => 'sometimes|in:low,medium,high',
            'status' => 'sometimes|in:pending,in_progress,resolved,out_of_stock,blocked',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldStatus = $complaint->status;
        $complaint->update($request->all());

        // If the status has been updated, update the task status as well
        if ($request->has('status') && $oldStatus != $request->status) {
            if ($complaint->task) {
                $complaint->task->update(['status' => $request->status]);
            }
        }

        return response()->json($complaint);
    }

    /**
     * Remove the specified complaint from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();
        return response()->json(['message' => 'Complaint deleted successfully']);
    }
}
