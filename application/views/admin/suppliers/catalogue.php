<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open_multipart(admin_url('suppliers/upload_files'), ['class' => 'staff-form', 'autocomplete' => 'off', 'method' => 'post']); ?>
            <input type="hidden" value="<?php echo $supplier_id; ?>" name="supplier_id">
            <div class="col-md-12" id="small-table">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="horizontal-tabs">
                            <h4>
                                Upload Catalogue
                            </h4>
                            <hr>
                        </div>
                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">
                                <div class="form-group">
                                    <input type="file" id="catalogue" name="catalogue[]" multiple>
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

        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {});
</script>
</body>

</html>