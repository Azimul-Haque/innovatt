@extends('adminlte::page')

@section('title', 'ব্যক্তিগত প্রোফাইল')

@section('css')

@stop

@section('content_header')
    <h1>
      ব্যক্তিগত প্রোফাইল
    </h1>
@stop

@section('content')
  <div class="row">
      <div class="col-md-8">
        <div class="panel panel-primary">
          <div class="panel-heading">ব্যক্তিগত তথ্য হালনাগাদ ফরম (* অর্থ বাধ্যতামূলক)</div>
          {!! Form::model(Auth::user(), ['route' => ['dashboard.profile.update', Auth::user()->id], 'method' => 'PUT']) !!}
          <div class="panel-body">
            {!! Form::label('name', 'নাম *') !!}
            {!! Form::text('name', null, array('class' => 'form-control', 'required' => '')) !!}<br/>

            {!! Form::label('designation', 'পদবি নাম *') !!}
            {!! Form::text('designation', null, array('class' => 'form-control', 'required' => '')) !!}<br/>

            {!! Form::label('password', 'পাসওয়ার্ড *') !!}
            {!! Form::password('password', array('class' => 'form-control', 'required' => '')) !!}<br/>
            <div class="row">
              <div class="col-md-6">
                {!! Form::label('role', 'ধরন') !!}
                <select name="role" class="form-control" disabled="">
                  <option value="" selected="" disabled="">ধরন নির্ধারণ করুন</option>
                  <option value="teo" @if(Auth::user()->role == 'teo') selected="" @endif>শিক্ষা অফিসার/ অনুমোদিত কর্তৃপক্ষ</option>
                  <option value="headmaster" @if(Auth::user()->role == 'headmaster') selected="" @endif>প্রধান শিক্ষক</option>
                  <option value="teacher" @if(Auth::user()->role == 'teacher') selected="" @endif>সহকারী শিক্ষক</option>
                </select>
              </div>
              <div class="col-md-6">
                {!! Form::label('phone', 'মোবাইল নম্বর (১১ ডিজিট)') !!}
                {!! Form::text('phone', null, array('class' => 'form-control', 'onkeypress' => 'if(this.value.length==11) return false;', 'disabled' => '')) !!}
              </div>
            </div> <br/>

            <div class="row">
              <div class="col-md-6">
                {!! Form::label('device_pin', 'ডিভাইস পিন (শিক্ষা অফিসারের ক্ষেত্রে প্রযোজ্য নয়)') !!}
                {!! Form::number('device_pin', null, array('class' => 'form-control', 'autocomplete' => 'off', 'disabled' => '')) !!}
              </div>
              <div class="col-md-6">
                {!! Form::label('upazilla_id', 'উপজেলা *') !!}
                <select name="upazilla_id" id="upazilla_id" class="form-control" disabled="">
                  <option value="" selected="" disabled="">উপজেলা নির্ধারণ করুন</option>
                  @foreach($upazillas as $upazilla)
                    <option value="{{ $upazilla->id }}" @if(Auth::user()->upazilla_id == $upazilla->id) selected="" @endif>{{ $upazilla->upazilla_bangla }} ({{ $upazilla->district_bangla }})</option>
                  @endforeach
                </select>    
              </div>
            </div> <br/>

            <div class="row">
              <div class="col-md-6">
                {!! Form::label('institute_id', 'প্রতিষ্ঠান *') !!}
                <select name="institute_id" id="institute_id" class="form-control" disabled="">
                  <option value="" selected="" disabled="">প্রতিষ্ঠান নির্ধারণ করুন</option>
                  @foreach($institutes as $institute)
                    <option value="{{ $institute->id }}" @if(Auth::user()->institute_id == $institute->id) selected="" @endif>{{ $institute->name }}, {{ $institute->upazilla->upazilla_bangla }}</option>
                  @endforeach
                  <option value="0">শিক্ষা অফিসার (স্কুল প্রযোজ্য নয়)</option>
                </select>    
              </div>
              <div class="col-md-6">
                * মোবাইল নম্বরসহ বাকি তথ্য পরিবর্তন করতে চাইলে কর্তৃপক্ষের সাথে যোগাযোগ করুন
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