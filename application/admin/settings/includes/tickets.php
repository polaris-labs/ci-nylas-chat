<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="nav-item active">
    <a href="#set_tickets_general" class="nav-item" aria-controls="set_tickets_general" role="tab" data-toggle="tab"><?php echo _l('settings_group_general'); ?></a>
  </li>
  <li role="presentation" class="nav-item">
    <a href="#set_tickets_piping" class="nav-item" aria-controls="set_tickets_piping" role="tab" data-toggle="tab"><?php echo _l('tickets_piping'); ?></a>
  </li>
  <li role="presentation" class="nav-item">
    <a href="#ticket_form" class="nav-item" aria-controls="ticket_form" role="tab" data-toggle="tab"><?php echo _l('ticket_form'); ?></a>
  </li>
</ul>
<div class="tab-content mt-5">
  <div role="tabpanel" class="tab-pane active" id="set_tickets_general">
    <?php render_yes_no_option('services','settings_tickets_use_services'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('staff_access_only_assigned_departments','settings_tickets_allow_departments_access'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('receive_notification_on_new_ticket','receive_notification_on_new_ticket', 'receive_notification_on_new_ticket_help'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('staff_members_open_tickets_to_all_contacts','staff_members_open_tickets_to_all_contacts','staff_members_open_tickets_to_all_contacts_help'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('access_tickets_to_none_staff_members','access_tickets_to_none_staff_members'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('allow_non_admin_staff_to_delete_ticket_attachments','allow_non_admin_staff_to_delete_ticket_attachments'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('allow_customer_to_change_ticket_status','allow_customer_to_change_ticket_status'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('only_show_contact_tickets','only_show_contact_tickets'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('ticket_replies_order','ticket_replies_order','ticket_replies_order_notice',_l('order_ascending'),_l('order_descending'),'asc','desc'); ?>
    <hr class="mt-4 mb-4" />
    <?php
      $this->load->model('tickets_model');
      $statuses = $this->tickets_model->get_ticket_status();
      $statuses['callback_translate'] = 'ticket_status_translate';
      echo render_select('settings[default_ticket_reply_status]',$statuses,array('ticketstatusid','name'),'default_ticket_reply_status',get_option('default_ticket_reply_status'),array(),array(),'','',false); ?>
    <hr class="mt-4 mb-4" />
    <?php echo render_input('settings[maximum_allowed_ticket_attachments]','settings_tickets_max_attachments',get_option('maximum_allowed_ticket_attachments'),'number'); ?>
    <hr class="mt-4 mb-4" />
    <?php echo render_input('settings[ticket_attachments_file_extensions]','settings_tickets_allowed_file_extensions',get_option('ticket_attachments_file_extensions')); ?>
  </div>
  <div role="tabpanel" class="tab-pane" id="set_tickets_piping">
    cPanel forwarder path: <code><?php echo do_action('cpanel_tickets_forwarder_path',FCPATH .'pipe.php'); ?></code>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('email_piping_only_registered','email_piping_only_registered'); ?>
    <hr class="mt-4 mb-4" />
    <?php render_yes_no_option('email_piping_only_replies','email_piping_only_replies'); ?>
    <hr class="mt-4 mb-4" />
    <?php echo render_select('settings[email_piping_default_priority]',$ticket_priorities,array('priorityid','name'),'email_piping_default_priority',get_option('email_piping_default_priority')); ?>
  </div>
  <div role="tabpanel" class="tab-pane" id="ticket_form">
    <h4 class="bold">Form Info</h4>
    <p><b>Form url:</b>
      <a href="<?php echo site_url('forms/ticket'); ?>" target="_blank">
        <?php echo site_url('forms/ticket'); ?>
      </a>
    </p>
    <p><b>Form file location:</b> <code><?php echo do_action('ticket_form_file_location_settings',VIEWPATH.'forms\ticket.php'); ?></code></p>
    <hr class="mt-4 mb-4" />
    <h4 class="bold">Embed form</h4>
    <p><?php echo _l('form_integration_code_help'); ?></p>
    <textarea readonly class="form-control mb-4" rows="5"><iframe width="600" height="850" src="<?php echo site_url('forms/ticket'); ?>" frameborder="0" allowfullscreen></iframe></textarea>
    <p class="bold mtop15">When placing the iframe snippet code consider the following:</p>
    <p class="<?php if(strpos(site_url(),'http://') !== false){echo 'bold text-success';} ?>">1. If the protocol of your installation is http use a http page inside the iframe.</p>
    <p class="<?php if(strpos(site_url(),'https://') !== false){echo 'bold text-success';} ?>">2. If the protocol of your installation is https use a https page inside the iframe.</p>
    <p>None SSL installation will need to place the link in non ssl eq. landing page and backwards.</p>
    <hr class="mt-4 mb-4" />
    <h4 class="bold">Change form container column (Bootstrap)</h4>
    <p>
      <a href="<?php echo site_url('forms/ticket?col=col-md-8'); ?>" target="_blank">
        <?php echo site_url('forms/ticket?col=col-md-8'); ?>
      </a>
    </p>
    <p>
      <a href="<?php echo site_url('forms/ticket?col=col-md-8+col-md-offset-2'); ?>" target="_blank"><?php echo site_url('forms/ticket?col=col-md-8+col-md-offset-2'); ?></a>
    </p>
    <p>
      <a href="<?php echo site_url('forms/ticket?col=col-md-5'); ?>" target="_blank">
        <?php echo site_url('forms/ticket?col=col-md-5'); ?>
      </a>
    </p>
  </div>
