<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2015, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die;

class AsinusTimeTrackingModelUsers extends JModelLegacy
{
	var $_users;

	function getUsers(){
		//$query = "SELECT * FROM #__users as u, #__asinustimetracking_user as c WHERE u.id=c.uid";
		$query = "SELECT * FROM #__users";
		$this->_users = $this->_getList($query);

		return $this->_users;
	}

	function createctUserByJUser($id){
		$query = "SELECT * FROM #__users WHERE id=$id";
		$juser = $this->_getList($query);

		$db = JFactory::getDBO();

		$query2 = "INSERT INTO #__asinustimetracking_user (crid, uid, is_admin) VALUES (2, $id, 0)";
		$db->setQuery($query2);

		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}

		$ctUser = $this->getctUserByJId($id);

		return $ctUser;
	}
	
	function getctUserByJId($id = null){
		$query = "SELECT c.*, r.description as roledesc, j.name as name, j.username as username FROM #__asinustimetracking_user c, #__asinustimetracking_roles r, #__users j WHERE c.uid=$id AND c.crid=r.crid AND c.uid = j.id";
		$result = $this->_getList($query);

		if (is_array($result) && array_key_exists(0, $result))
			return $result[0];
		else
			return null;
	}

	function getOrCreateCtUserByUid($id = null){
		//$query = "SELECT * FROM #__asinustimetracking_user WHERE uid=$id";
		$ctUser = $this->getctUserByJId($id);

		if ($ctUser){
			return $ctUser;
		} else {
			$ctUser = $this->createctUserByJUser($id);
			return $ctUser;
		}

	}

}