<nav class="breadcrumb">
    @foreach($links as $text => $url)
    @if ($loop->last)
    <span>{{ $text }}</span>
    @else
    <a href="{{ $url }}">{{ $text }}</a> /
    @endif
    @endforeach
</nav>