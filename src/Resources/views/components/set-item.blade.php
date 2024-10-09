@php
    use Filament\Forms\Components\Actions\Action;

    $containers = $getChildComponentContainers();
    $addAction = $getAction($getAddActionName());
    $deleteAction = $getAction($getDeleteActionName());
    $reorderAction = $getAction($getReorderActionName());
    $extraItemActions = $getExtraItemActions();
    $isAddable = $isAddable();
    $isDeletable = $isDeletable();
    $isReorderableWithButtons = $isReorderableWithButtons();
    $isReorderableWithDragAndDrop = $isReorderableWithDragAndDrop();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="flex justify-center">
        {{ $addAction }}
    </div>
    <div
        x-data="{}"
        {{
            $attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class(['fi-fo-repeater grid gap-y-4'])
        }}
    >
        @if (count($containers))
            <ul>
                <x-filament::grid
                    :default="$getGridColumns('default')"
                    :sm="$getGridColumns('sm')"
                    :md="$getGridColumns('md')"
                    :lg="$getGridColumns('lg')"
                    :xl="$getGridColumns('xl')"
                    :two-xl="$getGridColumns('2xl')"
                    :wire:end.stop="'mountFormComponentAction(\'' . $statePath . '\', \'reorder\', { items: $event.target.sortable.toArray() })'"
                    x-sortable
                    :data-sortable-animation-duration="$getReorderAnimationDuration()"
                    class="items-start gap-4"
                >
                    @foreach ($containers as $uuid => $item)
                        @php
                            $itemLabel = $getItemLabel($uuid);
                            $visibleExtraItemActions = array_filter(
                                $extraItemActions,
                                fn (Action $action): bool => $action(['item' => $uuid])->isVisible(),
                            );
                            $deleteAction = $deleteAction(['item' => $uuid]);
                            $deleteActionIsVisible = $isDeletable && $deleteAction->isVisible();
                            $reorderActionIsVisible = $isReorderableWithDragAndDrop && $reorderAction->isVisible();
                        @endphp
                        <li
                            wire:key="{{ $this->getId() }}.{{ $item->getStatePath() }}.{{ $field::class }}.item"
                            x-sortable-item="{{ $uuid }}"
                            class="fi-fo-repeater-item divide-y divide-gray-100 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-white/5 dark:ring-white/10"
                        >
                            @if ($reorderActionIsVisible || filled($itemLabel) || $deleteActionIsVisible || $visibleExtraItemActions)
                                <div
                                    @class([
                                        'fi-fo-repeater-item-header flex items-center gap-x-3 overflow-hidden px-4 py-3',
                                    ])
                                >
                                    @if ($reorderActionIsVisible)
                                        <ul class="flex items-center gap-x-3">
                                            @if ($reorderActionIsVisible)
                                                <li
                                                    x-sortable-handle
                                                    x-on:click.stop
                                                >
                                                    {{ $reorderAction }}
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                    @php
                                        if (isset($item->model->setitemable_id)){
                                            $sp = \Bishopm\Church\Models\Setitem::with('setitemable')->where('id',$item->model->id)->first();
                                            print "<b>" . $sp->setitemable->title . "</b> ";
                                            if (isset($item->model->note)){
                                                print " (" . $item->model->note . ")";
                                            }
                                        } else {
                                            if (isset($item->model->note)){
                                                print "<b>" . $item->model->note . "</b> ";
                                            } else {
                                                $newone=end($this->data['setitems']);
                                                print "<b>" . $newone['note'] . "</b> ";
                                            }
                                        }
                                    @endphp

                                    @if ($deleteActionIsVisible || $visibleExtraItemActions)
                                        <ul
                                            class="ms-auto flex items-center gap-x-3"
                                        >
                                            @foreach ($visibleExtraItemActions as $extraItemAction)
                                                <li x-on:click.stop>
                                                    {{ $extraItemAction(['item' => $uuid]) }}
                                                </li>
                                            @endforeach

                                            @if ($deleteActionIsVisible)
                                                <li x-on:click.stop>
                                                    {{ $deleteAction }}
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </div>
                            @endif
                        </li>

                    @endforeach
                </x-filament::grid>
            </ul>
        @endif
    </div>
</x-dynamic-component>
