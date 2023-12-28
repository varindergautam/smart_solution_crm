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
                                Payable Report
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                                <?php $value = (isset($month) ? $month : ''); ?>
                                <?php echo render_input('month', 'Month', $value, 'month'); ?>

                                <div class="form-group select-placeholder">
                                    <label for="supplier" class="control-label"><?php echo _l('Supplier'); ?></label>
                                    <select name="supplier" data-live-search="true" id="supplier" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""><?php echo _l('Select Supplier'); ?></option>
                                        <?php 
                                        foreach ($suppliers as $sup) {
                                            $selected = '';
                                            if (isset($supplier)) {
                                                if ($supplier == $sup['supplierid']) {
                                                    $selected = 'selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $sup['supplierid']; ?>" <?php echo $selected; ?>>
                                                <?php echo $sup['company']; ?></option>
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
            if (isset($month) || isset($supplier)) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <?php
                            $table_data = [
                                _l('S No.'),
                                _l('Company Name'),
                                _l('Invoice No.'),
                                _l('Invoice Date'),
                                _l('Invoice Due Date'),
                                _l('Amount Payable'),
                                _l('Date of PDC'),
                                _l('Cheque Number'),
                                _l('Bank'),
                                _l('Cheque Amount'),
                                _l('Remark'),
                                _l('Paid'),
                            ];

                            render_datatable($table_data, 'payable');
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
        initDataTable('.table-payable', window.location.href);
    });
</script>
</body>

</html>