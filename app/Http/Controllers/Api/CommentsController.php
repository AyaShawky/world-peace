<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentRequest;
use App\Models\Comment;
use App\Reposotry\Classes\CommentRepository;
use Illuminate\Http\Request;


class CommentsController extends Controller
{
    protected $comment;
    public function __construct(CommentRepository $commentRepository)
    {
        $this->comment = $commentRepository;
    }
    // show comments
    public function index(){
        return $this->comment->getAll();
    }

    public function deleteComment(Request $request)
    {

        return $this->comment->delete($request->all());
    }

    public function create(CommentRequest $request)
    {
        return $this->comment->create($request->all());
    }
}
