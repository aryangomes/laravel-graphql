<?php

namespace App\GraphQL\Mutations;

use App\Models\Post;
use Illuminate\Support\Facades\Log;

final class CreatePostMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
        try {
            return Post::create($args);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
