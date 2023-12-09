<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('budget', '', 'create')) { ?>
                <?php } ?>
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('budget/create'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('Create Budget'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $table_data = [
                            _l('S No.'),
                            _l('Amount'),
                            _l('Financial Year'),
                            _l('Head'),
                            _l('Head Type'),
                            _l('Into Month'),
                            _l('Created At'),
                        ];

                        render_datatable($table_data, 'budget');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_budget" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('budget/delete', ['delete_budget_form'])); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_budget'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_budget_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', @$budget_members, ['supplierid', ['firstname', 'lastname']], 'budget_member', [], [], [], '', '', false);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-danger _delete"><?php echo _l('confirm'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {

        initDataTable('.table-budget', window.location.href);
    });

    function delete_budget_member(id) {
        $('#delete_budget').modal('show');
        $('#transfer_data_to').find('option').prop('disabled', false);
        $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
        $('#delete_budget .delete_id input').val(id);
        $('#transfer_data_to').selectpicker('refresh');
    }
</script>
</body>

</html>