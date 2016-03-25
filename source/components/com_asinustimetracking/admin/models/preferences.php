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

class AsinusTimeTrackingModelPreferences extends JModelLegacy
{

	var $_values = array();
	var $_tablename = "#__asinustimetracking_config";

	function getPreferences()
	{

		$_values = array();

		// Mock values
		$_values['first_day'] = '01';
		$_values['currency']  = 'CURRENTY NOT DEFINED';
		$_values['tax']  = 19;
		$_values['print_tax']  = false;
		$_values['print_page_title']  = false;
		$_values['print_pause']  = false;
		$_values['print_notice']  = false;

		$query = "SELECT * FROM $this->_tablename";

		$data = $this->_getList($query);

		if ($data)
		{
			foreach ($data as $citem)
			{
				switch (strtoupper($citem->value))
				{
					case "TRUE":
						$_val = true;
						break;
					case "FALSE":
						$_val = false;
						break;
					default:
						$_val = $citem->value;
						break;
				}
				$_values[$citem->name] = $_val;
			}
		}

		return $_values;
	}

	function save($data)
	{
		$db    = JFactory::getDBO();
		$query = "DELETE FROM $this->_tablename";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}

		foreach ($data as $name => $value)
		{
			$query = "INSERT INTO $this->_tablename (name, value) VALUES (" . $db->quoteName($name) . ", " . $db->quote($value) . ")";

			$db->setQuery($query);
			if (!$db->query())
			{
				JError::raiseError(500, $db->getErrorMsg());

				return false;
			}
		}

		JError::raiseNotice(100, JText::_('COM_ASINUSTIMETRACKING_PREFERENCED_SAVED_SUCCESS_MSG'));
	}

}