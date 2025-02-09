<div class="col-md-12 no-padding">
    <div class="card">
        <div class="panel-body">
            <?php if ($expense->recurring == 1) {
                echo '<div class="ribbon info"><span>' . _l('expense_recurring_indicator') . '</span></div>';
            } ?>
            <div class="horizontal-scrollable-tabs preview-tabs-top">
                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                    <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab_expense" aria-controls="tab_expense" role="tab" data-toggle="tab">
                                <?php echo _l('expense'); ?>
                            </a>
                        </li>
                        <?php if ($expense->recurring > 0) { ?>
                            <li role="presentation">
                                <a href="#tab_child_expenses" aria-controls="tab_child_expenses" role="tab"
                                   data-toggle="tab">
                                    <?php echo _l('child_expenses'); ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li role="presentation">
                            <a href="#tab_tasks"
                               onclick="init_rel_tasks_table(<?php echo $expense->expenseid; ?>,'expense'); return false;"
                               aria-controls="tab_tasks" role="tab" data-toggle="tab">
                                <?php echo _l('tasks'); ?>
                            </a>
                        </li>
                        <li role="presentation" class="tab-separator">
                            <a href="#tab_reminders"
                               onclick="initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + <?php echo $expense->id; ?> + '/' + 'expense', undefined, undefined,undefined,[1,'ASC']); return false;"
                               aria-controls="tab_reminders" role="tab" data-toggle="tab">
                                <?php echo _l('expenses_reminders'); ?>
                                <?php
                                $total_reminders = total_rows('tblreminders',
                                    array(
                                        'isnotified' => 0,
                                        'staff' => get_staff_user_id(),
                                        'rel_type' => 'expense',
                                        'rel_id' => $expense->expenseid
                                    )
                                );
                                if ($total_reminders > 0) {
                                    echo '<span class="badge">' . $total_reminders . '</span>';
                                }
                                ?>
                            </a>
                        </li>
                        <li role="presentation" class="tab-separator toggle_view" data-toggle="tooltip"
                            data-title="<?php echo _l('toggle_full_view'); ?>" data-placement="top">
                            <a href="#" onclick="small_table_full_view(); return false;">
                                <i class="fa fa-expand"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <h3 class="bold no-margin"><?php echo $expense->category_name; ?></h3>
                    <?php if (!empty($expense->expense_name)) { ?>
                        <h4 class="text-muted f-16 no-margin-top"><?php echo $expense->expense_name; ?></h4>
                    <?php } ?>
                </div>
                <div class="col-md-6 _buttons text-right mt-4">
                    <div class="visible-xs">
                        <div class="mt-2"></div>
                    </div>
                    <div class="pull-right">
                        <?php if ($expense->billable == 1 && $expense->invoiceid == NULL) { ?>
                            <?php if (has_permission('invoices', '', 'create')) { ?>
                                <div class="btn-group ml-1">
                                    <button type="button" class="btn btn-success pull-right expense_convert_btn"
                                            data-id="<?php echo $expense->expenseid; ?>" data-toggle="modal"
                                            data-target="#expense_convert_helper_modal">
                                        <?php echo _l('expense_convert_to_invoice'); ?>
                                    </button>
                                </div>
                            <?php } ?>
                        <?php } else if ($expense->invoiceid != NULL) { ?>
                            <div class="btn-group ml-1">
                                <a href="<?php echo admin_url('invoices/list_invoices/' . $expense->invoiceid); ?>"
                                   class="btn pull-right btn-secondary"><?php echo format_invoice_number($invoice->id); ?></a>
                            </div>
                        <?php } ?>
                        <?php if (has_permission('expenses', '', 'edit')) { ?>
                            <div class="btn-group ml-1">
                                <a class="btn btn-default btn-with-tooltip"
                                   href="<?php echo admin_url('expenses/expense/' . $expense->expenseid); ?>"
                                   data-toggle="tooltip" data-placement="bottom"
                                   title="<?php echo _l('expense_edit'); ?>"><i
                                            class="fa fa-pencil-square-o s-4"></i></a>
                            </div>
                        <?php } ?>
                        <?php if (has_permission('expenses', '', 'create')) { ?>
                            <div class="btn-group ml-1">
                                <a class="btn btn-default btn-with-tooltip"
                                   href="<?php echo admin_url('expenses/copy/' . $expense->expenseid); ?>"
                                   data-toggle="tooltip" data-placement="bottom"
                                   title="<?php echo _l('expense_copy'); ?>"><i class="fa fa-clone s-4"></i></a>
                            </div>
                        <?php } ?>
                        <?php if (has_permission('expenses', '', 'delete')) { ?>
                            <div class="btn-group ml-1">
                                <a class="btn btn-danger btn-with-tooltip _delete"
                                   href="<?php echo admin_url('expenses/delete/' . $expense->expenseid); ?>"
                                   data-toggle="tooltip" data-placement="bottom"
                                   title="<?php echo _l('expense_delete'); ?>"><i class="fa fa-remove s-4"></i></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr class="hr-panel-heading hr-10 mt-3"/>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="tab_expense"
                     data-empty-note="<?php echo empty($expense->note); ?>"
                     data-empty-name="<?php echo empty($expense->expense_name); ?>">
                    <div class="row">
                        <?php
                        if ($expense->recurring > 0 || $expense->recurring_from != NULL) {
                        echo '<div class="col-md-12">';
                        $recurring_expense = $expense;
                        $next_recurring_date_compare = $recurring_expense->date;
                        if ($recurring_expense->last_recurring_date) {
                            $next_recurring_date_compare = $recurring_expense->last_recurring_date;
                        }
                        if ($expense->recurring_from != NULL) {
                            $recurring_expense = $this->expenses_model->get($expense->recurring_from);
                            $next_recurring_date_compare = $recurring_expense->last_recurring_date;
                        }

                        $next_date = date('Y-m-d', strtotime('+' . $recurring_expense->recurring . ' ' . strtoupper($recurring_expense->recurring_type), strtotime($next_recurring_date_compare)));

                        ?>
                        <?php if ($expense->recurring_from == null && $recurring_expense->cycles > 0 && $recurring_expense->cycles == $recurring_expense->total_cycles) { ?>
                            <div class="alert alert-info mbot15">
                                <?php echo _l('recurring_has_ended', _l('expense_lowercase')); ?>
                            </div>
                        <?php } else { ?>
                            <span class="label label-default padding-5">
                  <?php echo _l('cycles_remaining'); ?>:
                  <b>
                  <?php
                  if ($recurring_expense->cycles == 0) {
                      echo _l('cycles_infinity');
                  } else {
                      echo $recurring_expense->cycles - $recurring_expense->total_cycles;
                  }
                  ?>
                  </b>
                  </span>
                        <?php } ?>
                        <?php if ($recurring_expense->cycles == 0 || $recurring_expense->cycles != $recurring_expense->total_cycles) { ?>
                            <?php echo '<span class="label label-default padding-5 mleft5"><i class="fa fa-question-circle fa-fw" data-toggle="tooltip" data-title="' . _l('recurring_recreate_hour_notice', _l('expense')) . '"></i> ' . _l('next_expense_date', '<b>' . _d($next_date) . '</b>') . '</span>'; ?>
                        <?php } ?>
                        <?php
                        if ($expense->recurring_from != NULL) { ?>
                            <?php echo '<p class="text-muted mtop15 no-mbot">' . _l('expense_recurring_from', '<a href="' . admin_url('expenses/list_expenses/' . $expense->recurring_from) . '" onclick="init_expense(' . $expense->recurring_from . ');return false;">' . $recurring_expense->category_name . (!empty($recurring_expense->expense_name) ? ' (' . $recurring_expense->expense_name . ')' : '') . '</a></p>'); ?>
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading"/>
                    <?php } ?>
                    <div class="col-md-6">
                        <p><span class="bold font-medium"><?php echo _l('expense_amount'); ?></span> <span
                                    class="text-danger bold font-medium"><?php echo format_money($expense->amount, $expense->currency_data->symbol); ?></span>
                            <?php if ($expense->paymentmode != '0' && !empty($expense->paymentmode)) {
                                ?>
                                <span class="text-muted"><?php echo _l('expense_paid_via', $expense->payment_mode_name); ?></span>
                            <?php } ?>
                            <?php
                            if ($expense->tax != 0) {
                                echo '<br /><span class="bold">' . _l('tax_1') . ':</span> ' . $expense->taxrate . '% (' . $expense->tax_name . ')';
                                $total = $expense->amount;
                                $total += ($total / 100 * $expense->taxrate);
                            }
                            if ($expense->tax2 != 0) {
                                echo '<br /><span class="bold">' . _l('tax_2') . ':</span> ' . $expense->taxrate2 . '% (' . $expense->tax_name2 . ')';
                                $total += ($expense->amount / 100 * $expense->taxrate2);
                            }
                            if ($expense->tax != 0 || $expense->tax2 != 0) {
                                echo '<p class="font-medium bold text-danger">' . _l('total_with_tax') . ': ' . format_money($total, $expense->currency_data->symbol) . '</p>';
                            }
                            ?>
                            <?php if ($expense->billable == 1) {
                                echo '<br />';
                                echo '<br />';
                                if ($expense->invoiceid == NULL) {
                                    echo '<span class="text-danger">' . _l('expense_invoice_not_created') . '</span>';
                                } else {
                                    if ($invoice->status == 2) {
                                        echo '<span class="text-success">' . _l('expense_billed') . '</span>';
                                    } else {
                                        echo '<span class="text-danger">' . _l('expense_not_billed') . '</span>';
                                    }
                                }
                            } ?>
                        </p>
                        <p><span class="bold"><?php echo _l('expense_date'); ?></span> <span
                                    class="text-muted"><?php echo _d($expense->date); ?></span></p>
                        <br/>
                        <br/>
                        <?php if (!empty($expense->reference_no)) { ?>
                            <p class="bold mbot15"><?php echo _l('expense_ref_noe'); ?> <span
                                        class="text-muted"><?php echo $expense->reference_no; ?></span></p>
                        <?php } ?>
                        <?php if ($expense->clientid != 0) { ?>
                            <p class="bold mbot5"><?php echo _l('expense_customer'); ?></p>
                            <p class="mbot15"><a
                                        href="<?php echo admin_url('clients/client/' . $expense->clientid); ?>"><?php echo $expense->company; ?></a>
                            </p>
                        <?php } ?>
                        <?php if ($expense->project_id != 0) { ?>
                            <p class="bold mbot5"><?php echo _l('project'); ?></p>
                            <p class="mbot15"><a
                                        href="<?php echo admin_url('projects/view/' . $expense->project_id); ?>"><?php echo $expense->project_data->name; ?></a>
                            </p>
                        <?php } ?>
                        <?php
                        $custom_fields = get_custom_fields('expenses');
                        foreach ($custom_fields as $field) { ?>
                            <?php $value = get_custom_field_value($expense->expenseid, $field['id'], 'expenses');
                            if ($value == '') {
                                continue;
                            } ?>
                            <div class="row mbot10">
                                <div class="col-md-12 mtop5">
                                    <p class="mbot5">
                                        <span class="bold"><?php echo ucfirst($field['name']); ?></span>
                                    </p>
                                    <div class="text-left">
                                        <?php echo $value; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($expense->note != '') { ?>
                            <p class="bold mbot5"><?php echo _l('expense_note'); ?></p>
                            <p class="text-muted mbot15"><?php echo $expense->note; ?></p>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <h4 class="bold mbot25"><?php echo _l('expense_receipt'); ?></h4>
                        <?php if (empty($expense->attachment)) { ?>
                            <?php echo form_open('admin/expenses/add_expense_attachment/' . $expense->expenseid, array('class' => 'mtop10 dropzone dropzone-expense-preview dropzone-manual', 'id' => 'expense-receipt-upload')); ?>
                            <div id="dropzoneDragArea" class="dz-default dz-message">
                                <span><?php echo _l('expense_add_edit_attach_receipt'); ?></span>
                            </div>
                            <?php echo form_close(); ?>
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-md-10">
                                    <i class="<?php echo get_mime_class($expense->filetype); ?>"></i> <a
                                            href="<?php echo site_url('download/file/expense/' . $expense->expenseid); ?>"> <?php echo $expense->attachment; ?></a>
                                </div>
                                <?php if ($expense->attachment_added_from == get_staff_user_id() || is_admin()) { ?>
                                    <div class="col-md-2 text-right">
                                        <a class="_delete text-danger"
                                           href="<?php echo admin_url('expenses/delete_expense_attachment/' . $expense->expenseid . '/' . 'preview'); ?>"
                                           class="text-danger"><i class="fa fa fa-times"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($expense->recurring > 0) { ?>
                <div role="tabpanel" class="tab-pane" id="tab_child_expenses">
                    <?php if (count($child_expenses)) { ?>
                        <p class="bold"><?php echo _l('expenses_created_from_this_recurring_expense'); ?></p>
                        <br/>
                        <ul class="list-group">
                            <?php foreach ($child_expenses as $recurring) { ?>
                                <li class="list-group-item">
                                    <a href="<?php echo admin_url('expenses/list_expenses/' . $recurring->expenseid); ?>"
                                       onclick="init_expense(<?php echo $recurring->expenseid; ?>); return false;"
                                       target="_blank"><?php echo $recurring->category_name . (!empty($recurring->expense_name) ? ' (' . $recurring->expense_name . ')' : ''); ?>
                                    </a>
                                    <br/>
                                    <span class="inline-block mtop10">
                     <?php echo '<span class="bold">' . _d($recurring->date) . '</span>'; ?><br/>
                     <p><span class="bold font-medium"><?php echo _l('expense_amount'); ?></span> <span
                                 class="text-danger bold font-medium"><?php echo format_money($recurring->amount, $recurring->currency_data->symbol); ?></span>
                         <?php
                         if ($recurring->tax != 0) {
                             echo '<br /><span class="bold">' . _l('tax_1') . ':</span> ' . $recurring->taxrate . '% (' . $recurring->tax_name . ')';
                             $total = $recurring->amount;
                             $total += ($total / 100 * $recurring->taxrate);
                         }
                         if ($recurring->tax2 != 0) {
                             echo '<br /><span class="bold">' . _l('tax_2') . ':</span> ' . $recurring->taxrate2 . '% (' . $recurring->tax_name2 . ')';
                             $total += ($recurring->amount / 100 * $recurring->taxrate2);
                         }
                         if ($recurring->tax != 0 || $recurring->tax2 != 0) {
                             echo '<p class="font-medium bold text-danger">' . _l('total_with_tax') . ': ' . format_money($total, $recurring->currency_data->symbol) . '</p>';
                         }
                         ?>
                  </span>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <p class="bold"><?php echo _l('no_child_found', _l('expenses')); ?></p>
                    <?php } ?>
                </div>
            <?php } ?>
            <div role="tabpanel" class="tab-pane" id="tab_tasks">
                <?php init_relation_tasks_table(array('data-new-rel-id' => $expense->expenseid, 'data-new-rel-type' => 'expense')); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab_reminders">
                <a href="#" data-toggle="modal" class="btn btn-info"
                   data-target=".reminder-modal-expense-<?php echo $expense->id; ?>"><i
                            class="fa fa-bell-o s-4"></i> <?php echo _l('expense_set_reminder_title'); ?></a>
                <hr class="mt-4 mb-4"/>
                <?php render_datatable(array(_l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')), 'reminders'); ?>
                <?php $this->load->view('admin/includes_fuse/modals/reminder', array('id' => $expense->id, 'name' => 'expense', 'members' => $members, 'reminder_title' => _l('expense_set_reminder_title'))); ?>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    init_btn_with_tooltips();
    init_selectpicker();
    init_datepicker();
    init_form_reminder();
    init_tabs_scrollable();

    if ($('#dropzoneDragArea').length > 0) {
        if (typeof(expensePreviewDropzone) != 'undefined') {
            expensePreviewDropzone.destroy();
        }
        expensePreviewDropzone = new Dropzone("#expense-receipt-upload", $.extend({}, _dropzone_defaults(), {
            clickable: '#dropzoneDragArea',
            maxFiles: 1,
            success: function (file, response) {
                init_expense(<?php echo $expense->expenseid; ?>);
            }
        }));
    }
</script>
