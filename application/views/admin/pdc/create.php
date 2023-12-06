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
                                Pdc
                            </h3>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <div class="form-group select-placeholder">
                                    <label for="type" class="control-label"><?php echo _l('Type'); ?></label>
                                    <select name="type" data-live-search="true" id="type" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value="">Select Type</option>
                                        <option value="in" <?php echo isset($member) && $member->type == 'in' ? 'selected' : '' ?>>In</option>
                                        <option value="out" <?php echo isset($member) && $member->type == 'out' ? 'selected' : '' ?>>Out</option>
                                    </select>
                                </div>

                                <?php $value = (isset($member) ? $member->date : date('Y-m-d')); ?>
                                <?php echo render_input('date', 'Entry Date', $value, 'date'); ?>

                                <?php $value = (isset($member) ? $member->particular : ''); ?>
                                <?php echo render_input('particular', 'Particular', $value, 'text'); ?>
                                

                                <?php $value = (isset($member) ? $member->cheque_number : ''); ?>
                                <?php echo render_input('cheque_number', 'Cheque Number', $value, 'text'); ?>

                                <?php $value = (isset($member) ? $member->cheque_date : ''); ?>
                                <?php echo render_input('cheque_date', 'Cheque Date', $value, 'date'); ?>

                                <?php $value = (isset($member) ? $member->amount : ''); ?>
                                <?php echo render_input('amount', 'Amount', $value, 'text'); ?>

                                <?php $value = (isset($member) ? $member->bank_number : ''); ?>
                                <?php echo render_input('bank_number', 'Bank Number', $value, 'text'); ?>

                                <?php $value = (isset($member) ? $member->remark : ''); ?>
                                <?php echo render_input('remark', 'Remarks', $value, 'text'); ?>

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
                type: 'required',
                particular: 'required',
                cheque_date: 'required',
                amount: 'required',
            });

        });
    </script>

    </body>

    </html>