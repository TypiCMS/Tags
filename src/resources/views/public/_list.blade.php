<ul class="list-tags">
    @foreach ($items as $tag)
    @include('tags::public._list-item')
    @endforeach
</ul>
