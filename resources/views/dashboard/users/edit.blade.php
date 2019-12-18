@extends('adminlte::page')

@section('title', 'তথ্য হালনাগাদ')

@section('css')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <style>
        input, label {
            display:block;
        }
    </style>
@stop

@section('content_header')
    <h1>
        তথ্য হালনাগাদ
    </h1>
@stop

@section('content')
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || (Auth::user()->role == 'headmaster' && Auth::user()->institute->device_id == $teacher->institute->device_id))
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-primary">
                    <div class="panel-heading">ব্যক্তিগত তথ্য হালনাগাদ ফরম (* অর্থ বাধ্যতামূলক)</div>
                    @if($teacher->role == 'ateo')
                        {!! Form::model($teacher, ['route' => ['dashboard.users.update.ateo', $teacher->id], 'method' => 'PUT']) !!}
                    @else
                        {!! Form::model($teacher, ['route' => ['dashboard.users.update', $teacher->id], 'method' => 'PUT']) !!}
                    @endif
                        <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('name', 'নাম *') !!}
                                {!! Form::text('name', null, array('class' => 'form-control', 'required' => '')) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::label('gender', 'লিঙ্গ *') !!}
                                <select name="gender" class="form-control" required="">
                                    <option value="" selected="" disabled="">লিঙ্গ নির্ধারণ করুন</option>
                                    <option value="1" @if($teacher->gender == 1) selected="" @endif>পুরুষ</option>
                                    <option value="2" @if($teacher->gender == 2) selected="" @endif>মহিলা</option>
                                </select>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('role', 'ধরন *') !!}
                                    <select name="role" id="role_dropdown"  onchange="checkRoleDrowpdown(this.options[this.selectedIndex].value)" class="form-control" required="">
                                        @if(Auth::user()->role == 'admin')
                                        <option value="teo" @if($teacher->role == 'teo') selected="" @endif>শিক্ষা অফিসার/
                                            অনুমোদিত কর্তৃপক্ষ
                                        </option>
                                        <option value="headmaster" @if($teacher->role == 'headmaster') selected="" @endif>
                                            প্রধান শিক্ষক
                                        </option>
                                        <option value="teacher" @if($teacher->role == 'teacher') selected="" @endif>সহকারী
                                            শিক্ষক
                                        </option>
                                        <option value="ateo"  @if($teacher->role == 'ateo') selected="" @endif>ATEO</option>
                                        @elseif(Auth::user()->role=='teo')
                                            @if($teacher->role == 'ateo')
                                                <option value="ateo" @if($teacher->role == 'ateo') selected="" @endif>ATEO</option>
                                            @else
                                                <option value="headmaster" @if($teacher->role == 'headmaster') selected="" @endif>
                                                    প্রধান শিক্ষক
                                                </option>
                                                <option value="teacher" @if($teacher->role == 'teacher') selected="" @endif>সহকারী
                                                    শিক্ষক
                                                </option>
                                            @endif
                                        @endif
                                    </select>

                            </div>
                            <div class="col-md-6">
                                {!! Form::label('phone', 'মোবাইল নম্বর (১১ ডিজিট) *') !!}
                                {!! Form::text('phone', null, array('class' => 'form-control', 'required' => '', 'onkeypress' => 'if(this.value.length==11) return false;')) !!}
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            @if($teacher->role == 'ateo')
                              
                            @else
                              <div class="col-md-6">
                                {!! Form::label('device_pin', 'ডিভাইস পিন (মেশিনে এই শিক্ষকের আইডি) *') !!}
                                {!! Form::number('device_pin', null, array('class' => 'form-control', 'required' => '', 'autocomplete' => 'off')) !!}
                              </div>
                            @endif
                            <div class="col-md-6">
                                {!! Form::label('upazilla_id', 'উপজেলা *') !!}
                                <select name="upazilla_id" id="upazilla_id" class="form-control" required>
                                    <option value="" selected="" disabled="">উপজেলা নির্ধারণ করুন</option>
                                    @foreach($upazillas as $upazilla)
                                        <option value="{{ $upazilla->id }}"
                                                @if($teacher->upazilla_id == $upazilla->id) selected="" @endif>{{ $upazilla->upazilla_bangla }}
                                            ({{ $upazilla->district_bangla }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('institute_id', 'প্রতিষ্ঠান *') !!}
                                    <select name="institute_id[]" id="institute_id" class="form-control" required   @if($teacher->role=='ateo')
                                    multiple="multiple" @endif>
    {{--                                    <option value="" selected="" disabled="">প্রতিষ্ঠান নির্ধারণ করুন</option>--}}

                                        @if($teacher->role=='teacher' || $teacher->role=='headmaster')
                                            @foreach($institutes as $institute)
                                                <option value="{{ $institute->id }}"
                                                        @if($teacher->institute_id == $institute->id ) selected="" @endif>{{ $institute->name }}
                                                    , {{ $institute->upazilla->upazilla_bangla }}</option>
                                            @endforeach
                                        @elseif($teacher->role=='ateo')
                                            @foreach($ateoinstitutes as $institute)
                                                <option value="{{ $institute->id }}"
                                                        selected="" >{{ $institute->name }}
                                                    , {{ $institute->upazilla->upazilla_bangla }}</option>
                                            @endforeach
                                            @foreach($institutes as $institute)
                                                <option value="{{ $institute->id }}"
                                                        >{{ $institute->name }}
                                                    , {{ $institute->upazilla->upazilla_bangla }}</option>
                                            @endforeach
                                        @endif
{{--                                        <option value="0">শিক্ষা অফিসার (স্কুল প্রযোজ্য নয়)</option>--}}
                                    </select>


                            </div>
                            <div class="col-md-6">
                                {!! Form::label('password', 'পাসওয়ার্ড (ঐচ্ছিক)') !!}
                                {!! Form::password('password', array('class' => 'form-control', 'autocomplete' => 'off')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> দাখিল করুন</button>
                    </div>

                </div>
            </div>
            @if($teacher->role=='teacher' || $teacher->role=='headmaster')
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">ছুটির বর্ণনা</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('on_leave', 'ছুটি *') !!}
                                <input name="on_leave" id="on_leave"  class="form-control" type="checkbox" disabled="disabled" {{($teacher->leave_start_date != null && $teacher->leave_end_date != null) ? 'checked' : ''}} data-toggle="toggle" data-size="small" data-on="ছুটি" data-off="কর্মরত" >
                            </div>
                        </div>
                        <div class="row">
                            <br>
                            <div class="col-md-6">
                                <label for="leave_start_date">শুরু</label>
                                <input  type="text" class="form-control" name="leave_start_date" id='leave_start_date' placeholder="F d, Y" @if($teacher->leave_start_date!=null)value="{{$teacher->leave_start_date}}" @endif>
                                {{--                                {!! Form::label('leave_end_date', 'শেষ') !!}--}}
{{--                                {!! Form::label('leave_start_date', 'শুরু') !!}--}}
{{--                                {!! Form::date('leave_start_date', null, array('class'=>'form-control')) !!}--}}
                            </div>
                            <div class="col-md-6">
                                <label for="leave_end_date">শেষ</label>
{{--                                {!! Form::label('leave_end_date', 'শেষ') !!}--}}
                                <input  type="text" class="form-control" name="leave_end_date" id='leave_end_date' placeholder="F d, Y" @if($teacher->leave_end_date!=null)value="{{$teacher->leave_end_date}}" @endif>
                            </div>
                            </div>
                        </div>
                        <br/>
                    </div>
                </div>
            </div>
            @endif
        </div>
        {!! Form::close() !!}
    @endif
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $('#institute_id').select2();
        $('#upazilla_id').select2();

        setTimeout(function () {
            $('#password').val('');
        }, 500);
    </script>
    <script>
        function checkRoleDrowpdown(selected_option){
            if(selected_option === 'teo'){
                $('#institute_id').prop('disabled', 'disabled');
                $("#institute_id").prop('required',false);
            } else{
                $("#institute_id").prop('required',true);
                $('#institute_id').prop('disabled', false);
            }
        }
        checkRoleDrowpdown($('#role_dropdown').val());

    </script>

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
    </script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

@endsection