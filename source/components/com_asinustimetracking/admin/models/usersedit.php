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

class AsinusTimeTrackingModelUsersedit extends JModelLegacy
{
	var $_tablename = '#__asinustimetracking_user';

	function getById($id = 0)
	{
		$query   = 'SELECT * FROM ' . $this->_tablename. ' as c, #__users as u, #__asinustimetracking_roles as r ' .
				   'WHERE c.uid=u.id AND c.crid=r.crid AND cuid=' . (int) $id;
		$_result = $this->_getList($query);

		if ($_result)
		{
			if (property_exists($_result[0], 'employee_id') === false)
			{
				$_result[0]->employee_id = null;
			}
			return $_result[0];
		}
		else
		{
			return $this->createEmptyUser();
		}
	}

	/**
	 * @return stdClass
	 */
	public function createEmptyUser()
	{
		$item = new stdClass();
		$item->name = "";
		$item->employee_id = 0;
		$item->is_admin = 0;
		$item->crid = 0;
		$item->cuid = 0;
		return $item;
	}

	function merge($cuid = null, $crid = null, $is_admin = 0, $employee_id, $preise = array(0))
	{
		$db = JFactory::getDBO();

		$query = 'UPDATE ' .$this->_tablename. ' ' .
				 'SET crid=' . (int) $crid . ', is_admin=' . (int) $is_admin. ', employee_id=' . (int) $employee_id . ' ' .
				 'WHERE cuid=' . (int) $cuid;

		$db->setQuery($query);

		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}

		$this->_saveUserServices($cuid, $preise);

	}

	function _saveUserServices($cuid = null, $preise = array(0))
	{
		$db = JFactory::getDBO();

		$query = "DELETE FROM #__asinustimetracking_userservices WHERE cu_id=" . (int) $cuid;
		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}

		foreach ($preise as $key => $preis)
		{
			if ($preis && $preis > 0)
			{
				$preis = str_replace(",", ".", $preis);
				$query = 'INSERT INTO #__asinustimetracking_userservices (cu_id, csid, price) ' .
						 'VALUES(' . (int) $cuid . ', ' . (int) $key . ', ' . (float) $preis . ')';

				$db->setQuery($query);
				if (!$db->query())
				{
					JError::raiseError(500, $db->getErrorMsg());

					return false;
				}
			}
		}
	}

	function getUserServices($cuid = null)
	{
		$query   = "SELECT * FROM #__asinustimetracking_userservices ORDER BY csid";
		$_result = $this->_getlist($query);

		return $_result;
	}

	function getUserPrice($cuid = null, $csid = null)
	{
		$query = 'SELECT * FROM #__asinustimetracking_userservices ' .
				 'WHERE cu_id=' . (int) $cuid .' AND csid='. (int) $csid;
		$_result = $this->_getlist($query);

		if ($_result)
		{
			return $_result[0]->price;
		}
		else
		{
			return null;
		}

	}

	function getServices()
	{
		$query   = "SELECT * FROM #__asinustimetracking_services ORDER BY csid";
		$_result = $this->_getlist($query);

		return $_result;
	}

	function getRoles()
	{
		$query   = "SELECT * FROM #__asinustimetracking_roles ORDER BY crid";
		$_result = $this->_getlist($query);

		return $_result;
	}
}