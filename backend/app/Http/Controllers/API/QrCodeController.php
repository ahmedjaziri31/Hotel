<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the QR codes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $qrCodes = QrCode::with('room')->get();
        return response()->json($qrCodes);
    }

    /**
     * Store a newly created QR code in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'expiry_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate a unique code
        $uniqueCode = Str::uuid()->toString();

        $qrCode = QrCode::create([
            'room_id' => $request->room_id,
            'unique_code' => $uniqueCode,
            'expiry_date' => $request->expiry_date,
            'status' => 'active',
        ]);

        return response()->json($qrCode, 201);
    }

    /**
     * Display the specified QR code.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $qrCode = QrCode::with('room')->findOrFail($id);
        return response()->json($qrCode);
    }

    /**
     * Find a QR code by its unique code.
     *
     * @param  string  $uniqueCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByUniqueCode($uniqueCode)
    {
        $qrCode = QrCode::with('room')->where('unique_code', $uniqueCode)
            ->where('status', 'active')
            ->first();

        if (!$qrCode) {
            return response()->json(['message' => 'QR code not found or inactive'], 404);
        }

        // Check if the QR code has expired
        if ($qrCode->expiry_date && now()->gt($qrCode->expiry_date)) {
            return response()->json(['message' => 'QR code has expired'], 403);
        }

        return response()->json($qrCode);
    }

    /**
     * Update the specified QR code in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $qrCode = QrCode::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'room_id' => 'sometimes|exists:rooms,id',
            'expiry_date' => 'nullable|date',
            'status' => 'sometimes|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $qrCode->update($request->all());
        return response()->json($qrCode);
    }

    /**
     * Remove the specified QR code from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $qrCode = QrCode::findOrFail($id);
        $qrCode->delete();
        return response()->json(['message' => 'QR code deleted successfully']);
    }

    /**
     * Generate a new QR code for a specific room.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $roomId
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request, $roomId)
    {
        $room = Room::findOrFail($roomId);

        $validator = Validator::make($request->all(), [
            'expiry_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate a unique code
        $uniqueCode = Str::uuid()->toString();

        $qrCode = QrCode::create([
            'room_id' => $roomId,
            'unique_code' => $uniqueCode,
            'expiry_date' => $request->expiry_date,
            'status' => 'active',
        ]);

        // Return QR code data, including the QR code URL/base64 if needed
        return response()->json([
            'qr_code' => $qrCode,
            'room' => $room,
            'qr_data' => url('/api/qr-codes/' . $uniqueCode), // URL to access this QR code
        ], 201);
    }
}
