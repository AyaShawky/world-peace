<?php


namespace App\Reposotry\Classes;


use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\friendResource;
use App\Http\Resources\UserResource;
use App\Models\FcmToken;
use App\Models\Join;
use App\Models\Post;
use App\Models\User;
use App\Reposotry\RepositoryInterface;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class UserRepository extends BaseController
{

    protected $model;
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {

        return UserResource::collection(User::orderBy('id','DESC'));

    }

    public function getById($id)
    {
        return UserResource::collection(User::whereId($id)->first());
    }


    public function update(array $data)
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

      if (isset($data['photo']))
      {
        $filename = $data['photo']->store('photo');
        $user->photo = $filename;
        $user->save($data);
          return $this->sendResponse('successfully.',new UserResource($user));
      }

      $user->update($data);
      return $this->sendResponse('User update successfully.',UserResource::collection($user));
    }

    public function delete($id)
    {

        $user = User::where('id',$id)->first();
        if($user){
            $user->delete();
            return $this->sendResponse('success delete user',[]);
        }else{
            return $this->sendResponse('Not found user', []);
        }
    }
    public function register(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse('User register successfully.' ,$success);
    }
    public function login()
    {
        $proxy = Request::create('oauth/token', 'POST');
        $response = Route::dispatch($proxy);
        $statusCode = $response->getStatusCode();
        $response = json_decode($response->getContent());
        if ($statusCode != 200)
          return $this->sendError($response->message);
        $response_token = $response;
        $token = $response->access_token;
        \request()->headers->set('Authorization', 'Bearer ' . $token);
        $proxy = Request::create('api/profile', 'GET');
        $response = Route::dispatch($proxy);
        //dd($response);
        $statusCode = $response->getStatusCode();
       //dd(json_decode($response->getContent()));
        $user = json_decode($response->getContent())->items;
       // dd($user);
        if (isset($user)) {
            // create fcm token
            $data = \request()->all();
            $data['user_id'] = $user->id;
            if (isset($data['fcm_token'])) {
                $fcmToken = FcmToken::where('device_type', $data['device_type'])->where('user_id', $user->id)->where('device_id', $data['device_id'])->first();
                if (!isset($fcmToken))
                    FcmToken::create($data);
                else{
                    $fcmToken->fcm_token = $data['fcm_token'] ;
                    $fcmToken->save();
                }
            }
        }
        return $this->sendResponse('Successfully Login', ['token' => $response_token, 'user' => $user]);
    }

    public function getUser(){
        $user=auth()->user();
        return $this->sendResponse('user info', new UserResource($user));
    }

    public function logout(array $data){

      auth()->user()->token()->revoke();
        return $this->sendResponse(
        'Successfully logged out',[]
        );
    }

    public function Join(array $data)
    {
        $user= Auth()->user()->id;
        $friend=$data['id'];

        $isFriend=Join::where('friend_id' , $friend)->where('user_id',$user)->first();
        if($user != $friend){
            if( ! $isFriend){
                $join = new Join();
                $join->user_id = $user;
                $join->friend_id = $friend;
                $join->save();

                return $this->sendResponse('friend join',new UserResource($join));
            }
            return $this->sendResponse('you are already exists', [] );
        }
    }


    public function getfriend(){
        $user= Auth()->user()->id;
        $friends =Join::where('user_id',$user)->get();

        return $this->sendResponse('all friend',UserResource::collection($friends));
    }


    public function deleteFriend($id)
    {
        $user= Auth()->user()->id;
        $friend=Join::where('user_id',$user)->delete('friend_id',$id);

        return $this->sendResponse('friend delete',[]);
   }


public function changePassword(array $data) {
    $user = Auth::user();

    if (Hash::check('current_password', $user->password)) {
        return $this->sendResponse('error', 'Current password does not match!');
    }

    $user->password = Hash::make($data['password']);
    $user->save();

return $this->sendResponse('Successfully change password !',$user);
}


public function search(array $data){
    $data = $data['name'];
    $users = User::where('name',  'LIKE' , "%$data%")
    ->first();
    if( $users){
        return $this->sendResponse('success',new UserResource ($users));

    }else{
        return $this->sendResponse('No Data not found',$users);

    }

}

}
