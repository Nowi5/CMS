@foreach($processed_links as $link)
    @if ($link->external)
        <li><a href="{{ url($link->external_url) }}">{{ $link->name }}</a></li>
    @else
        <li><a href="{{ url(\Yab\Quarx\Models\Page::find($link->page_id)->url) }}">{{ $link->name }}</a></li>
    @endif
@endforeach