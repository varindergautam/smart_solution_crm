<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('pdc', '', 'create')) { ?>
                    <?php } ?>
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('pdc/create'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('Create PDC'); ?>
                    </a>
                </div>
                
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $table_data = [
                            _l('ID'),
                            _l('Type'),
                            _l('Cheque Date'),
                            _l('Cheque No.'),
                            _l('Date'),
                            _l('Paid Status'),
                        ];
                     
                        render_datatable($table_data, 'pdc');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_pdc" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('pdc/delete', ['delete_pdc_form'])); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_pdc'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_pdc_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', @$pdc_members, ['supplierid', ['firstname', 'lastname']], 'pdc_member', [], [], [], '', '', false);
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

    initDataTable('.table-pdc', window.location.href);
});

function delete_pdc_member(id) {
    $('#delete_pdc').modal('show');
    $('#transfer_data_to').find('option').prop('disabled', false);
    $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
    $('#delete_pdc .delete_id input').val(id);
    $('#transfer_data_to').selectpicker('refresh');
}
</script>
</body>

</html>