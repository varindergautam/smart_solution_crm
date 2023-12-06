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
                                PDC Report
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                                <?php $value = (isset($month) ? $month : ''); ?>
                                <?php echo render_input('month', 'Month', $value, 'month'); ?>

                                <div class="form-group select-placeholder">
                                    <label for="type" class="control-label"><?php echo _l('Type'); ?></label>
                                    <select name="type" data-live-search="true" id="type" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value="">Select Type</option>
                                        <option value="in" <?php echo isset($type) && $type == 'in' ? 'selected' : ''; ?>>In</option>
                                        <option value="out" <?php echo isset($type) && $month == 'out' ? 'selected' : ''; ?>>Out</option>
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
            if (isset($month) || isset($type)) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <?php
                            $table_data = [
                                _l('ID'),
                                _l('Particular'),
                                _l('Date of PDC'),
                                _l('Cheque Number'),
                                _l('Bank'),
                                _l('Cheque Date'),
                                _l('Cheque Amount'),
                                _l('Remark'),
                                _l('Paid Status'),
                            ];

                            render_datatable($table_data, 'pdc');
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
        initDataTable('.table-pdc', window.location.href);
    });
</script>
</body>

</html>