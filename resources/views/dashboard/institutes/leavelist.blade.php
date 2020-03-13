@extends('adminlte::page')

@section('title', 'ছুটি তালিকা')

@section('css')

@stop

@section('content_header')
    <h1>
        ছুটি তালিকা, {{ $institute->name }}, {{ $institute->upazilla->upazilla_bangla }}
        
        <div class="pull-right">
            
        </div>
    </h1>
@stop

@section('content')
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo' || Auth::user()->role == 'ateo' || (Auth::user()->role == 'headmaster' && Auth::user()->institute->device_id == $institute->device_id))
        <div class="row">
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>শিক্ষক/ অফিস সহকারি</th>
                            <th>ছুটি শুরুর তারিখ</th>
                            <th>ছুটি শেষের তারিখ</th>
                            <th>ছুটির কারণ</th>
                            <th>ছুটি প্রদানকারী</th>
                            <th>কার্যক্রম</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                            <tr>
                                <td>{{ $leave->teacher->name }}</td>
                                <td>{{ bangla(date('F d, Y', strtotime($leave->leave_start))) }}</td>
                                <td>{{ bangla(date('F d, Y', strtotime($leave->leave_end))) }}</td>
                                <td>{{ $leave->reason }}</td>
                                <td>
                                    @if($leave->issuer !=null)
                                        {{ $leave->issuer->name }} ({{ designation($leave->issuer->role) }})
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteLeave{{ $leave->id }}" data-backdrop="static" title="ছুটি ডিলেট করুন">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <!-- Delete Modal -->
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteLeave{{ $leave->id }}" role="dialog">
                                      <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                          <div class="modal-header modal-header-danger">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> ছুটি ডিলেট</h4>
                                          </div>
                                          <div class="modal-body">
                                            আপনি কি নিশ্চিতভাবে <b>{{ $leave->teacher->name }}</b> এর ছুটি বাতিল করতে চান??
                                          </div>
                                          <div class="modal-footer">
                                            {!! Form::model($leave, ['route' => ['dashboard.deleteleave', $leave->id], 'method' => 'DELETE', 'class' => 'form-default']) !!}
                                                {!! Form::submit('ডিলেট', array('class' => 'btn btn-danger')) !!}
                                                <button type="button" class="btn btn-default" data-dismiss="modal">ফিরে যান</button>
                                            {!! Form::close() !!}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Delete Modal -->
                                    <!-- Delete Modal -->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@stop


@section('js')
    
@endsection