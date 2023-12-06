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
                                Payable
                            </h3>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <div class="form-group select-placeholder">
                                    <label for="supplier_id" class="control-label"><?php echo _l('Supplier'); ?></label>
                                    <select name="supplier_id" data-live-search="true" id="supplier" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""><?php echo _l('Select Supplier'); ?></option>
                                        <?php foreach ($suppliers as $supplier) {
                                            $selected = '';
                                            if (isset($member)) {
                                                if ($member->supplier_id == $supplier['supplierid']) {
                                                    $selected = 'selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $supplier['supplierid']; ?>" <?php echo $selected; ?>>
                                                <?php echo ucfirst($supplier['company']); ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div>

                                <?php $value = (isset($member) ? $member->supplier_name : ''); ?>
                                <?php echo render_input('supplier_name', 'Supplier Name', $value, 'text', ['readonly' => 'readonly']); ?>

                                <?php $value = (isset($member) ? $member->company_name : ''); ?>
                                <?php echo render_input('company_name', 'Company', $value, 'text', ['readonly' => 'readonly']); ?>

                                <?php $value = (isset($member) ? $member->supplier_mobile : ''); ?>
                                <?php echo render_input('supplier_mobile', 'Supplier Mobile', $value, 'text', ['readonly' => 'readonly']); ?>

                                <?php $value = (isset($member) ? $member->supplier_email : ''); ?>
                                <?php echo render_input('supplier_email', 'Supplier Email', $value, 'text', ['readonly' => 'readonly']); ?>

                                <?php $value = (isset($member) ? $member->supplier_city : ''); ?>
                                <?php echo render_input('supplier_city', 'Supplier City', $value, 'text', ['readonly' => 'readonly']); ?>

                                <?php $value = (isset($member) ? $member->entry_date : date('Y-m-d')); ?>
                                <?php echo render_input('entry_date', 'Entry Date', $value, 'date'); ?>

                                <?php $value = (isset($member) ? $member->invoice_date : ''); ?>
                                <?php echo render_input('invoice_date', 'Invoice Date', $value, 'date'); ?>

                                <?php $value = (isset($member) ? $member->invoice_number : ''); ?>
                                <?php echo render_input('invoice_number', 'Invoice Number', $value, 'text'); ?>

                                <?php $value = (isset($member) ? $member->invoice_amount : ''); ?>
                                <?php echo render_input('invoice_amount', 'Invoice Amount', $value, 'text'); ?>

                                <?php $value = (isset($member) ? $member->invoice_due_date : ''); ?>
                                <?php echo render_input('invoice_due_date', 'Invoice Due Date', $value, 'date'); ?>

                                <?php $value = (isset($member) ? $member->remarks : ''); ?>
                                <?php echo render_input('remarks', 'Remarks', $value, 'text'); ?>

                                <div class="form-check">
                                    <label class="form-check-label" for="pdc">
                                        <?php echo _l('PDC'); ?>
                                    </label>
                                    <input class="form-check-input" type="checkbox" value="1" id="pdc" name="pdc" <?php echo isset($member) && $member->pdc == 1 ? 'checked' : ''; ?>>
                                </div>

                                <div class="pdc_section" style="<?php echo isset($member->pdc) && $member->pdc == 1 ? 'display:block' : 'display:none'; ?>">
                                    <input type="hidden" value="<?php echo isset($member) ? $member->pdcID : ''; ?>" name="pdcID">
                                    <?php $value = (isset($member) ? $member->cheque_number : ''); ?>
                                    <?php echo render_input('cheque_number', 'Cheque Number', $value, 'text'); ?>

                                    <?php $value = (isset($member) ? $member->cheque_date : ''); ?>
                                    <?php echo render_input('cheque_date', 'Cheque Date', $value, 'date'); ?>

                                    <?php $value = (isset($member) ? $member->amount : ''); ?>
                                    <?php echo render_input('amount', 'Amount', $value, 'text'); ?>

                                    <?php $value = (isset($member) ? $member->bank_number : ''); ?>
                                    <?php echo render_input('bank_number', 'Bank Name', $value, 'text'); ?>
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
                supplier_id: 'required',
                invoice_amount: 'required',
                invoice_due_date: 'required',
            });


            $(document).ready(function() {

                $('#pdc').change(function() {
                    var isChecked = $('#pdc').prop('checked');
                    if (isChecked) {
                        $('.pdc_section').show();
                        appValidateForm($('.staff-form'), {
                            amount: 'required',
                            supplier_id: 'required',
                            invoice_amount: 'required',
                            invoice_due_date: 'required',
                        });
                    } else {
                        $('.pdc_section').hide();
                        appValidateForm($('.staff-form'), {
                            supplier_id: 'required',
                            invoice_amount: 'required',
                            invoice_due_date: 'required',
                        });
                    }
                });

                $('#supplier').change(function() {
                    var selectedValue = $(this).val();
                    $.ajax({
                        url: admin_url + '/suppliers/supplierJson/' + selectedValue,
                        type: 'GET',
                        data: {},
                        dataType: "json",
                        success: function(response) {
                            $('#company_name').val(response.company);
                            $('#supplier_name').val(response.name);
                            $('#supplier_mobile').val(response.phone_number);
                            $('#supplier_email').val(response.email);
                            $('#supplier_city').val(response.city);

                        },
                        error: function(error) {
                            console.error('Error fetching dynamic data:', error);
                        }
                    });
                });
            });
        });
    </script>
    </body>

    </html>