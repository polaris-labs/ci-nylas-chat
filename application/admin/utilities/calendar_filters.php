<div id="calendar_filters" class="mt-4 mb-4" style="<?php if (!$this->input->post('calendar_filters')) {
    echo 'display:none;';
} ?>">
    <?php echo form_open(); ?>
    <?php echo form_hidden('calendar_filters', true); ?>
    <div class="row">
        <div class="col-md-3">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="1" name="events"
                           id="cf_events"<?php if ($this->input->post('events')) {
                        echo ' checked';
                    } ?>>
                    <span class="checkbox-icon"></span>
                    <span class="form-check-description"><?php echo _l('events'); ?></span>
                </label>
            </div>
            <?php if (get_option('show_tasks_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="tasks"
                               id="cf_tasks"<?php if ($this->input->post('tasks')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('tasks'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (get_option('show_projects_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="projects"
                               id="cf_projects"<?php if ($this->input->post('projects')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('projects'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (get_option('show_invoices_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="invoices"
                               id="cf_invoices"<?php if ($this->input->post('invoices')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('invoices'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (get_option('show_estimates_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="estimates"
                               id="cf_estimates"<?php if ($this->input->post('estimates')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('estimates'); ?></span>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-3">
            <?php if (get_option('show_proposals_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="proposals"
                               id="cf_proposals"<?php if ($this->input->post('proposals')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('proposals'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (get_option('show_contracts_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="contracts"
                               id="cf_contracts"<?php if ($this->input->post('contracts')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('contracts'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (get_option('show_customer_reminders_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="customer_reminders"
                               id="cf_customers_reminders"<?php if ($this->input->post('customer_reminders')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('show_customer_reminders_on_calendar'); ?></span>
                    </label>
                </div>
            <?php } ?>

            <?php if (get_option('show_expense_reminders_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="expense_reminders"
                               id="cf_expenses_reminders"<?php if ($this->input->post('expense_reminders')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('calendar_expense_reminder'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (get_option('show_lead_reminders_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="lead_reminders"
                               id="cf_leads_reminders"<?php if ($this->input->post('lead_reminders')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('show_lead_reminders_on_calendar'); ?></span>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-3">

            <?php if (get_option('show_estimate_reminders_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="estimate_reminders"
                               id="cf_estimates_reminders"<?php if ($this->input->post('estimate_reminders')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('show_estimate_reminders_on_calendar'); ?></span>
                    </label>
                </div>
            <?php } ?>

            <?php if (get_option('show_invoice_reminders_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="invoice_reminders"
                               id="cf_invoices_reminders"<?php if ($this->input->post('invoice_reminders')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('show_invoice_reminders_on_calendar'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (get_option('show_credit_note_reminders_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="credit_note_reminders"
                               id="cf_credit_note_reminders"<?php if ($this->input->post('credit_note_reminders')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('show_credit_note_reminders_on_calendar'); ?></span>
                    </label>
                </div>
            <?php } ?>
            <?php if (get_option('show_proposal_reminders_on_calendar') == 1) { ?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" name="proposal_reminders"
                               id="cf_proposal_reminders"<?php if ($this->input->post('proposal_reminders')) {
                            echo ' checked';
                        } ?>>
                        <span class="checkbox-icon"></span>
                        <span class="form-check-description"><?php echo _l('show_proposal_reminders_on_calendar'); ?></span>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-3 text-right">
            <a class="btn btn-default"
               href="<?php echo site_url($this->uri->uri_string()); ?>"><?php echo _l('clear'); ?></a>
            <button class="btn btn-success" type="submit"><?php echo _l('apply'); ?></button>
        </div>

    </div>
    <hr class="mbot15"/>
    <div class="clearfix"></div>
    <?php echo form_close(); ?>
</div>
