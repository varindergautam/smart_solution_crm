<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('supplier_items', '', 'create')) { ?>
                    <?php } ?>
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('supplier_item/create'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('Create Supplier Item'); ?>
                    </a>
                </div>
                
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $table_data = [
                            _l('S No.'),
                            _l('Supplier Company Name'),
                            _l('Supplier Name 1'),
                            _l('Supplier Name 2'),
                            _l('Supplier Name 3'),
                            _l('Supplier Email 1'),
                            _l('Supplier Email 2'),
                            _l('Supplier Email 3'),
                            _l('Supplier Phone No. 1'),
                            _l('Supplier Phone No. 2'),
                            _l('Supplier Phone No. 3'),
                            _l('Item'),
                            _l('Rate'),
                            _l('Date'),
                        ];
                     
                        render_datatable($table_data, 'supplier_items');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_supplier_items" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('supplier_items/delete', ['delete_supplier_items_form'])); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_supplier_items'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_supplier_items_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', @$supplier_items_members, ['supplierid', ['firstname', 'lastname']], 'supplier_items_member', [], [], [], '', '', false);
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
    initDataTable('.table-supplier_items', window.location.href);
});

function delete_supplier_items_member(id) {
    $('#delete_supplier_items').modal('show');
    $('#transfer_data_to').find('option').prop('disabled', false);
    $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
    $('#delete_supplier_items .delete_id input').val(id);
    $('#transfer_data_to').selectpicker('refresh');
}
</script>
</body>

</html>