<?php
namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;

// import our models
use App\Project;
use App\Task;
use App\TaskFiles;
use App\User; 
use App\Comment;
use Illuminate\Support\Facades\Input; 
use DB;
class TaskController extends Controller
{
/*===============================================
    INDEX
===============================================*/
    public function index()
    {

        $users =  User::all() ; 
        $tasks  = Task::orderBy('created_at', 'desc')->paginate(10) ;  // Paginate Tasks 

        return view('task.tasks', compact('tasks', 'users'));
        //return view('task.tasks')->with('tasks', $tasks) 
          //                       ->with('users', $users ) ;
    //    return response()
    //        ->json(['users' => $users]);
        
       
                             
    }

/*===============================================
    LIST Tasks
===============================================*/
    public function tasklist( $projectid ) {

        // dd($projectid);
        $users =  User::all() ;
        $p_name = Project::find($projectid) ;
        // ->get()  will return a collection
        $task_list = Task::where('project_id','=' , $projectid)->get();
        return view('task.list')->with('users', $users) 
                                ->with('p_name', $p_name)
                                ->with('task_list', $task_list) ;
    }

/*===============================================
    VIEW Task
===============================================*/
    public function view($id)  {
        $images_set = [] ;
        $files_set = [] ;
        $images_array = ['png','gif','jpeg','jpg'] ;
        // get task file names with task_id number
        $taskfiles = TaskFiles::where('task_id', $id )->get() ;
       // $taskfiless = TaskFiles::where('task_id', $id )->first() ;
        $taskfiless = DB::table('task_files')
            ->join('users', 'users.id', '=', 'task_files.user_id')
            ->select('*')
            ->where('task_id', $id)
            ->get();
        // dd($taskfiles);
        if ( count($taskfiles) > 0 ) { 
            foreach ( $taskfiles as $taskfile ) {

                // explode the filename into 2 parts: the filename and the extension
                $taskfile = explode(".", $taskfile->filename ) ;
                // store images only in one array
                // $taskfile[0] = filename
                // $taskfile[1] = jpg
                // check if extension is a image filetype
                if ( in_array($taskfile[1], $images_array ) ) 
                    $images_set[] = $taskfile[0] . '.' . $taskfile[1] ;
                    // if not an image, store in files array
                else
                    $files_set[] = $taskfile[0] . '.' . $taskfile[1]; 
            }
        }



        $task_view = Task::find($id) ;

        // Get task created and due dates
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $task_view->created_at);
        $to   = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $task_view->duedate ); // add here the due date from create task

        $current_date = \Carbon\Carbon::now();
 
        // Format dates for Humans
        $formatted_from = $from->toRfc850String();  
        $formatted_to   = $to->toRfc850String();

        // Get Difference between current_date and duedate = days left to complete task
        // $diff_in_days = $from->diffInDays($to);
        $diff_in_days = $current_date->diffInDays($to);

        // Check for overdue tasks
        $is_overdue = ($current_date->gt($to) ) ? true : false ;

        // $task_view->project->project_name   will output the project name for this specific task
        // to populate the right sidebar with related tasks
        $projects = Project::all() ;
       // $comments = Comment::where('task_id', $id)->get();
        $comments = DB::table('comments')
        ->select('body', 'name')
        ->join('users', 'users.id', '=', 'comments.user_id')
        ->where('task_id', $id)
        ->orderBy('comments.created_at', 'DESC')
        ->get();
        //$comments =(array)$comments;
        //dd($comments);
        $user_id = \Auth::user()->id;
        return view('task.view')
            ->with('task_view', $task_view) 
            ->with('projects', $projects) 
            ->with('taskfiles', $taskfiles)
            ->with('taskfiless', $taskfiless)
            ->with('diff_in_days', $diff_in_days )
            ->with('is_overdue', $is_overdue) 
            ->with('formatted_from', $formatted_from ) 
            ->with('formatted_to', $formatted_to )
            ->with('images_set', $images_set)
            ->with('files_set', $files_set) 
            ->with('user_id', $user_id)
            ->with('comments', $comments);
    }

/*===============================================
    USER VIEW Task
===============================================*/
public function userview($id)  {
    $images_set = [] ;
    $files_set = [] ;
    $images_array = ['png','gif','jpeg','jpg'] ;
    // get task file names with task_id number
    $taskfiles = TaskFiles::where('task_id', $id )->get() ;
    $taskfiless = DB::table('task_files')
    ->join('users', 'users.id', '=', 'task_files.user_id')
    ->select('*')
    ->where('task_id', $id)
    ->get();
    if ( count($taskfiles) > 0 ) { 
        foreach ( $taskfiles as $taskfile ) {

            // explode the filename into 2 parts: the filename and the extension
            $taskfile = explode(".", $taskfile->filename ) ;
            // store images only in one array
            // $taskfile[0] = filename
            // $taskfile[1] = jpg
            // check if extension is a image filetype
            if ( in_array($taskfile[1], $images_array ) ) 
                $images_set[] = $taskfile[0] . '.' . $taskfile[1] ;
                // if not an image, store in files array
            else
                $files_set[] = $taskfile[0] . '.' . $taskfile[1]; 
        }
    }



    $task_view = Task::find($id) ;

    // Get task created and due dates
    $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $task_view->created_at);
    $to   = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $task_view->duedate ); // add here the due date from create task

    $current_date = \Carbon\Carbon::now();

    // Format dates for Humans
    $formatted_from = $from->toRfc850String();  
    $formatted_to   = $to->toRfc850String();

    // Get Difference between current_date and duedate = days left to complete task
    // $diff_in_days = $from->diffInDays($to);
    $diff_in_days = $current_date->diffInDays($to);

    // Check for overdue tasks
    $is_overdue = ($current_date->gt($to) ) ? true : false ;

    // $task_view->project->project_name   will output the project name for this specific task
    // to populate the right sidebar with related tasks
    $projects = Project::all() ;
    $comments = DB::table('comments')
    ->select('body', 'name')
    ->join('users', 'users.id', '=', 'comments.user_id')
    ->where('task_id', $id)
    ->orderBy('comments.created_at', 'DESC')
    ->get();
    $user_id = \Auth::user()->id;
        return view('task.view')
            ->with('task_view', $task_view) 
            ->with('projects', $projects) 
            ->with('taskfiles', $taskfiles)
            ->with('taskfiless', $taskfiless)
            ->with('diff_in_days', $diff_in_days )
            ->with('is_overdue', $is_overdue) 
            ->with('formatted_from', $formatted_from ) 
            ->with('formatted_to', $formatted_to )
            ->with('images_set', $images_set)
            ->with('files_set', $files_set) 
            ->with('user_id', $user_id)
            ->with('comments', $comments);
}
/*===============================================
    SORT TASKS
===============================================*/
    public function sort( $key ) {
        $users = User::all() ;
        // dd ($key) ; 
        switch($key) {
            case 'task':
                $tasks = Task::orderBy('task')->paginate(10); // replace get() with paginate()
            break;
            case 'priority':
                $tasks = Task::orderBy('priority')->paginate(10);
            break;
            case 'completed':
                $tasks = Task::orderBy('completed')->paginate(10);
            break;
        }

        return view('task.tasks')->with('users', $users)
                                ->with('tasks', $tasks) ;
    }

/*===============================================
    CREATE TASK
===============================================*/
    public function create()
    {
        $projects = Project::all()  ;
        $users = User::all() ;
        return view('task.create')->with('projects', $projects) 
                                  ->with('users', $users) ;        
    }

/*===============================================
    STORE NEW TASK
===============================================*/
    public function store(Request $request)
    {
        // dd($request->all() ) ;
        $tasks_count = Task::count() ;
        
        // if ( $tasks_count < 20  ) { 
            // dd( $request->all()  ) ;
            // dd($request->file('photos'));

            $this->validate( $request, [
                'task_title' => 'required',
                'task'       => 'required',
                'project_id' => 'required|numeric',
                'photos.*'   => 'sometimes|required|mimes:png,gif,jpeg,jpg,txt,pdf,doc',  // photos is an array: photos.*
                'duedate'    => 'required',
               
            ]) ;

            //  dd($request->all() ) ;
            // First save Task Info
            $role = \Auth::user()->role; 
            $user_id = \Auth::user()->id;
            // echo $user_id;
            // exit();
            $created_by;
            if($role=='Admin' || $role == 'sa'){
               global $created_by;
                $created_by = \Auth::user()->name;
            }
            $task = Task::create([
                'project_id' => $request->project_id,
                'user_id'    => $request->user,
                'task_title' => $request->task_title,
                'task'       => $request->task,
                'priority'   => $request->priority,
                'duedate'    => $request->duedate,
                'created_by'    => $created_by
            ]);

            // Then save files using the newly created ID above
            if( $request->hasFile('photos') ) {
                foreach ($request->photos as $file) {

                    $filename = strtr( pathinfo( time() . '_' . $file->getClientOriginalName(), PATHINFO_FILENAME) , [' ' => '', '.' => ''] ) . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                    $file->move('images',$filename);

                    // save to DB
                    TaskFiles::create([
                        'task_id'  => $task->id, // newly created ID
                        'filename' => $filename,  // For Regular Public Images
                        'user_id' => $user_id
                    ]);
                }
            }
    
            Session::flash('success', 'Task Created') ;
            return redirect()->route('task.show') ; 
        // }
        
        // else {
        //     Session::flash('info', 'Please delete some tasks, Demo max tasks: 20') ;
        //     return redirect()->route('task.show') ;         
        // }

    }

/*===============================================
    MARK TASK AS COMPLETED
===============================================*/
    public function completed($id)
    {
        $task_complete = Task::find($id) ;
        $task_complete->completed = 1;
        $task_complete->save() ;
        return redirect()->back();
    }

/*===============================================
    EDIT TASK
===============================================*/
    public function edit($id)
    {
        // $task_list = Task::where('project_id','=' , $projectid)->get();
        $task = Task::find($id)  ; 
        $taskfiles = TaskFiles::where('task_id', '=', $id)->get() ;
        // dd($taskfiles) ;
        $projects = Project::all() ;
        $users = User::all() ;

        return view('task.edit')->with('task', $task)
                                ->with('projects', $projects ) 
                                ->with('users', $users)
                                ->with('taskfiles', $taskfiles);
    }

/*===============================================
    UPDATE TASK
===============================================*/
    public function update(Request $request, $id)
    {
        // dd( $request->all() ) ;
        $update_task = Task::find($id) ;

        $this->validate( $request, [
            'task_title' => 'required',
            'task'       => 'required',
            'project_id' => 'required|numeric',
            'photos.*'   => 'sometimes|required|mimes:png,gif,jpeg,jpg,txt,pdf,doc' // photos is an array: photos.*
        ]) ;
        $role = \Auth::user()->role; 
        $user_id = \Auth::user()->id;
        $created_by;
        if($role=='Admin'){
           global $created_by;
            $created_by = \Auth::user()->name;
        }
        $update_task->task_title = $request->task_title; 
        $update_task->task       = $request->task;
        $update_task->user_id    = $request->user_id;
        $update_task->project_id = $request->project_id;
        $update_task->priority   = $request->priority;
        $update_task->completed  = $request->completed;
        $update_task->duedate    = $request->duedate;
        $update_task->created_by    = $created_by;
        $update_task->extra_time    = 0;

        if( $request->hasFile('photos') ) {
            foreach ($request->photos as $file) {
                // remove whitespaces and dots in filenames : [' ' => '', '.' => ''] 
                $filename = strtr( pathinfo( time() . '_' . $file->getClientOriginalName(), PATHINFO_FILENAME) , [' ' => '', '.' => ''] ) . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

                $file->move('images',$filename);

                // save to DB
                TaskFiles::create([
                    'task_id'  => $request->task_id,
                    'filename' => $filename,  // For Regular Public Images
                    'user_id' => $user_id
                ]);
            }        
        }

        $update_task->save() ;
        
        Session::flash('success', 'Task was sucessfully edited') ;
        return redirect()->route('task.show') ;
    }

/*===============================================
    DESTROY TASK
===============================================*/
    public function destroy($id)
    {
        $delete_task = Task::find($id) ;
        $delete_task->delete() ;
        Session::flash('success', 'Task was deleted') ;
        return redirect()->back();
    }

/*===============================================
    DELETE FILE
===============================================*/
    public function deleteFile($id) {
        $delete_file = TaskFiles::find($id) ;
        // remove  file from public directory
        unlink( public_path() . '/images/' . $delete_file->filename ) ;

        // delete entry from database
        $delete_file->delete() ;
        Session::flash('success', 'File Deleted') ;
        return redirect()->back(); 
    }

/*===============================================
    SEARCH TASK
===============================================*/
    public function searchTask()
    {
        $value = Input::get('task_search');
        // Search Inside the Contents of a task
        $tasks = Task::where('task_title', 'LIKE', '%' . $value . '%')->limit(25)->get();
        return view('task.search')->with('value', $value)
                                  ->with('tasks', $tasks) ;
    }


    /*===============================================
    Extratime FOR TASK
===============================================*/
public function extratime($id)
{
    $extra_time = Task::find($id) ;
    $extra_time->extra_time = 1;
    $extra_time->save() ;
    return redirect()->back();
}


// ======================== SUB ADMIN TASKS =======================
public function subadmintasks()
{

     
    $dep_id = \Auth::user()->dept_id;
   
    $users =  User::where('dep_id', $dep_id) ;
    $tasks  = Task::where('project_id', $dep_id)
                ->orderBy('created_at', 'desc')->paginate(10) ;  // Paginate Tasks 

    return view('subadmin.tasks', compact('tasks', 'users'));
    //return view('task.tasks')->with('tasks', $tasks) 
      //                       ->with('users', $users ) ;
//    return response()
//        ->json(['users' => $users]);
    
   
                         
}
/*===============================================
    CREATE TASK
===============================================*/
public function createtask()
{
    $dep_id = \Auth::user()->dept_id;
    $projects = Project::where('id', $dep_id)->get();
    $users = User::all() ;
    return view('subadmin.create')->with('projects', $projects) 
                              ->with('users', $users) ;        
}

/*===============================================
    STORE NEW TASK
===============================================*/
public function subadminstore(Request $request)
{
    // dd($request->all() ) ;
    $tasks_count = Task::count() ;
    
    // if ( $tasks_count < 20  ) { 
        // dd( $request->all()  ) ;
        // dd($request->file('photos'));

        $this->validate( $request, [
            'task_title' => 'required',
            'task'       => 'required',
            'project_id' => 'required|numeric',
            'photos.*'   => 'sometimes|required|mimes:png,gif,jpeg,jpg,txt,pdf,doc',  // photos is an array: photos.*
            'duedate'    => 'required',
           
        ]) ;

        // dd($request->all() ) ;
        // First save Task Info
        $role = \Auth::user()->role; 
        $user_id = \Auth::user()->id;
        $created_by;
        if($role=='Admin' || $role == 'sa'){
           global $created_by;
            $created_by = \Auth::user()->name;
        }
        $task = Task::create([
            'project_id' => $request->project_id,
            'user_id'    => $request->user,
            'task_title' => $request->task_title,
            'task'       => $request->task,
            'priority'   => $request->priority,
            'duedate'    => $request->duedate,
            'created_by'    => $created_by
        ]);

        // Then save files using the newly created ID above
        if( $request->hasFile('photos') ) {
            foreach ($request->photos as $file) {

                $filename = strtr( pathinfo( time() . '_' . $file->getClientOriginalName(), PATHINFO_FILENAME) , [' ' => '', '.' => ''] ) . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $file->move('images',$filename);

                // save to DB
                TaskFiles::create([
                    'task_id'  => $task->id, // newly created ID
                    'filename' => $filename,  // For Regular Public Images
                    'user_id' => $user_id
                ]);
            }
        }

        Session::flash('success', 'Task Created') ;
        return redirect()->route('subadmintask.show') ; 
    // }
    
    // else {
    //     Session::flash('info', 'Please delete some tasks, Demo max tasks: 20') ;
    //     return redirect()->route('subadmintask.show') ;         
    // }

}
/*===============================================
    VIEW Task
===============================================*/
public function subadminview($id)  {
    $images_set = [] ;
    $files_set = [] ;
    $images_array = ['png','gif','jpeg','jpg'] ;
    // get task file names with task_id number
    $taskfiles = TaskFiles::where('task_id', $id )->get() ;
    $taskfiless = DB::table('task_files')
    ->join('users', 'users.id', '=', 'task_files.user_id')
    ->select('*')
    ->where('task_id', $id)
    ->get();
    if ( count($taskfiles) > 0 ) { 
        foreach ( $taskfiles as $taskfile ) {

            // explode the filename into 2 parts: the filename and the extension
            $taskfile = explode(".", $taskfile->filename ) ;
            // store images only in one array
            // $taskfile[0] = filename
            // $taskfile[1] = jpg
            // check if extension is a image filetype
            if ( in_array($taskfile[1], $images_array ) ) 
                $images_set[] = $taskfile[0] . '.' . $taskfile[1] ;
                // if not an image, store in files array
            else
                $files_set[] = $taskfile[0] . '.' . $taskfile[1]; 
        }
    }



    $task_view = Task::find($id) ;

    // Get task created and due dates
    $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $task_view->created_at);
    $to   = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $task_view->duedate ); // add here the due date from create task

    $current_date = \Carbon\Carbon::now();

    // Format dates for Humans
    $formatted_from = $from->toRfc850String();  
    $formatted_to   = $to->toRfc850String();

    // Get Difference between current_date and duedate = days left to complete task
    // $diff_in_days = $from->diffInDays($to);
    $diff_in_days = $current_date->diffInDays($to);

    // Check for overdue tasks
    $is_overdue = ($current_date->gt($to) ) ? true : false ;

    // $task_view->project->project_name   will output the project name for this specific task
    // to populate the right sidebar with related tasks
    $projects = Project::all() ;
   // $comments = Comment::where('task_id', $id)->get();
    $comments = DB::table('comments')
    ->select('body', 'name')
    ->join('users', 'users.id', '=', 'comments.user_id')
    ->where('task_id', $id)
    ->orderBy('comments.created_at', 'DESC')
    ->get();
    //$comments =(array)$comments;
    //dd($comments);
    $user_id = \Auth::user()->id;
    return view('subadmin.view')
        ->with('task_view', $task_view) 
        ->with('projects', $projects) 
        ->with('taskfiles', $taskfiles)
        ->with('taskfiless', $taskfiless)
        ->with('diff_in_days', $diff_in_days )
        ->with('is_overdue', $is_overdue) 
        ->with('formatted_from', $formatted_from ) 
        ->with('formatted_to', $formatted_to )
        ->with('images_set', $images_set)
        ->with('files_set', $files_set) 
        ->with('user_id', $user_id)
        ->with('comments', $comments);
}

/*===============================================
    EDIT TASK
===============================================*/
public function subadminedit($id)
{
    // $task_list = Task::where('project_id','=' , $projectid)->get();
    $task = Task::find($id)  ; 
    $taskfiles = TaskFiles::where('task_id', '=', $id)->get() ;
    // dd($taskfiles) ;
    $projects = Project::all() ;
    $users = User::all() ;

    return view('subadmin.edit')->with('task', $task)
                            ->with('projects', $projects ) 
                            ->with('users', $users)
                            ->with('taskfiles', $taskfiles);
}

/*===============================================
    UPDATE TASK
===============================================*/
public function subadminupdate(Request $request, $id)
{
    // dd( $request->all() ) ;
    $update_task = Task::find($id) ;

    $this->validate( $request, [
        'task_title' => 'required',
        'task'       => 'required',
        'project_id' => 'required|numeric',
        'photos.*'   => 'sometimes|required|mimes:png,gif,jpeg,jpg,txt,pdf,doc' // photos is an array: photos.*
    ]) ;
    $role = \Auth::user()->role; 
    $user_id = \Auth::user()->id;

    $created_by;
    if($role=='Admin' || $role='sa'){
       global $created_by;
        $created_by = \Auth::user()->name;
    }
    $update_task->task_title = $request->task_title; 
    $update_task->task       = $request->task;
    $update_task->user_id    = $request->user_id;
    $update_task->project_id = $request->project_id;
    $update_task->priority   = $request->priority;
    $update_task->completed  = $request->completed;
    $update_task->duedate    = $request->duedate;
    $update_task->created_by    = $created_by;
    $update_task->extra_time    = 0;

    if( $request->hasFile('photos') ) {
        foreach ($request->photos as $file) {
            // remove whitespaces and dots in filenames : [' ' => '', '.' => ''] 
            $filename = strtr( pathinfo( time() . '_' . $file->getClientOriginalName(), PATHINFO_FILENAME) , [' ' => '', '.' => ''] ) . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

            $file->move('images',$filename);

            // save to DB
            // save to DB
            TaskFiles::create([
                'task_id'  => $request->task_id,
                'filename' => $filename,  // For Regular Public Images
                'user_id' => $user_id
            ]);
        }        
    }

    $update_task->save() ;
    
    Session::flash('success', 'Task was sucessfully edited') ;
    return redirect()->route('subadmintask.show') ;
}

/*===============================================
    SORT TASKS
===============================================*/
public function subadminsort( $key ) {
    $dep_id = \Auth::user()->dept_id;
    $users = User::where('dept_id', $dep_id);
    // dd ($key) ; 
    switch($key) {
        case 'task':
            $tasks = Task::where('project_id', $dep_id)->orderBy('task')->paginate(10); // replace get() with paginate()
        break;
        case 'priority':
            $tasks = Task::where('project_id', $dep_id)->orderBy('priority')->paginate(10);
        break;
        case 'completed':
            $tasks = Task::where('project_id', $dep_id)->orderBy('completed')->paginate(10);
        break;
    }

    return view('subadmin.tasks')->with('users', $users)
                            ->with('tasks', $tasks) ;
}

/*===============================================
    SEARCH TASK
===============================================*/
public function subadminsearchTask()
{
    $value = Input::get('task_search');
    $dep_id = \Auth::user()->dept_id;
    // Search Inside the Contents of a task
    $tasks = Task::where('task_title', 'LIKE', '%' . $value . '%')->where('project_id', $dep_id)->limit(25)->get();
    return view('subadmin.search')->with('value', $value)
                              ->with('tasks', $tasks) ;
}

/*===============================================
    ATTACH THE FILE FROM THE USER DASHBOARD
===============================================*/
public function userfileattach (Request $request, $id){
    if( $request->hasFile('photos') ) {
        $user_id = \Auth::user()->id;
        
        foreach ($request->photos as $file) {
            // remove whitespaces and dots in filenames : [' ' => '', '.' => ''] 
            $filename = strtr( pathinfo( time() . '_' . $file->getClientOriginalName(), PATHINFO_FILENAME) , [' ' => '', '.' => ''] ) . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            // dd($filename);
            $file->move('images',$filename);

            // save to DB
            // save to DB
            TaskFiles::create([
                'task_id'  => $id,
                'filename' => $filename,  // For Regular Public Images
                'user_id' => $user_id
            ]);
        }  
        Session::flash('success', 'File Attached Successfuly!') ;     
        return redirect()->back(); 
    }
    else{
        echo "df";
        Session::flash('info', 'Please Attach FIle!') ;
        return redirect()->back();
    }
}

}




