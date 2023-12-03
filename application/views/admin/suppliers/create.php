<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php if (isset($member)) { ?>
            <?php //$this->load->view('admin/staff/stats'); 
            ?>
            <div class="member">
                <?php echo form_hidden('isedit'); ?>
                <?php echo form_hidden('memberid', $member->supplierid); ?>
            </div>
        <?php } ?>
        <div class="row">
            <?php if (isset($member)) { ?>
                <div class="col-md-12">

                    <div class="tw-flex tw-justify-between">
                        <h4 class="tw-mb-0 tw-font-semibold tw-text-lg tw-text-neutral-700">

                        </h4>
                        <a href="#" onclick="small_table_full_view(); return false;" data-placement="left" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>" class="toggle_view tw-mt-3 tw-shrink-0 tw-inline-flex tw-items-center tw-justify-center hover:tw-text-neutral-800 active:tw-text-neutral-800 hover:tw-bg-neutral-300 tw-h-10 tw-w-10 tw-rounded-full tw-bg-neutral-200 tw-text-neutral-500">
                            <i class="fa fa-expand"></i></a>
                    </div>
                </div>
            <?php } ?>
            <?php echo form_open_multipart($this->uri->uri_string(), ['class' => 'staff-form', 'autocomplete' => 'off']); ?>
            <div class="col-md-<?php if (!isset($member)) {
                                    echo '8 col-md-offset-2';
                                } else {
                                    echo '8 col-md-offset-2';
                                } ?>" id="small-table">
                <div class="panel_s">
                    <div class="panel-body ">

                        <div class="tab-content tw-mt-5">
                            <div role="tabpanel" class="tab-pane active" id="tab_staff_profile">

                                <?php $value = (isset($member) ? $member->company : ''); ?>
                                <?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
                                <?php echo render_input('company', 'Company', $value, 'text', $attrs); ?>

                                <?php $value = (isset($member) ? $member->vat_number : ''); ?>
                                <?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
                                <?php echo render_input('vat_number', 'VAT Number', $value, 'text', $attrs); ?>

                                <?php $value = (isset($member) ? $member->phone_number : ''); ?>
                                <?php echo render_input('phone_number', 'Phone', $value); ?>

                                <?php $value = (isset($member) ? $member->website : ''); ?>
                                <?php echo render_input('website', 'Website', $value); ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group select-placeholder">
                                                <label for="currency" class="control-label"><?php echo _l('currency'); ?></label>
                                                <select name="currency" data-live-search="true" id="currency" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                    <option value=""><?php echo _l('system_default_string'); ?></option>
                                                    <?php foreach ($currencies as $currency) {
                                                        $selected = '';
                                                        if (isset($member)) {
                                                            if ($member->currency == $currency['id']) {
                                                                $selected = 'selected';
                                                            }
                                                        } ?>
                                                        <option value="<?php echo $currency['id']; ?>" <?php echo $selected; ?>>
                                                            <?php echo ucfirst($currency['name']); ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (!is_language_disabled()) { ?>
                                            <div class="form-group select-placeholder">
                                                <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?></label>
                                                <select name="default_language" data-live-search="true" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                    <option value=""><?php echo _l('system_default_string'); ?></option>
                                                    <?php foreach ($this->app->get_available_languages() as $availableLanguage) {
                                                        $selected = '';
                                                        if (isset($member)) {
                                                            if ($member->default_language == $availableLanguage) {
                                                                $selected = 'selected';
                                                            }
                                                        } ?>
                                                        <option value="<?php echo $availableLanguage; ?>" <?php echo $selected; ?>>
                                                            <?php echo ucfirst($availableLanguage); ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="address" class="form-control"><?php echo (isset($member) ? $member->address : ''); ?></textarea>
                                </div>

                                <?php $value = (isset($member) ? $member->city : ''); ?>
                                <?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
                                <?php echo render_input('city', 'City', $value, 'text', $attrs); ?>

                                <?php $value = (isset($member) ? $member->state : ''); ?>
                                <?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
                                <?php echo render_input('state', 'State', $value, 'text', $attrs); ?>

                                <?php $value = (isset($member) ? $member->zip_code : ''); ?>
                                <?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
                                <?php echo render_input('zip_code', 'Zip Code', $value, 'text', $attrs); ?>

                                <div class="form-group select-placeholder">
                                    <label for="country" class="control-label"><?php echo _l('Country'); ?></label>
                                    <select name="country" data-live-search="true" id="country" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""><?php echo _l('system_default_string'); ?></option>
                                        <?php foreach ($countries as $country) {
                                            $selected = '';
                                            if (isset($member)) {
                                                if ($member->country == $country['country_id']) {
                                                    $selected = 'selected';
                                                }
                                            } ?>
                                            <option value="<?php echo $country['country_id']; ?>" <?php echo $selected; ?>>
                                                <?php echo ucfirst($country['short_name']); ?></option>
                                        <?php
                                        } ?>
                                    </select>
                                </div>

                                <?php $rel_id = (isset($member) ? $member->supplierid : false); ?>
                                <?php echo render_custom_fields('staff', $rel_id); ?>

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
                vat_number: 'required',
                company: 'required',
                phonenumber: {
                    required: true,
                    number: true,
                }
            });
        });
    </script>
    </body>

    </html>