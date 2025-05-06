@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => $url])
<p style="text-align: center;"><img src="{{setting('website.logo_url')}}" class="logo" alt="{{ setting('general.church_abbreviation') }}"></p>
<p style="text-align: center;">{{setting('general.church_name')}}</p>
@endcomponent
@endslot

{{-- Body --}}
# {{$subject}}

Dear {{$firstname}}

{{$body}}

Thanks,<br>
{{ $sender }}<br>
{{ setting('general.church_abbreviation') }}

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
{{setting('general.physical_address')}}<br>
{{setting('website.church_telephone')}}
@endcomponent
@endslot
@endcomponent