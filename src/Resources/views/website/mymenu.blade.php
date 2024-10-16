<x-church::website.layout pageName="People">
    <h1>{{$member['fullname']}} </h1> 
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-serve-tab" data-bs-toggle="pill" data-bs-target="#pills-serve" type="button" role="tab" aria-controls="pills-serve" aria-selected="true">
                <i class="bi bi-hourglass-split"> </i>Serve
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-connect-tab" data-bs-toggle="pill" data-bs-target="#pills-connect" type="button" role="tab" aria-controls="pills-connect" aria-selected="false">
                <i class="bi bi-people"> </i>Connect
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-give-tab" data-bs-toggle="pill" data-bs-target="#pills-give" type="button" role="tab" aria-controls="pills-give" aria-selected="false">
                <i class="bi bi-wallet2"> </i>Give
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-worship-tab" data-bs-toggle="pill" data-bs-target="#pills-worship" type="button" role="tab" aria-controls="pills-worship" aria-selected="false">
                <i class="bi bi-person-arms-up"> </i>Worship
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-learn-tab" data-bs-toggle="pill" data-bs-target="#pills-learn" type="button" role="tab" aria-controls="pills-learn" aria-selected="false">
                <i class="bi bi-book"> </i>Learn
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                <i class="bi bi-person-square"> </i>Contact details
            </button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-serve" role="tabpanel" aria-labelledby="pills-serve-tab">
            <div class="card p-3">
                @if(count($servicegroups))
                    You are a member of the following service teams:
                    <ul>
                        @foreach ($servicegroups as $sgroup)
                            <li>{{$sgroup->groupname}}</li>
                        @endforeach
                    </ul>
                    <h4>Upcoming roster dates</h4>
                    @forelse ($roster as $kk=>$rosterdate)
                        <div><b>{{$kk}}&nbsp;</b>{{implode(', ',$rosterdate)}}</div>
                    @empty
                        You have no upcoming roster duties
                    @endforelse
                @else
                    To join a service team, click here
                @endif
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-connect" role="tabpanel" aria-labelledby="pills-connect-tab">
            <div class="card p-3">
                @if(count($fellowship))
                    These are the fellowship groups that you belong to:
                    <ul>
                        @foreach ($fellowship as $fgroup)
                            <li>{{$fgroup->groupname}}</li>
                        @endforeach
                    </ul>
                @else
                    To join a home group, click here
                @endif
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-give" role="tabpanel" aria-labelledby="pills-give-tab">
            <div class="card p-3">
                @if($indiv->giving)
                    Giving records for PG {{$indiv->giving}} for the last 12 months:
                    <table class="table">
                        <tr><th>Date</th><th>Amount</th></tr>
                        @foreach ($giving as $gift)
                            <tr><td>{{$gift->paymentdate}}</td><td>R {{number_format($gift->amount,0)}}</td></tr>
                        @endforeach
                    </table>
                @else
                    To sign up as a planned giver, click here
                @endif
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
        <div class="tab-pane fade show" id="pills-learn" role="tabpanel" aria-labelledby="pills-learn-tab">
            <div class="card p-3">
                <b>Books currently on loan:</b>
                @foreach ($loans as $loan)
                    <div>{{$loan->book->title}} ({{implode(',',$loan->book->authors[0])}})</div>
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade show" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div class="card p-3">
                <b>Contact details: {{$indiv->fullname}}</b>
                <div class="row">
                    <div class="col-md-6">
                        <div>{{$indiv->household->addressee}}</div>
                        <div>{{$indiv->cellphone}}</div>
                        <div>{{$indiv->email}}</div>
                    </div>
                    <div class="col-md-6">
                        <div>{{$indiv->household->address1}}</div>
                        <div>{{$indiv->household->address2}}</div>
                        <div>{{$indiv->household->address3}}</div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</x-church::layout>                
