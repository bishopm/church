<x-church::layouts.app pageName="Pastoral care">
    @livewire('pastoralnote',[
        'pastoralnotable_type'=>$pastoralnotable_type,
        'pastoralnotable_id'=>$case->id,
        'pastor_id'=>$pastor_id,
        'case'=>$case,
        'mostrecent'=>$mostrecent,
        'detail'=>$detail
    ])
    <script>
        function addDetails(e) {
            document.getElementById("details").value = e.target.value + ' ' + '{{$name}}'
        }
    </script>
</x-church::layout>                
