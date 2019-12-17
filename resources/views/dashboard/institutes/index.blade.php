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
                            <th>শিক্ষক সংখ্যা</th>
                            <th>কার্যক্রম</th>
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
                                                class="fa fa-eye"></i> বিস্তারিত</a>
                                    <a href="{{ route('dashboard.institutes.edit', $institute->id) }}"
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