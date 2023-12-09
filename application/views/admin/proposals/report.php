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

                                <div class="form-group ">
                                    <label >Supplier</label>
                                    <select name="supplier" id="supplier" class="form-control " >
                                        <option value="">Select Supplier</option>
                                        <?php 
                                        if(isset($suppliers)){
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
                                        } } ?>
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

            $('#date').change(function() {
                var selectedValue = $(this).val();
                console.log(selectedValue);

                $.ajax({
                    url: admin_url + 'proposals/getSupplierData/' + selectedValue,
                    type: 'GET',
                    data: {},
                    dataType: "json",
                    success: function(response) {
                        // Assuming response is an array of supplier data
                        //var supplierSelect = $('#supplier');

                        // Clear existing options
                        $('#supplier').empty();

                        // Add default option
                        //supplierSelect.append('<option value="">Select Supplier</option>');


                        var htmlRow = '<option value="">Select Supplier</option>';

                        // Add options based on the response
                        $.each(response, function(index, supplier) {
                            htmlRow += '<option value="' + supplier.supplierid + '">' + supplier.company + '</option>';
                        });
                        $('#supplier').append(htmlRow);
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