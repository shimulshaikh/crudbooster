<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FrontController extends Controller
{

	public $blog_name = "MySimpleBlog";

    public function getIndex()
    {
        $data['page_title'] = 'Home - Blog';
    	$data['blog_name'] = $this->blog_name;

    	$data['posts'] = DB::table('posts')
    	->join('categories','categories.id','=','categories_id')
    	->join('cms_users','cms_users.id','=','cms_users_id')
    	->select('posts.*','categories.name as name_categories','cms_users.name as name_author')
    	->orderby('posts.id','asc')
    	->take(5)
        ->where('posts.status',1)
    	->get();

    	return view('home',$data);
    }

    public function getArtical($slug)
    {
    	$post = DB::table('posts')
    	->join('categories','categories.id','=','categories_id')
    	->join('cms_users','cms_users.id','=','cms_users_id')
    	->select('posts.*','categories.name as name_categories','cms_users.name as name_author')
    	->where('posts.slug',$slug)
    	->first();

        $data['page_title'] = $post->title.' | MySimpleBlog';
        $data['post'] = $post;

    	return view('deatil',$data);
    }

    public function getLatestPost()
    {
        $data['page_title'] = 'Latest - Post';

    	$data['blog_name'] = $this->blog_name;

    	$data['posts'] = DB::table('posts')
    	->join('categories','categories.id','=','categories_id')
    	->join('cms_users','cms_users.id','=','cms_users_id')
    	->select('posts.*','categories.name as name_categories','cms_users.name as name_author')
    	->orderby('posts.id','desc')
        ->where('posts.status',1)
        ->take(5)
    	->get();

    	return view('home',$data);	
    }
}
