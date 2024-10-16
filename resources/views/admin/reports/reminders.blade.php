@extends('layouts.admin')
@section('content')
    <form action="{{ route('admin.reports.reminders') }}" method="get">
        <div class="row form-group">
            <div class="col-md-6">
                <label for="branch_id">Branch</label>
                <div class="input-group">
                    <select name="branch_id" id="branch_id" class="form-control" {{ $employee && $employee->branch_id != NULL ? 'readonly' : '' }}>
                        <option value="{{ NULL }}" selected >All Branches</option>
                        @foreach (\App\Models\Branch::pluck('name','id') as $id => $name)
                            <option value="{{ $id }}" {{ $branch_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-prepend">
                        <button class="btn btn-primary" type="submit" >{{ trans('global.submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="form-group row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.reminders_report') }}
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover table-bordered zero-configuration">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('cruds.user.title_singular') }}</th>
                                <th>{{ trans('cruds.reminder.fields.overdue_remiders') }}</th>
                                <th>{{ trans('global.today_reminders') }}</th>
                                <th>{{ trans('cruds.reminder.fields.upcomming_reminders').' '.'( Month )' }}</th>
                                <th>{{ trans('global.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sale->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.reminders.overdue',['user_id[]' => $sale->id]) }}" target="_blank" >
                                            {{ $sale->overdueReminders->count() }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.reminders.index',['user_id[]' => $sale->id]) }}" target="_blank">
                                            {{ $sale->todayReminders->count() }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.reminders.upcomming',[
                                                'user_id[]' => $sale->id,
                                                'due_date[from]' => date('Y-m-01'),
                                                'due_date[to]' => date('Y-m-t'),
                                            ]) }}" target="_blank">
                                            {{ $sale->upcommingReminders->count() }}
                                        </a>
                                    </td>
                                    <td>{{ $sale->reminders_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection