<x-church::layouts.app pageName="Pastoral care">
    <div>
        @livewire('pastoralnote',[
            'pastoralnotable_type'=>$pastoralnotable_type,
            'pastoralnotable_id'=>$case->id,
            'pastor'=>$pastor,
            'pastoraldate'=>$pastoraldate,
            'case'=>$case,
            'mostrecent'=>$mostrecent,
            'detail'=>$detail,
            'name'=>$name
        ])
    </div>
</x-church::layout>                
