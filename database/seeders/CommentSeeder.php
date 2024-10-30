<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;

class CommentSeeder extends Seeder
{
    /**
     * It is the Comment Seeder for 3 levels
     * @return void
    */
    public function run(): void
    {
        $post = Post::first(); // Assuming at least one post exists

        for ($i = 0; $i < 20; $i++) {
            $parent = Comment::create([
                'text' => "Parent comment $i",
                'author' => 'Author ' . $i,
                'post_id' => $post->id
            ]);

            for ($j = 0; $j < 5; $j++) {
                $child = Comment::create([
                    'text' => "Child comment $i.$j",
                    'author' => 'Author ' . $j,
                    'post_id' => $post->id,
                    'parent_id' => $parent->id,
                ]);

                for ($k = 0; $k < 3; $k++) {
                    Comment::create([
                        'text' => "Grandchild comment $i.$j.$k",
                        'author' => 'Author ' . $k,
                        'post_id' => $post->id,
                        'parent_id' => $child->id,
                    ]);
                }
            }
        }
    }
}
