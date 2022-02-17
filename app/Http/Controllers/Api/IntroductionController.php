<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Reposotry\Classes\IntroductionRepository;

class IntroductionController extends Controller
{
    protected $introduction;
    public function __construct(IntroductionRepository $IntroductionRepository)
    {
        $this->introduction = $IntroductionRepository;
    }

    public function getIntro()
    {
        return $this->introduction->getAll();
    }

    public function create(Request $request)
    {
        return $this->introduction->create($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->introduction->update($request->all(),$id);
    }

    public function delete(Request $request)
    {
        return $this->introduction->delete($request->all());

    }

}
