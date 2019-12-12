<?php

namespace App\Services;

class Statistics
{
    public function getAmountPosts()
    {
        return \DB::table('posts')->count();
    }
    
    public function getAmountInformations()
    {
        return \DB::table('informations')->count();
    }
    
    public function getUserWhoHasTheMostPosts()
    {
        return \DB::table('posts')
                    ->select('users.name', \DB::raw('count(*) as amount_post, owner_id'))
                    ->groupBy('owner_id')
                    ->orderBy('amount_post', 'desc')
                    ->join('users', 'users.id', '=', 'owner_id')
                    ->first() ?? null
        ;
    }
    
    protected function getPostByLengthProperty(string $sort = 'asc')
    {
        return \DB::table('posts')
                    ->select(\DB::raw('char_length(body) as post_length, title, slug'))
                    ->orderBy('post_length', $sort)
                    ->first() ?? null
        ;
    }
    
    public function getBiggestPost()
    {
        return $this->getPostByLengthProperty('desc');
    }
    
    public function getSmallerPost()
    {
        return $this->getPostByLengthProperty('asc');
    }
    
    public function getAveragePostsOfActiveUsers()
    {
        $subquery = \DB::table('posts')
                    ->select(\DB::raw('count(*) as amount_post'))
                    ->groupBy('owner_id')
                    ->havingRaw('count(*) > 1')
                    ->toSQL()
        ;
        return round(\DB::table(\DB::raw("($subquery) as agr"))
                    ->avg('agr.amount_post'))
        ;
        //SELECT AVG(agr.amount_post) FROM (select count(*) as amount_post from posts group by owner_id having count(*) > 1) as agr
        //->select(\DB::raw('avg(agr.amount_post) as average'))->first()
    }
    
    public function getMostCommentable()
    {
        return \DB::table('commentables')
                    ->join('posts', 'posts.id', 'commentable_id')
                    ->select(\DB::raw('count(*) as amount_comments, posts.title, posts.slug'))
                    ->groupBy('commentable_id')
                    ->where('commentable_type', 'App\Post')
                    ->orderBy('amount_comments', 'desc')
                    ->first() ?? null
        ;
    }
    
    public function getMostChangeable()
    {
        return \DB::table('post_histories')
                    ->join('posts', 'posts.id', 'post_id')
                    ->select(\DB::raw('count(*) as amount_changes, posts.title, posts.slug'))
                    ->groupBy('post_id')
                    ->orderBy('amount_changes', 'desc')
                    ->first() ?? null
        ;
    }   
}