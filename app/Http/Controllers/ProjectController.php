<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends ApiController
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();

        return $this->respond([
            'projects'  => ProjectResource::collection($projects)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $project = Project::create([
            'name'  => $request->input('name')
        ]);

        return $this->respondCreated([
            'project' => new ProjectResource($project)
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
        $project = Project::findOrFail($id);
        
        return $this->respond([
            'project' => new ProjectResource($project->load('tasks'))
        ]);
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
        $task = Project::findOrFail($id);

        $task->update([
            'name' => $request->input('name'),
        ]);

        return $this->respond([
            'project' => new ProjectResource($task)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Project::find($id)->delete();

        return $this->respondNoContent();
    }
}
