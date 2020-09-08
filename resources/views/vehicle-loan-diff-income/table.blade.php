<table class="table table-responsive table-striped table-hover table-all" cellspacing="0">
    <tr>
        <td>SN</td>
        <td>Transaction Date</td>
        <td>Particulars</td>
        <td>Loan Amount</td>
        <td>Paid Amount</td>
        <td>Balance Amount</td>
        <td>Payroll Name</td>
        <td>Remarks</td>
    </tr>
    <tbody>
    <tr>
        <td>{{$i++}}</td>
        <td>{{$house_loan->trans_date ?? ''}}</td>
        <td>Opening Loan Amount of {{$house_loan->staff->name_eng ?? ''}}</td>
        <td>{{$house_loan->loan_amount}}</td>
        <td>-</td>
        <td>{{$house_loan->loan_amount}}</td>
        <td>-</td>
        <td>-</td>
    </tr>
    @foreach($house_loan->houseLoanTransaction as $transaction_detail)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$transaction_detail->trans_date}}</td>
            <td>Paid Installment of Rs {{$transaction_detail->paid_installment_amt}}</td>
            <td>-</td>
            <td>{{$transaction_detail->paid_installment_amt}}</td>
            <td>{{$transaction_detail->remaining_amt}}</td>
            <td>{{$transaction_detail->payroll->payroll_name ?? ''}}</td>
            <td>{{$transaction_detail->detail_note}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3">
            Total
        </td>
        <td>Rs.{{$house_loan->loan_amount}}</td>
        <td>Rs.{{$house_loan->houseLoanTransaction->sum('paid_installment_amt') ?? 0}}</td>
        <td>Rs.{{$house_loan->loan_amount- ($house_loan->houseLoanTransaction->sum('paid_installment_amt') ?? 0)}}</td>
        <td colspan="2"></td>
    </tr>
    </tbody>
</table>
