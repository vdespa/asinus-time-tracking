<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2016, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die;

class AsinusTimeTrackingModelRolesedit extends JModelLegacy
{
	var $_tablename = '#__asinustimetracking_roles';

	function getById($id = 0)
	{
		$query   = "SELECT * FROM $this->_tablename WHERE crid=" . (int) $id;
		$_result = $this->_getList($query);

		if (array_key_exists(0, $_result))
		{
			return $_result[0];
		}
		else
		{
			$role = new stdClass();
			$role->description = '';
			$role->crid = null;
			return $role;
		}
	}

	function merge($crid = null, $description = '')
	{
		$db = JFactory::getDBO();

		$query = "UPDATE $this->_tablename SET description=" . $db->quote($description) . " WHERE crid=" . (int) $crid;

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}
	}

	function remove($crid = null)
	{

		$query = "SELECT count(*) as anz FROM #__asinustimetracking_user WHERE crid=" . (int) $crid;

		$test = $this->_getList($query);

		if ($test[0]->anz > 0)
		{
			JError::raiseWarning(100, JText::_('COM_ASINUSTIMETRACKING_ROLES_DELETING_ERROR_MSG'));
		}
		else
		{
			$db    = JFactory::getDBO();
			$query = "DELETE FROM $this->_tablename WHERE crid=" . (int) $crid;

			$db->setQuery($query);

			if (!$db->query())
			{
				JError::raiseError(500, $db->getErrorMsg());

				return false;
			}

			JError::raiseNotice(100, JText::_('COM_ASINUSTIMETRACKING_ROLES_DELETED_SUCCESS_MSG'));
		}
	}

	function create($description = '')
	{
		$db = JFactory::getDBO();

		$query = "INSERT INTO $this->_tablename (description) VALUES (" . $db->quote($description) . ")";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}

	}
}