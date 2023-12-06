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
                                Budget
                            </h3>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <div class="form-group">
                                    <div class="form-group select-placeholder">
                                        <label for="financial_year" class="control-label"><?php echo _l('Financial Year'); ?></label>
                                        <select name="financial_year" data-live-search="true" id="financial_year" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                            <option value=""><?php echo _l('system_default_string'); ?></option>
                                            <?php foreach ($financial_years as $financial_year) {
                                                $selected = '';
                                                if (isset($member)) {
                                                    if ($member->financial_year == $financial_year['id']) {
                                                        $selected = 'selected';
                                                    }
                                                } ?>
                                                <option value="<?php echo $financial_year['id']; ?>" <?php echo $selected; ?>>
                                                    <?php echo ucfirst($financial_year['year_name']); ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group select-placeholder">
                                        <label for="head" class="control-label"><?php echo _l('Head'); ?></label>
                                        <select name="head" data-live-search="true" id="head" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                            <option value=""><?php echo _l('system_default_string'); ?></option>
                                            <?php foreach ($heads as $head) {
                                                $selected = '';
                                                if (isset($member)) {
                                                    if ($member->head == $head['id']) {
                                                        $selected = 'selected';
                                                    }
                                                } ?>
                                                <option value="<?php echo $head['id']; ?>" <?php echo $selected; ?>>
                                                    <?php echo ucfirst($head['head_name']); ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <?php $value = (isset($member) ? $member->head_type : ''); ?>
                                <?php echo render_input('head_type', 'Head Type', $value, 'text', ['readonly' => 'readonly']); ?>

                                <?php $value = (isset($member) ? $member->amount : ''); ?>
                                <?php echo render_input('amount', 'Amount', $value, 'text'); ?>

                                <?php $value = (isset($member) ? $member->into_month : ''); ?>
                                <?php echo render_input('into_month', 'Divide into months', $value, 'text'); ?>

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
                financial_year: 'required',
                head: 'required',
                amount: 'required',
                into_month: 'required',
            });
        });

        $(document).ready(function() {
            $('#head').change(function() {
                var selectedValue = $(this).val();
                $.ajax({
                    url: admin_url + 'head/headJson/' + selectedValue,
                    type: 'GET',
                    data: {},
                    dataType: "json",
                    success: function(response) {
                        $('#head_type').val(response.type);
                    },
                    error: function(error) {
                        console.error('Error fetching dynamic data:', error);
                    }
                });
            });
        });
    </script>

    </body>

    </html>