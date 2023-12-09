<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('financial_year', '', 'create')) { ?>
                    <?php } ?>
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('financial_year/create'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('Create Financial Year'); ?>
                    </a>
                </div>
                
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $table_data = [
                            _l('S No.'),
                            _l('Year Name'),
                            _l('Date'),
                        ];
                     
                        render_datatable($table_data, 'financial_year');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_financial_year" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('financial_year/delete', ['delete_financial_year_form'])); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_financial_year'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_financial_year_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', @$financial_year_members, ['supplierid', ['firstname', 'lastname']], 'financial_year_member', [], [], [], '', '', false);
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

    initDataTable('.table-financial_year', window.location.href);
});

function delete_financial_year_member(id) {
    $('#delete_financial_year').modal('show');
    $('#transfer_data_to').find('option').prop('disabled', false);
    $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
    $('#delete_financial_year .delete_id input').val(id);
    $('#transfer_data_to').selectpicker('refresh');
}
</script>
</body>

</html>