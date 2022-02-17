<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Reposotry\Classes\NotificationEloquent;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notification;
    public function __construct(NotificationEloquent $notification)
    {
        $this->notification = $notification;
    }
    public function allNotificaion()
    {
        return $this->notification->getAll();
    }
    public function deleteNotificatoin(Request $request)
    {
        return $this->notification->delete($request->all());
    }
}
