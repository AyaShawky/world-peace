<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\postRequest;
use App\Reposotry\Classes\PostRepository;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    protected $post;
    public function __construct(PostRepository $postRepository)
    {
        $this->post = $postRepository;
    }

    // public function allPost()
    // {
    //     return $this->post->getAll();
    // }

    public function createPost(postRequest $request)
    {
        return $this->post->create($request->all());
    }


    public function getPost($id)
    {
        return $this->post->getById($id);
    }

    public function allPost()
    {
        return $this->post->getAll();
    }

    public function update(Request $request, $id)
    {

        return $this->post->update($request->all(),$id);

    }

    public function deletePost(Request $request)
    {
        return $this->post->delete($request->all());
    }

    // public function comments(Request $request)
    // {

    //     return $this->post->comments($request->all());
    // }

    public function like(Request $request)
    {
        return $this->post->like($request->all());
    }

    public function share(Request $request)
    {
        return $this->post->share($request->all());
    }
    public function timeLine(){
        return $this->post->timeLine();
    }
}
