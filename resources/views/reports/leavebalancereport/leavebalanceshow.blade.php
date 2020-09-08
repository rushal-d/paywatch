@extends('layouts.default', ['crumbroute' => 'leavebalancestatementshow'])
@section('title', $title)
@section('style')
    <style>
        @media print {
            .printable {
                padding: 20px !important;
            }
        }
    </style>
@endsection
@section('content')

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <span>{{ $title }}</span>
        </div>
        <div class="card-block">
            <div class="float-right">
                <input type="button" onclick="printDiv('printableArea')" value="Print"
                       class="btn btn-primary btn-sm" id="print">
                @php $_GET['export']=1; @endphp
                <a href="{{route('leavebalancestatementshow',$_GET)}}" class="btn btn-sm btn-danger">Excel Export</a>
            </div>
            <div class="printable" id="printableArea">
                <div class="text-center col-md-12">
                    <h5>Leave Balance Details</h5>
                </div>
                @include('reports.leavebalancereport.table')
            </div>

        </div>
    </div>

@endsection

