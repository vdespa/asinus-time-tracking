<?php
/**
 * @package        Joomla.Site
 * @subpackage     com_asinustimetracking
 * @copyright      Copyright (c) 2014, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

abstract class AsinustimetrackingHelper
{
	public static function getEmployeeOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id As value, name As text');
		$query->from('#__users AS a');
		$query->where('a.block = 0');
		$query->order('a.name ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public static function getCustomerOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('a.cc_id AS value, a.description AS text');
		$query->from('#__asinustimetracking_costunit AS a');
		$query->order('a.description ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public static function getMonthOptions()
	{
		// Initialize variables.
		$options = array();

		for ($i = 1; $i <=12; $i++)
		{
			array_push($options, JHtml::_('select.option', $i, JText::_($i)));
		}

		return $options;
	}

	public static function getMinutesOptions()
	{
		$options = array();

		foreach (range(0,55,5) as $value)
		{
			$min = new stdClass();
			$min->id = $value;
			$min->value = sprintf("%02d", $value);
			$options[] = $min;
		}

		return $options;
	}

	public static function getHoursOptions()
	{
		$options = array();
		foreach (range(0,23) as $value)
		{
			$hour = new stdClass();
			$hour->id = $value;
			$hour->value = sprintf("%02d", $value);
			$options[] = $hour;
		}

		return $options;
	}

	public static function loadMedia()
	{

	}

	public static function getParameter($paramName, $defaultValue = null)
	{
		$param = JComponentHelper::getParams('com_asinustimetracking')->get($paramName, $defaultValue);

		return $param;
	}
} 