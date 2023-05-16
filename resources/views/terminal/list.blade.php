

<div class="mx-2 my-1">
    @foreach($items as $item)
        <div class="flex space-x-1">
            <span class="text-yellow">{{$item['handler']}}</span>
            <span class="text-gray">{{$item['pattern']}}</span>
            <span class="flex-1 content-repeat-['.']"></span>
            <span class="text-blue">{{$item['callable']}}</span>
        </div>
    @endforeach
</div>
