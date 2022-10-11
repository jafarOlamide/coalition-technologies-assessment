<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('priority', 'ASC')->get();

        return $this->respond([
            'tasks'  => TaskResource::collection($tasks)
        ]);   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'project_id' => 'required'
        ]);

        $currentMaxPriority = Task::max('priority') ? Task::max('priority') : 0;
        
        $task = Task::create([
            'name'          => $request->input('name'),
            'project_id'    => $request->input('project_id'),
            'priority'      => $currentMaxPriority + 1 
        ]);

        return $this->respondCreated([
            'task' => new TaskResource($task)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        return $this->respond([
            'task' => new TaskResource($task)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'name'       => $request->input('name'),
            'project_id' => $request->input('project_id'),
        ]);

        return $this->respond([
            'task' => new TaskResource($task)
        ]);
    }

    public function setPriority(Request $request){

        $task = Task::findOrFail($request->input('current_task'));
        $task_before = Task::find($request->input('task_before'));
        $task_after = Task::find($request->input('task_after'));

        if(!$task_before){            
            $new_priority = 1;
        }else if(!$task_after){
            $new_priority = Task::max('priority');
        }else{
            $new_priority = $task->priority < $task_before->priority ? $task_before->priority : $task_before->priority + 1;
        }


        Task::where('priority', '>', $task->priority)
            ->where('priority', '<=', $new_priority)
            ->update([
                'priority' => DB::raw('priority - 1')
            ]);

        Task::where('priority', '<', $task->priority)
            ->where('priority', '>=', $new_priority)
            ->update([
                'priority' => DB::raw('priority + 1')
            ]);


        $task->update([
            'priority' => $new_priority
        ]);


        return $this->respondOk('Successful');
        // $old_priority = $request->input('old_pri');
        // $new_prio = $request->input('new_pri');

        // $maxPriority = Task::max('priority');

        // for($i = 1; $ $i<=$maxPriority; $i++){
        //     if()
        // }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return $this->respondNoContent();
    }
}
