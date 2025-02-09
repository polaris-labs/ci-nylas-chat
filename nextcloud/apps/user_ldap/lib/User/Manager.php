<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Arthur Schiwon <blizzz@arthur-schiwon.de>
 * @author Joas Schilling <coding@schilljs.com>
 * @author Jörn Friedrich Dreyer <jfd@butonic.de>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Roger Szabo <roger.szabo@web.de>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\User_LDAP\User;

use OC\Cache\CappedMemoryCache;
use OCA\User_LDAP\Access;
use OCA\User_LDAP\LogWrapper;
use OCA\User_LDAP\FilesystemHelper;
use OCP\IAvatarManager;
use OCP\IConfig;
use OCP\IDBConnection;
use OCP\Image;
use OCP\IUserManager;
use OCP\Notification\IManager as INotificationManager;

/**
 * Manager
 *
 * upon request, returns an LDAP user object either by creating or from run-time
 * cache
 */
class Manager {
	/** @var IUserTools */
	protected $access;

	/** @var IConfig */
	protected $ocConfig;

	/** @var IDBConnection */
	protected $db;

	/** @var IUserManager */
	protected $userManager;

	/** @var INotificationManager */
	protected $notificationManager;

	/** @var FilesystemHelper */
	protected $ocFilesystem;

	/** @var LogWrapper */
	protected $ocLog;

	/** @var Image */
	protected $image;

	/** @param \OCP\IAvatarManager */
	protected $avatarManager;

	/**
	 * @var CappedMemoryCache $usersByDN
	 */
	protected $usersByDN;
	/**
	 * @var CappedMemoryCache $usersByUid
	 */
	protected $usersByUid;

	/**
	 * @param IConfig $ocConfig
	 * @param \OCA\User_LDAP\FilesystemHelper $ocFilesystem object that
	 * gives access to necessary functions from the OC filesystem
	 * @param  \OCA\User_LDAP\LogWrapper $ocLog
	 * @param IAvatarManager $avatarManager
	 * @param Image $image an empty image instance
	 * @param IDBConnection $db
	 * @throws \Exception when the methods mentioned above do not exist
	 */
	public function __construct(IConfig $ocConfig,
								FilesystemHelper $ocFilesystem, LogWrapper $ocLog,
								IAvatarManager $avatarManager, Image $image,
								IDBConnection $db, IUserManager $userManager,
								INotificationManager $notificationManager) {

		$this->ocConfig            = $ocConfig;
		$this->ocFilesystem        = $ocFilesystem;
		$this->ocLog               = $ocLog;
		$this->avatarManager       = $avatarManager;
		$this->image               = $image;
		$this->db                  = $db;
		$this->userManager         = $userManager;
		$this->notificationManager = $notificationManager;
		$this->usersByDN           = new CappedMemoryCache();
		$this->usersByUid          = new CappedMemoryCache();
	}

	/**
	 * @brief binds manager to an instance of IUserTools (implemented by
	 * Access). It needs to be assigned first before the manager can be used.
	 * @param IUserTools
	 */
	public function setLdapAccess(IUserTools $access) {
		$this->access = $access;
	}

	/**
	 * @brief creates an instance of User and caches (just runtime) it in the
	 * property array
	 * @param string $dn the DN of the user
	 * @param string $uid the internal (owncloud) username
	 * @return \OCA\User_LDAP\User\User
	 */
	private function createAndCache($dn, $uid) {
		$this->checkAccess();
		$user = new User($uid, $dn, $this->access, $this->ocConfig,
			$this->ocFilesystem, clone $this->image, $this->ocLog,
			$this->avatarManager, $this->userManager, 
			$this->notificationManager);
		$this->usersByDN[$dn]   = $user;
		$this->usersByUid[$uid] = $user;
		return $user;
	}

	/**
	 * removes a user entry from the cache
	 * @param $uid
	 */
	public function invalidate($uid) {
		if(!isset($this->usersByUid[$uid])) {
			return;
		}
		$dn = $this->usersByUid[$uid]->getDN();
		unset($this->usersByUid[$uid]);
		unset($this->usersByDN[$dn]);
	}

	/**
	 * @brief checks whether the Access instance has been set
	 * @throws \Exception if Access has not been set
	 * @return null
	 */
	private function checkAccess() {
		if(is_null($this->access)) {
			throw new \Exception('LDAP Access instance must be set first');
		}
	}

	/**
	 * returns a list of attributes that will be processed further, e.g. quota,
	 * email, displayname, or others.
	 *
	 * @param bool $minimal - optional, set to true to skip attributes with big
	 * payload
	 * @return string[]
	 */
	public function getAttributes($minimal = false) {
		$attributes = array_merge(Access::UUID_ATTRIBUTES, ['dn', 'uid', 'samaccountname', 'memberof']);
		$possible = array(
			$this->access->getConnection()->ldapExpertUUIDUserAttr,
			$this->access->getConnection()->ldapQuotaAttribute,
			$this->access->getConnection()->ldapEmailAttribute,
			$this->access->getConnection()->ldapUserDisplayName,
			$this->access->getConnection()->ldapUserDisplayName2,
		);
		foreach($possible as $attr) {
			if(!is_null($attr)) {
				$attributes[] = $attr;
			}
		}

		$homeRule = $this->access->getConnection()->homeFolderNamingRule;
		if(strpos($homeRule, 'attr:') === 0) {
			$attributes[] = substr($homeRule, strlen('attr:'));
		}

		if(!$minimal) {
			// attributes that are not really important but may come with big
			// payload.
			$attributes = array_merge(
				$attributes,
				$this->access->getConnection()->resolveRule('avatar')
			);
		}

		return $attributes;
	}

	/**
	 * Checks whether the specified user is marked as deleted
	 * @param string $id the Nextcloud user name
	 * @return bool
	 */
	public function isDeletedUser($id) {
		$isDeleted = $this->ocConfig->getUserValue(
			$id, 'user_ldap', 'isDeleted', 0);
		return intval($isDeleted) === 1;
	}

	/**
	 * creates and returns an instance of OfflineUser for the specified user
	 * @param string $id
	 * @return \OCA\User_LDAP\User\OfflineUser
	 */
	public function getDeletedUser($id) {
		return new OfflineUser(
			$id,
			$this->ocConfig,
			$this->db,
			$this->access->getUserMapper());
	}

	/**
	 * @brief returns a User object by it's Nextcloud username
	 * @param string $id the DN or username of the user
	 * @return \OCA\User_LDAP\User\User|\OCA\User_LDAP\User\OfflineUser|null
	 */
	protected function createInstancyByUserName($id) {
		//most likely a uid. Check whether it is a deleted user
		if($this->isDeletedUser($id)) {
			return $this->getDeletedUser($id);
		}
		$dn = $this->access->username2dn($id);
		if($dn !== false) {
			return $this->createAndCache($dn, $id);
		}
		return null;
	}

	/**
	 * @brief returns a User object by it's DN or Nextcloud username
	 * @param string $id the DN or username of the user
	 * @return \OCA\User_LDAP\User\User|\OCA\User_LDAP\User\OfflineUser|null
	 * @throws \Exception when connection could not be established
	 */
	public function get($id) {
		$this->checkAccess();
		if(isset($this->usersByDN[$id])) {
			return $this->usersByDN[$id];
		} else if(isset($this->usersByUid[$id])) {
			return $this->usersByUid[$id];
		}

		if($this->access->stringResemblesDN($id) ) {
			$uid = $this->access->dn2username($id);
			if($uid !== false) {
				return $this->createAndCache($id, $uid);
			}
		}

		return $this->createInstancyByUserName($id);
	}

}
