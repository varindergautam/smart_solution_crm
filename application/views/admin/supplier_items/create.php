<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php if (isset($member)) { ?>
            <?php //$this->load->view('admin/staff/stats'); 
            ?>
            <div class="member">
                <?php echo form_hidden('isedit'); ?>
                <?php echo form_hidden('memberid', $member->id); ?>
            </div>
        <?php } ?>
        <div class="row">

            <?php echo form_open_multipart($this->uri->uri_string(), ['class' => 'staff-form', 'autocomplete' => 'off']); ?>
            <div class="col-md-<?php if (!isset($member)) {
                                    echo '8 col-md-offset-2';
                                } else {
                                    echo '8 col-md-offset-2';
                                } ?>" id="small-table">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="horizontal-tabs">
                            <h3>
                                <?php echo isset($id) && !empty($id) ? 'Edit' : 'Add'; ?>
                                Supplier Item
                            </h3>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <div class="form-group select-placeholder">
                                    <label for="supplier_id" class="control-label"><?php echo _l('Supplier'); ?></label>
                                    <select name="supplier_id" data-live-search="true" id="supplier" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""><?php echo _l('Select supplier'); ?></option>
                                        <?php foreach ($suppliers_members as $suppliers_member) {
                                            $selected = '';
                                            if (isset($member)) {
                                                if ($member->supplier_id == $suppliers_member['supplierid']) {
                                                    $selected = 'selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $suppliers_member['supplierid']; ?>" <?php echo $selected; ?>>
                                                <?php echo ucfirst($suppliers_member['vat_number']) . ' - ' . ucfirst($suppliers_member['company']); ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div>

                                <div class="form-group select-placeholder">
                                    <label for="item_id" class="control-label"><?php echo _l('Item'); ?></label>
                                    <select name="item_id" data-live-search="true" id="item" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""><?php echo _l('Select item'); ?></option>
                                        <?php foreach ($item_members as $items_member) {
                                            $selected = '';
                                            if (isset($member)) {
                                                if ($member->item_id == $items_member['itemid']) {
                                                    $selected = 'selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $items_member['itemid']; ?>" <?php echo $selected; ?>>
                                                <?php echo ucfirst($items_member['description']); ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div>

                                <?php $value = (isset($member) ? $member->rate : ''); ?>
                                <?php echo render_input('rate', 'Rate', $value); ?>

                                <?php $value = (isset($member) ? $member->date : ''); ?>
                                <?php echo render_input('date', 'Date', $value, 'date'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-bottom-toolbar text-right">
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>

        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
    <?php init_tail(); ?>
    <script>
        $(function() {

            init_roles_permissions();

            appValidateForm($('.staff-form'), {
                supplier_id: 'required',
                item_id: 'required',
                rate: {
                    required: true,
                    number: true,
                }
            });
        });
    </script>
    </body>

    </html>