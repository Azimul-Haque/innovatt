@extends('adminlte::page')

@section('title', 'Loan Accounts | '. $member->name .' | Microfinance Management')

@section('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plain-table.css') }}">
@stop

@section('content_header')
    <h1>
      Loan Accounts [Member: <b>{{ $member->name }}-{{ $member->fhusband }}</b>, Group: <b>{{ $group->name }}</b>, Staff: <b>{{ $staff->name }}</b>]
      <div class="pull-right">
        <a href="{{ route('dashboard.loans.create', [$staff->id, $group->id, $member->id]) }}" class="btn btn-primary" title="Add a New Loan Account"><i class="fa fa-plus"></i> Add Loan Account</a>
      </div>
    </h1>
@stop

@section('content')
  <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-condensed table-bordered">
            <thead>
              <tr>
                <th>Program</th>
                <th>Installment Type</th>
                <th>Disburse Date</th>
                <th>Total Installments</th>
                <th>Disbursed (৳)</th>
                <th>Total Paid (৳)</th>
                <th>Total Outstanding (৳)</th>
                <th>Status</th>
                <th>Closing Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($loans as $loan)
                <tr>
                  <td>
                    <a href="{{ route('dashboard.loans.single', [$staff->id, $group->id, $member->id, $loan->id]) }}">{{ $loan->loanname->name }}</a>
                  </td>
                  <td>{{ installment_type($loan->installment_type) }}</td>
                  <td>{{ date('D, d/m/Y', strtotime($loan->disburse_date)) }}</td>
                  <td>{{ $loan->installments }}</td>
                  <td>{{ $loan->total_disbursed }}</td>
                  <td>{{ $loan->total_paid }}</td>
                  <td>{{ $loan->total_outstanding }}</td>
                  <td>
                    @if($loan->status == 1)
                      <span class="badge badge-warning"><i class="fa fa-hourglass-half"></i> {{ status($loan->status) }}</span>
                    @else
                      <span class="badge badge-success"><i class="fa fa-check"></i> {{ status($loan->status) }}</span>
                    @endif
                  </td>
                  <td>
                    @if($loan->closing_date != '1970-01-01')
                      {{ date('D, d/m/Y', strtotime($loan->closing_date)) }}
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('dashboard.loans.single', [$staff->id, $group->id, $member->id, $loan->id]) }}" class="btn btn-success btn-sm" title="See Loan"><i class="fa fa-pencil"></i> Edit</a>
                    {{-- <button class="btn btn-danger btn-sm" title="Delete Member" disabled><i class="fa fa-trash"></i> Delete</button> --}}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
@stop