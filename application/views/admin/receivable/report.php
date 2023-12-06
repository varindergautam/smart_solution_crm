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
                                Receivable Report
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                                <?php $value = (isset($month) ? $month : ''); ?>
                                <?php echo render_input('month', 'Month', $value, 'month'); ?>
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
            if (isset($month)) {
            ?>
                <div class="col-md-12" id="small-table">
                    <div class="panel_s">
                        <div class="panel-body panel-table-full">
                            <?php
                            $table_data = [
                                _l('ID'),
                                _l('Customer Name'),
                                _l('Invoice No.'),
                                _l('Invoice Date'),
                                _l('Invoice Due Date'),
                                _l('Amount Receivable'),
                                _l('Date of PDC'),
                                _l('Cheque Number'),
                                _l('Bank'),
                                _l('Cheque Amount'),
                                _l('Remark'),
                                _l('Paid'),
                            ];

                            render_datatable($table_data, 'receivable');
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
        initDataTable('.table-receivable', window.location.href);
    });
</script>
</body>

</html>