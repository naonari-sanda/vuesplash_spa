<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddCommentApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function should_add_comment()
    {
        factory(Photo::class)->create();
        $photo = Photo::first();

        $content = 'sample content';

        $response = $this->actingAs($this->user)
        ->json('POST', route('photo.comment', [
            'photo' => $photo->id,
        ]), compact('content'));

        $comments = $photo->comments()->get();

        $response->assertStatus(201)
        ->assertJsonFragment([
            'author' => [
                'name' => $this->user->name,
            ],
            'content' => $content,
        ]);

        $this->assertEquals(1, $comments->count());

        $this->assertEquals($content, $comments[0]->content);
    }

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
