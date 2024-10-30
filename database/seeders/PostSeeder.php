<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * It is the Post Seeder
     * @return void
    */
    public function run()
    {
        // Retrieve the first user to associate posts with a user
        $user = User::first();

        // Check if there's at least one user in the database
        if (!$user) {
            $this->command->info('No users found. Please seed the users table first.');
            return;
        }

        // Create 10 sample posts for the user
        for ($i = 1; $i <= 10; $i++) {
            Post::create([
                'title' => "Sample Post Title $i",
                'content' => "This is the content for sample post $i. It provides a brief example of a post's content.",
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('10 sample posts have been created.');
    }
}
