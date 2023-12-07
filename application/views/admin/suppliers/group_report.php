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
                                Group Report
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <div class="form-group select-placeholder">
                                    <label for="group" class="control-label"><?php echo _l('Group'); ?></label>
                                    <select name="group" data-live-search="true" id="group" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value="">Select Type</option>
                                        <?php foreach ($groups as $b) {
                                            $selected = '';
                                            if (isset($group)) {
                                                if ($group == $b['id']) {
                                                    $selected = 'selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $b['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo ucfirst($b['name']); ?></option>
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
            if (isset($group)) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <?php
                            $table_data = [
                                _l('ID'),
                                _l('Group Name'),
                                _l('Company Name'),
                                _l('Supplier Name'),
                                _l('Supplier Email'),
                                _l('Supplier Phone No.'),
                            ];

                            render_datatable($table_data, 'group_report_table');
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
        initDataTable('.table-group_report_table', window.location.href);
    });
</script>
</body>

</html>