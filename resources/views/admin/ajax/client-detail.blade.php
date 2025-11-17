<div class="modal-dialog">
<div class="modal-content">     
<!-- Modal Header -->
<div class="modal-header">
<h4 class="modal-title">Client Details</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<!-- Modal body -->
<div class="modal-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr>
<th>Name</th>
<td>{{ $ad->name ?? 'NA' }}</td>
<th>Email</th>
<td>{{ $ad->email ?? 'NA' }}</td>
</tr>
<tr>
<th>Mobile No.</th>
<td>{{ $ad->contact ?? 'NA' }}</td>
@if ($ad->type=='custom')
<th>Ad Image</th>
<td>
    @if (isset($ad->image) && Storage::exists($ad->image))
    <img src="{{ URL::asset('storage/'.$ad->image) }}" class="img-fluid" style="height: 50px;">
    @else
    NA
    @endif
</td>
@endif
</tr>
@if ($ad->type=='custom')
<tr>
<th>Ad Link</th>
<td colspan="3"><a href="{{ $ad->link }}">View Ad Link</a></td>
</tr>
@endif

</table>  
</div>
</div>
</div>
</div>