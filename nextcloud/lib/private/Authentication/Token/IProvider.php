<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Christoph Wurst <christoph@owncloud.com>
 * @author Lukas Reschke <lukas@statuscode.ch>
 * @author Marcel Waldvogel <marcel.waldvogel@uni-konstanz.de>
 * @author Robin Appelman <robin@icewind.nl>
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

namespace OC\Authentication\Token;

use OC\Authentication\Exceptions\InvalidTokenException;
use OC\Authentication\Exceptions\PasswordlessTokenException;
use OCP\IUser;

interface IProvider {


	/**
	 * Create and persist a new token
	 *
	 * @param string $token
	 * @param string $uid
	 * @param string $loginName
	 * @param string|null $password
	 * @param string $name
	 * @param int $type token type
	 * @param int $remember whether the session token should be used for remember-me
	 * @return IToken
	 */
	public function generateToken($token, $uid, $loginName, $password, $name, $type = IToken::TEMPORARY_TOKEN, $remember = IToken::DO_NOT_REMEMBER);

	/**
	 * Get a token by token id
	 *
	 * @param string $tokenId
	 * @throws InvalidTokenException
	 * @throws ExpiredTokenException
	 * @return IToken
	 */
	public function getToken($tokenId);

	/**
	 * Get a token by token id
	 *
	 * @param string $tokenId
	 * @throws InvalidTokenException
	 * @return DefaultToken
	 * @throws ExpiredTokenException
	 */
	public function getTokenById($tokenId);

	/**
	 * Duplicate an existing session token
	 *
	 * @param string $oldSessionId
	 * @param string $sessionId
	 * @throws InvalidTokenException
	 */
	public function renewSessionToken($oldSessionId, $sessionId);

	/**
	 * Invalidate (delete) the given session token
	 *
	 * @param string $token
	 */
	public function invalidateToken($token);

	/**
	 * Invalidate (delete) the given token
	 *
	 * @param IUser $user
	 * @param int $id
	 */
	public function invalidateTokenById(IUser $user, $id);

	/**
	 * Invalidate (delete) old session tokens
	 */
	public function invalidateOldTokens();

	/**
	 * Save the updated token
	 *
	 * @param IToken $token
	 */
	public function updateToken(IToken $token);

	/**
	 * Update token activity timestamp
	 *
	 * @param IToken $token
	 */
	public function updateTokenActivity(IToken $token);

	/**
	 * Get all tokens of a user
	 *
	 * The provider may limit the number of result rows in case of an abuse
	 * where a high number of (session) tokens is generated
	 *
	 * @param IUser $user
	 * @return IToken[]
	 */
	public function getTokenByUser(IUser $user);

	/**
	 * Get the (unencrypted) password of the given token
	 *
	 * @param IToken $token
	 * @param string $tokenId
	 * @throws InvalidTokenException
	 * @throws PasswordlessTokenException
	 * @return string
	 */
	public function getPassword(IToken $token, $tokenId);

	/**
	 * Encrypt and set the password of the given token
	 *
	 * @param IToken $token
	 * @param string $tokenId
	 * @param string $password
	 * @throws InvalidTokenException
	 */
	public function setPassword(IToken $token, $tokenId, $password);

	/**
	 * Rotate the token. Usefull for for example oauth tokens
	 *
	 * @param IToken $token
	 * @param string $oldTokenId
	 * @param string $newTokenId
	 * @return IToken
	 */
	public function rotate(IToken $token, $oldTokenId, $newTokenId);
}
