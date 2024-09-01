<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TasksController extends Controller
{
    public function index(Request $request)
    {
       
        abort_if(Gate::denies('task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee = Auth()->user()->employee;
        if ($request->ajax()) {
            if (Auth()->user()->roles[0]->title == 'Super Admin') 
            {
                $query = Task::with(['to_user', 'created_by', 'to_role','supervisor'])
                    ->select(sprintf('%s.*', (new Task())->table));
            }elseif (Auth()->user()->roles[0]->title == 'Admin') {
                $query = Task::with(['to_user', 'created_by', 'to_role','supervisor'])
                    ->whereHas('created_by',fn($q) => $q->whereHas('employee',fn($x) => $x->whereBranchId($employee->branch_id)))
                    ->orWhere('supervisor_id',Auth()->id())
                    ->select(sprintf('%s.*', (new Task())->table));
            }else{
                $query = Task::with(['to_user', 'created_by', 'to_role','supervisor'])
                    ->whereSupervisorId(Auth()->id())
                    ->orWhere('to_user_id',Auth()->id())
                    ->orWhere('created_by_id',Auth()->id())
                    ->select(sprintf('%s.*', (new Task())->table));
            }
            if ($request->has('employee_id') && $request->employee_id !='') {
                $query->where('to_user_id', $request->employee_id);
            }

            $table = DataTables::eloquent($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'task_show';
                $editGate = 'task_edit';
                $deleteGate = 'task_delete';
                $crudRoutePart = 'tasks';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : 'N/D';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : 'N/D';
            });
            $table->editColumn('created_by', function ($row) {
                return $row->created_by ? $row->created_by->name : 'N/D';
            });
            $table->editColumn('to_user', function ($row) {
                return $row->to_user ? $row->to_user->name : 'N/D';
            });
            $table->editColumn('to_role', function ($row) {
                return $row->to_role ? $row->to_role->title : 'N/D';
            });

            $table->editColumn('confirmation_at', function ($row) {
                return $row->confirmation_at ? $row->confirmation_at : 'N/D';
            });

            $table->editColumn('done_at', function ($row) {
                return $row->done_at ? $row->done_at : 'N/D';
            });
            $table->addColumn('status', function ($row) {
                return
                    '<span class="badge badge-' . Task::STATUS_COLOR[$row->status] . '  p-2">' . Task::STATUS[$row->status] . '</span>';
            });
            $table->editColumn('task_date', function ($row) {
                return $row->task_date ? $row->task_date : 'N/D';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->toFormattedDateString() . ' , ' . $row->created_at->format('g:i A') : '';
            });

            $table->addColumn('supervisor_name', function ($row) {
                return $row->supervisor ? $row->supervisor->name : 'N/D';
            });

            $table->rawColumns(['actions', 'placeholder', 'status','supervisor_name','description']);

            return $table->make(true);
        }

        $employees = Employee::where('status' ,'active')->get();

        return view('admin.tasks.index' , compact('employees'));
    }

    public function create()
    {
        abort_if(Gate::denies('task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee = Auth()->user()->employee;

        $branch_id = $employee && $employee->branch_id != NULL ? $employee->branch_id : NULL;        

        if ($branch_id == NULL) 
        {
            $users = User::with(['roles', 'employee'])
                        ->whereHas('employee',fn($q) => $q->whereStatus('active'))
                        ->orderBy('name')
                        ->get();
        }else{
            $users = User::with(['roles', 'employee'])
                        ->whereHas('employee',fn($q) => $q->whereStatus('active')->whereBranchId($branch_id))
                        ->orWhere(function($q){
                            $q->whereRelation('roles','title','Maintenance');
                        })
                        ->orderBy('name')
                        ->get();
        }
      
        $roles  = Role::pluck('title', 'id')->prepend('Plese Select Role', '');

        return view('admin.tasks.create', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request['created_by_id']   = Auth()->user()->id;
        $task = Task::create($request->all());

        return redirect()->route('admin.tasks.index');
    }

    public function edit(Task $task)
    {
        abort_if(Gate::denies('task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee = Auth()->user()->employee;

        $branch_id = $employee && $employee->branch_id != NULL ? $employee->branch_id : NULL;  

        
        if ($branch_id == NULL) 
        {
            $users = User::with(['roles', 'employee'])
                        ->whereHas('employee',fn($q) => $q->whereStatus('active'))
                        ->get();
        }else{
            $users = User::with(['roles', 'employee'])
                        ->whereHas('employee',fn($q) => $q->whereStatus('active')->whereBranchId($branch_id))
                        ->get();
        }
      
        $roles  = Role::pluck('title', 'id')->prepend('Plese Select Role', '');

        return view('admin.tasks.edit', compact('task', 'users', 'roles'));
    }

    public function update(Request $request, Task $task)
    {
        dd($request->all());
        $task->update([
            'name'                   => $request['name'],
            'branch_id'              => $request['branch_id'],
            'commission_percentage'  => $request['commission_percentage'],
            'manager'                => isset($request['manager']) ? true : false
        ]);

        $this->updated();
        return redirect()->route('admin.tasks.index');
    }

    public function show(Task $task)
    {
        abort_if(Gate::denies('task_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tasks.show', compact('task'));
    }

    public function destroy(Task $task)
    {
        abort_if(Gate::denies('task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->delete();

        return back();
    }


    public function done_tasks(Task $task)
    {
        abort_if(Gate::denies('task_action'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->update([
            'status'    => 'done',
            'done_at'   => date('Y-m-d')
        ]);

        return back();
    }

    public function in_progress_tasks(Task $task)
    {
        abort_if(Gate::denies('task_action'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->update([
            'status'    => 'in_progress'
        ]);

        return back();
    }

    public function confirm_task(Task $task)
    {
        abort_if(Gate::denies('task_action'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->update([
            'status'                => 'done_with_confirm',
            'confirmation_at'       => date('Y-m-d'),
        ]);

        return back();
    }

    public function my_tasks(Request $request)
    {
        abort_if(Gate::denies('task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Task::with(['to_user', 'created_by', 'to_role','supervisor'])
                ->where('to_user_id', Auth()->user()->id)
                ->orWhere('to_role_id', Auth()->user()->roles[0]->id)->select(sprintf('%s.*', (new Task())->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'task_show';
                // $editGate = 'task_edit';
                // $deleteGate = 'task_delete';
                $crudRoutePart = 'tasks';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    // 'editGate',
                    // 'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('created_by', function ($row) {
                return $row->created_by ? $row->created_by->name : '';
            });
            $table->editColumn('to_user', function ($row) {
                return $row->to_user ? $row->to_user->name : '';
            });
            $table->editColumn('to_role', function ($row) {
                return $row->to_role ? $row->to_role->title : 'N/D';
            });
            
            $table->editColumn('done_at', function ($row) {
                return $row->done_at ? $row->done_at : 'N/D';
            });

            $table->addColumn('supervisor_name', function ($row) {
                return $row->supervisor ? $row->supervisor->name : 'N/D';
            });

            $table->editColumn('confirmation_at', function ($row) {
                return $row->confirmation_at ? $row->confirmation_at : 'N/D';
            });

            $table->addColumn('status', function ($row) {
                return
                    '<span class="badge badge-' . Task::STATUS_COLOR[$row->status] . '  p-2">' . Task::STATUS[$row->status] . '</span>';
            });
            $table->editColumn('task_date', function ($row) {
                return $row->task_date ? $row->task_date : '';
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->toFormattedDateString() . ' , ' . $row->created_at->format('g:i A') : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'status','supervisor_name']);

            return $table->make(true);
        }

        return view('admin.tasks.my_tasks');
    }
    public function created_tasks(Request $request)
    {
        abort_if(Gate::denies('task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Task::with('to_user', 'created_by', 'to_role')
                ->where('created_by_id', Auth()->user()->id)->select(sprintf('%s.*', (new Task())->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'task_show';
                // $editGate = 'task_edit';
                // $deleteGate = 'task_delete';
                $crudRoutePart = 'tasks';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    // 'editGate',
                    // 'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('created_by', function ($row) {
                return $row->created_by ? $row->created_by->name : '';
            });
            $table->editColumn('to_user', function ($row) {
                return $row->to_user ? $row->to_user->name : '';
            });
            $table->editColumn('to_role', function ($row) {
                return $row->to_role ? $row->to_role->title : 0;
            });
            $table->editColumn('done_at', function ($row) {
                return $row->done_at ? $row->done_at : '';
            });
            $table->addColumn('status', function ($row) {
                return
                    '<span class="badge badge-' . Task::STATUS_COLOR[$row->status] . '  p-2">' . Task::STATUS[$row->status] . '</span>';
            });
            $table->editColumn('task_date', function ($row) {
                return $row->task_date ? $row->task_date : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->toFormattedDateString() . ' , ' . $row->created_at->format('g:i A') : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'status']);

            return $table->make(true);
        }

        return view('admin.tasks.created_tasks');
    }

    
}
