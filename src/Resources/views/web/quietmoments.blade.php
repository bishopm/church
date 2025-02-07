<x-church::layouts.web pageName="Groups">
    <h1>Quiet moments</h1>
    <table class="table-responsive table-sm mt-2 mb-2">
        @forelse ($scs as $sc)
            <tr>
                <td colspan="2"><a target="_blank" href="{{url('/')}}/storage/{{$sc->filename}}">{{$sc->document}}</a></td>
            </tr>
        @empty
            No editions of Quiet Moments have been added yet
        @endforelse
        </table>
        <small>{{$scs->links()}}</small>
</x-church::layout>                
