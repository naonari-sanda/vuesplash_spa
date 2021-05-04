<?php

namespace Tests\Feature;

use App\Photo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoDetailApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function should_response_data_json()
    {
        factory(Photo::class)->create();
        $photo = Photo::first();

        $response = $this->json('GET', route('photo.show', [
            'id' => $photo->id
        ]));

        $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $photo->id,
            'url' => $photo->url,
            'owner' => [
                'name' => $photo->owner->name,
            ],
            'comments' => $photo->comments
            ->sortByDesc('id')
            ->map(function ($comment) {
                return [
                    'author' => [
                        'name' => $comment->author->name,
                    ],
                    'content' => $comment->content,
                ];
            })->all()
            
        ]);
    }

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
