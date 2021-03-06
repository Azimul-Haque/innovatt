@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান যোগ')

@section('css')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@stop

@section('content_header')
    <h1>
      নতুন প্রতিষ্ঠান যোগ
    </h1>
@stop

@section('content')
  <div class="row">
      <div class="col-md-8">
        <div class="panel panel-primary">
          <div class="panel-heading">প্রতিষ্ঠান যোগ ফরম (* অর্থ বাধ্যতামূলক)</div>
          {!! Form::model($institute, ['route' => ['dashboard.institutes.update', $institute->id], 'method' => 'PUT']) !!}
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                {!! Form::label('name', 'প্রতিষ্ঠানের নাম *') !!}
                {!! Form::text('name', null, array('class' => 'form-control', 'required' => '')) !!}
              </div>
              <div class="col-md-6">
                {!! Form::label('serial', 'প্রতিষ্ঠানের ক্রমিক *') !!}
                {!! Form::text('serial', null, array('class' => 'form-control', 'required' => '')) !!}
              </div>
            </div>
            <br/>

            <div class="row">
              <div class="col-md-6">
                {!! Form::label('name', 'প্রতিষ্ঠানে প্রবেশের সময় *') !!}
                <input type="text" id="entrance" name="entrance" class="form-control" value="{{ date('h:i A', strtotime($institute->entrance)) }}" autocomplete="off" required="">
              </div>
              <div class="col-md-6">
                {!! Form::label('serial', 'প্রতিষ্ঠানে ত্যাগের সময় *') !!}
                <input type="text" id="departure" name="departure" class="form-control" value="{{ date('h:i A', strtotime($institute->departure)) }}" autocomplete="off" required="">
              </div>
            </div>
            <br/>

            {!! Form::label('device_id', 'ডিভাইস আইডি (বক্স/ মেশিনের গায়ে স্টিকারে SN এর পরের অংশটুকু) *') !!}
            {!! Form::text('device_id', null, array('class' => 'form-control', 'required' => '', 'autocomplete' => 'off')) !!} <br/>
            
            {!! Form::label('upazilla_id', 'উপজেলা *') !!}
            <select name="upazilla_id" id="upazilla_id" class="form-control" required>
              <option value="" selected="" disabled="">উপজেলা সিলেক্ট করুন</option>
              @foreach($upazillas as $upazilla)
                <option value="{{ $upazilla->id }}" @if($institute->upazilla_id == $upazilla->id) selected="" @endif>{{ $upazilla->upazilla_bangla }} ({{ $upazilla->district_bangla }})</option>
              @endforeach
            </select>            
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
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script>
      $("#entrance").datetimepicker({
          format: 'LT',
      });
      $("#departure").datetimepicker({
          format: 'LT',
      });
  </script>
  <script type="text/javascript">
    $('#upazilla_id').select2();
  </script>
@endsection