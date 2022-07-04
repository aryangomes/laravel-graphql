<?php

namespace App\GraphQL\Mutations;

use App\Models\Post;
use Illuminate\Support\Facades\Log;

final class DeletePostMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {

        try {
            $post = Post::findOrFail($args['id']);

            $post->delete();

            return $post;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
