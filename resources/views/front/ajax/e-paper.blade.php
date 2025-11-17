<div class="col-sm-8">
    @if (isset($epaper))
        <h3 class="date-h">Showing Result: {{ $epaper->date }} <a href="{{ URL::asset('storage/'.$epaper->file) }}" download>Download E-Paper</a></h3>
        @if (isset($epaper->file) && Storage::exists($epaper->file))
        <iframe src="{{ URL::asset('storage/'.$epaper->file) }}" width="100%" height="800px">
        @else
        <a href="#">File Not Available</a>
        @endif
    @endif
</div>