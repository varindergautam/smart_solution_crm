<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <style>
        table tfoot tr td {
            color: red !important;
            font-weight: bolder !important;
        }
    </style>

    <div class="content">
        <div class="row">
            <?php echo form_open_multipart($this->uri->uri_string(), ['class' => 'staff-form', 'autocomplete' => 'off', 'method' => 'get']); ?>

            <div class="col-md-12" id="small-table">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="horizontal-tabs">
                            <h4>
                                Proposal Report
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                            <?php $value = (isset($date) ? $date : ''); ?>
                                <?php echo render_input('date', 'Proposal Date', $value, 'date'); ?>

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
                                                <?php echo ucfirst($sup['company']); ?></option>
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
            if (isset($supplier) || $date) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                        <?php
                            $table_data = [
                                _l('ID'),
                                _l('Proposal No.'),
                                _l('Proposal Date'),
                                _l('Item'),
                                _l('Group'),
                                _l('Amount'),
                                _l('Date'),
                            ];

                            render_datatable($table_data, 'proposal');
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

        $(function() {
        initDataTable('.table-proposal', window.location.href);
    });

    });
</script>
</body>

</html>