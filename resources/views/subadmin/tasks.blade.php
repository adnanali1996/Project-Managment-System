@extends('layout')

@section('content')










<!--   /views/task/task/tasks.blade.php   -->
<div class="row">
    <div class="col-md-6">
        <h1>ALL TASKS</h1>
    </div>

    <div class="col-md-6">

        <!-- search form (Optional) -->

      <form action="{{ route('task.search') }}" method="get" name="main_search_form" class="navbar-form">


        <div class="input-group">

            <input autocomplete="off" type="text" placeholder="Search Tasks" class="form-control" name="task_search" id="task_search">
            <span class="input-group-btn">
            <button type="submit"  id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                        </button>
            </span>
        </div>
        <div class="input-group">
            
            <button type="button"  onclick="myFunction()"  class="btn btn-flat"><i class="fa fa-print"></i>
                        </button>
           
        </div>

        </form>

    </div> 

</div>

<div class="table-responsive">
<table class="table table-striped">
    <thead>
      <tr>
        <th>Created At</th>
        <th><a href="{{ route('subadmintask.sort', [ 'key' => 'task' ]) }}">Task Title <span class="glyphicon glyphicon-sort-by-alphabet" aria-hidden="true"></span> </a></th>
        <th>Assigned To</th>
        <th>Department</th>
        <th></th>
        <th>Assigned By</th>
        <th><a href="{{ route('subadmintask.sort', [ 'key' => 'priority' ]) }}">Priority <span class="glyphicon glyphicon-sort-by-alphabet" aria-hidden="true"></span> </a></th>
        <th><a href="{{ route('subadmintask.sort', [ 'key' => 'completed' ]) }}">Status <span class="glyphicon glyphicon-sort-by-alphabet" aria-hidden="true"></span> </a></th>
        <th>Actions</th>
      </tr>
    </thead>

@if ( !$tasks->isEmpty() ) 
    <tbody>
    @foreach ( $tasks as $task)
      <tr>
        <td>{{ Carbon\Carbon::parse($task->created_at)->format('m-d-Y') }}</td>
        <td>{{ $task->task_title }} </td>

        <td>
         
            @foreach( $users as $user) 
                @if ( $user->id == $task->user_id )
                    <a href="{{ route('user.list', [ 'id' => $user->id ]) }}">{{ $user->name }}</a>
                @endif
            @endforeach
            </td>
            
           <td> <span class="label label-jc">{{ $task->project->project_name }}</span><td>
           
        
        <td> <span class="label label-success">{{ $task->created_by }}</span></td>

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
                <span class="label label-danger">{{ ( $task->duedate < Carbon\Carbon::now() )  ? "!" : "" }}</span>
                @if ( $task->extra_time==1 )
                <a href="{{ route('task.edit', ['id' => $task->id]) }}" class="btn btn-danger"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> </a>
                @endif
            @else
                <span class="label label-success">Completed</span>
            @endif
  
            
  

        </td>
        <td>
            <a href="{{ route('subadmintask.view', ['id' => $task->id]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
            <!-- <a href="{{ route('task.edit', ['id' => $task->id]) }}" class="btn btn-primary"> edit </a>  -->
            <a href="{{ route('task.delete', ['id' => $task->id]) }}" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

        </td>
      </tr>

    @endforeach
    </tbody>

    {{ $tasks->links() }}


@else 
    <p><em>There are no tasks assigned yet</em></p>
@endif


</table>
</div>

@stop

@section('scripts')
<!-- TYPE AHEAD LIB -->
<script src="{{ asset('js/typeahead.min.js') }}"></script>

<script>

//This Function is used to Print The Report
function myFunction() {
    window.print();
}
//END of This Function is used to Print The Report

$(document).ready(function() {
    $('#task_search').on('keyup', function(e){
        if(e.which == 13){
            $('#main_search_form').submit();
        }
    });
    $.get("/subadminmain-search-autocomplete", function(data){
        $("#task_search").typeahead({
            "items": "all", // Number of Items
            "source": data,
            "autoSelect": false,
            displayText: function(item){
                console.log('returning item: ' + item.task_title ) ;
                return item.task_title;
            },

            updater: function(item) {
              // http://laratubedemo.test/admin/videos/search?video_search=Code+Geass+Op1
                window.location.href = '{{ route('subadmintask.search') }}?task_search=' + item.task_title.split(' ').join('+') ;
            }

        });
    },'json');
});

</script>
@stop
