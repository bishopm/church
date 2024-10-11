<x-church::website.layout pageName="People">
    <h1>{{$member['fullname']}}</h1>         
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-serve-tab" data-bs-toggle="pill" data-bs-target="#pills-serve" type="button" role="tab" aria-controls="pills-serve" aria-selected="true">Serve</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-connect-tab" data-bs-toggle="pill" data-bs-target="#pills-connect" type="button" role="tab" aria-controls="pills-connect" aria-selected="false">Connect</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-give-tab" data-bs-toggle="pill" data-bs-target="#pills-give" type="button" role="tab" aria-controls="pills-give" aria-selected="false">Give</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-worship-tab" data-bs-toggle="pill" data-bs-target="#pills-worship" type="button" role="tab" aria-controls="pills-worship" aria-selected="false">Worship</button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-serve" role="tabpanel" aria-labelledby="pills-serve-tab">
            <div class="card p-3">
                These are the service teams that you belong to:
                <ul>
                    @foreach ($servicegroups as $sgroup)
                        <li>{{$sgroup->groupname}}</li>
                    @endforeach
                </ul>
                <h4>Upcoming roster dates</h4>
                @foreach ($roster as $kk=>$rosterdate)
                    <div><b>{{$kk}}&nbsp;</b>{{implode(', ',$rosterdate)}}</div>
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-connect" role="tabpanel" aria-labelledby="pills-connect-tab">
            <div class="card p-3">
                These are the fellowship groups that you belong to:
                <ul>
                    @foreach ($fellowship as $fgroup)
                        <li>{{$fgroup->groupname}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-give" role="tabpanel" aria-labelledby="pills-give-tab">
            <div class="card p-3">
                Giving records for PG {{$indiv->giving}} for the last 12 months:
                <table class="table">
                    <tr><th>Date</th><th>Amount</th></tr>
                    @foreach ($giving as $gift)
                        <tr><td>{{$gift->paymentdate}}</td><td>R {{number_format($gift->amount,0)}}</td></tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-worship" role="tabpanel" aria-labelledby="pills-worship-tab">
            <div class="card p-3">
                Based on the use of your nametag, you have participated in services as follows during the last year:
                @foreach ($worship as $service=>$stat)
                    <div><b>{{$service}}  </b>{{$stat}}</div>
                @endforeach
            </div>
        </div>
    </div>
</x-church::layout>                
