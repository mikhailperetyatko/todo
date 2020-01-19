<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAUserCanCreatePostWithoutTags()
    {
        //$this->withoutExceptionHandling();
        
        $this->actingAs($user = factory(\App\User::class)->create());
        
        $this->post('/posts', $attr = factory(\App\Post::class)->raw(['published' => 1, 'owner_id' => $user->id]));
        
        $this->assertDatabaseHas('posts', $attr);
    }
    
    public function testAUserCanCreatePostWithTags()
    {
        //$this->withoutExceptionHandling();
        
        $this->actingAs($user = factory(\App\User::class)->create());
        $tag_attr = factory(\App\Tag::class)->raw();
        
        $this->post('/posts', 
            array_merge(
                $post_attr = factory(\App\Post::class)->raw([
                    'published' => 1, 
                    'owner_id' => $user->id,
                ]),
                ['tags' => $tag_attr['name']],
            )
        );
        
        $this->assertDatabaseHas('posts', $post_attr)->assertDatabaseHas('tags', $tag_attr);
    }
    
    public function testAUserCanUpdatePost()
    {
        //$this->withoutExceptionHandling();
        $this->actingAs($user = factory(\App\User::class)->create());
        
        $post = factory(\App\Post::class)->create(['published' => 1, 'owner_id' => $user->id]);
        
        $attr = $post->toArray();
        $attr['title'] .= '_new';
        
        $this->patch('/posts/' . $post->slug, $attr);

        $this->assertDatabaseHas('posts', $attr);
    }
}
