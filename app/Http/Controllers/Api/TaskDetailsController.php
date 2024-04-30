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
use Illuminate\Validation\Rule;

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

    public function update(Request $request, Task $task_id)
    {
        $validatedData = $request->validate([
            'token' => 'required|string',
            '_token' => 'nullable',
            'status' => [
                'required',
                Rule::in(['New', 'In Progress', 'Testing', 'Deployed'])
            ]
        ]);
        $additionalVariables = array_diff(array_keys($request->all()), array_keys($validatedData));
        if (!empty($additionalVariables)) {
            return response()->json(['message' => 'Invalid request data.'], 400);
        }

        $tokenExists = Token::where('token', $request->token)->exists();
        if ($tokenExists) {
            if ($task_id->status == $request->status){
                return response()->json(['message' => 'Task is already in the '.$request->status.' state.'], 401);
            }
            $statusChanged = $task_id->changeStatus($request->status);

            if ($statusChanged === true) {
                $currentTask = json_decode(Redis::get('task:' . $task_id->id));
                $currentTask->status = $request->status;
                Redis::set('task:'.$task_id->id, json_encode($currentTask));
                return response()->json(['message' => 'Status updated successfully'], 200);
            } elseif ($statusChanged === false) {
                return response()->json(['message' => 'Unable to update status'], 400);
            } elseif ($statusChanged === 'already_deployed') {
                return response()->json(['message' => 'Task is already in the Deployed state'], 401);
            } elseif (str_starts_with($statusChanged, 'not_allowed')) {
                $remainingMinutes = explode(':', $statusChanged)[1];
                return response()->json(['message' => "Can't change status within ". 15 - $remainingMinutes ." minutes of previous update"], 401);
            }
        }
        return response()->json(['message' => 'Invalid token'], 401);
    }
}
