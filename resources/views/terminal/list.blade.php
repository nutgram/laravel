<div class="mx-2 my-1">
    @foreach($items as $item)
        <div class="flex space-x-1">
            <span class="text-yellow w-{{$handlerWidth}}">{{$item['handler']}}</span>
            @if($item['pattern'])
                <span class="text-gray">{{$item['pattern']}}</span>
            @endif
            <span class="flex-1 text-blue content-repeat-['.']"></span>
            <span class="text-blue">{{$item['callable']}}</span>
        </div>
    @endforeach
    <div class="text-right font-bold text-blue mt-1 w-full">
        Showing [{{$items->count()}}] handlers
    </div>
</div>
