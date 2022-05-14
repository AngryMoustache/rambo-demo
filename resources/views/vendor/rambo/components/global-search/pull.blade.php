<li>
    <a href="{{ $resource->show() }}">
        @if ($resource->item->attachment)
            <img src="{{ $resource->item->attachment->format('thumb') }}">
        @endif

        {{ $resource->itemName() }}
    </a>
</li>
