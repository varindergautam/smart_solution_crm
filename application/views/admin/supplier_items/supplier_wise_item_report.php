<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open_multipart($this->uri->uri_string(), ['class' => 'staff-form', 'autocomplete' => 'off', 'method' => 'get']); ?>

            <div class="col-md-12" id="small-table">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="horizontal-tabs">
                            <h4>
                                Supplier Wise Item Report
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <div class="form-group select-placeholder">
                                    <label for="item_id" class="control-label"><?php echo _l('Supplier'); ?></label>
                                    <select name="supplier" data-live-search="true" id="item" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""><?php echo _l('Select supplier'); ?></option>
                                        <?php foreach ($suppliers_members as $suppliers_member) {
                                            $selected = '';
                                            if (isset($supplier)) {
                                                if ($supplier == $suppliers_member['supplierid']) {
                                                    $selected = 'selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $suppliers_member['supplierid']; ?>" <?php echo $selected; ?>>
                                                <?php echo ucfirst($suppliers_member['company']); ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-bottom-toolbar text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <?php echo form_close(); ?>

            <?php
            if (isset($supplier)) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <?php
                            $table_data = [
                                _l('S No.'),
                                _l('Item Name'),
                                _l('Item Rate'),
                                _l('Brand'),
                                _l('Group'),
                                _l('Company Name'),
                                _l('Supplier Name 1'),
                                _l('Supplier Name 2'),
                                _l('Supplier Name 3'),
                                _l('Supplier Email 1'),
                                _l('Supplier Email 2'),
                                _l('Supplier Email 3'),
                                _l('Supplier Phone No. 1'),
                                _l('Supplier Phone No. 2'),
                                _l('Supplier Phone No. 3'),
                                _l('Date'),
                            ];

                            render_datatable($table_data, 'supplier_wise');
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-supplier_wise', window.location.href);
    });
</script>
</body>

</html>