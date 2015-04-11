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

defined('_JEXEC') or die;

class AsinusTimeTrackingModelSelectionsedit extends JModelLegacy
{
	var $_tablename = '#__asinustimetracking_selection';

	function getById($id = 0)
	{
		$query   = "SELECT * FROM $this->_tablename WHERE cg_id=" . $id;
		$_result = $this->_getList($query);

		if ($_result)
		{
			return $_result[0];
		}
		else
		{
			$empty              = new stdClass();
			$empty->description = '';
			$empty->cg_id       = 0;

			return $empty;
		}

	}

	function merge($id = null, $description = '')
	{
		$db = JFactory::getDBO();

		$query = "UPDATE $this->_tablename SET description='$description' WHERE cg_id=$id";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}
	}

	function remove($id = null)
	{

		$query = "SELECT count(*) as anz FROM #__asinustimetracking_entries WHERE cg_id=$id";

		$test = $this->_getList($query);

		if (($test[0]->anz > 0))
		{
			JError::raiseWarning(100, 'Abhängigkeiten vorhanden. Selektion kann nicht gelöscht werden.');
		}
		else
		{
			$db    = JFactory::getDBO();
			$query = "DELETE FROM $this->_tablename WHERE cg_id=$id";

			$db->setQuery($query);

			if (!$db->query())
			{
				JError::raiseError(500, $db->getErrorMsg());

				return false;
			}

			JError::raiseNotice(100, 'Selektion gelöscht');
		}
	}

	function create($description = '')
	{
		$db = JFactory::getDBO();

		$query = "INSERT INTO $this->_tablename (description) VALUES ('$description')";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}

	}
}