<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('catalog', '', 'create')) { ?>
                <?php } ?>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php echo form_open_multipart($this->uri->uri_string(), ['class' => 'staff-form', 'autocomplete' => 'off', 'method' => 'get']); ?>
                        <div class="col-md-12" id="small-table">
                            <div class="tab-content tw-mt-5">
                                <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                                    <div class="form-group select-placeholder">
                                        <label for="supplier" class="control-label"><?php echo _l('Supplier'); ?></label>
                                        <select name="supplier" data-live-search="true" id="item" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                            <option value=""><?php echo _l('Select item'); ?></option>
                                            <?php foreach ($suppliers as $items_member) {
                                                $selected = '';
                                                if (isset($supplier)) {
                                                    if ($supplier == $items_member['supplierid']) {
                                                        $selected = 'selected';
                                                    }
                                                } ?>
                                                <option value="<?php echo $items_member['supplierid']; ?>" <?php echo $selected; ?>>
                                                    <?php echo ucfirst($items_member['company']); ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class=" text-left">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $table_data = [
                            _l('S No.'),
                            _l('Catalogue'),
                            _l('Created At'),
                            _l('Action'),
                        ];

                        render_datatable($table_data, 'catalog');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-catalog', window.location.href);
    });
</script>
</body>

</html>