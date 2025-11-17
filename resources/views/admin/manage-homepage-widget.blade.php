@include('admin.header')
<form class="form-body" method="POST" action="{{ route('update-homepage-widget') }}">
@csrf
<section class="breadcrumb-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div class="content-header">
					<h3 class="content-header-title">Site Settings</h3>
					<button class="btn btn-dark btn-save" type="submit"><i class="fas fa-plus"></i> Update Settings</button>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item">Site Settings</li>
						<li class="breadcrumb-item active">Homepage Widgets</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="content-main-body">
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
								<div class="row">
									<div class="col-sm-12">
										<h5 class="form-section-h">Catalogue</h5>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Catalogue Category</label>
												<select class="text-control" name="cataloguecategory">
                                                    <option value="*">All Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('cataloguecategory',$homepagewidget) && old('cataloguecategory',$homepagewidget->cataloguecategory ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('cataloguecategory')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Catalogue Post Type</label>
												<select class="text-control" name="catalogueposttype">
                                                    <option value="all" @if (old('catalogueposttype',$homepagewidget) && old('catalogueposttype',$homepagewidget->catalogueposttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('catalogueposttype',$homepagewidget) && old('catalogueposttype',$homepagewidget->catalogueposttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('catalogueposttype',$homepagewidget) && old('catalogueposttype',$homepagewidget->catalogueposttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('catalogueposttype',$homepagewidget) && old('catalogueposttype',$homepagewidget->catalogueposttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('catalogueposttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<h5 class="form-section-h">4 Category Tabs</h5>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Tab #1 Category</label>
												<select class="text-control" name="categorytab1">
                                                    <option value="">Select Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('categorytab1',$homepagewidget) && old('categorytab1',$homepagewidget->categorytab1 ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('categorytab1')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Tab #1 Post Type</label>
												<select class="text-control" name="categorytab1posttype">
													<option value="all" @if (old('categorytab1posttype',$homepagewidget) && old('categorytab1posttype',$homepagewidget->categorytab1posttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('categorytab1posttype',$homepagewidget) && old('categorytab1posttype',$homepagewidget->categorytab1posttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('categorytab1posttype',$homepagewidget) && old('categorytab1posttype',$homepagewidget->categorytab1posttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('categorytab1posttype',$homepagewidget) && old('categorytab1posttype',$homepagewidget->categorytab1posttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('categorytab1posttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Tab #2 Category</label>
												<select class="text-control" name="categorytab2">
                                                    <option value="">Select Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('categorytab2',$homepagewidget) && old('categorytab2',$homepagewidget->categorytab2 ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('categorytab2')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Tab #2 Post Type</label>
												<select class="text-control" name="categorytab2posttype">
													<option value="all" @if (old('categorytab2posttype',$homepagewidget) && old('categorytab2posttype',$homepagewidget->categorytab2posttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('categorytab2posttype',$homepagewidget) && old('categorytab2posttype',$homepagewidget->categorytab2posttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('categorytab2posttype',$homepagewidget) && old('categorytab2posttype',$homepagewidget->categorytab2posttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('categorytab2posttype',$homepagewidget) && old('categorytab2posttype',$homepagewidget->categorytab2posttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('categorytab2posttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Tab #3 Category</label>
												<select class="text-control" name="categorytab3">
                                                    <option value="">Select Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('categorytab3',$homepagewidget) && old('categorytab3',$homepagewidget->categorytab3 ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('categorytab3')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Tab #3 Post Type</label>
												<select class="text-control" name="categorytab3posttype">
													<option value="all" @if (old('categorytab3posttype',$homepagewidget) && old('categorytab3posttype',$homepagewidget->categorytab3posttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('categorytab3posttype',$homepagewidget) && old('categorytab3posttype',$homepagewidget->categorytab3posttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('categorytab3posttype',$homepagewidget) && old('categorytab3posttype',$homepagewidget->categorytab3posttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('categorytab3posttype',$homepagewidget) && old('categorytab3posttype',$homepagewidget->categorytab3posttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('categorytab3posttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Tab #4 Category</label>
												<select class="text-control" name="categorytab4">
                                                    <option value="">Select Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('categorytab4',$homepagewidget) && old('categorytab4',$homepagewidget->categorytab4 ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('categorytab4')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Tab #4 Post Type</label>
												<select class="text-control" name="categorytab4posttype">
													<option value="all" @if (old('categorytab4posttype',$homepagewidget) && old('categorytab4posttype',$homepagewidget->categorytab4posttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('categorytab4posttype',$homepagewidget) && old('categorytab4posttype',$homepagewidget->categorytab4posttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('categorytab4posttype',$homepagewidget) && old('categorytab4posttype',$homepagewidget->categorytab4posttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('categorytab4posttype',$homepagewidget) && old('categorytab4posttype',$homepagewidget->categorytab4posttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('categorytab4posttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<h5 class="form-section-h">Slider</h5>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Slider Category</label>
												<select class="text-control" name="slidercategory">
                                                    <option value="*">All Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('slidercategory',$homepagewidget) && old('slidercategory',$homepagewidget->slidercategory ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('slidercategory')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Slider Post Type</label>
												<select class="text-control" name="sliderposttype">
													<option value="all" @if (old('sliderposttype',$homepagewidget) && old('sliderposttype',$homepagewidget->sliderposttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('sliderposttype',$homepagewidget) && old('sliderposttype',$homepagewidget->sliderposttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('sliderposttype',$homepagewidget) && old('sliderposttype',$homepagewidget->sliderposttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('sliderposttype',$homepagewidget) && old('sliderposttype',$homepagewidget->sliderposttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('sliderposttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Trending Category</label>
												<select class="text-control" name="trendingcategory">
                                                    <option value="*">All Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('trendingcategory',$homepagewidget) && old('trendingcategory',$homepagewidget->trendingcategory ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('trendingcategory')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Trending Post Type</label>
												<select class="text-control" name="trendingposttype">
													<option value="all" @if (old('trendingposttype',$homepagewidget) && old('trendingposttype',$homepagewidget->trendingposttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('trendingposttype',$homepagewidget) && old('trendingposttype',$homepagewidget->trendingposttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('trendingposttype',$homepagewidget) && old('trendingposttype',$homepagewidget->trendingposttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('trendingposttype',$homepagewidget) && old('trendingposttype',$homepagewidget->trendingposttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('trendingposttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<h5 class="form-section-h">3 Category Tabs</h5>
										<div class="form-group row">
											<div class="col-sm-12">
                                                <label class="label label-control">Select Category</label><br>
                                                @if (isset($categories) && count($categories)>0)
                                                    @foreach ($categories as $category)
                                                        <label><input type="checkbox" name="centercategorytab[]" value="{{ $category->id }}" @if (old('centercategorytab') && in_array($category->id,old('centercategorytab')))
                                                            checked
                                                        @elseif (!empty($centercategoryids) && in_array($category->id,$centercategoryids))
                                                            checked
                                                        @endif> {{ $category->name }}</label>&nbsp;&nbsp;
                                                    @endforeach
                                                @endif
                                                @error('centercategorytab')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<h5 class="form-section-h">Video Tab</h5>
										<div class="form-group row">
											<div class="col-sm-12">
                                                <label class="label label-control">Select Category</label><br>
                                                @if (isset($categories) && count($categories)>0)
                                                    @foreach ($categories as $category)
                                                        <label><input type="checkbox" name="videocategorytab[]" value="{{ $category->id }}" @if (old('videocategorytab') && in_array($category->id,old('videocategorytab')))
                                                            checked
                                                        @elseif (!empty($videocategoryids) && in_array($category->id,$videocategoryids))
                                                        checked
                                                        @endif> {{ $category->name }}</label>&nbsp;&nbsp;
                                                    @endforeach
                                                @endif
                                                @error('videocategorytab')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<h5 class="form-section-h">Other Widgets</h5>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Category</label>
												<select class="text-control" name="otherwidgetcategory">
                                                    <option value="">Select Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('otherwidgetcategory',$homepagewidget) && old('otherwidgetcategory',$homepagewidget->otherwidgetcategory ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('otherwidgetcategory')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Post Type</label>
												<select class="text-control" name="otherwidgetposttype">
													<option value="all" @if (old('otherwidgetposttype',$homepagewidget) && old('otherwidgetposttype',$homepagewidget->otherwidgetposttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('otherwidgetposttype',$homepagewidget) && old('otherwidgetposttype',$homepagewidget->otherwidgetposttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('otherwidgetposttype',$homepagewidget) && old('otherwidgetposttype',$homepagewidget->otherwidgetposttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('otherwidgetposttype',$homepagewidget) && old('otherwidgetposttype',$homepagewidget->otherwidgetposttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('otherwidgetposttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">Must Read Category</label>
												<select class="text-control" name="mustreadcategory">
                                                    <option value="">Select Category</option>
                                                    @if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('mustreadcategory',$homepagewidget) && old('mustreadcategory',$homepagewidget->mustreadcategory ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('mustreadcategory')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">Must Read Post Type</label>
												<select class="text-control" name="mustreadposttype">
													<option value="all" @if (old('mustreadposttype',$homepagewidget) && old('mustreadposttype',$homepagewidget->mustreadposttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('mustreadposttype',$homepagewidget) && old('mustreadposttype',$homepagewidget->mustreadposttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('mustreadposttype',$homepagewidget) && old('mustreadposttype',$homepagewidget->mustreadposttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('mustreadposttype',$homepagewidget) && old('mustreadposttype',$homepagewidget->mustreadposttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('mustreadposttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="label label-control">You May Like Category</label>
												<select class="text-control" name="youmaylikecategory">
													<option value="">Select Category</option>
													@if (isset($categories) && count($categories)>0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (old('youmaylikecategory',$homepagewidget) && old('youmaylikecategory',$homepagewidget->youmaylikecategory ?? '')==$category->id)
                                                                selected
                                                            @endif>{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('youmaylikecategory')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
											<div class="col-sm-6">
												<label class="label label-control">You May Like  Post Type</label>
												<select class="text-control" name="youmaylikeposttype">
													<option value="all" @if (old('youmaylikeposttype',$homepagewidget) && old('youmaylikeposttype',$homepagewidget->youmaylikeposttype ?? '')=='all')
                                                        selected
                                                    @endif>All</option>
													<option value="trending" @if (old('youmaylikeposttype',$homepagewidget) && old('youmaylikeposttype',$homepagewidget->youmaylikeposttype ?? '')=='trending')
                                                        selected
                                                    @endif>Trending</option>
													<option value="latest" @if (old('youmaylikeposttype',$homepagewidget) && old('youmaylikeposttype',$homepagewidget->youmaylikeposttype ?? '')=='latest')
                                                        selected
                                                    @endif>Latest</option>
													<option value="oldest" @if (old('youmaylikeposttype',$homepagewidget) && old('youmaylikeposttype',$homepagewidget->youmaylikeposttype ?? '')=='oldest')
                                                        selected
                                                    @endif>Older</option>
                                                </select>
                                                @error('youmaylikeposttype')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
										<h5 class="form-section-h">3 Category Tabs</h5>
										<div class="form-group row">
											<div class="col-sm-12">
                                                <label class="label label-control">Select Category</label><br>
                                                @if (isset($categories) && count($categories)>0)
                                                    @foreach ($categories as $category)
                                                    <label>
                                                        <input type="checkbox" name="lowercategorytab[]" value="{{ $category->id }}" @if (old('lowercategorytab') && in_array($category->id,old('lowercategorytab')))
                                                        checked
                                                    @elseif (!empty($lowercategoryids) && in_array($category->id,$lowercategoryids))
                                                    checked
                                                    @endif> {{ $category->name }}</label>&nbsp;&nbsp;
                                                    @endforeach
                                                @endif
                                                @error('lowercategorytab')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
											</div>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="form-body">
								<h4 class="form-section-h">Sidebar</h4>
								<div class="form-group row">
									<div class="col-sm-8">
										<label class="label label-control">Tab #1 Category</label>
										<select class="text-control" name="sidebartab1category">
                                            <option value="">Select Category</option>
                                            @if (isset($categories) && count($categories)>0)
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @if (old('sidebartab1category',$homepagewidget) && old('sidebartab1category',$homepagewidget->sidebartab1category ?? '')==$category->id)
                                                    selected
                                                    @endif>{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('sidebartab1category')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
									</div>
									<div class="col-sm-4">
										<label class="label label-control">Post Type</label>
										<select class="text-control" name="sidebartab1posttype">
											<option value="all" @if (old('sidebartab1posttype',$homepagewidget) && old('sidebartab1posttype',$homepagewidget->sidebartab1posttype ?? '')=='all')
                                                selected
                                            @endif>All</option>
                                            <option value="trending" @if (old('sidebartab1posttype',$homepagewidget) && old('sidebartab1posttype',$homepagewidget->sidebartab1posttype ?? '')=='trending')
                                                selected
                                            @endif>Trending</option>
                                            <option value="latest" @if (old('sidebartab1posttype',$homepagewidget) && old('sidebartab1posttype',$homepagewidget->sidebartab1posttype ?? '')=='latest')
                                                selected
                                            @endif>Latest</option>
                                            <option value="oldest" @if (old('sidebartab1posttype',$homepagewidget) && old('sidebartab1posttype',$homepagewidget->sidebartab1posttype ?? '')=='oldest')
                                                selected
                                            @endif>Older</option>
                                        </select>
                                        @error('sidebartab1posttype')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-8">
										<label class="label label-control">Tab #2 Category</label>
										<select class="text-control" name="sidebartab2category">
                                            <option value="">Select Category</option>
                                            @if (isset($categories) && count($categories)>0)
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @if (old('sidebartab2category',$homepagewidget) && old('sidebartab2category',$homepagewidget->sidebartab2category ?? '')==$category->id)
                                                        selected
                                                    @endif>{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('sidebartab2category')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
									</div>
									<div class="col-sm-4">
										<label class="label label-control">Post Type</label>
										<select class="text-control" name="sidebartab2posttype">
											<option value="all" @if (old('sidebartab2posttype',$homepagewidget) && old('sidebartab2posttype',$homepagewidget->sidebartab2posttype ?? '')=='all')
                                                selected
                                            @endif>All</option>
                                            <option value="trending" @if (old('sidebartab2posttype',$homepagewidget) && old('sidebartab2posttype',$homepagewidget->sidebartab2posttype ?? '')=='trending')
                                                selected
                                            @endif>Trending</option>
                                            <option value="latest" @if (old('sidebartab2posttype',$homepagewidget) && old('sidebartab2posttype',$homepagewidget->sidebartab2posttype ?? '')=='latest')
                                                selected
                                            @endif>Latest</option>
                                            <option value="oldest" @if (old('sidebartab2posttype',$homepagewidget) && old('sidebartab2posttype',$homepagewidget->sidebartab2posttype ?? '')=='oldest')
                                                selected
                                            @endif>Older</option>
                                        </select>
                                        @error('sidebartab2posttype')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-8">
										<label class="label label-control">Tab #3 Category</label>
										<select class="text-control" name="sidebartab3category">
                                            <option value="">Select Category</option>
                                            @if (isset($categories) && count($categories)>0)
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @if (old('sidebartab3category',$homepagewidget) && old('sidebartab3category',$homepagewidget->sidebartab3category ?? '')==$category->id)
                                                        selected
                                                    @endif>{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('sidebartab3category')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
									</div>
									<div class="col-sm-4">
										<label class="label label-control">Post Type</label>
										<select class="text-control" name="sidebartab3posttype">
											<option value="all" @if (old('sidebartab3posttype',$homepagewidget) && old('sidebartab3posttype',$homepagewidget->sidebartab3posttype ?? '')=='all')
                                                selected
                                            @endif>All</option>
                                            <option value="trending" @if (old('sidebartab3posttype',$homepagewidget) && old('sidebartab3posttype',$homepagewidget->sidebartab3posttype ?? '')=='trending')
                                                selected
                                            @endif>Trending</option>
                                            <option value="latest" @if (old('sidebartab3posttype',$homepagewidget) && old('sidebartab3posttype',$homepagewidget->sidebartab3posttype ?? '')=='latest')
                                                selected
                                            @endif>Latest</option>
                                            <option value="oldest" @if (old('sidebartab3posttype',$homepagewidget) && old('sidebartab3posttype',$homepagewidget->sidebartab3posttype ?? '')=='oldest')
                                                selected
                                            @endif>Older</option>
                                        </select>
                                        @error('sidebartab3posttype')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</form>
@include('admin.footer')
