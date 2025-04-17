<?php

namespace Database\Seeders;

use App\Models\QrCode;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QrCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate QR codes for 80% of available rooms
        $rooms = Room::where('status', 'available')->get();

        foreach ($rooms as $index => $room) {
            // Only create QR codes for 80% of available rooms
            if ($index < $rooms->count() * 0.8) {
                QrCode::create([
                    'room_id' => $room->id,
                    'unique_code' => Str::uuid()->toString(),
                    'expiry_date' => Carbon::now()->addMonths(rand(1, 12)),
                    'status' => 'active',
                ]);
            }
        }

        // Create a few expired QR codes
        for ($i = 0; $i < 5; $i++) {
            $randomRoom = Room::inRandomOrder()->first();

            QrCode::create([
                'room_id' => $randomRoom->id,
                'unique_code' => Str::uuid()->toString(),
                'expiry_date' => Carbon::now()->subDays(rand(1, 30)),
                'status' => 'inactive',
            ]);
        }
    }
}
