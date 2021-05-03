<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Photo;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PhotoListApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function should_currect_data_json()
    {
        factory(Photo::class, 5)->create();

        $response = $this->json('GET', route('photo.index'));

        $photos = Photo::with(['owner'])->orderBy('created_at', 'desc')->get();

        $expected_data = $photo->map(function($photo) {
            return [
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name,
                ]
                ];
        })->all();

        $response->assertStatus(200)
        // レスポンスJSONのdata項目に含まれる要素が5つであること
        ->assertJsonCount(5, 'data')
        ->assertJsonFragment([
            'data' => $expected_data,
        ]);
    }

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
