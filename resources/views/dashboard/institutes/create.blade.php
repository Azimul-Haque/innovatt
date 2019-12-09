@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান যোগ')

@section('css')

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
          {!! Form::open(['route' => 'dashboard.institutes.store', 'method' => 'POST']) !!}
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

            {!! Form::label('device_id', 'ডিভাইস আইডি (বক্স/ মেশিনের গায়ে স্টিকারে SN এর পরের অংশটুকু) *') !!}
            {!! Form::text('device_id', null, array('class' => 'form-control', 'required' => '', 'autocomplete' => 'off')) !!} <br/>
            
            {!! Form::label('upazilla_id', 'উপজেলা *') !!}
            <select name="upazilla_id" id="upazilla_id" class="form-control" required>
              <option value="" selected="" disabled="">উপজেলা নির্ধারণ করুন</option>
              @foreach($upazillas as $upazilla)
                <option value="{{ $upazilla->id }}" @if(Auth::user()->upazilla_id == $upazilla->id) selected="" @endif>{{ $upazilla->upazilla_bangla }} ({{ $upazilla->district_bangla }})</option>
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
  <script type="text/javascript">
    $('#upazilla_id').select2();
  </script>
@endsection