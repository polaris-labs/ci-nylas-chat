$(document).ready(function() {
	function saveSettings() {
		OC.msg.startSaving('#activity_notifications_msg');
		var post = $('#activity_notifications').serialize();

		$.post(OC.generateUrl('/apps/activity/settings'), post, function(response) {
			OC.msg.finishedSuccess('#activity_notifications_msg', response.data.message);
		});
	}

	var $activityNotifications = $('#activity_notifications:not(.default-settings)');
	$activityNotifications.find('input[type=checkbox]').change(saveSettings);

	$activityNotifications.find('select').change(saveSettings);

	$activityNotifications.find('.activity_select_group').click(function() {
		var $selectGroup = '#activity_notifications .' + $(this).attr('data-select-group');
		var $filteredBoxes = $($selectGroup).not(':disabled');
		var $checkedBoxes = $filteredBoxes.filter(':checked').length;

		$filteredBoxes.prop('checked', true);
		if ($checkedBoxes === $filteredBoxes.filter(':checked').length) {
			// All values were already selected, so invert it
			$filteredBoxes.prop('checked', false);
		}

		saveSettings();
	});
});
