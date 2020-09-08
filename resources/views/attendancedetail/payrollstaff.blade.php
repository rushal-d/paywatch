@extends('layouts.default', ['crumbroute' => 'payroll'])
@section('title', $title)

@section('content')

    <div class="card">
        <div class="search-box">
            {{ Form::open(array('route' => 'listPayrollStaffofBranch', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">

                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::label('branch_id', 'Branch') }}
                    {{ Form::select('branch_id', $branches, request('branch_id'), array('id' => 'branch_id', 'placeholder' => 'Select a branch')) }}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::label('from_date_np', 'Date From') }}
                    {{ Form::text('from_date_np', request('from_date_np'), array('class' => 'form-control nep-date','id'=>'nep-date1' , 'placeholder' => 'Please enter starting Date', 'readonly'
                            ))  }}
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    {{ Form::label('to_date_np', 'Date To') }}
                    {{ Form::text('to_date_np', request('to_date_np'), array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Please enter Ending Date', 'readonly'
                                   ))  }}
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <button class="btn btn-outline-success btn-reset" type="submit"> Apply</button>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

   <div class="card">
       <div class="card-body">
           <table class="table table-bordered">
               <thead>
               <th>SN</th>
               <th>Staff Name</th>
               <th>Staff Central ID</th>
               <th>Branch ID</th>
               </thead>
               <tbody>
               @foreach($staffs as $staff)
                   <tr>
                       <td>{{$loop->iteration}}</td>
                       <td>{{$staff->name_eng}}</td>
                       <td>{{$staff->staff_central_id}}</td>
                       <td>{{$staff->main_id}}</td>
                   </tr>
               @endforeach
               </tbody>
           </table>
       </div>
   </div>
@endsection


@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,// Options | Number of years to show
        });
    </script>
@endsection
