<div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add Poll</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <form method="POST" action="{{ route('manage-question-of-the-day.update',$question->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="label-control">Question</label>
                        <input type="text" class="text-control" placeholder="Enter Question" name="question" id="question" value="{{ $question->question }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="label-control">Option 1</label>
                        <input type="text" class="text-control" placeholder="Enter Option 1" name="option1" id="option1" value="{{ $question->option1 }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="label-control">Option 2</label>
                        <input type="text" class="text-control" placeholder="Enter Option 2" name="option2" id="option2" value="{{ $question->option2 }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="label-control">Option 3</label>
                        <input type="text" class="text-control" placeholder="Enter Option 3" name="option3" id="option3" value="{{ $question->option3 }}">
                    </div>
                </div>
                <!--<div class="form-group row">-->
                <!--    <div class="col-sm-12">-->
                <!--        <label class="label-control">Option 4</label>-->
                <!--        <input type="text" class="text-control" placeholder="Enter Option 4" name="option4" id="option4" value="{{ $question->option4 }}">-->
                <!--    </div>-->
                <!--</div>-->
                <div class="form-action row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-dark btn-save" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>