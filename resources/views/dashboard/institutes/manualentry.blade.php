@extends('adminlte::page')

@section('title', 'উপস্থিতি যোগ')

@section('css')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@stop

@section('content_header')
    <h1>
      উপস্থিতি যোগ</b>
    </h1>
@stop

@section('content')
  @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'ateo')
      <div class="row">
          <div class="col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">উপস্থিতি যোগ ফরম</div>
              {!! Form::open(['route' => 'dashboard.storemanualentry', 'method' => 'POST']) !!}
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12">
                      {!! Form::label('leave_start', 'শিক্ষক নির্ধারণ') !!}
                      <select class="form-control" name="teacher_id" required="">
                        <option value="" selected="" disabled="">শিক্ষক নির্ধারণ করুন</option>
                        @foreach($institute->users as $teacher)
                          <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ designation($teacher->role) }})</option>

                        @endforeach
                      </select>
                    </div>
                  </div><br/>
                  <div class="row">
                    <div class="col-md-6">
                      {!! Form::label('entrancetime', 'প্রবেশ') !!}
                      {!! Form::text('entrancetime', null, array('class' => 'form-control', 'placeholder' => 'প্রবেশের সময়', 'autocomplete' => 'off', 'required' => '')) !!}
                    </div>
                    <div class="col-md-6">
                      {!! Form::label('departuretime', 'প্রস্থান') !!}
                      {!! Form::text('departuretime', null, array('class' => 'form-control', 'placeholder' => 'প্রস্থানের সময়', 'autocomplete' => 'off', 'required' => '')) !!}
                    </div>
                  </div>
                </div>
                <div class="panel-footer">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> দাখিল করুন</button>
                </div>
              {!! Form::close() !!}
            </div>
          </div>
          <div class="col-md-4">

          </div>
      </div>
  @endif
@stop

@section('js')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script>
      $("#entrancetime").datetimepicker();
      $("#departuretime").datetimepicker();
  </script>
@endsection