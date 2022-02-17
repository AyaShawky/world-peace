<?php


namespace App\Reposotry\Classes;
use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\friendResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Join;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class PostRepository extends BaseController
{
    protected $model;
    protected $notification;

    public function __construct(Post $model , NotificationEloquent $notificationEloquent)
    {
        $this->post = $model;
        $this->notification=$notificationEloquent;
    }

    public function getById($id)
    {
        $user= Auth()->user()->id;
        $post= Post::all()->where('id',$id)->where('user_id',$user);


        return $this->sendResponse('get post successfully.',PostResource::collection($post));

    }

    public function getAll()
    {
        $user= Auth()->user()->id;
       $post= Post::all()->where('user_id',$user);
       return $this->sendResponse('post user successfully.',PostResource::collection($post));

    }

    public function create(array $data)
    {
        $success = false;
        DB::beginTransaction();
        try {
            $post = new Post();
            $post->description =$data['description'];
            $post->user_id=Auth::user()->id;
            $post->save();
            if(isset($data['images'])){
                foreach($data['images'] as $image){
                    $postmedia = new PostMedia();
                    $postmedia->post_id=$post->id;
                    $filename = $image->store('images');
                    $postmedia->media = $filename;
                    $postmedia->type='image';
                    $postmedia->save();
                }
            }


            if ($post->save()) {
                $friends = Join::where('user_id', Auth::user()->id)->pluck('friend_id')->toArray();

                foreach ($friends as $friend_id)
             $this->notification->sendNotification(Auth::user()->id, $friend_id, 'post', $post->id);
            }
            $success = true;
            if ($success) {
                DB::commit();
            }

         }

        catch (\Throwable $e){
            DB::rollback();
		$success = false;
            return $this->sendError('faild to add post');
        }
        return $this->sendResponse(' add post successfully.',new PostResource($post));
    }


    public function update(array $data, $id)
    {
        $user = Auth::user()->id;
        // $user = User::findOrFail($id);
        $post = Post::where('user_id',$user)->first();
        // $post->description =$data['description'];
        // $post->save();
        if(isset($data['images'])){
            foreach($data['images'] as $image){
                $postmedia = new PostMedia();
                $postmedia->post_id=$post->id;
                $postmedia->type='image';
                $filename = $data['image']->store('images');
                $imagename = $image->hashName();
                $postmedia->media = ($imagename);
                $postmedia->update();
            }
        }
        $post->update($data);

        return $this->sendResponse(new PostResource($post),'update post successfully',$post);
    }


    public function delete(array $data)
    {
        $user= Auth::user()->id;
        $post = Post::where('id',$data['id'])->where('user_id',$user)->first();
        if($post){
            $post->delete();
            return $this->sendResponse('success delete post',[]);


        }else{
            return $this->sendResponse('you cant delete this post',[]);
        }
}




public function like(array $data)
{
    $id=$data['id'];
    $post = Post::find($id);
     if($post){

      $like=Like::where('post_id' ,$post->id)
      ->where('user_id',Auth::user()->id)->first();

   if($like){
    $like->delete();
    return $this->sendResponse('like deleted ',[]);
     }

      $likePost=Like::create([
    'post_id'=> $post->id,
    'user_id'=>Auth::user()->id,
       ]);

       if ($likePost->save()) {
      $this->notification->sendNotification(Auth::user()->id, $post->user_id, 'like', $likePost->id);
    }

      return $this->sendResponse('like add ',$likePost);
      }
      return $this->sendError('not Found',[]);

}


public function share(array $data)
{
    $data['user_id']=Auth::user()->id;
    $post = Post::findOrFail($data['id']);
    $post=Post::create([
      'description'=>$data['description'],
      'user_id'=>$data['user_id'],
      'post_share_id'=>$post->id,
      'is_pin'=>$data['is_pin'],

  ]);

        return $this->sendResponse(new PostResource($post),'share post successfully');

}


   public function timeLine(){
    $user= Auth()->user()->id;
    $my_post=Post::where('user_id',$user)->pluck('id')->toArray();
    $my_friend=Join::where('user_id',$user)->pluck('friend_id')->toArray();
    $my_friend_posts=Post::whereIn('user_id',$my_friend)->pluck('id')->toArray();
    $posts=Post::whereIn('id',$my_post)
                ->orWhereIn('id',$my_friend_posts)
                ->count();
                $page_size=request()->has('page_size')?request()->get('page_size'):10;
                $current_page=request()->has('current_page')?request()->get('current_page'):1;
                $total_page = ceil($posts / $page_size);
                $post_num = $page_size * ($current_page - 1);
                $total_posts=Post::whereIn('id',$my_post)
                ->orWhereIn('id',$my_friend_posts)
                ->skip($post_num)
                ->take($page_size)
                ->orderByDesc('created_at')->get();
    return $this->sendResponse('all posts',['total_page'=>$total_page,
    'current_page'=>$current_page,'data'=>PostResource::collection($total_posts)]);

}
}


