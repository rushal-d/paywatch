{{--filter start--}}
<div class="mt-3">
    <form method="GET" action="{{route('attendance-detail-search')}}">
        {{ csrf_field() }}
        <div class="card-text">
            <div class="form-group row">
                <div class="col-4">
                    <label for="branch_id" class="col-3 col-form-label">
                        Staff Id
                    </label>
                    <select id="branch_id" name="branch_id" class="input-sm" >
                        <option value="">Select Branch</option>
                        {{--@foreach($branch as $bran)--}}
                        {{--<option value="{{$bran->office_id}}">{{$bran->office_name}}</option>--}}

                        {{--<option @if(\Request::get('branch_id') == $bran->office_id ) selected--}}
                        {{--@endif value="{{$bran->office_id}}">{{$bran->office_name}}</option>--}}

                        {{--@endforeach--}}
                    </select>
                </div>
                <div class="col-4">
                    <label for="branch_id" class="col-3 col-form-label">
                        Date From
                    </label>
                    <select name="" id="">
                        <option value="">Slect Branch</option>
                        <option value="1">option 1</option>
                        <option value="2">option 1</option>
                        <option value="3">option 1</option>
                        <option value="4">option 1</option>
                    </select>
                </div>

                <div class="col-4">
                    <label for="month_id" class="col-form-label">
                        Date To
                    </label>
                    <select id="month_id" name="month_id" class="input-sm" >
                        <option value="">Select Month</option>
                        <option value="1">Baishakh</option>
                        <option value="2">Jestha</option>
                        <option value="3">Asar</option>
                        <option value="4">Shrawan</option>
                        <option value="5">Bhadau</option>
                        <option value="6">Aswin</option>
                        <option value="7">Kartik</option>
                        <option value="8">Mansir</option>
                        <option value="9">Poush</option>
                        <option value="10">Magh</option>
                        <option value="11">Falgun</option>
                        <option value="12">Chaitra</option>
                    </select>
                </div>

            </div>
            <div class="form-group row">

                <div class="col-4">
                    <label for="fiscal_year" class="col-3 col-form-label">
                        Leave Type
                    </label>
                    <select id="fiscal_year" name="fiscal_year" class="input-sm" >
                        <option value="">Select Leave</option>
                        {{--@foreach($fiscalyear as $fiscal)--}}
                        {{--<option value="{{$fiscal->id}}">{{$fiscal->fiscal_code}}</option>--}}
                        {{--<option @if(\Request::get('fiscal_year') == $fiscal->id ) selected--}}
                        {{--@endif value="{{$fiscal->id}}">{{$fiscal->fiscal_code}}</option>--}}
                        {{--@endforeach--}}
                    </select>
                </div>
            </div>
            <div class="form-group row ml-1">
                <div class="col-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <button type="button" class="btn btn-success">Reset</button></div>
            </div>
        </div>
    </form>
</div>
{{--filter end--}}