<option value="">Select Subcategory</option>
@if (isset($subcategories) && count($subcategories)>0)
@forelse($subcategories as $subcategory)
<option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
@empty
<option value="">No subcategory Found</option>
@endforelse
@endif
