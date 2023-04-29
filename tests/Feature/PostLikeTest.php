<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can like a post', function () {
    $post = Post::factory()->create();
    $response = $this->actingAs($this->user)->postJson('/api/posts/reaction', [
        'post_id' => $post->id,
        'like' => true,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 200,
        'message' => 'You have liked this post successfully',
    ]);
});

it('can unlike a post', function () {
    $post = Post::factory()->create();
    $like = $post->likes()->create([
        'user_id' => $this->user->id,
    ]);
    $response = $this->actingAs($this->user)->postJson('/api/posts/reaction', [
        'post_id' => $post->id,
        'like' => false,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 200,
        'message' => 'You have unliked this post successfully',
    ]);
});

it('cannot like same post twice', function () {
    $post = Post::factory()->create();
    $post->likes()->create([
        'user_id' => $this->user->id,
    ]);
    $response = $this->actingAs($this->user)->postJson('/api/posts/reaction', [
        'post_id' => $post->id,
        'like' => true,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 500,
        'message' => 'You have already liked this post',
    ]);
});
