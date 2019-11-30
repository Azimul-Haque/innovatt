@extends('adminlte::page')

@section('title', 'Transfer Group | Microfinance Management')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@stop

@section('content_header')
    <h1>
      Transfer Group
    </h1>
@stop

@section('content')
  <div class="row">
      <div class="col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading">Transfer Group</div>
          {!! Form::model($group, ['route' => ['dashboard.group.transfer', $group->id], 'method' => 'PUT']) !!}
          <div class="panel-body">
            {!! Form::label('name', 'Group Name') !!}
            {!! Form::text('name', null, array('class' => 'form-control', 'readonly' => '')) !!}
            
            <br/>
            {!! Form::label('user_id', 'Assign Staff *') !!}
            <select name="user_id" id="user_id" class="form-control" required>
              <option disabled="">Select Staff</option>
              @foreach($staffs as $staffselect)
                <option value="{{ $staffselect->id }}" @if($staffselect->id == $staff->id) selected="" @endif>{{ $staffselect->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="panel-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
      <div class="col-md-4">

      </div>
    </div>
@stop

@section('js')
  <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript">
    $(function() {
      $("#formation").datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true,
      });
    });

    $('#user_id').select2();
  </script>
@endsection