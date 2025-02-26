{!!$data['emailbody']!!}
<h3>Last {{$data['scope']}}</h3>
@if (isset($data['current']))
    <table>
        <tr>
            <th>Date received</th><th>Amount</th>
        </tr>
        @foreach ($data['current'] as $payment)
            <tr>
                <td>{{$payment['paymentdate']}}</td>
                <td>{{$payment['amount']}}</td>
            </tr>
        @endforeach
    </table>
@else
    No payments received during this period
@endif

@if (isset($data['historic']))
    <h3>Other {{$data['pgyr']}} payments (prior to the last {{$data['scope']}})</h3>

    <table>
        <tr>
            <th>Date received</th><th>Amount</th>
        </tr>
        @foreach ($data['historic'] as $hpayment)
            <tr>
                <td>{{$hpayment['paymentdate']}}</td>
                <td>{{$hpayment['amount']}}</td>
            </tr>
        @endforeach
    </table>
@endif



{!!$data['emailending']!!}