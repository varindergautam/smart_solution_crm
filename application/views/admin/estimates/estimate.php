<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            echo form_open($this->uri->uri_string(), ['id' => 'estimate-form', 'class' => '_transaction_form estimate-form']);
            if (isset($estimate)) {
                echo form_hidden('isedit');
            }
            ?>
            <div class="col-md-12">
                <h4
                    class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                    <span>
                        <?php echo isset($estimate) ? format_estimate_number($estimate) : _l('create_new_estimate'); ?>
                    </span>
                    <?php echo isset($estimate) ? format_estimate_status($estimate->status) : ''; ?>
                </h4>
                <?php $this->load->view('admin/estimates/estimate_template'); ?>
            </div>
            <?php echo form_close(); ?>
            <?php $this->load->view('admin/invoice_items/item'); ?>
        </div>
    </div>
</div>
</div>
<?php init_tail(); ?>
<script>
$(function() {
    validate_estimate_form();
    // Init accountacy currency symbol
    init_currency();
    // Project ajax search
    init_ajax_project_search_by_customer_id();
    // Maybe items ajax search
    init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');

    // jQuery code
    $(document).ready(function () {
        
        $('#item_select').change(function () {
            var selectedValue = $(this).val();
            $.ajax({
                url: admin_url+'/estimates/supplier_items/'+selectedValue, // Replace with your actual API endpoint
                type: 'GET',
                data: {},
                dataType: "json",  
                success: function (dynamicData) {
                    
                    if (Array.isArray(dynamicData) && dynamicData.length > 0) {
                        // Update the content of the table body
                        updateTable(dynamicData);
            
                    } else {
                        console.error('Error: Invalid or empty response received:', dynamicData);
                    }
                    
                    // Show the modal
                    // $('#dynamicModal').modal('show');
                },
                error: function (error) {
                    console.error('Error fetching dynamic data:', error);
                }
            });
        });

        function updateTable(dynamicData) {
            // Clear existing rows
            $('#dynamicTableBody').empty();

            // Append new rows based on the dynamic data
            dynamicData.forEach(function (rowData) {
                var row = {
                    "supplier_item_id": rowData.id,
                    "supplier_id": rowData.supplier_id,
                    "item_id": rowData.item_id,
                    "rate": rowData.rate,
                    "date": rowData.date
                };

                var jsonString = JSON.stringify(row).replace(/"/g, '&quot;');

                var htmlRow  = '<tr>';
                htmlRow  += '<td><input type="hidden" name="supplier_id"  value="' + rowData.supplier_id + '"><input type="radio" name="supplier_item_data_checkbox" class="supplier_item_data_checkbox" value="' + jsonString + '"></td>';
                htmlRow  += '<td>' + rowData.company + '</td>';
                htmlRow  += '<td>' + rowData.description + '</td>';
                htmlRow  += '<td>' + rowData.rate + '</td>';
                htmlRow += '<td>' + rowData.date + '</td>';
                htmlRow  += '</tr>';
             
                $('#dynamicTableBody').append(htmlRow );
            });
            
        }
    });
});
</script>
</body>

</html>
