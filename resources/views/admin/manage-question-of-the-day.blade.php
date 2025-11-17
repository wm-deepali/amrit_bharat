@include('admin.header')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Content</h3>
          <button class="btn btn-dark btn-save add-question"><i class="fas fa-plus"></i> Add Question</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active">Manage Question Of the Day</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content-main-body">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="card-block">
              <div class="table-responsive">
                <table class="table table-bordered table-fitems" id="manage-question">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Question</th>
                      <th>Posted On</th>
                      <th>Answer Bifurcation</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($questions) && count($questions)>0)
                    @foreach ($questions as $question)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $question->question }}</td>
                        <td>{{ $question->created_at }}</td>
                        <td>
                          <li>{{ $question->option1 }}- {{ $question->option1vote }}</li>
                          <li>{{ $question->option2 }}- {{ $question->option2vote }}</li>
                          <li>{{ $question->option3 }}- {{ $question->option3vote }}</li>
                          <!--<li>{{ $question->option4 }}- {{ $question->option4vote }}</li>-->
                        </td>
                        <td class="status">{{ $question->status }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-question" questionid='{{ $question->id }}'><i class="fas fa-pencil-alt"></i></a></li>
                                {{-- <li><a href="{{ route('change-status-question-of-the-day',$question->id) }}" class="change-status-question"><i class="fas fa-times"></i></a></li> --}}
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-question-form-{{ $question->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-question-form-{{ $question->id }}" action="{{ route('manage-question-of-the-day.destroy',$question->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('Delete')
                                    </form>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal" id="add-question">
</div>

<div class="modal" id="edit-question">
</div>
@include('admin.footer')
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $( document ).ajaxStart(function() {
    $("#loader").modal('show');
    });
    $( document ). ajaxComplete(function() {
    $("#loader").modal('hide');
    });
    $(document).ready(function(){
    $(document).on("click",".add-question",function(event){
        $.ajax({
            url:"{{ URL::to('manage-question-of-the-day/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200'){
                    $("#add-question").html(result.html);
                    $("#add-question").modal('show');
                }else if(result.msgCode==='400'){
                    toastr.error('error encountered '+result.msgText);
                }
                $("#loader").modal('hide');
            },
            error:function(error){
                toastr.error('error encountered '+error.statusText);
                $("#loader").modal('hide');
            }
        });
    });


    $(document).on("click",".edit-question",function(event){
        let questionid = $(this).attr('questionid');
        $.ajax({
            url:`{{ URL::to('manage-question-of-the-day/${questionid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-question").html(result.html);
                    $("#edit-question").modal('show');
                }
                else if(result.msgCode==='400')
                {
                    toastr.error('error encountered '+result.msgText);
                }
                $("#loader").modal('hide');
            },
            error:function(error){
                toastr.error('error encountered '+error.statusText);
                $("#loader").modal('hide');
            }
        });
    });

    $(document).on("change","#state",function(event){
        let stateid = $(this).val();
        $.ajax({
            url:"{{ URL::to('fetchcitybystateid') }}",
            type:"POST",
            dataType:"json",
            data:{"state_id":stateid},
            success:function(result) {
                if(result.msgCode==='200') {
                    $("#city").html(result.html);
                } else if(result.msgCode==='400') {
                    toastr.error('error encountered '+result.msgText);
                }
                $("#loader").modal('hide');
            },
            error:function(error) {
                toastr.error('error encountered '+error.statusText);
                $("#loader").modal('hide');
            }
        });
    });
    $("#manage-question").DataTable();
});
</script>
