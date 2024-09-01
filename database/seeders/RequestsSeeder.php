<?php

namespace Database\Seeders;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Database\Seeder;

class RequestsSeeder extends Seeder
{
    public function run()
    {
        // Get all users
        $users = User::all();

        // Create connection requests
        foreach ($users as $user) {
            $otherUsers = User::where('id', '!=', $user->id)->inRandomOrder()->take(20)->get();
            foreach ($otherUsers as $otherUser) {
                Connection::factory()->create([
                    'user_id' => $user->id,
                    'connected_user_id' => $otherUser->id,
                    'status' => 'pending'
                ]);
            }
        }
    }
}
