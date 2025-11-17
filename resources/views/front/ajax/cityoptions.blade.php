<option value="">Select City</option>
@if (isset($cities) && count($cities)>0)
@forelse($cities as $city)
<option value="{{ $city->id }}">{{ $city->name }}</option>
@empty
<option value="">No City Found</option>
@endforelse
@endif
