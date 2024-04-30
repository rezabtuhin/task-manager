<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskImages;
use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class TaskController extends Controller
{
    public function index($task)
    {
//        $tokens = Token::where('token', $token)->first();
//        $tasks = Task::find($task);
        if (Redis::exists("task:".$task)){
            $cachedTask = json_decode(Redis::get("task:".$task));
            return view('pages.task-view', compact('cachedTask'));
        }
        else{
            $task_from_db = Task::find($task);
            $images = TaskImages::where('task_id', $task)->get();
            $imagesPaths = [];
            foreach ($images as $image){
                $imagesPaths[] = $image->image_path;
            }
            $assigned_user = User::find($task_from_db->assignee);
            $cachedTask = [
                'id' => $task,
                'title' => $task_from_db['title'],
                'description' => $task_from_db['description'],
                'priority' => $task_from_db['priority'],
                'assignee' => $assigned_user ? $assigned_user->name : "N/A",
                'status' => $task_from_db->status,
                'images' => $imagesPaths
            ];
            Redis::set("task:".$task, json_encode($cachedTask));
            return view('pages.task-view', compact('cachedTask'));
        }
    }

    public function edit($task_id)
    {
        if (Redis::exists("task:".$task_id)){
            $cachedTask = json_decode(Redis::get("task:".$task_id));
//            dd($cachedTask);
        }
        else{
            $cachedTask = Task::find($task_id);
        }
        return view('pages.task-edit', compact('cachedTask'));
    }
}
