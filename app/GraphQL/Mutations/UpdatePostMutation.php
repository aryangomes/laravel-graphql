<?php

namespace App\GraphQL\Mutations;

use App\Models\Post;
use Illuminate\Support\Facades\Log;

final class UpdatePostMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {

        try {
            $post = Post::findOrFail($args['id']);

            $post->update($args);

            return $post;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
