<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        factory(Photo::class)->create();
        $this->photo = Photo::first();
    }

    public function should_add_favorite()
    {
        $response = $this->actingAs($this->user)
        ->json('PUT', route('photo.like', [
            'id' => $this->photo->id
        ]));

        $response->assertStatus(200)
        ->assertJsonFragment([
            'photo_id' => $this->photo->id,
            'liked_by_user' => false,
            'likes_count' => 0,
        ]);

        $this->assertEquals(1, $this->photo->likes()->count());
    }

    public function should_2回同じ写真にいいねしても1個しかいいねがつかない()
    {
        $param = ['id' => $this->photo->id];
        $this->actingAs($this->user)->json('PUT', route('photo.like', $param));
        $this->actingAs($this->user)->json('PUT', route('photo.like', $param));

        $this->assertEquals(1, $this->photo->likes()->count());
    }

    public function should_いいねを解除できる()
    {
        $this->photo->likes()->attach($this->user->id);

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('photo.like', [
                'id' => $this->photo->id,
            ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'photo_id' => $this->photo->id,
                'liked_by_user' => false,
                'likes_count' => 0,
            ]);

        $this->assertEquals(0, $this->photo->likes()->count());
    }
    
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
