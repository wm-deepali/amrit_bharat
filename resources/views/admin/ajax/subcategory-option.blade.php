@if (isset($subcategories) && count($subcategories)>0)
    @foreach ($subcategories as $subcategory)
    <li>
        <label>
            <input type="checkbox" class="subcategory" name="subcategory[]" value="{{ $subcategory->id }}"> {{ $subcategory->name }}
        </label>
    </li>
    @endforeach
@endif
