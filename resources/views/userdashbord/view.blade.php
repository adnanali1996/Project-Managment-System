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
                                        <img class="img-responsive" src="<?php echo asset("images/$image") ?>"> <strong>Added BY</strong>
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
                            @foreach ( $files_set as $file )
                                <li> 
                                    <a target="_blank" href="<?php echo asset("images/$file") ; ?>">{{ $file }}</a> <strong>Added BY</strong>
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
                    
                        <textarea class="form-control col-md-12" name="comment" id="comment"></textarea>
                        <input type="hidden" name="user_id" id="user_id" value=" {{$user_id}}">
                        <input type="hidden" name="task_id" id="task_id" value="{{ $task_view->id }}">
                        <input type="submit" name="submit" id="submit" class="btn btn-primary">
                
            </form>    
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-6">
            
            @foreach ($comments as $comments)
                <i>{{$comments->body}}  <strong>{{$comments->name}}sdf</strong></i>
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


