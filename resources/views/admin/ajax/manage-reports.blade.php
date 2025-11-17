<div class="table-responsive">
    <table class="table table-bordered table-fitems">
      <thead>
        <tr>
          <th>Added At</th>
          <th>Category</th>
          <th>Sub Cat</th>
          <th>News Title</th>
          <th>Reporter</th>
          <th>Mob No.</th>
          <th>URL</th>
          <th>Views</th>
          <th>Clicks</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @if (isset($posts) && count($posts)>0)
            @foreach ($posts as $post)
            <tr>
              <td>{{ $post->created_at }}</td>
              <td>
                @if (isset($post->categories) && count($post->categories)>0)
                @foreach ($post->categories as $category)
                @if (isset($category->category->name))
                {{ $category->category->name }}
                @else
                Category was Deleted
                @endif
                @endforeach
                @endif
              </td>
              <td>
                @if (isset($post->subcategories) && count($post->subcategories)>0)
                @foreach ($post->subcategories as $subcategory)
                @if (isset($subcategory->subcategory->name))
                {{ $subcategory->subcategory->name }}
                @else
                Subcategory was Deleted
                @endif
                @endforeach
                @endif
              </td>
              <td>{{ $post->title }}</td>
              <td>{{ $post->user->name ?? '' }}</td>
              <td>{{ $post->user->contact ?? '' }}</td>
              <td>
                @if ($post->status=='published')
                @if (isset($post->categories[0]->category->slug))
                <a href="{{ route('postdetail',[$post->categories[0]->category->slug,$post->slug]) }}" target="_blank">View Link</a>
                @else
                Category was Deleted
                @endif
                @else
                -
                @endif
              </td>
              <td>{{ $post->views }}</td>
              <td>{{ $post->views }}</td>
              <td>
                @if ($post->status=='published')
                <span class="publised-status">Published</span>
                @else
                {{ $post->status }}
                @endif
                
              </td>
            </tr>
            @endforeach
        @endif

      </tbody>
    </table>
    {!! $posts->render() !!}
  </div>