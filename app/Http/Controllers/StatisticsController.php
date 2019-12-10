<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticsController extends Controller
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
                    ->first()
        ;
    }
    
    public function getBiggestPost()
    {
        return \DB::table('posts')
                    ->select(\DB::raw('char_length(body) as post_length, title, slug'))
                    ->orderBy('post_length', 'desc')
                    ->first()
                    
        ;
    }
    
    public function getSmallerPost()
    {
        return \DB::table('posts')
                    ->select(\DB::raw('char_length(body) as post_length, title, slug'))
                    ->orderBy('post_length')
                    ->first()
                    
        ;
    }
    
    public function getAveragePostsOfActiveUsers()
    {
        $subquery = \DB::table('posts')
                    ->select(\DB::raw('count(*) as amount_post'))
                    ->groupBy('owner_id')
                    ->havingRaw('count(*) > 1')
                    ->toSQL()
        ;
        return \DB::table(\DB::raw("($subquery) as agr"))
                    ->avg('agr.amount_post') 
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
                    ->first()
        ;
    }
    
    public function getMostChangeable()
    {
        return \DB::table('post_histories')
                    ->join('posts', 'posts.id', 'post_id')
                    ->select(\DB::raw('count(*) as amount_changes, posts.title, posts.slug'))
                    ->groupBy('post_id')
                    ->orderBy('amount_changes', 'desc')
                    ->first()
        ;
    }
    
    public function show()
    {
        return view('statistics', [
            'amountPosts' => $this->getAmountPosts(),
            'amountInformations' => $this->getAmountInformations(),
            'userWhoHasTheMostPosts' => $this->getUserWhoHasTheMostPosts() ?? null,
            'biggestPost' => $this->getBiggestPost() ?? null,
            'smallerPost' => $this->getSmallerPost() ?? null,
            'averagePostsOfActiveUsers' => round($this->getAveragePostsOfActiveUsers()) ?? 0,
            'mostCommentable' => $this->getMostCommentable() ?? null,
            'mostChangeable' => $this->getMostChangeable() ?? null,
        ]);
    }
}
