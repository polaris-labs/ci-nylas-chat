<?php if (isset($client)) { ?>
    <h4 class="customer-profile-group-heading"><?php echo _l('subscriptions'); ?></h4>
    <div class="p-4">
        <?php if (has_permission('subscriptions', '', 'create')) { ?>
            <a href="<?php echo admin_url('subscriptions/create?customer_id=' . $client->userid); ?>"
               class="btn btn-info mb-1<?php if ($client->active == 0) {
                   echo ' disabled';
               } ?>"><?php echo _l('new_subscription'); ?></a>
        <?php } ?>
        <?php $this->load->view('admin/subscriptions/table_html', array('url' => admin_url('subscriptions/table?client_id=' . $client->userid))); ?>
    </div>
<?php } ?>
