<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define room types
        $roomTypes = ['Standard', 'Deluxe', 'Suite', 'Executive'];

        // Create rooms on each floor
        for ($floor = 1; $floor <= 5; $floor++) {
            for ($room = 1; $room <= 10; $room++) {
                $roomNumber = $floor . sprintf('%02d', $room);
                $type = $roomTypes[array_rand($roomTypes)];

                // Set random status (mostly available)
                $statusOptions = ['available', 'available', 'available', 'occupied', 'maintenance'];
                $status = $statusOptions[array_rand($statusOptions)];

                Room::create([
                    'room_number' => $roomNumber,
                    'floor' => $floor,
                    'type' => $type,
                    'status' => $status,
                ]);
            }
        }
    }
}
