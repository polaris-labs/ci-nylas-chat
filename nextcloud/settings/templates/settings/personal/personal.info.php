<?php
/**
 * @copyright Copyright (c) 2017 Arthur Schiwon <blizzz@arthur-schiwon.de>
 *
 * @author Arthur Schiwon <blizzz@arthur-schiwon.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/** @var \OCP\IL10N $l */
/** @var array $_ */

script('settings', [
	'usersettings',
	'federationsettingsview',
	'federationscopemenu',
	'settings/personalInfo',
]);
vendor_script('strengthify/jquery.strengthify');
vendor_style('strengthify/strengthify');
vendor_script('jcrop/js/jquery.Jcrop');
vendor_style('jcrop/css/jquery.Jcrop');

?>

<div id="quota" class="section">
	<progress value="<?php p($_['usage_relative']); ?>" max="100"
	<?php if($_['usage_relative'] > 80): ?> class="warn" <?php endif; ?>></progress>

	<div style="width:<?php p($_['usage_relative']);?>%" class="quotatext-fg">
		<p class="quotatext">
			<?php if ($_['quota'] === \OCP\Files\FileInfo::SPACE_UNLIMITED): ?>
				<?php print_unescaped($l->t('You are using <strong>%s</strong> of <strong>%s</strong>',
					[$_['usage'], $_['total_space']]));?>
			<?php else: ?>
				<?php print_unescaped($l->t('You are using <strong>%s</strong> of <strong>%s</strong> (<strong>%s %%</strong>)',
					[$_['usage'], $_['total_space'],  $_['usage_relative']]));?>
			<?php endif ?>
		</p>
	</div>
	<div class="quotatext-bg">
		<p class="quotatext">
			<?php if ($_['quota'] === \OCP\Files\FileInfo::SPACE_UNLIMITED): ?>
				<?php print_unescaped($l->t('You are using <strong>%s</strong> of <strong>%s</strong>',
					[$_['usage'], $_['total_space']]));?>
			<?php else: ?>
				<?php print_unescaped($l->t('You are using <strong>%s</strong> of <strong>%s</strong> (<strong>%s %%</strong>)',
					[$_['usage'], $_['total_space'],  $_['usage_relative']]));?>
			<?php endif ?>
		</p>
	</div>
</div>

<div id="personal-settings">
	<div id="personal-settings-avatar-container" class="personal-settings-container">
		<div>
			<form id="avatarform" class="section" method="post" action="<?php p(\OC::$server->getURLGenerator()->linkToRoute('core.avatar.postAvatar')); ?>">
				<h2>
					<label><?php p($l->t('Profile picture')); ?></label><span class="icon-federation-menu icon-password">
						<span class="icon-triangle-s"></span>
					</span>
				</h2>
				<div id="displayavatar">
					<div class="avatardiv"></div>
					<div class="warning hidden"></div>
					<?php if ($_['avatarChangeSupported']): ?>
						<label for="uploadavatar" class="inlineblock button icon-upload svg" id="uploadavatarbutton" title="<?php p($l->t('Upload new')); ?>"></label>
						<div class="inlineblock button icon-folder svg" id="selectavatar" title="<?php p($l->t('Select from Files')); ?>"></div>
						<div class="hidden button icon-delete svg" id="removeavatar" title="<?php p($l->t('Remove image')); ?>"></div>
						<input type="file" name="files[]" id="uploadavatar" class="hiddenuploadfield">
						<p><em><?php p($l->t('png or jpg, max. 20 MB')); ?></em></p>
					<?php else: ?>
						<?php p($l->t('Picture provided by original account')); ?>
					<?php endif; ?>
				</div>

				<div id="cropper" class="hidden">
					<div class="inner-container">
						<div class="inlineblock button" id="abortcropperbutton"><?php p($l->t('Cancel')); ?></div>
						<div class="inlineblock button primary" id="sendcropperbutton"><?php p($l->t('Choose as profile picture')); ?></div>
					</div>
				</div>
				<span class="icon-checkmark hidden"></span>
				<?php if($_['lookupServerUploadEnabled']) { ?>
				<input type="hidden" id="avatarscope" value="<?php p($_['avatarScope']) ?>">
				<?php } ?>
			</form>
		</div>
	</div>

	<div class="personal-settings-container">
		<div class="personal-settings-setting-box">
			<form id="displaynameform" class="section">
				<h2>
					<label for="displayname"><?php p($l->t('Full name')); ?></label>
					<span class="icon-federation-menu icon-password">
						<span class="icon-triangle-s"></span>
					</span>
				</h2>
				<input type="text" id="displayname" name="displayname"
					<?php if(!$_['displayNameChangeSupported']) { print_unescaped('class="hidden"'); } ?>
					   value="<?php p($_['displayName']) ?>"
					   autocomplete="on" autocapitalize="none" autocorrect="off" />
				<?php if(!$_['displayNameChangeSupported']) { ?>
					<span><?php if(isset($_['displayName']) && !empty($_['displayName'])) { p($_['displayName']); } else { p($l->t('No display name set')); } ?></span>
				<?php } ?>
				<span class="icon-checkmark hidden"></span>
				<span class="icon-error hidden" ></span>
				<?php if($_['lookupServerUploadEnabled']) { ?>
					<input type="hidden" id="displaynamescope" value="<?php p($_['displayNameScope']) ?>">
				<?php } ?>
			</form>
		</div>
		<div class="personal-settings-setting-box">
			<form id="emailform" class="section">
				<h2>
					<label for="email"><?php p($l->t('Email')); ?></label>
					<span class="icon-federation-menu icon-password">
						<span class="icon-triangle-s"></span>
					</span>
				</h2>
				<div class="verify <?php if ($_['email'] === ''  || $_['emailScope'] !== 'public') p('hidden'); ?>">
					<img id="verify-email" title="<?php p($_['emailMessage']); ?>" data-status="<?php p($_['emailVerification']) ?>" src="
				<?php
					switch($_['emailVerification']) {
						case \OC\Accounts\AccountManager::VERIFICATION_IN_PROGRESS:
							p(image_path('core', 'actions/verifying.svg'));
							break;
						case \OC\Accounts\AccountManager::VERIFIED:
							p(image_path('core', 'actions/verified.svg'));
							break;
						default:
							p(image_path('core', 'actions/verify.svg'));
					}
					?>">
				</div>
				<input type="email" name="email" id="email" value="<?php p($_['email']); ?>"
					<?php if(!$_['displayNameChangeSupported']) { print_unescaped('class="hidden"'); } ?>
					   placeholder="<?php p($l->t('Your email address')); ?>"
					   autocomplete="on" autocapitalize="none" autocorrect="off" />
			   	<span class="icon-checkmark hidden"></span>
				<span class="icon-error hidden" ></span>
				<?php if(!$_['displayNameChangeSupported']) { ?>
					<span><?php if(isset($_['email']) && !empty($_['email'])) { p($_['email']); } else { p($l->t('No email address set')); }?></span>
				<?php } ?>
				<?php if($_['displayNameChangeSupported']) { ?>
					<em><?php p($l->t('For password reset and notifications')); ?></em>
				<?php } ?>
				<?php if($_['lookupServerUploadEnabled']) { ?>
					<input type="hidden" id="emailscope" value="<?php p($_['emailScope']) ?>">
				<?php } ?>
			</form>
		</div>
		<?php if (!empty($_['phone']) || $_['lookupServerUploadEnabled']) { ?>
		<div class="personal-settings-setting-box">
			<form id="phoneform" class="section">
				<h2>
					<label for="phone"><?php p($l->t('Phone number')); ?></label>
					<span class="icon-federation-menu icon-password">
						<span class="icon-triangle-s"></span>
					</span>
				</h2>
				<input type="tel" id="phone" name="phone" <?php if(!$_['lookupServerUploadEnabled']) print_unescaped('disabled="1"'); ?>
					   value="<?php p($_['phone']) ?>"
					   placeholder="<?php p($l->t('Your phone number')); ?>"
				       autocomplete="on" autocapitalize="none" autocorrect="off" />
				<span class="icon-checkmark hidden"></span>
				<?php if($_['lookupServerUploadEnabled']) { ?>
				<input type="hidden" id="phonescope" value="<?php p($_['phoneScope']) ?>">
				<?php } ?>
			</form>
		</div>
		<?php } ?>
		<?php if (!empty($_['address']) || $_['lookupServerUploadEnabled']) { ?>
		<div class="personal-settings-setting-box">
			<form id="addressform" class="section">
				<h2>
					<label for="address"><?php p($l->t('Address')); ?></label>
					<span class="icon-federation-menu icon-password">
						<span class="icon-triangle-s"></span>
					</span>
				</h2>
				<input type="text" id="address" name="address" <?php if(!$_['lookupServerUploadEnabled']) print_unescaped('disabled="1"');  ?>
					   placeholder="<?php p($l->t('Your postal address')); ?>"
					   value="<?php p($_['address']) ?>"
					   autocomplete="on" autocapitalize="none" autocorrect="off" />
				<span class="icon-checkmark hidden"></span>
				<?php if($_['lookupServerUploadEnabled']) { ?>
				<input type="hidden" id="addressscope" value="<?php p($_['addressScope']) ?>">
				<?php } ?>
			</form>
		</div>
		<?php } ?>
		<?php if (!empty($_['website']) || $_['lookupServerUploadEnabled']) { ?>
		<div class="personal-settings-setting-box">
			<form id="websiteform" class="section">
				<h2>
					<label for="website"><?php p($l->t('Website')); ?></label>
					<span class="icon-federation-menu icon-password">
						<span class="icon-triangle-s"></span>
					</span>
				</h2>
				<?php if($_['lookupServerUploadEnabled']) { ?>
				<div class="verify <?php if ($_['website'] === ''  || $_['websiteScope'] !== 'public') p('hidden'); ?>">
					<img id="verify-website" title="<?php p($_['websiteMessage']); ?>" data-status="<?php p($_['websiteVerification']) ?>" src="
					<?php
					switch($_['websiteVerification']) {
						case \OC\Accounts\AccountManager::VERIFICATION_IN_PROGRESS:
							p(image_path('core', 'actions/verifying.svg'));
							break;
						case \OC\Accounts\AccountManager::VERIFIED:
							p(image_path('core', 'actions/verified.svg'));
							break;
						default:
							p(image_path('core', 'actions/verify.svg'));
					}
					?>"
					<?php if($_['websiteVerification'] === \OC\Accounts\AccountManager::VERIFICATION_IN_PROGRESS || $_['websiteVerification'] === \OC\Accounts\AccountManager::NOT_VERIFIED) print_unescaped(' class="verify-action"') ?>
					>
					<div class="verification-dialog popovermenu bubble menu">
						<div class="verification-dialog-content">
							<p class="explainVerification"></p>
							<p class="verificationCode"></p>
							<p><?php p($l->t('It can take up to 24 hours before the account is displayed as verified.'));?></p>
						</div>
					</div>
				</div>
				<?php } ?>
				<input type="text" name="website" id="website" value="<?php p($_['website']); ?>"
				       placeholder="<?php p($l->t('Link https://…')); ?>"
				       autocomplete="on" autocapitalize="none" autocorrect="off"
					   <?php if(!$_['lookupServerUploadEnabled']) print_unescaped('disabled="1"');  ?>
				/>
				<span class="icon-checkmark hidden"></span>
				<?php if($_['lookupServerUploadEnabled']) { ?>
				<input type="hidden" id="websitescope" value="<?php p($_['websiteScope']) ?>">
				<?php } ?>
			</form>
		</div>
		<?php } ?>
		<?php if (!empty($_['twitter']) || $_['lookupServerUploadEnabled']) { ?>
		<div class="personal-settings-setting-box">
			<form id="twitterform" class="section">
				<h2>
					<label for="twitter"><?php p($l->t('Twitter')); ?></label>
					<span class="icon-federation-menu icon-password">
						<span class="icon-triangle-s"></span>
					</span>
				</h2>
				<?php if($_['lookupServerUploadEnabled']) { ?>
				<div class="verify <?php if ($_['twitter'] === ''  || $_['twitterScope'] !== 'public') p('hidden'); ?>">
					<img id="verify-twitter" title="<?php p($_['twitterMessage']); ?>" data-status="<?php p($_['twitterVerification']) ?>" src="
					<?php
					switch($_['twitterVerification']) {
						case \OC\Accounts\AccountManager::VERIFICATION_IN_PROGRESS:
							p(image_path('core', 'actions/verifying.svg'));
							break;
						case \OC\Accounts\AccountManager::VERIFIED:
							p(image_path('core', 'actions/verified.svg'));
							break;
						default:
							p(image_path('core', 'actions/verify.svg'));
					}
					?>"
					<?php if($_['twitterVerification'] === \OC\Accounts\AccountManager::VERIFICATION_IN_PROGRESS || $_['twitterVerification'] === \OC\Accounts\AccountManager::NOT_VERIFIED) print_unescaped(' class="verify-action"') ?>
					>
					<div class="verification-dialog popovermenu bubble menu">
						<div class="verification-dialog-content">
							<p class="explainVerification"></p>
							<p class="verificationCode"></p>
							<p><?php p($l->t('It can take up to 24 hours before the account is displayed as verified.'));?></p>
						</div>
					</div>
				</div>
				<?php } ?>
				<input type="text" name="twitter" id="twitter" value="<?php p($_['twitter']); ?>"
					   placeholder="<?php p($l->t('Twitter handle @…')); ?>"
					   autocomplete="on" autocapitalize="none" autocorrect="off"
					   <?php if(!$_['lookupServerUploadEnabled']) print_unescaped('disabled="1"');  ?>
				/>
				<span class="icon-checkmark hidden"></span>
				<?php if($_['lookupServerUploadEnabled']) { ?>
				<input type="hidden" id="twitterscope" value="<?php p($_['twitterScope']) ?>">
				<?php } ?>
			</form>
		</div>
		<?php } ?>
	</div>

	<div class="clear"></div>

	<div id="personal-settings-group-container">
		<div class="personal-settings-setting-box personal-settings-group-box">
			<div id="groups" class="section">
				<h2><?php p($l->t('Groups')); ?></h2>
				<p><?php p($l->t('You are a member of the following groups:')); ?></p>
				<p id="groups-groups">
				<strong><?php p(implode(', ', $_['groups'])); ?></strong>
				</p>
			</div>
		</div>
	</div>

	<div class="profile-settings-container">
		<div class="personal-settings-setting-box personal-settings-language-box">
			<?php if (isset($_['activelanguage'])) { ?>
			<form id="language" class="section">
				<h2>
					<label for="languageinput"><?php p($l->t('Language'));?></label>
				</h2>
				<select id="languageinput" name="lang" data-placeholder="<?php p($l->t('Language'));?>">
					<option value="<?php p($_['activelanguage']['code']);?>">
						<?php p($_['activelanguage']['name']);?>
					</option>
					<?php foreach($_['commonlanguages'] as $language):?>
						<option value="<?php p($language['code']);?>">
							<?php p($language['name']);?>
						</option>
					<?php endforeach;?>
					<optgroup label="––––––––––"></optgroup>
					<?php foreach($_['languages'] as $language):?>
						<option value="<?php p($language['code']);?>">
							<?php p($language['name']);?>
						</option>
					<?php endforeach;?>
				</select>
				<a href="https://www.transifex.com/nextcloud/nextcloud/"
					target="_blank" rel="noreferrer noopener">
					<em><?php p($l->t('Help translate'));?></em>
				</a>
			</form>
			<?php } ?>
		</div>
		<div class="personal-settings-setting-box personal-settings-password-box">
			<?php
			if($_['passwordChangeSupported']) {
				script('jquery-showpassword');
			?>
			<form id="passwordform" class="section">
				<h2 class="inlineblock"><?php p($l->t('Password'));?></h2>
				<div id="password-error-msg" class="msg success inlineblock" style="display: none;">Saved</div>

				<label for="pass1" class="hidden-visually"><?php p($l->t('Current password')); ?>: </label>
				<input type="password" id="pass1" name="oldpassword"
					placeholder="<?php p($l->t('Current password'));?>"
					autocomplete="off" autocapitalize="none" autocorrect="off" />

				<div class="personal-show-container">
					<label for="pass2" class="hidden-visually"><?php p($l->t('New password'));?>: </label>
					<input type="password" id="pass2" name="newpassword"
						placeholder="<?php p($l->t('New password')); ?>"
						data-typetoggle="#personal-show"
						autocomplete="off" autocapitalize="none" autocorrect="off" />
					<input type="checkbox" id="personal-show" name="show" /><label for="personal-show" class="personal-show-label"></label>
				</div>

				<input id="passwordbutton" type="submit" value="<?php p($l->t('Change password')); ?>" />

			</form>
			<?php
			}
			?>
		</div>
		<span class="msg"></span>
	</div>
</div>

<div class="clear"></div>
