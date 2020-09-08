<div class="basic-info card">
    <div class="level">
        <h5 class="card-header flex">Loan Deduct</h5>
    </div>
    <p class="text-center"><b>Fiscal Year:</b> {{$fiscalYear}}</p>
    <p class="text-center"><b>Month Name:</b> {{$monthName}}</p>
    <form action="{{route('loan-deduct-save')}}" id="loan-deduct-create-form" method="POST">
        <input type="hidden" name="fiscal_year_id" value="{{$fiscal_year_id}}">
        <input type="hidden" name="month_id" value="{{$month_id}}">
        {{csrf_field()}}

        <div class="table-responsive">
            <table class="table table-responsive" id="input-form-staff">
                <thead>
                <tr>
                    <th scope="col">Loan Type</th>
                    <th scope="col">Staff Central ID</th>
                    <th scope="col">Branch ID</th>
                    <th scope="col">Staff Name</th>
                    <th scope="col">Branch Name</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Previous Remark</th>
                    <th scope="col">Remark</th>
                </tr>
                </thead>
                <tbody>

                @foreach($previousHouseLoanDeductRecords as $previousHouseLoanDeductRecord)
                    <tr>
                        <th>House Loan</th>
                        <td>{{$previousHouseLoanDeductRecord->houseLoan->staff->staff_central_id ?? null}}</td>
                        <td>{{$previousHouseLoanDeductRecord->houseLoan->staff->main_id ?? null}}</td>
                        <td>{{$previousHouseLoanDeductRecord->houseLoan->staff->name_eng ?? null}}</td>
                        <td>{{$previousHouseLoanDeductRecord->houseLoan->staff->branch->office_name ?? null}}</td>
                        <td>
                            <input type="number"
                                   name="existingHouseLoanDeducts[{{$previousHouseLoanDeductRecord->id}}][loan_deduct_amount]"
                                   value="{{$previousHouseLoanDeductRecord->loan_deduct_amount}}"
                                   class="positive-integer-number" step=0.01 required>
                        </td>
                        <td>
                            <p>{!! $previousHouseLoanDeductRecord->remarks !!}</p>
                        </td>
                        <td><input type="text"
                                   name="existingHouseLoanDeducts[{{$previousHouseLoanDeductRecord->id}}][remarks]"
                                   class="remarks"></td>
                        <input type="hidden"
                               name="existingHouseLoanDeducts[{{$previousHouseLoanDeductRecord->id}}][loan_id]"
                               value="{{$previousHouseLoanDeductRecord->loan_id}}">
                        <input type="hidden"
                               name="existingHouseLoanDeducts[{{$previousHouseLoanDeductRecord->id}}][staff_central_id]"
                               value="{{$previousHouseLoanDeductRecord->houseLoan->staff->id ?? null}}">
                    </tr>
                @endforeach

                @foreach($nonExistingHouseLoanDeductRecords as $nonExistingHouseLoanDeductRecord)
                    <tr>
                        <th>House Loan</th>
                        <td>{{$nonExistingHouseLoanDeductRecord->staff->staff_central_id ?? null}}</td>
                        <td>{{$nonExistingHouseLoanDeductRecord->staff->main_id ?? null}}</td>
                        <td>{{$nonExistingHouseLoanDeductRecord->staff->name_eng ?? null}}</td>
                        <td>{{$nonExistingHouseLoanDeductRecord->staff->branch->office_name ?? null}}</td>
                        <td>
                            <input type="number"
                                   name="nonExistingHouseLoanDeducts[{{$nonExistingHouseLoanDeductRecord->house_id}}][loan_deduct_amount]"
                                   class="positive-integer-number" step=0.01
                                   value="{{$nonExistingHouseLoanDeductRecord->installment_amount ?? 0}}" required>
                        </td>
                        <td></td>
                        <td>
                            <input type="text"
                                   name="nonExistingHouseLoanDeducts[{{$nonExistingHouseLoanDeductRecord->house_id}}][remarks]"
                                   class="remarks">
                        </td>
                        <input type="hidden"
                               name="nonExistingHouseLoanDeducts[{{$nonExistingHouseLoanDeductRecord->house_id}}][staff_central_id]" value="{{$nonExistingHouseLoanDeductRecord->staff->id ?? null}}">
                    </tr>
                @endforeach
                {{--  End of house loan --}}

                @foreach($previousVehicleLoanDeductRecords as $previousVehicleLoanDeductRecord)
                    <tr>
                        <th>Vehicle Loan</th>
                        <td>{{$previousVehicleLoanDeductRecord->vehicleLoan->staff->staff_central_id ?? null}}</td>
                        <td>{{$previousVehicleLoanDeductRecord->vehicleLoan->staff->main_id ?? null}}</td>
                        <td>{{$previousVehicleLoanDeductRecord->vehicleLoan->staff->name_eng ?? null}}</td>
                        <td>{{$previousVehicleLoanDeductRecord->vehicleLoan->staff->branch->office_name ?? null}}</td>
                        <td>
                            <input type="number"
                                   name="existingVehicleLoanDeducts[{{$previousVehicleLoanDeductRecord->id}}][loan_deduct_amount]"
                                   value="{{$previousVehicleLoanDeductRecord->loan_deduct_amount}}"
                                   step=0.01
                                   class="positive-integer-number" required>
                        </td>
                        <td>
                            <p>{!! $previousVehicleLoanDeductRecord->remarks !!}</p>
                        </td>
                        <td><input type="text"
                                   name="existingVehicleLoanDeducts[{{$previousVehicleLoanDeductRecord->id}}][remarks]"
                                   class="remarks"></td>

                        <input type="hidden"
                               name="existingVehicleLoanDeducts[{{$previousVehicleLoanDeductRecord->id}}][loan_id]"
                               value="{{$previousVehicleLoanDeductRecord->loan_id}}">

                        <input type="hidden"
                               name="existingVehicleLoanDeducts[{{$previousVehicleLoanDeductRecord->id}}][staff_central_id]"
                               value="{{$previousVehicleLoanDeductRecord->vehicleLoan->staff->id}}">
                    </tr>
                @endforeach

                @foreach($nonExistingVehicleLoanDeductRecords as $nonExistingVehicleLoanDeductRecord)
                    <tr>
                        <th>Vehicle Loan</th>
                        <td>{{$nonExistingVehicleLoanDeductRecord->staff->staff_central_id ?? null}}</td>
                        <td>{{$nonExistingVehicleLoanDeductRecord->staff->main_id ?? null}}</td>
                        <td>{{$nonExistingVehicleLoanDeductRecord->staff->name_eng ?? null}}</td>
                        <td>{{$nonExistingVehicleLoanDeductRecord->staff->branch->office_name ?? null}}</td>

                        <td>
                            <input type="number"
                                   name="nonExistingVehicleLoanDeducts[{{$nonExistingVehicleLoanDeductRecord->vehical_id}}][loan_deduct_amount]"
                                   class="positive-integer-number" step=0.01
                                   value="{{$nonExistingVehicleLoanDeductRecord->installment_amount ?? 0}}" required>
                        </td>
                        <td></td>
                        <td>
                            <input type="text"
                                   name="nonExistingVehicleLoanDeducts[{{$nonExistingVehicleLoanDeductRecord->vehical_id}}][remarks]"
                                   class="remarks">
                            <input type="hidden"
                                   name="nonExistingVehicleLoanDeducts[{{$nonExistingVehicleLoanDeductRecord->vehical_id}}][staff_central_id]"
                                   value="{{$nonExistingVehicleLoanDeductRecord->staff->id}}">
                        </td>
                    </tr>
                @endforeach
                {{--  End of vehicle loan --}}

                </tbody>
            </table>
        </div>
        <div class="background-color-brown text-center">
            <button class="btn btn-success" id="bulk-force-submit-button">Submit</button>
        </div>
    </form>
</div>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
