<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function index()
    {
        $tasks = Task::with('assigneeUser')->orderByDesc('created_at')->paginate(5);
        return view('pages.task-list', compact('tasks'));
    }
}
