@extends('adminlte::page')

@section('title', 'ছুটি প্রদান')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@stop

@section('content_header')
    <h1>
      ছুটি প্রদান | শিক্ষক/ অফিস সহকারিঃ <b>{{ $teacher->name }}</b>
    </h1>
@stop

@section('content')
  <div class="row">
      <div class="col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading">ছুটি প্রদান ফরম</div>
          {!! Form::open(['route' => 'dashboard.users.store', 'method' => 'POST']) !!}
            <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                {!! Form::label('leave_start_date', 'ছুটি শুরুর তারিখ') !!}
                {!! Form::text('leave_start_date', null, array('class' => 'form-control', 'placeholder' => 'ছুটি শুরুর তারিখ', 'autocomplete' => 'off', 'required' => '')) !!}
              </div>
              <div class="col-md-6">
                {!! Form::label('leave_end_date', 'ছুটি শেষের তারিখ') !!}
                {!! Form::text('leave_end_date', null, array('class' => 'form-control', 'placeholder' => 'ছুটি শেষের তারিখ', 'autocomplete' => 'off', 'required' => '')) !!}
              </div>
            </div> <br/>
            <div class="row">
                <div class="col-md-6">
                  {!! Form::label('reason', 'কারণ') !!}
                  <select name="reason" id="reason" class="form-control" required="">
                    <option value="" selected="" disabled="">কারণ নির্ধারণ করুন</option>
                    <option value="ট্রেনিং">ট্রেনিং</option>
                    <option value="মাতৃত্বকালীন">মাতৃত্বকালীন</option>
                    <option value="অসুস্থতা">অসুস্থতা</option>
                    <option value="other">অন্যান্য</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <div id="otherreasontoggle">
                      {!! Form::label('otherreason', 'কারণ লিখুন') !!}
                      {!! Form::text('otherreason', null, array('class' => 'form-control', 'placeholder' => 'কারণ লিখুন')) !!}
                  </div>
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
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $("#leave_start_date").datepicker({
            format: 'MM dd, yyyy',
            todayHighlight: true,
            autoclose: true,
        });
        $("#leave_end_date").datepicker({
            format: 'MM dd, yyyy',
            todayHighlight: true,
            autoclose: true,
        });

        $("#otherreasontoggle").hide();
        $("#reason").change(function() {
            console.log($("#reason").val());
            if($("#reason").val() == 'other') {
                $("#otherreasontoggle").show();
                $("#otherreason").attr('required', true);
            } else {
                $("#otherreasontoggle").hide();
                $("#otherreason").removeAttr('required', true);
            }
        });
    </script>
@endsection