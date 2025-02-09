<?php echo form_hidden('_attachment_sale_id', $credit_note->id); ?>
<?php echo form_hidden('_attachment_sale_type', 'credit_note'); ?>
<div class="col-md-12 no-padding">
    <div class="card">
        <div class="panel-body">
            <div class="horizontal-scrollable-tabs preview-tabs-top mb-4">
                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                    <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab_credit_note" aria-controls="tab_credit_note" role="tab" data-toggle="tab">
                                <?php echo _l('credit_note'); ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#invoices_credited" aria-controls="invoices_credited" role="tab" data-toggle="tab">
                                <?php echo _l('invoices_credited'); ?>
                                <?php if (count($credit_note->applied_credits) > 0) {
                                    echo '<span class="badge">' . count($credit_note->applied_credits) . '</span>';
                                }
                                ?>
                            </a>
                        </li>
                        <li role="presentation" class="tab-separator">
                            <a href="#tab_reminders"
                               onclick="initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + <?php echo $credit_note->id; ?> + '/' + 'credit_note', undefined, undefined, undefined,[1,'asc']); return false;"
                               aria-controls="tab_reminders" role="tab" data-toggle="tab">
                                <?php echo _l('reminders'); ?>
                                <?php
                                $total_reminders = total_rows('tblreminders',
                                    array(
                                        'isnotified' => 0,
                                        'staff' => get_staff_user_id(),
                                        'rel_type' => 'credit_note',
                                        'rel_id' => $credit_note->id
                                    )
                                );
                                if ($total_reminders > 0) {
                                    echo '<span class="badge">' . $total_reminders . '</span>';
                                }
                                ?>
                            </a>
                        </li>
                        <li role="presentation" data-toggle="tooltip" title="<?php echo _l('emails_tracking'); ?>"
                            class="tab-separator">
                            <a href="#tab_emails_tracking" aria-controls="tab_emails_tracking" role="tab"
                               data-toggle="tab">
                                <?php if (!is_mobile()) { ?>
                                    <i class="fa fa-envelope-open-o" aria-hidden="true"></i>
                                <?php } else { ?>
                                    <?php echo _l('emails_tracking'); ?>
                                <?php } ?>
                            </a>
                        </li>
                        <li role="presentation" class="tab-separator toggle_view" data-placement="top"
                            data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>">
                            <a href="#" onclick="small_table_full_view(); return false;">
                                <i class="fa fa-expand"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?php echo format_credit_note_status($credit_note->status, false, 'mt-2'); ?>
                </div>
                <div class="col-md-9">
                    <div class="visible-xs">
                        <div class="mt-2"></div>
                    </div>
                    <div class="pull-right _buttons">
                        <?php if (has_permission('credit_notes', '', 'edit') && $credit_note->status != 3) { ?>
                            <div class="btn-group ml-1">
                                <a href="<?php echo admin_url('credit_notes/credit_note/' . $credit_note->id); ?>"
                                   class="btn btn-default btn-with-tooltip" data-toggle="tooltip"
                                   title="<?php echo _l('edit', _l('credit_note_lowercase')); ?>"
                                   data-placement="bottom"><i class="fa fa-pencil-square-o s-4"></i>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="btn-group ml-1">
                            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false"><i
                                        class="fa fa-file-pdf-o s-4"></i><?php if (is_mobile()) {
                                    echo ' PDF';
                                } ?>&nbsp;&nbsp;<span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right width200">
                                <li class="hidden-xs"><a
                                            href="<?php echo admin_url('credit_notes/pdf/' . $credit_note->id . '?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a>
                                </li>
                                <li class="hidden-xs"><a
                                            href="<?php echo admin_url('credit_notes/pdf/' . $credit_note->id . '?output_type=I'); ?>"
                                            target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                                <li>
                                    <a href="<?php echo admin_url('credit_notes/pdf/' . $credit_note->id); ?>"><?php echo _l('download'); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo admin_url('credit_notes/pdf/' . $credit_note->id . '?print=true'); ?>"
                                       target="_blank">
                                        <?php echo _l('print'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php if ($credit_note->status != 3 && !empty($credit_note->clientid)) { ?>
                            <div class="btn-group ml-1">
                                <a href="#" class="credit-note-send-to-client btn btn-default" data-toggle="modal"
                                   data-target="#credit_note_send_to_client_modal">
                                    <i class="fa fa-envelope s-4"></i>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($credit_note->status == 1 && !empty($credit_note->clientid)) { ?>
                            <div class="btn-group ml-1">
                                <a href="#" data-toggle="modal" data-target="#apply_credits" class="btn btn-secondary">
                                    <?php echo _l('apply_to_invoice'); ?>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if (($credit_note->status != 2 && $credit_note->status != 3 && !$credit_note->credits_used && has_permission('credit_notes', '', 'edit'))
                            || ($credit_note->status == 3 && has_permission('credit_notes', '', 'edit'))
                            || has_permission('credit_notes', '', 'delete')
                        ) { ?>
                            <div class="btn-group ml-1">
                                <button type="button" class="btn btn-default pull-left dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo _l('more'); ?>&nbsp;&nbsp;<span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right width250">
                                    <?php
                                    // is not closed and is not void
                                    if ($credit_note->status != 2 && $credit_note->status != 3 && !$credit_note->credits_used && has_permission('credit_notes', '', 'edit')) { ?>
                                        <li>
                                            <a href="<?php echo admin_url('credit_notes/mark_void/' . $credit_note->id); ?>">
                                                <?php echo _l('credit_note_status_void'); ?>
                                            </a>
                                        </li>
                                    <?php } else if ($credit_note->status == 3 && has_permission('credit_notes', '', 'edit')) { ?>
                                        <li>
                                            <a href="<?php echo admin_url('credit_notes/mark_open/' . $credit_note->id); ?>">
                                                <?php echo _l('credit_note_mark_as_open'); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a href="#" data-toggle="modal"
                                           data-target="#sales_attach_file"><?php echo _l('invoice_attach_file'); ?></a>
                                    </li>
                                    <?php
                                    if (has_permission('credit_notes', '', 'delete')) {
                                        $delete_tooltip = '';
                                        if ($credit_note->status == 2) {
                                            $delete_tooltip = _l('credits_applied_cant_delete_status_closed');
                                        } else if ($credit_note->credits_used) {
                                            $delete_tooltip = _l('credits_applied_cant_delete_credit_note');
                                        }
                                        ?>
                                        <li>
                                            <a data-toggle="tooltip" data-title="<?php echo $delete_tooltip; ?>"
                                               href="<?php echo admin_url('credit_notes/delete/' . $credit_note->id); ?>"
                                               class="text-danger delete-text <?php if (!$credit_note->credits_used && $credit_note->status != 2) {
                                                   echo ' _delete';
                                               } ?>"<?php if ($credit_note->credits_used || $credit_note->status == 2) {
                                                echo ' style="cursor:not-allowed;" onclick="return false;" ';
                                            }; ?>><?php echo _l('delete'); ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr class="hr-panel-heading"/>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="tab_credit_note">
                    <div id="credit-note-preview">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 mb-4">
                                <h4 class="bold">
                                    <a href="<?php echo admin_url('credit_notes/credit_note/' . $credit_note->id); ?>">
                           <span id="credit-note-number">
                           <?php echo format_credit_note_number($credit_note->id); ?>
                           </span>
                                    </a>
                                </h4>
                                <address>
                                    <?php echo format_organization_info(); ?>
                                </address>
                            </div>
                            <div class="col-sm-6 text-right mb-4">
                                <span class="bold"><?php echo _l('credit_note_bill_to'); ?>:</span>
                                <address>
                                    <?php echo format_customer_info($credit_note, 'credit_note', 'billing', true); ?>
                                </address>
                                <?php if ($credit_note->include_shipping == 1 && $credit_note->show_shipping_on_credit_note == 1) { ?>
                                    <span class="bold"><?php echo _l('ship_to'); ?>:</span>
                                    <address>
                                        <?php echo format_customer_info($credit_note, 'credit_note', 'shipping'); ?>
                                    </address>
                                <?php } ?>
                                <p class="no-margin-bottom">
                           <span class="bold">
                           <?php echo _l('credit_note_date'); ?>:
                           </span>
                                    <?php echo _d($credit_note->date) ?>
                                </p>
                                <?php if (!empty($credit_note->reference_no)) { ?>
                                    <p class="no-margin-bottom">
                                        <span class="bold"><?php echo _l('reference_no'); ?>:</span>
                                        <?php echo $credit_note->reference_no; ?>
                                    </p>
                                <?php } ?>
                                <?php if ($credit_note->project_id != 0 && get_option('show_project_on_credit_note') == '1') { ?>
                                    <p class="no-margin-bottom">
                                        <span class="bold"><?php echo _l('project'); ?>:</span>
                                        <?php echo get_project_name_by_id($credit_note->project_id); ?>
                                    </p>
                                <?php } ?>
                                <?php $pdf_custom_fields = get_custom_fields('credit_note', array('show_on_pdf' => 1));
                                foreach ($pdf_custom_fields as $field) {
                                    $value = get_custom_field_value($credit_note->id, $field['id'], 'credit_note');
                                    if ($value == '') {
                                        continue;
                                    } ?>
                                    <p class="no-margin-bottom">
                                        <span class="bold"><?php echo $field['name']; ?>: </span>
                                        <?php echo $value; ?>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table items credit-note-items-preview">
                                        <thead>
                                        <tr>
                                            <th align="center">#</th>
                                            <th class="description" width="50%" align="left">
                                                <?php echo _l('credit_note_table_item_heading'); ?>
                                            </th>
                                            <?php
                                            $custom_fields = get_items_custom_fields_for_table_html($credit_note->id, 'credit_note');
                                            foreach ($custom_fields as $cf) {
                                                echo '<th class="custom_field" align="left">' . $cf['name'] . '</th>';
                                            }
                                            $qty_heading = _l('credit_note_table_quantity_heading');
                                            if ($credit_note->show_quantity_as == 2) {
                                                $qty_heading = _l('credit_note_table_hours_heading');
                                            } else if ($credit_note->show_quantity_as == 3) {
                                                $qty_heading = _l('credit_note_table_quantity_heading') . '/' . _l('credit_note_table_hours_heading');
                                            }
                                            ?>
                                            <th align="right"><?php echo $qty_heading; ?></th>
                                            <th align="right"><?php echo _l('credit_note_table_rate_heading'); ?></th>
                                            <?php if (get_option('show_tax_per_item') == 1) { ?>
                                                <th align="right"><?php echo _l('credit_note_table_tax_heading'); ?></th>
                                            <?php } ?>
                                            <th align="right"><?php echo _l('credit_note_table_amount_heading'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $items_data = get_table_items_and_taxes($credit_note->items, 'credit_note', true);
                                        $taxes = $items_data['taxes'];
                                        echo $items_data['html'];
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-8">
                                <table class="table text-right">
                                    <tbody>
                                    <tr id="subtotal">
                                        <td><span class="bold"><?php echo _l('credit_note_subtotal'); ?></span>
                                        </td>
                                        <td class="subtotal">
                                            <?php echo format_money($credit_note->subtotal, $credit_note->symbol); ?>
                                        </td>
                                    </tr>
                                    <?php if (is_sale_discount_applied($credit_note)) { ?>
                                        <tr>
                                            <td>
                                    <span class="bold"><?php echo _l('credit_note_discount'); ?>
                                        <?php if (is_sale_discount($credit_note, 'percent')) { ?>
                                            (<?php echo _format_number($credit_note->discount_percent, true); ?>%)
                                        <?php } ?></span>
                                            </td>
                                            <td class="discount">
                                                <?php echo '-' . format_money($credit_note->discount_total, $credit_note->symbol); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                    foreach ($taxes as $tax) {
                                        echo '<tr class="tax-area"><td class="bold">' . $tax['taxname'] . ' (' . _format_number($tax['taxrate']) . '%)</td><td>' . format_money($tax['total_tax'], $credit_note->symbol) . '</td></tr>';
                                    }
                                    ?>
                                    <?php if ((int)$credit_note->adjustment != 0) { ?>
                                        <tr>
                                            <td>
                                                <span class="bold"><?php echo _l('credit_note_adjustment'); ?></span>
                                            </td>
                                            <td class="adjustment">
                                                <?php echo format_money($credit_note->adjustment, $credit_note->symbol); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td><span class="bold"><?php echo _l('credit_note_total'); ?></span>
                                        </td>
                                        <td class="total">
                                            <?php echo format_money($credit_note->total, $credit_note->symbol); ?>
                                        </td>
                                    </tr>
                                    <?php if ($credit_note->credits_used) { ?>
                                        <tr>
                                            <td>
                                    <span class="bold">
                                    <?php echo _l('credits_used'); ?>
                                    </span>
                                            </td>
                                            <td>
                                                <?php echo '-' . format_money($credit_note->credits_used, $credit_note->symbol); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>
                                    <span class="bold">
                                    <?php echo _l('credits_remaining'); ?>
                                    </span>
                                        </td>
                                        <td>
                                            <?php echo format_money($credit_note->remaining_credits, $credit_note->symbol); ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <hr class="mb-4 mt-4"/>
                            </div>
                            <?php if ($credit_note->clientnote != '') { ?>
                                <div class="col-md-12">
                                    <p class="bold text-muted mr-4"><?php echo _l('credit_note_client_note'); ?>: </p>
                                    <p><?php echo $credit_note->clientnote; ?></p>
                                </div>
                            <?php } ?>
                            <?php if ($credit_note->terms != '') { ?>
                                <div class="col-md-12">
                                    <p class="bold text-muted mr-4"><?php echo _l('terms_and_conditions'); ?>: </p>
                                    <p><?php echo $credit_note->terms; ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        if (count($credit_note->attachments) > 0) { ?>
                            <div class="clearfix"></div>
                            <hr class="mt-4 mb-4"/>
                            <p class="bold text-muted"><?php echo _l('credit_note_files'); ?></p>
                            <?php foreach ($credit_note->attachments as $attachment) {
                                $attachment_url = site_url('download/file/sales_attachment/' . $attachment['attachment_key']);
                                if (!empty($attachment['external'])) {
                                    $attachment_url = $attachment['external_link'];
                                }
                                ?>
                                <div class="mb-3 row inline-block full-width"
                                     data-attachment-id="<?php echo $attachment['id']; ?>">
                                    <div class="col-md-8">
                                        <div class="pull-left"><i
                                                    class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                                        </div>
                                        <a href="<?php echo $attachment_url; ?>"
                                           target="_blank"><?php echo $attachment['file_name']; ?></a>
                                        <br/>
                                        <small class="text-muted"> <?php echo $attachment['filetype']; ?></small>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <?php if ($attachment['staffid'] == get_staff_user_id() || is_admin()) { ?>
                                            <a href="#" class="text-danger"
                                               onclick="delete_credit_note_attachment(<?php echo $attachment['id']; ?>); return false;"><i
                                                        class="fa fa-times text-danger"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="invoices_credited">
                    <?php if (count($credit_note->applied_credits) == 0) {
                        echo '<div class="alert alert-info no-margin-bottom">';
                        echo _l('credited_invoices_not_found');
                        echo '</div>';
                    } else { ?>
                        <table class="table table-bordered no-margin-top items">
                            <thead>
                            <tr>
                                <th><span class="bold"><?php echo _l('credit_invoice_number'); ?></span></th>
                                <th><span class="bold"><?php echo _l('amount_credited'); ?></span></th>
                                <th><span class="bold"><?php echo _l('credit_date'); ?></span></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($credit_note->applied_credits as $credit) { ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo admin_url('invoices/list_invoices/' . $credit['invoice_id']); ?>"><?php echo format_invoice_number($credit['invoice_id']); ?></a>
                                    </td>
                                    <td>
                                        <?php echo format_money($credit['amount'], $credit_note->symbol); ?>
                                    </td>
                                    <td>
                                        <?php echo _d($credit['date']); ?>
                                        <?php if (has_permission('credit_notes', '', 'delete')) { ?>
                                            <a href="<?php echo admin_url('credit_notes/delete_credit_note_applied_credit/' . $credit['id'] . '/' . $credit['credit_id'] . '/' . $credit['invoice_id']); ?>"
                                               class="pull-right text-danger _delete"><i class="fa fa-trash"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab_emails_tracking">
                    <?php
                    $this->load->view('admin/includes_fuse/emails_tracking', array(
                            'tracked_emails' =>
                                get_tracked_emails($credit_note->id, 'credit_note'))
                    );
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab_reminders">
                    <a href="#" class="btn btn-secondary btn-xs" data-toggle="modal"
                       data-target=".reminder-modal-credit_note-<?php echo $credit_note->id; ?>"><i
                                class="fa fa-bell-o s-4"></i> <?php echo _l('credit_note_set_reminder_title'); ?></a>
                    <hr class="mt-4 mb-4"/>
                    <?php render_datatable(array(_l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')), 'reminders'); ?>
                    <?php $this->load->view('admin/includes_fuse/modals/reminder', array('id' => $credit_note->id, 'name' => 'credit_note', 'members' => $members, 'reminder_title' => _l('credit_note_set_reminder_title'))); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('admin/credit_notes/send_to_client'); ?>
<?php $this->load->view('admin/credit_notes/apply_credits_to_invoices'); ?>
<script>
    init_items_sortable(true);
    init_btn_with_tooltips();
    init_datepicker();
    init_selectpicker();
    init_form_reminder();
    init_tabs_scrollable();
</script>
