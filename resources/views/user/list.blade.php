@extends('layout')

@section('content')

<h1>Task List for:  "{{ $username->name }}" </h1>
<div>
            
            <button type="button"  onclick="myFunction()"  class="btn btn-flat"><i class="fa fa-print"></i>
                        </button>
           
<table class="table table-striped">
    <thead>
      <tr>
        <th>Task Title</th>
        <th>Department Name</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Actions</th>
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
            <a href="{{ route('task.view', ['id' => $task->id]) }}" class="btn btn-primary"> <span id="one" class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> </a>
            <a href="{{ route('task.delete', ['id' => $task->id]) }}" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

        </td>
      </tr>

    @endforeach
    </tbody>
@else 
    <p><em>There are no tasks assigned yet</em></p>
@endif


</table>



<div class="btn-group">
    <a class="btn btn-default" href="{{ redirect()->getUrlGenerator()->previous() }}">Go Back</a>
</div>




@stop

@section('scripts')
<!-- TYPE AHEAD LIB -->
<!-- <script src="{{ asset('js/typeahead.min.js') }}"></script> -->

<script>

//This Function is used to Print The Report
function myFunction() {
    //document.getElementById(one).style.visibility = "hidden";
    window.print();
}
//END of This Function is used to Print The Report


</script>
@stop
