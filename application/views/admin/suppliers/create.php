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
                            <?php echo $member->firstname . ' ' . $member->lastname; ?>
                            
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

                                <?php if ((isset($member) && $member->profile_image == null) || !isset($member)) { ?>
                                    <div class="form-group">
                                        <label for="profile_image" class="profile-image"><?php echo _l('staff_edit_profile_image'); ?></label>
                                        <input type="file" name="profile_image" class="form-control" id="profile_image">
                                    </div>
                                <?php } ?>
                                <?php if (isset($member) && $member->profile_image != null) { ?>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <?php echo supplier_profile_image($member->supplierid, ['img', 'img-responsive', 'staff-profile-image-thumb'], 'thumb'); ?>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <a href="<?php echo admin_url('suppliers/remove_supplier_profile_image/' . $member->supplierid); ?>"><i class="fa fa-remove"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php $value = (isset($member) ? $member->firstname : ''); ?>
                                <?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
                                <?php echo render_input('firstname', 'staff_add_edit_firstname', $value, 'text', $attrs); ?>
                                <?php $value = (isset($member) ? $member->lastname : ''); ?>
                                <?php echo render_input('lastname', 'staff_add_edit_lastname', $value); ?>
                                <?php $value = (isset($member) ? $member->email : ''); ?>
                                <?php echo render_input('email', 'staff_add_edit_email', $value, 'email', ['autocomplete' => 'off']); ?>

                                <?php $value = (isset($member) ? $member->phonenumber : ''); ?>
                                <?php echo render_input('phonenumber', 'staff_add_edit_phonenumber', $value); ?>
                                <div class="form-group">
                                    <label for="facebook" class="control-label"><i class="fa-brands fa-facebook-f"></i>
                                        <?php echo _l('staff_add_edit_facebook'); ?></label>
                                    <input type="text" class="form-control" name="facebook" value="<?php if (isset($member)) {
                                            echo $member->facebook;
                                    } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="linkedin" class="control-label"><i class="fa-brands fa-linkedin-in"></i>
                                        <?php echo _l('staff_add_edit_linkedin'); ?></label>
                                    <input type="text" class="form-control" name="linkedin" value="<?php if (isset($member)) {
                                                                                                        echo $member->linkedin;
                                                                                                    } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="skype" class="control-label"><i class="fa-brands fa-skype"></i>
                                        <?php echo _l('staff_add_edit_skype'); ?></label>
                                    <input type="text" class="form-control" name="skype" value="<?php if (isset($member)) {
                                                                                                    echo $member->skype;
                                                                                                } ?>">
                                </div>
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

                                <?php $rel_id = (isset($member) ? $member->supplierid : false); ?>
                                <?php echo render_custom_fields('staff', $rel_id); ?>

                                <div class="row">
                                    <div class="col-md-12">

                                        <?php if (!isset($member) && is_email_template_active('new-staff-created')) { ?>
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="send_welcome_email" id="send_welcome_email" checked>
                                                <label for="send_welcome_email"><?php echo _l('staff_send_welcome_email'); ?></label>
                                            </div>
                                        <?php } ?>
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
                firstname: 'required',
                lastname: 'required',
                username: 'required',
                password: {
                    required: {
                        depends: function(element) {
                            return ($('input[name="isedit"]').length == 0) ? true : false
                        }
                    }
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: admin_url + "misc/supplier_email_exists",
                        type: 'post',
                        data: {
                            email: function() {
                                return $('input[name="email"]').val();
                            },
                            memberid: function() {
                                return $('input[name="memberid"]').val();
                            }
                        }
                    }
                },
                phonenumber: {
                    required: true,
                    number: true,
                }
            });
        });
    </script>
    </body>

    </html>