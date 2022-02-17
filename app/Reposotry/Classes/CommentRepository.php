<?php


namespace App\Reposotry\Classes;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Join;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;


class CommentRepository extends BaseController
{
    protected $model;
    protected $notification;

    public function __construct(Comment $model , NotificationEloquent $notificationEloquent)
    {
        $this->Comment = $model;
        $this->notification=$notificationEloquent;
    }
    public function getAll()
    {

      $comment = Comment::where('user_id',Auth::user()->id)->get();

      return $this->sendResponse('success get comment', CommentResource::collection($comment));
    }

    public function delete(array $data)
    {

        $comment = Comment::where('id',$data['id'])->where('user_id',Auth::user()->id)->first();
        if($comment){
            $comment->delete();
            return $this->sendResponse('success delete comment',[]);
        }else{
            return $this->sendResponse('Not found comment',[]);
        }
    }

    public function create(array $data)
    {
        $comment = new Comment();
        $comment->text =$data['text'];
        $comment->post_id=$data['post_id'];
        $comment->user_id=Auth::user()->id;
        $comment->save();
        if ($comment->save()) {

     $this->notification->sendNotification(Auth::user()->id, $comment->user_id, 'comment', $comment->id);
        }
        return $this->sendResponse(' Comment created successfully !',new CommentResource ($comment));
    }
}
