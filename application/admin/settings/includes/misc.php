<div class="horizontal-scrollable-tabs">
    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
            <li role="presentation" class="nav-item active">
                <a href="#misc" class="nav-item" aria-controls="misc" role="tab" data-toggle="tab">
                    <i class="fa fa-cog"></i> <?php echo _l('settings_group_misc'); ?></a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#settings_tables" class="nav-link" aria-controls="settings_tables" role="tab" data-toggle="tab">
                    <i class="fa fa-table"></i> <?php echo _l('tables'); ?></a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#inline_create" class="nav-link" aria-controls="inline_create" role="tab" data-toggle="tab">
                    <i class="fa fa-plus"></i> <?php echo _l('inline_create'); ?>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#set_recaptcha" class="nav-link" aria-controls="set_recaptcha" role="tab" data-toggle="tab">
                    <i class="fa fa-google"></i> <?php echo _l('re_captcha'); ?></a>
            </li>
        </ul>
    </div>
    <div class="tab-content mt-5">
        <div role="tabpanel" class="tab-pane active" id="misc">
            <?php echo render_yes_no_option('view_contract_only_logged_in', 'settings_require_client_logged_in_to_view_contract'); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_input('settings[google_api_key]', 'settings_google_api', get_option('google_api_key')); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_input('settings[dropbox_app_key]', 'dropbox_app_key', get_option('dropbox_app_key')); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_input('settings[media_max_file_size_upload]', 'settings_media_max_file_size_upload', get_option('media_max_file_size_upload'), 'number'); ?>
            <hr class="mt-4 mb-4"/>
            <i class="fa fa-question-circle pull-left" data-toggle="tooltip"
               data-title="<?php echo _l('settings_group_newsfeed'); ?>"></i>
            <?php echo render_input('settings[newsfeed_maximum_files_upload]', 'settings_newsfeed_max_file_upload_post', get_option('newsfeed_maximum_files_upload'), 'number'); ?>
            <hr class="mt-4 mb-4"/>

            <?php echo render_input('settings[limit_top_search_bar_results_to]', 'settings_limit_top_search_bar_results', get_option('limit_top_search_bar_results_to'), 'number'); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_select('settings[default_staff_role]', $roles, array('roleid', 'name'), 'settings_general_default_staff_role', get_option('default_staff_role'), array(), array('data-toggle' => 'tooltip', 'title' => 'settings_general_default_staff_role_tooltip')); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_input('settings[delete_activity_log_older_then]', 'delete_activity_log_older_then', get_option('delete_activity_log_older_then'), 'number'); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_yes_no_option('show_setup_menu_item_only_on_hover', 'show_setup_menu_item_only_on_hover'); ?>


            <hr class="mt-4 mb-4"/>
            <?php echo render_yes_no_option('show_help_on_setup_menu', 'show_help_on_setup_menu'); ?>
            <hr class="mt-4 mb-4"/>
            <?php render_yes_no_option('use_minified_files', 'use_minified_files'); ?>
        </div>

        <div role="tabpanel" class="tab-pane" id="settings_tables">
            <?php echo render_yes_no_option('scroll_responsive_tables', 'scroll_responsive_tables', 'scroll_responsive_tables_help'); ?>
            <hr class="mt-4 mb-4"/>
            <div class="form-group">
                <label for="save_last_order_for_tables" class="control-label clearfix">
                    <i class="fa fa-question-circle pointer" data-toggle="popover" data-html="true"
                       data-content="Currently supported tables: Customers, Leads, Tickets, Tasks, Projects, Payments, Subscriptions, Expenses, Proposals, Knowledge Base, Contracts <br /><br /> Note: Changing this option will delete all saved table orders!"
                       data-position="top"></i> <?php echo _l('save_last_order_for_tables'); ?>
                </label>

                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" id="y_opt_1_save_last_order_for_tables" class="form-check-input"
                               name="settings[save_last_order_for_tables]"
                               value="1"<?php if (get_option('save_last_order_for_tables') == '1') {
                            echo ' checked';
                        } ?>>
                        <span class="radio-icon"></span>
                        <span><?php echo _l('settings_yes'); ?></span>
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" id="y_opt_2_save_last_order_for_tables" class="form-check-input"
                               name="settings[save_last_order_for_tables]"
                               value="0" <?php if (get_option('save_last_order_for_tables') == '0') {
                            echo ' checked';
                        } ?>>
                        <span class="radio-icon"></span>
                        <span><?php echo _l('settings_no'); ?></span>
                    </label>
                </div>

            </div>
            <hr class="mt-4 mb-4"/>

            <div class="form-group">
                <label><?php echo _l('show_table_export_button'); ?></label><br/>

                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" id="stbxb_all" name="settings[show_table_export_button]" class="form-check-input"
                               value="to_all"<?php if (get_option('show_table_export_button') == 'to_all') {
                            echo ' checked';
                        } ?>>
                        <span class="radio-icon"></span>
                        <span><?php echo _l('show_table_export_all'); ?></span>
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" id="stbxb_admins" name="settings[show_table_export_button]" class="form-check-input"
                               value="only_admins"<?php if (get_option('show_table_export_button') == 'only_admins') {
                            echo ' checked';
                        } ?>>
                        <span class="radio-icon"></span>
                        <span><?php echo _l('show_table_export_admins'); ?></span>
                    </label>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" id="stbxb_hide" name="settings[show_table_export_button]" class="form-check-input"
                               value="hide"<?php if (get_option('show_table_export_button') == 'hide') {
                            echo ' checked';
                        } ?>>
                        <span class="radio-icon"></span>
                        <span><?php echo _l('show_table_export_hide'); ?></span>
                    </label>
                </div>
            </div>
            <hr class="mt-4 mb-4"/>
            <?php echo render_input('settings[tables_pagination_limit]', 'settings_general_tables_limit', get_option('tables_pagination_limit'), 'number'); ?>
            <hr class="mt-4 mb-4"/>

        </div>

        <div role="tabpanel" class="tab-pane" id="set_recaptcha">
            <?php echo render_input('settings[recaptcha_site_key]', 'recaptcha_site_key', get_option('recaptcha_site_key')); ?>
            <?php echo render_input('settings[recaptcha_secret_key]', 'recaptcha_secret_key', get_option('recaptcha_secret_key')); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_yes_no_option('use_recaptcha_customers_area', 'use_recaptcha_customers_area'); ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="inline_create">
            <?php echo render_yes_no_option('staff_members_create_inline_lead_status', _l('inline_create_option', array(
                '<b>' . _l('lead_status') . '</b>',
                '<b>' . _l('lead') . '</b>'
            ))); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_yes_no_option('staff_members_create_inline_lead_source', _l('inline_create_option', array(
                '<b>' . _l('lead_source') . '</b>',
                '<b>' . _l('lead') . '</b>'
            ))); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_yes_no_option('staff_members_create_inline_customer_groups', _l('inline_create_option', array(
                '<b>' . _l('customer_group') . '</b>',
                '<b>' . _l('client') . '</b>'
            ))); ?>
            <hr class="mt-4 mb-4"/>
            <?php if (get_option('services') == 1) { ?>
                <?php echo render_yes_no_option('staff_members_create_inline_ticket_services', _l('inline_create_option', array(
                    '<b>' . _l('service') . '</b>',
                    '<b>' . _l('ticket') . '</b>'
                ))); ?>
                <hr class="mt-4 mb-4"/>
            <?php } ?>
            <?php echo render_yes_no_option('staff_members_save_tickets_predefined_replies', _l('inline_create_option_predefined_replies')); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_yes_no_option('staff_members_create_inline_contract_types', _l('inline_create_option', array(
                '<b>' . _l('contract_type') . '</b>',
                '<b>' . _l('contract') . '</b>'
            ))); ?>
            <hr class="mt-4 mb-4"/>
            <?php echo render_yes_no_option('staff_members_create_inline_expense_categories', _l('inline_create_option', array(
                '<b>' . _l('expense_category') . '</b>',
                '<b>' . _l('expense') . '</b>'
            ))); ?>
        </div>
    </div>
