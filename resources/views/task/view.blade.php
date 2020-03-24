@extends('layout')

@section('content')

<!--
<strong>Debug vars:</strong><br>
task_view->project->id :  {{ $task_view->project->id }} <br>
task_view->project->project_name: {{ $task_view->project->project_name }}  <br>
task_view->id: {{ $task_view->id }}<br>
-->




<div class="col-md-8">
    <h1>{{ $task_view->task_title }}</h1>

    <div class="form-group">
        <label>Description:</label>
        <p>{!! $task_view->task !!}</p>
    </div>
        

    <div class="btn-group">
        @if(\Auth::user()->role == 'Admin')
        <a href="{{ route('task.edit', ['id' => $task_view->id ]) }}" class="btn btn-primary"> edit </a>
        @endif
        <a class="btn btn-default" href="{{ route('user.userdashbord') }}">Go Back</a>
    </div>

@if(\Auth::user()->role =='User')
<div class="row">
<form action="{{ route('user.fileattach', [ 'id' => $task_view->id ] ) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
	<input type="hidden" name="task_id" value="{{ $task_view->id }}">
    <div class="col-md-6">
        <label>Add Project Files (png,gif,jpeg,jpg,txt,pdf,doc) <span class="glyphicon glyphicon-file" aria-hidden="true"></span></label>
           	<input type="file" class="form-control" name="photos[]" multiple>
               
    </div>
    <div class="col-md-3">
    <label>Attached File <span class="glyphicon glyphicon-file" aria-hidden="true"></span></label>

           	<input type="submit" class="col-md-3 form-control  btn-primary" name="submit" multiple>
    </div>
</form>
</div>
@endif

    <div class="row">
        <hr>
        @if( count($images_set) > 0 ) 
            <div class="col-md-6">

                <div class="panel panel-jc">
                    <div class="panel-heading">Uploaded Images</div>
                    <div class="panel-body">
                        <ul id="images_col">
                            @foreach ( $images_set as $image )
                                <li> 
                                    <a href="<?php echo asset("images/$image") ?>" data-lightbox="images-set">
                                        <img class="img-responsive" src="<?php echo asset("images/$image") ?>">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        @endif


        
        @if( count($files_set) > 0 ) 
            <div class="col-md-6">

                <div class="panel panel-jc">
                    <div class="panel-heading"> Uploaded Files</div>
                    <div class="panel-body">
                        <ul id="images_col">
                        @foreach ($taskfiless as $taskfiless)
                                       
                      
                           
                                <li> 
                                    <a target="_blank" href="<?php echo asset("images/$taskfiless->filename") ; ?>">{{ $taskfiless->filename }}</a>
                                    @if($taskfiless->role=="sa")
                                    <strong>(<?php echo "Super Admin";?>)</strong>
                                    @else
                                    <strong>({{$taskfiless->role}})</strong>
                                    @endif
                                  
                                </li>
                           
                           
                        @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        @endif


    </div>

    <div class="row">
    <h3>Feedback</h3>
    <div class="col-md-12">
        <form method="post" action="{{route('create_comment')}}">
            {{ csrf_field() }}
                 
                    <textarea  name="comment" id="comment" width="30px"></textarea>
                    <input type="hidden" name="user_id" id="user_id" value=" {{$user_id}}">
                    <input type="hidden" name="task_id" id="task_id" value="{{ $task_view->id }}">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary">
               
        </form>    
    </div>
</div><br>
<div class="row">
    <div class="col-md-6">
        @foreach ($comments as $comments)
             <p>{{$comments->body}}  <strong>{{$comments->name}}</strong></p>
        @endforeach
    </div>
</div>

</div>

<div class="col-md-4">


    <div class="panel panel-jc">
        <div class="panel-heading">Project</div>
        <div class="panel-body">
            <span class="label label-jc">
                <a href="{{ route('task.list', [ 'projectid' => $task_view->project->id ]) }}">{{ $task_view->project->project_name }}</a>
            </span>
        </div>
    </div>

    <div class="panel panel-jc">
        <div class="panel-heading">Priority</div>
        <div class="panel-body">
            @if ( $task_view->priority == 0 )
                <span class="label label-info">Normal</span>
            @else
                <span class="label label-danger">High</span>
            @endif
        </div>
    </div>



    <div class="panel panel-jc">
        <div class="panel-heading">Created</div>
        <div class="panel-body">
            {{ $formatted_from }} 
        </div>
    </div>

    <div class="panel panel-jc">
        <div class="panel-heading">Due Date</div>
        <div class="panel-body">
            {{ $formatted_to }} 
        </div>
    </div>


    <div class="panel panel-jc">
        <div class="panel-heading">Status</div>
        <div class="panel-body">
            @if ( $task_view->completed == 0 )
                <span class="label label-warning">Open</span>
                @if ( $is_overdue )
                    <span class="label label-danger">Overdue</span>
                @else
                    <p><br>{{ $diff_in_days }} days left to complete this task</p>
                @endif                
            @else
                <span class="label label-success">Closed</span>
            @endif
        </div>
    </div>

</div>

@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}">
@stop


@section('scripts')
    <script src="{{ asset('js/lightbox.min.js') }}"></script>  



@stop

