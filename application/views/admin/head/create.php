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
                                Head
                            </h3>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <?php $value = (isset($member) ? $member->head_name : ''); ?>
                                <?php echo render_input('head_name', 'Head Name', $value, 'text'); ?>

                                <div class="form-group">
                                    <div class="form-group select-placeholder">
                                        <label for="type" class="control-label"><?php echo _l('Type'); ?></label>
                                        <select name="type" data-live-search="true" id="type" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                            <option value=""><?php echo _l('system_default_string'); ?></option>
                                            <option value="income" <?php echo isset($member) && $member->type == 'income' ? 'selected' : '' ?>>Income</option>
                                            <option value="expense" <?php echo isset($member) && $member->type == 'expense' ? 'selected' : '' ?>>Expense</option>
                                        </select>
                                    </div>
                                </div>

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
                head_name: 'required',
                type: 'required',
            });
        });
    </script>

    </body>

    </html>