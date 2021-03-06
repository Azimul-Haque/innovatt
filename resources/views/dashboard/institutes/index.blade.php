@extends('adminlte::page')

@section('title', 'প্রতিষ্ঠান তালিকা')

@section('css')

@stop

@section('content_header')
    <h1>
        সকল প্রতিষ্ঠান
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
            <div class="pull-right">
                <a href="{{ route('dashboard.users.create.ateo') }}" class="btn btn-primary"
                   title="নতুন ATEO যোগ করুন"><i class="fa fa-plus"></i> ATEO যোগ</a>
                <a href="{{ route('dashboard.institutes.create') }}" class="btn btn-success"
                   title="নতুন প্রতিষ্ঠান যোগ করুন"><i class="fa fa-plus"></i> প্রতিষ্ঠান যোগ</a>
            </div>
        @endif
    </h1>
@stop

@section('content')
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')

        <div class="row">
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table" id="datatable-institutes">
                        <thead>
                        <tr>
                            <th>ক্রমিক</th>
                            <th>নাম</th>
                            <th>উপজেলা</th>
                            <th>ডিভাইস আইডি (SN)</th>
                            <th>মোট কর্মরত</th>
                            <th width="17%">কার্যক্রম</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($institutes as $institute)
                            <tr>
                                <td>{{ $institute->serial }}</td>
                                <td>
                                    <a href="{{ route('dashboard.institute.single', $institute->device_id) }}">{{ $institute->name }}</a>
                                </td>
                                <td>{{ $institute->upazilla->upazilla_bangla }}
                                    , {{ $institute->upazilla->district_bangla }}</td>
                                <td>{{ $institute->device_id }}</td>
                                <td>{{ bangla($institute->users->count()) }} জন</td>
                                <td>
                                    <a href="{{ route('dashboard.institute.single', $institute->device_id) }}"
                                       class="btn btn-info btn-sm" title="প্রতিষ্ঠান বৃত্তান্ত দেখুন"><i
                                                class="fa fa-eye"></i></a>
                                    <a href="{{ route('dashboard.institutes.edit', $institute->id) }}"
                                       class="btn btn-success btn-sm" title="প্রতিষ্ঠান সম্পাদনা করুন"><i
                                                class="fa fa-pencil"></i></a>
                                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teo')
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteInstituteModal{{ $institute->id }}" data-backdrop="static" title="প্রতিষ্ঠান ডিলেট করুন">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <!-- Delete Modal -->
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteInstituteModal{{ $institute->id }}" role="dialog">
                                      <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                          <div class="modal-header modal-header-danger">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> প্রতিষ্ঠান ডিলেট</h4>
                                          </div>
                                          <div class="modal-body">
                                            আপনি কি নিশ্চিতভাবে এই প্রতিষ্ঠানটিকে (<b>{{ $institute->name }}</b>) মুছে দিতে চান?<br/>
                                            <small>* সকল শিক্ষক/ অফিস সহকারি এবং তথ্য মুছে যাবে!</small>
                                          </div>
                                          <div class="modal-footer">
                                            {!! Form::model($institute, ['route' => ['dashboard.institutes.delete', $institute->id], 'method' => 'DELETE', 'class' => 'form-default']) !!}
                                                {!! Form::submit('ডিলেট', array('class' => 'btn btn-danger')) !!}
                                                <button type="button" class="btn btn-default" data-dismiss="modal">ফিরে যান</button>
                                            {!! Form::close() !!}
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Delete Modal -->
                                    <!-- Delete Modal -->
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{-- {{ $institutes->links() }} --}}
                    </div>
                </div>

            </div>


            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table" id="datatable-ateos">
                        <thead>
                        <tr>
                            <th>ATEO</th>
                            <th>উপজেলা</th>
                            <th>প্রতিষ্ঠান সংখ্যা</th>
                          <th>কার্যক্রম</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ateos as $ateo)
                            <tr>
                                <td>{{ $ateo->name }}</td>

                                <td>{{ $ateo->upazilla->upazilla_bangla }}
                                    , {{ $ateo->upazilla->district_bangla }}</td>
                                <td>{{ bangla($ateo->institutes()->count()) }} </td>
                                <td>
                                    <a href="{{ route('dashboard.upazillas.ateo', $ateo->unique_key) }}"
                                       class="btn btn-info btn-sm" title="প্রতিষ্ঠান বৃত্তান্ত দেখুন"><i
                                                class="fa fa-eye"></i> বিস্তারিত</a>
                                    <a href="{{ route('dashboard.users.edit', $ateo->id) }}"
                                       class="btn btn-success btn-sm" title="প্রতিষ্ঠান সম্পাদনা করুন"><i
                                                class="fa fa-pencil"></i> সম্পাদনা</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{-- {{ $institutes->links() }} --}}
                    </div>
                </div>

            </div>
        </div>

    @endif
@stop

@section('js')
    <script type="text/javascript">
        $(function () {
            //$.fn.dataTable.moment('DD MMMM, YYYY hh:mm:ss tt');
            $('#datatable-institutes').DataTable({
                'paging': true,
                'pageLength': 15,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true,
                'order': [[0, "asc"]],
                columnDefs: [
                    // { targets: [5], type: 'date'}
                ]
            });
            $('#datatable-members_wrapper').removeClass('form-inline');
        })
    </script>

{{--    <script type="text/javascript">--}}
{{--      $(function () {--}}
{{--        //$.fn.dataTable.moment('DD MMMM, YYYY hh:mm:ss tt');--}}
{{--        $('#datatable-ateos').DataTable({--}}
{{--          'paging': true,--}}
{{--          'pageLength': 15,--}}
{{--          'lengthChange': true,--}}
{{--          'searching': true,--}}
{{--          'ordering': true,--}}
{{--          'info': true,--}}
{{--          'autoWidth': true,--}}
{{--          'order': [[0, "asc"]],--}}
{{--          columnDefs: [--}}
{{--            // { targets: [5], type: 'date'}--}}
{{--          ]--}}
{{--        });--}}
{{--        $('#datatable-members_wrapper').removeClass('form-inline');--}}
{{--      })--}}
{{--    </script>--}}
@stop