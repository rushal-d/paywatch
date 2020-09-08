<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><i class="far fa-trash-alt"></i> Confirm Delete</h4>
            </div>
            <form id="firstform" action="" method="post">
                {{csrf_field()}}
                @method('DELETE')
                <div class="modal-body">
                    Are you Sure You Want To Delete ?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary modal-delete">Yes
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->