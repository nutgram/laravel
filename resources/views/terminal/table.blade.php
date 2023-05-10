<div class="mx-2 my-1">
    @foreach($items as $key => $value)
        <div class="flex space-x-1">
            <span class="text-yellow">{{$key}}</span>
            <span class="flex-1 content-repeat-['.']"></span>
            <span class="text-blue">{{$value}}</span>
        </div>
    @endforeach
</div>
