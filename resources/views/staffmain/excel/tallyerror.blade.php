@extends('layouts.default', ['crumbroute' => 'staff-excel-tally'])
@section('title', $title)
@section('content')
    <div class="conatiner">
        <div class="card">
            <table class="table table-bordered">
                <thead>
                <th>Staff Central ID</th>
                <th>Branch ID</th>
                <th>Name</th>
                <th>Current Record</th>
                <th>Excel Record</th>
                <th>Error</th>
                </thead>
                <tbody>
                @foreach($list_errors as $error)
                    <tr>
                        <td>

                            {{$error['staff_central_id']}}

                        </td>
                        <td>
                            @if(!empty($error['id']))
                                <a href="{{route('staff-main-edit',$error['id'])}}" target="_blank">
                                    {{$error['branch_id']}}
                                </a>
                            @else
                                {{$error['branch_id']}}
                            @endif
                        </td>

                        <td>

                            @if(!empty($error['id']))
                                <a href="{{route('staff-main-edit',$error['id'])}}" target="_blank">
                                    {{$error['name']}}
                                </a>
                            @else
                                {{$error['name']}}
                            @endif
                        </td>
                        <td>{{$error['current_record']}}</td>
                        <td>{{$error['correct_record']}}</td>
                        <td>{{$error['error']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
