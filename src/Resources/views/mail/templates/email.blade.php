<x-mail::message>
# {{$subject}}

Dear {{$firstname}}

{{$body}}
 
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>