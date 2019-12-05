@extends('adminlte::page')

@section('title', 'শিক্ষক/ ব্যবহারকারী যোগ')

@section('css')

@stop

@section('content_header')
    <h1>
      নতুন শিক্ষক/ ব্যবহারকারী যোগ
    </h1>
@stop

@section('content')
  <div class="row">
      <div class="col-md-8">
        <div class="panel panel-primary">
          <div class="panel-heading">শিক্ষক/ ব্যবহারকারী যোগ ফরম (* অর্থ বাধ্যতামূলক)</div>
          {!! Form::open(['route' => 'dashboard.institute.user.store', 'method' => 'POST']) !!}
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                {!! Form::hidden('device_id', $institute->device_id) !!}
                
                {!! Form::label('name', 'নাম *') !!}
                {!! Form::text('name', null, array('class' => 'form-control', 'required' => '')) !!}
              </div>
              <div class="col-md-6">
                {!! Form::label('gender', 'লিঙ্গ *') !!}
                <select name="gender" class="form-control" required="">
                  <option value="" selected="" disabled="">লিঙ্গ নির্ধারণ করুন</option>
                  <option value="1">পুরুষ</option>
                  <option value="2">মহিলা</option>
                </select>
              </div>
            </div> <br/>
            <div class="row">
              <div class="col-md-6">
                {!! Form::label('role', 'ধরন *') !!}
                <select name="role" class="form-control" required="">
                  <option value="" selected="" disabled="">ধরন নির্ধারণ করুন</option>
                  <option value="headmaster">প্রধান শিক্ষক</option>
                  <option value="teacher">সহকারী শিক্ষক</option>
                </select>
              </div>
              <div class="col-md-6">
                {!! Form::label('phone', 'মোবাইল নম্বর (১১ ডিজিট) *') !!}
                {!! Form::text('phone', null, array('class' => 'form-control', 'required' => '', 'onkeypress' => 'if(this.value.length==11) return false;')) !!}
              </div>
            </div> <br/>

            <div class="row">
              <div class="col-md-6">
                {!! Form::label('device_pin', 'ডিভাইস পিন (মেশিনে এই শিক্ষকের আইডি) *') !!}
                {!! Form::number('device_pin', null, array('class' => 'form-control', 'required' => '', 'autocomplete' => 'off')) !!}
              </div>
              <div class="col-md-6">
                {!! Form::label('upazilla_id', 'উপজেলা *') !!}
                <select name="upazilla_id" id="upazilla_id" class="form-control" required>
                  <option value="" selected="" disabled="">উপজেলা নির্ধারণ করুন</option>
                  @foreach($upazillas as $upazilla)
                    <option value="{{ $upazilla->id }}" @if($upazilla->id == $institute->upazilla_id) selected="" @endif>{{ $upazilla->upazilla_bangla }} ({{ $upazilla->district_bangla }})</option>
                  @endforeach
                </select>    
              </div>
            </div> <br/>

            <div class="row">
              
              <div class="col-md-6">
                {!! Form::label('institute_id', 'প্রতিষ্ঠান *') !!}
                <select name="institute_id" id="institute_id" class="form-control" required>
                  <option value="" selected="" disabled="">প্রতিষ্ঠান নির্ধারণ করুন</option>
                  @foreach($institutes as $institutesingle)
                    <option value="{{ $institutesingle->id }}" @if($institutesingle->id == $institute->id) selected="" @endif>{{ $institutesingle->name }}, {{ $institutesingle->upazilla->upazilla_bangla }}</option>
                  @endforeach
                </select>    
              </div>
            </div> <br/>
            
                    
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
    $('#institute_id').select2();
    $('#upazilla_id').select2();
  </script>
@endsection