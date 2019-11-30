@extends('adminlte::page')

@section('title', 'Group Transaction | Microfinance Management')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plain-table.css') }}">
@stop

@section('content_header')
    <h1>Group Transaction [Staff: <b>{{ $staff->name }}</b>, Group: <b>{{ $group->name }}</b>]</h1>
@stop

@section('content')
    <div class="row">
      <div class="col-md-2">
        <select class="form-control" name="group_to_load" id="group_to_load" required="">
          <option value="" selected="" disabled="">Select Group</option>
          @if(Auth::user()->role == 'admin')
            @foreach($groups as $groupforselect)
              <option value="{{ $groupforselect->id }}" @if($group->id == $groupforselect->id) selected="" @endif>{{ $groupforselect->name }}</option>
            @endforeach
          @else
            @foreach($staff->groups as $groupforselect)
              <option value="{{ $groupforselect->id }}" @if($group->id == $groupforselect->id) selected="" @endif>{{ $groupforselect->name }}</option>
            @endforeach
          @endif
        </select><br/>
      </div>
      <div class="col-md-2">
        <select class="form-control" name="loan_type_to_load" id="loan_type_to_load" required="">
          <option value="" selected="" disabled="">Select Loan Type</option>
          @foreach($loannames as $loanname)
            <option value="{{ $loanname->id }}" @if(!empty($loantype) && ($loantype == $loanname->id)) selected="" @endif>{{ $loanname->name }}</option>
          @endforeach
        </select><br/>
      </div>
      <div class="col-md-2">
        <input class="form-control" type="text" name="date_to_load" id="date_to_load" @if(!empty($transactiondate)) value="{{ date('F d, Y', strtotime($transactiondate)) }}" @endif placeholder="Select Date" readonly=""><br/>
      </div>
      <div class="col-md-3">
        <button class="btn btn-success" id="loadTransactions"><i class="fa fa-users"></i> Load</button><br/>
      </div>
      <div class="col-md-3">
        <a href="{{ url()->current() }}" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save</a><br/>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-hover table-condensed table-bordered table-striped " id="editable">
            <thead>
              <tr>
                <th>P#</th>
                <th>Member Name</th>
                <th>Loan Program</th>
                <th>Loan <br/>Installment</th>
                @if(!empty($loantype) && $loantype == 1) {{-- if primary then show savings only --}}
                  <th>General Savings<br/> Deposit</th>
                  <th>Long Term<br/> Savings</th>
                @endif
                
                <th>Total Collection</th>
                
                @if(!empty($loantype) && $loantype == 1) {{-- if primary then show savings only --}}
                  <th>General Savings <br/>Withdraw</th>
                  <th>Long Term <br/>Savings Withdraw</th>
                @endif
                <th>Net <br/>Collection</th>
              </tr>
            </thead>
            <tbody>
              @foreach($members as $member)
                @foreach($member->loans as $loan)
                  @foreach($loan->loaninstallments as $loaninstallment)
                    @if(!empty($transactiondate))
                    <tr>
                      <td readonly>{{ $member->passbook }}</td>
                      <td id="membername{{ $loaninstallment->id }}" readonly>{{ $member->name }}</td>
                      <td readonly>{{ $loan->loanname->name }} (Scheme: {{ $loan->schemename->name }})</td>
                      <td id="loaninstallment{{ $loaninstallment->id }}" onchange="loancalcandpost({{ $member->id }}, {{ $loaninstallment->id }}, '{{ $transactiondate }}', {{ $loaninstallment->installment_no }})">{{ $loaninstallment->paid_total }}</td>

                      @if(!empty($loantype) && $loantype == 1) {{-- if primary then show savings only --}}
                        @php
                          $generalsaving = 0;
                          if(!empty($member->savinginstallments->where('savingname_id', 1)->where('due_date', $transactiondate)->first())) {
                            $generalsaving = $member->savinginstallments->where('member_id', $member->id)->where('savingname_id', 1)->where('due_date', $transactiondate)->first()->amount;
                          }
                        @endphp
                        <td id="generalsaving{{ $loaninstallment->id }}" onchange="loancalcandpost({{ $member->id }}, {{ $loaninstallment->id }}, '{{ $transactiondate }}', {{ $loaninstallment->installment_no }})">{{ $generalsaving }}</td>
                        @php
                          $longsaving = 0;
                          if(!empty($member->savinginstallments->where('savingname_id', 2)->where('due_date', $transactiondate)->first())) {
                            $longsaving = $member->savinginstallments->where('member_id', $member->id)->where('savingname_id', 2)->where('due_date', $transactiondate)->first()->amount;
                          }
                        @endphp
                        @if(!empty($member->savings->where('savingname_id', 2)->first()))
                        <td id="longsaving{{ $loaninstallment->id }}" onchange="loancalcandpost({{ $member->id }}, {{ $loaninstallment->id }}, '{{ $transactiondate }}', {{ $loaninstallment->installment_no }})">{{ $longsaving }}</td>
                        @else
                        <td readonly>N/A</td>
                        @endif

                        <td id="totalcollection{{ $loaninstallment->id }}" readonly>{{ $loaninstallment->paid_total + $generalsaving + $longsaving }}</td>

                        @php
                          $generalsavingwd = 0;
                          if(!empty($member->savinginstallments->where('savingname_id', 1)->where('due_date', $transactiondate)->first())) {
                            $generalsavingwd = $member->savinginstallments->where('member_id', $member->id)->where('savingname_id', 1)->where('due_date', $transactiondate)->first()->withdraw;
                          }
                        @endphp
                        <td id="generalsavingwd{{ $loaninstallment->id }}" onchange="loancalcandpost({{ $member->id }}, {{ $loaninstallment->id }}, '{{ $transactiondate }}', {{ $loaninstallment->installment_no }})">{{ $generalsavingwd }}</td>
                        @php
                          $longsavingwd = 0;
                          if(!empty($member->savinginstallments->where('savingname_id', 2)->where('due_date', $transactiondate)->first())) {
                            $longsavingwd = $member->savinginstallments->where('member_id', $member->id)->where('savingname_id', 2)->where('due_date', $transactiondate)->first()->withdraw;
                          }
                        @endphp
                        
                        @if(!empty($member->savings->where('savingname_id', 2)->first()))
                        <td id="longsavingwd{{ $loaninstallment->id }}" onchange="loancalcandpost({{ $member->id }}, {{ $loaninstallment->id }}, '{{ $transactiondate }}', {{ $loaninstallment->installment_no }})">{{ $longsavingwd }}</td>
                        @else
                        <td readonly>N/A</td>
                        @endif
                        <td id="netcollection{{ $loaninstallment->id }}" readonly>{{ $loaninstallment->paid_total + $generalsaving + $longsaving - $generalsavingwd - $longsavingwd }}</td>
                      @else
                        <td id="totalcollection{{ $loaninstallment->id }}" readonly>{{ $loaninstallment->paid_total }}</td>
                        <td id="netcollection{{ $loaninstallment->id }}" readonly>{{ $loaninstallment->paid_total }}</td>
                      @endif
                      
                      
                    </tr>
                    @endif
                  @endforeach
                @endforeach
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
@stop

@section('js')
  <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
  {{-- <script type="text/javascript" src="{{ asset('js/dateformat.js') }}"></script> --}}
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script type="text/javascript">
    $(function() {
      $("#date_to_load").datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true,
      });
    });

    $('#loadTransactions').click(function() {
      var group_to_load =$('#group_to_load').val();
      var date_to_load =$('#date_to_load').val();
      var loan_type_to_load =$('#loan_type_to_load').val();

      if(isEmptyOrSpaces(loan_type_to_load)) {
        if($(window).width() > 768) {
          toastr.warning('Select Loan Type!', 'WARNING').css('width', '400px');
        } else {
          toastr.warning('Select Loan Type!', 'WARNING').css('width', ($(window).width()-25)+'px');
        }
      } else if(isEmptyOrSpaces(date_to_load)) {
        if($(window).width() > 768) {
          toastr.warning('Select Date!', 'WARNING').css('width', '400px');
        } else {
          toastr.warning('Select Date!', 'WARNING').css('width', ($(window).width()-25)+'px');
        }
      } else {
        window.location.href = '/group/{{ $staff->id }}/'+ group_to_load +'/transactions/' + loan_type_to_load + '/'+ moment(date_to_load).format('YYYY-MM-DD');
      }
    })

    // on enter search
    function isEmptyOrSpaces(str){
        return str === null || str.match(/^ *$/) !== null;
    }
  </script>

  <script src="{{ asset('js/mindmup-editabletable.js') }}"></script>
  <!-- <script src="http://mindmup.github.io/editable-table/numeric-input-example.js"></script> -->
  <script>
    $(document).ready(function () {
      $('#editable').editableTableWidget();
      
      $('#editable td.uneditable').on('change', function(evt, newValue) {
        console.log('false clicked!');
        return false;
      });
    });
    $('#editable td').on('change', function(evt, newValue) {
      // toastr.success(newValue + ' Added!', 'SUCCESS').css('width', '400px');
    });

    function loancalcandpost(member_id, loaninstallment_id, transactiondate, installment_no) {
      var membername = $('#membername' + loaninstallment_id).text();
      var loaninstallment = parseInt($('#loaninstallment' + loaninstallment_id).text()) ? parseInt($('#loaninstallment' + loaninstallment_id).text()) : 0;
      var generalsaving = parseInt($('#generalsaving' + loaninstallment_id).text()) ? parseInt($('#generalsaving' + loaninstallment_id).text()) : 0;
      var longsaving = parseInt($('#longsaving' + loaninstallment_id).text()) ? parseInt($('#longsaving' + loaninstallment_id).text()) : 0;
      var generalsavingwd = parseInt($('#generalsavingwd' + loaninstallment_id).text()) ? parseInt($('#generalsavingwd' + loaninstallment_id).text()) : 0;
      var longsavingwd = parseInt($('#longsavingwd' + loaninstallment_id).text()) ? parseInt($('#longsavingwd' + loaninstallment_id).text()) : 0;
      
      var totalcollection = loaninstallment + generalsaving + longsaving;
      var netcollection = totalcollection - generalsavingwd - longsavingwd;
      $('#totalcollection' + loaninstallment_id).text(totalcollection);
      $('#netcollection' + loaninstallment_id).text(netcollection);

      // now post the data
      $.post("/group/transaction/store/api", {_token: '{{ csrf_token() }}', _method : 'POST', 
        data: {
          member_id: member_id,
          loaninstallment_id: loaninstallment_id,
          transactiondate: transactiondate,
          installment_no: installment_no,

          loaninstallment: loaninstallment,

          generalsaving: generalsaving,
          longsaving: longsaving,
          generalsavingwd: generalsavingwd,
          longsavingwd: longsavingwd
        }},
        function(data, status){
        console.log(status);
        console.log(data);
        if(status == 'success') {
          toastr.success('Member: <b>' + membername + '</b><br/>Total Collection: <u>৳ ' + totalcollection + '</u>, Net Collection: <u>৳ ' + netcollection , '</u>SUCCESS').css('width', '400px');
        } else {
          toastr.warning('Error!').css('width', '400px');
        }
        
      });
      console.log(totalcollection);
      console.log(member_id);
      
    }

    $('td[readonly]').on('click dblclick keydown', function(e) {
      e.preventDefault();
      e.stopPropagation();
    });

  </script>
@endsection