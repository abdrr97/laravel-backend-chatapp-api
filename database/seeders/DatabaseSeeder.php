<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory(3)->create();

        $conversations  = Conversation::factory()
            ->count(3)
            ->create();

        $messages = Message::factory()
            ->count(5)
            ->create();
    }
}
