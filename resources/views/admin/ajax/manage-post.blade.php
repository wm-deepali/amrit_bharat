
              <div class="table-responsive">
                <table class="table table-bordered table-fitems">
                  <thead>
                    <tr> 
                      <th>Sr. No.</th>
                      <th>Date and Time</th>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Added By</th>
                      <th>Status</th>
                      <th>URL</th>
                      <th>Views</th><!--
                      <th>Tags</th>
                      <th>ID</th>-->
                      <th>Approved By</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @if (isset($posts) && count($posts)>0)
                      @foreach ($posts as $index => $post)
                      @isset($post->categories[0]->category)
                      <tr>
                        <td>{{ $posts->firstItem() + $index }}</td>
                        <td>{{ $post->created_at }}</td>
                        <td>{{ $post->title }}</td>
                        <td>
                            @if (isset($post->categories) && count($post->categories)>0)
                            @foreach ($post->categories as $category)
                            <span class="cat-btns">{{ $category->category->name ?? '' }}</span>
                            @endforeach
                            @endif
                        </td>
                        <td>
                            {{-- @if ($post->user->role=='admin')
                            {{ $post->user->name }}
                            @else --}}
                            <a href="javascript:void(0)" class="reporter-info" userid="{{ $post->user->id }}">{{ $post->user->name }}</a>
                            {{-- @endif --}}
                        </td>
                        <td><span class="status">{{ $post->status }}</span></td> 
                        <td class="url">
                            @if ($post->status=='published')
                            <a href="{{ route('postdetail',[$post->categories[0]->category->slug,$post->slug]) }}" target="_blank">View Link</a>
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $post->views }}</td><!--
                        <td>
                            @if (isset($post->tags) && count($post->tags)>0)
                            @foreach ($post->tags as $tag)
                            <span class="tags">{{ $tag->tag->name ?? 'NA' }}</span>
                            @endforeach
                            @endif
                        </td>
                        <td>{{ $post->postnumber }}</td>-->
                        <td class="approvedby">{{ $post->approvedby->name ?? '-' }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="preview-post" postid="{{ $post->id }}" title="Preview Image"><i class="fas fa-eye"></i></a></li>
                                <li><a href="{{ route('manage-post.edit',$post->id) }}"><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="javascript:void(0)" class="change-status-post" postid="{{ $post->id }}" title="Change Status"><i class="fas fa-exchange-alt"></i></a></li>
                                @if ($post->status=='pending' && Auth::user()->role=='admin')
                                    <li><a href="javascript:void(0)" class="publish-post" postid="{{ $post->id }}" title="Approve and Publish Post"><i class="fas fa-check"></i></a></li>
                                @endif
                                <li><a href="javascript:void(0)" class="delete-post" postid="{{ $post->id }}"><i class="fas fa-trash"></i></a></li>
                            </ul>
                        </td>
                      </tr>
                      @endif
                      @endforeach
                      @endif
                  </tbody>
                </table>
                {!! $posts->render() !!}
              </div>