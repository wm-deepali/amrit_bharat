<div id="loader" class="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog loader">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{ URL::asset('admin/images/loader.gif') }}" alt="loader">
            </div>
        </div>
    </div>
</div>
<footer>
    <div class="footer-copyright-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2020 Amrit Bharat Admin | All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<script src="{{ URL::asset('admin/js/jquery.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('admin/js/poppers.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('admin/js/bootstrap-4.js') }}" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="{{ URL::asset('admin/js/custom.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
<script src="{{ URL::asset('admin/ckeditor/ckeditor.js') }}"></script>
</body>
</html>