@extends('layout')

@section('content')

<h1>Project Task List for:  "{{ $username->name }}" </h1>

<table class="table table-striped">
    <thead>
      <tr>
        <th>Task Title</th>
        <th>Department Name</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Actions</th>
        <th>Extra Time</th>
      </tr>
    </thead>

@if ( !$task_list->isEmpty() ) 
    <tbody>
    @foreach ( $task_list as $task)
      <tr>
        <td>{{ $task->task_title }} </td>
        <td>{{ $task->project->project_name }}</td>
        <td>
            @if ( $task->priority == 0 )
                <span class="label label-info">Normal</span>
            @else
                <span class="label label-danger">High</span>
            @endif
        </td>
        <td>
            @if ( !$task->completed )
                <a href="{{ route('task.completed', ['id' => $task->id]) }}" class="btn btn-warning"> Mark as completed</a>
            @else
                <span class="label label-success">Completed</span>
            @endif
        </td>
        <td>
            <!-- <a href="{{ route('task.edit', ['id' => $task->id]) }}" class="btn btn-primary"> edit </a> -->
            <a href="{{ route('usertask.view', ['id' => $task->id]) }}" class="btn btn-primary"> <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> </a>
           

        </td>
        <td>
        @if ( !$task->completed )
            @if ( $task->extra_time==0 )
            <a href="{{ route('task.extratime', ['id' => $task->id]) }}" class="btn btn-primary"> <span class="glyphicon glyphicon-time" aria-hidden="true"></span> </a>
            @else
            <a href="" disabled="" class="btn btn-danger" > <span class="glyphicon glyphicon-time" aria-hidden="true"></span> </a>
            @endif
        @else
        <span class="label label-success">On Time</span>
        @endif
            
            
           

        </td>
      </tr>

    @endforeach
    </tbody>
@else 
    <p><em>There are no tasks assigned yet</em></p>
@endif


</table>



<!-- -->




@stop