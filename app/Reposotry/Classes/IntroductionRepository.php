<?php


namespace App\Reposotry\Classes;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use App\Models\Introduction;

class IntroductionRepository extends BaseController
{
    protected $model;
    public function __construct(Introduction $model)
    {
        $this->introduction = $model;
    }

    public function getAll()
    {
        return Introduction::all();
    }

    public function create(array $data)
    {
        $intro = new Introduction();
        $intro->title =$data['title'];
        $intro->description = $data['description'];
        $intro->image = $data['image']->store('public/images');
        $intro->save();
        return $this->sendResponse(' add introduction successfully.',$intro);
    }

    public function update(array $data, $id)
    {
        $intro = Introduction::whereId($id)->first();
        $intro->title =$data['title'];
        $intro->description = $data['description'];
        if(isset($data['images'])){
        $intro->image = $data['image']->store('public/images');
        }
        $intro->save();
        return $this->sendResponse(' update introduction successfully.',$intro);

    }

    public function delete(array $data)
    {
        $intro= Introduction::where('id',$data['id'])->delete();
        return $this->sendResponse('success delete introduction',[]);

    }
}
