<?php

namespace App\Services;

class Statistics
{
    protected $statistics = [];
    
    protected function getAmountPosts()
    {
        return \DB::table('posts')->count();
    }
    
    protected function getAmountInformations()
    {
        return \DB::table('informations')->count();
    }
    
    protected function getUserWhoHasTheMostPosts()
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
    
    protected function getBiggestPost()
    {
        return $this->getPostByLengthProperty('desc');
    }
    
    protected function getSmallerPost()
    {
        return $this->getPostByLengthProperty('asc');
    }
    
    protected function getAveragePostsOfActiveUsers()
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
    
    protected function getMostCommentable()
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
    
    protected function getMostChangeable()
    {
        return \DB::table('post_histories')
                    ->join('posts', 'posts.id', 'post_id')
                    ->select(\DB::raw('count(*) as amount_changes, posts.title, posts.slug'))
                    ->groupBy('post_id')
                    ->orderBy('amount_changes', 'desc')
                    ->first() ?? null
        ;
    }
    
    public function prepareKey(string $key)
    {
        return lcfirst(str_replace('get', '', $key));
    }
    
    public function withMethod(string $method)
    {   
        if (method_exists($this, $method)) $this->statistics[$this->prepareKey($method)] = $this->$method();
        return $this;
    }
    
    public function withMethods(array $methods)
    {
        foreach ($methods as $method) {
            $this->withMethod($method);
        }
        return $this;
    }
    
    public function get()
    {
        return $this->statistics;
    }
}