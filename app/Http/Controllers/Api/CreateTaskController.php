<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\TaskAssigned;
use App\Models\Task;
use App\Models\TaskImages;
use App\Models\TaskLog;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class CreateTaskController extends Controller
{
    public function store(Request $request)
    {
        // task 1: save the information into database
        $data = $request->all();
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'priority' => 'required|in:Low,Medium,High',
            'assignee' => 'required|exists:tokens,token',
            'images.*' => 'required|image|mimes:jpeg,png,jpg',
            'token' => 'required|exists:tokens,token'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $token = Token::where('token', $request['assignee'])->first();
        $assignee_id = $token['user_id'];

        $user = Token::where('token', $request['token'])->first();
        $user_id = $user['user_id'];

        $task = Task::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'priority' => $request['priority'],
            'assignee' => $assignee_id
        ]);
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $path = $image->storeAs('images/' . $task->id, $image->getClientOriginalName(), 'public');
            $imagePaths[] = '/storage/'.$path;
            TaskImages::create([
                'task_id' => $task->id,
                'image_path' => $path
            ]);
        }

        $assigned_user = User::find($assignee_id);
        if ($assigned_user) {
            $assigneeName = $assigned_user->name;
        } else {
            $assigneeName = null;
        }
        $cachedTask = json_encode([
            'id' => $task->id,
            'title' => $request['title'],
            'description' => $request['description'],
            'priority' => $request['priority'],
            'assignee' => $assigneeName,
            'status' => "New",
            'images' => $imagePaths
        ]);
        // task 2: Cache the information
        Redis::set("task:".$task->id, $cachedTask);
        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => $user_id,
            'event_type' => 'Create New Task',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        // TODO: Send email to the assigned user.
        Mail::to($assigned_user->email)->queue(
            new TaskAssigned($task)
        );
        return response()->json(['message' => 'Task created successfully'], 201);
    }
}
