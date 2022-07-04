<?php

namespace Tests\Feature\GraphQL;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use Tests\CreatesApplication;
use Tests\TestCase;

class PostCRUDTest extends TestCase
{
    use  MakesGraphQLRequests, RefreshesSchemaCache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootRefreshesSchemaCache();
    }

    /**
     * @test
     */
    public function create_a_post_successfully()
    {
        $dataToCreatePost = Post::factory()->make()->toArray();
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
        mutation ($title: String!,$content: String!,$user_id: ID!) {
            createPostMutation(title: $title, content: $content, user_id: $user_id,) {
            id
            title
            }
        }
    ',
            $dataToCreatePost
        );


        $response->assertJsonFragment(['title' => $dataToCreatePost['title']]);

        $postId = $response['data']['createPostMutation']['id'];

        $this->assertTrue(!is_null(Post::find($postId)));
    }


    /**
     * @test
     */
    public function get_a_post_successfully()
    {
        $post = Post::factory()->create();

        $this->graphQL(
            /** @lang GraphQL */
            '
    query($id: ID!){
        post(id:$id) {
                id
                title
        
        }
    }
    ',
            [
                'id' => $post->id
            ]
        )->assertJson([
            'data' => [
                'post' =>
                [
                    'id' => $post->id,
                    'title' => $post->title,
                ],

            ],
        ]);
    }



    /**
     * @test
     */
    public function update_a_post_successfully()
    {
        $post = Post::factory()->create();

        $dataToUpdatePost = [
            'id' => $post->id,
            'title' => 'New title'
        ];

        $response = $this->graphQL(
            /** @lang GraphQL */
            '
        mutation ($id:ID!, $title: String) {
            updatePostMutation(id: $id, title: $title) {
            title
            }
        }
    ',
            $dataToUpdatePost
        );


        $response->assertJsonFragment(['title' => $dataToUpdatePost['title']]);
    }

    /**
     * @test
     */
    public function delete_a_post_successfully()
    {
        $post = Post::factory()->create();


        $response = $this->graphQL(
            /** @lang GraphQL */
            '
        mutation ($id:ID!) {
            deletePostMutation(id: $id) {
            title
            }
        }
    ',
            ['id' => $post->id]
        );


        $this->assertTrue(is_null(Post::find($post->id)));
    }
}
