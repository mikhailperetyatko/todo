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
        
        $this->actingAs(factory(\App\User::class)->create());
        
        $this->post('/posts', $attr = [
            'slug' => $this->faker->unique()->words(1, true),
            'title' => $this->faker->words(5, true),
            'description' => $this->faker->words(15, true),
            'body' => $this->faker->sentence,
            'published' => 1
        ]);
        
        $this->assertDatabaseHas('posts', $attr);
    }
    
    public function testAUserCanCreatePostWithTags()
    {   
        //$this->withoutExceptionHandling();
        
        $this->actingAs($user = factory(\App\User::class)->create());
        
        $attr = factory(\App\Post::class)->raw(['owner_id' => $user, 'published' => 1]);
        
        $tag = factory(\App\Tag::class)->create();
        
        $this->post('/posts', array_merge($attr, ['tags' => $tag->name]));
                
        $this->assertDatabaseHas('posts', $attr);
    }
    
/*
    public function testAUserCanModifySelfPost()
    {   
        $this->withoutExceptionHandling();
        
        $this->actingAs($user = factory(\App\User::class)->create());
        $tag = factory(\App\Tag::class)->create();
        $post = factory(\App\Post::class)->create(['owner_id' => $user->id, 'published' => 1]);
        $post->tags()->sync($tag->id);
        
        $post->title .= 'Exp';
        
        $this->patch('/posts/' . $post->slug, $post->toArray());
        
        $this->assertDatabaseHas('posts', $post);
    }
*/
    
    /*
    public function testASimpleUserMayNotVisitAdminResourse()
    {

    }
    */

}
