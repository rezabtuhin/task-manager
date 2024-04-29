<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskImages;
use App\Models\TaskLog;
use App\Models\Token;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TaskDetailsController extends Controller
{
    public function index($task)
    {
        if (Redis::exists("task:".$task)){
            return Redis::get("task:".$task);
        }
        else{
            $task_from_db = Task::find($task);
            $images = TaskImages::where('task_id', $task)->get();
            $imagesPaths = [];
            foreach ($images as $image){
                $imagesPaths[] = $image->image_path;
            }
            $assigned_user = User::find($task_from_db->assignee);
            $cachedTask = json_encode([
                'title' => $task_from_db['title'],
                'description' => $task_from_db['description'],
                'priority' => $task_from_db['priority'],
                'assignee' => $assigned_user ? $assigned_user->name : "N/A",
                'status' => $task_from_db->status,
                'images' => $imagesPaths
            ]);
            Redis::set("task:".$task, $cachedTask);
            return $cachedTask;
        }
    }

    public function delete(Request $request, $id)
    {
        $token = $request->header('Authorization');
        $tokenValue = str_replace('Bearer ', '', $token);
        $user = Token::where('token', $tokenValue)->first();
        $user_id = $user['user_id'];
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            Redis::del('task:' . $id);
            TaskLog::create([
                'task_id' => $id,
                'user_id' => $user_id,
                'event_type' => 'Delete Task',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }
}
