@include('admin.header')
<section class="content-main-body">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="stats-box">
                        <i class="fas fa-users align-self-center"></i>
                        <div class="stats-con">
                            <p>Total Posts</p>
                            <h3>{{ $postCount  ?? '0' }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="stats-box">
                        <i class="fas fa-box align-self-center"></i>
                        <div class="stats-con">
                            <p>Total Reporter</p>
                            <h3>{{ $reporterCount ?? '0' }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="stats-box">
                        <i class="fas fa-file align-self-center"></i>
                        <div class="stats-con">
                            <p>Total Visitors</p>
                            <h3>{{ $totalViews ?? '0' }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="stats-box">
                        <i class="fas fa-dollar-sign align-self-center"></i>
                        <div class="stats-con">
                            <p>Pending Posts</p>
                            <h3>{{ $pendingCount ?? '0' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@include('admin.footer')
