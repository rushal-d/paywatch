<form action="{{route('staff-cit-deduction-save')}}" id="staff-cit-deduction-create-form" method="POST">
    <div class="basic-info card">
        <div class="level">
            <h5 class="card-header flex">CIT Deduction</h5>
        </div>
        <p class="text-center"><b>Fiscal Year:</b> {{$fiscalYear->fiscal_code}}</p>
        <p class="text-center"><b>Month Name:</b> {{$monthName}}</p>
        <p class="text-center"><b>Branch Name:</b> {{$branch->office_name}}</p>

        <input type="hidden" name="fiscal_year_id" value="{{$fiscal_year_id}}">
        <input type="hidden" name="month_id" value="{{$month_id}}">
        <input type="hidden" name="branch_id" value="{{$branch->office_id}}">
        {{csrf_field()}}

        <div class="table-responsive">
            <table class="table table-responsive" id="input-form-staff">
                <thead>
                <tr>
                    <th scope="col">Staff Name</th>
                    <th scope="col">Staff Central ID</th>
                    <th scope="col">Branch ID</th>
                    <th scope="col">Branch Name</th>
                    <th scope="col">Amount</th>
                </tr>
                </thead>
                <tbody>

                @foreach($previousStaffCitDeductions as $previousStaffCitDeduction)
                    <tr>
                        <td>{{$previousStaffCitDeduction->staff->name_eng ?? null}}</td>
                        <td>{{$previousStaffCitDeduction->staff->staff_central_id ?? null}}</td>
                        <td>{{$previousStaffCitDeduction->staff->main_id ?? null}}</td>
                        <td>{{$previousStaffCitDeduction->branch->office_name ?? null}}</td>
                        <td>
                            <input type="number"
                                   name="previousStaffCitDeductionRecord[{{$previousStaffCitDeduction->id}}][cit_deduction_amount]"
                                   value="{{$previousStaffCitDeduction->cit_deduction_amount}}"
                                   class="positive-integer-number" step=0.01 required>
                        </td>
                    </tr>
                @endforeach

                @foreach($nonExistingStaffs as $nonExistingStaff)
                    <tr>
                        <td>{{$nonExistingStaff->name_eng ?? null}}</td>
                        <td>{{$nonExistingStaff->staff_central_id ?? null}}</td>
                        <td>{{$nonExistingStaff->main_id ?? null}}</td>
                        <td>{{$nonExistingStaff->branch->office_name ?? null}}</td>
                        <td>
                            <input type="number"
                                   name="nonExistingStaffs[{{$nonExistingStaff->id}}][cit_deduction_amount]"
                                   value="{{$nonExistingStaff->default_cit_deduction_amount}}"
                                   class="positive-integer-number" step=0.01 required>
                        </td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="text-right form-control">
                {{ Form::submit('Save',array('id' => 'submit-form bulk-force-submit-button', 'class'=>'btn btn-success btn-lg'))}}
            </div>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
