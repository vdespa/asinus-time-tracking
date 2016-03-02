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

abstract class AsinustimetrackingUpdateHelper
{
	static $componentName = 'com_asinustimetracking';

	/**
	 * Fix missing report file by updating component settings.
	 *
	 * This was introduced as a b/c fix when updating from version 1.1.14
	 *
	 * @return string
	 */
	public static function fixMissingExcelReportSetting()
	{
		$reportName = 'monthly-report-template-2.xlsx';

		// Add a new parameter (as per configuration)
		self::setParam('report_excel_template', $reportName);
		// Remove old parameter (which is no longer used)
		self::unsetParam('report_template');

		return $reportName;
	}

	public static function unsetParam($paramName)
	{
		// Get the params and set the new values
		$params = JComponentHelper::getParams(static::$componentName);

		// Unset parameter
		$data = $params->toObject();
		unset($data->{$paramName});

		// Re-create the registry object
		$params = new JRegistry($data);

		// Get the database object
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Build the query
		$query->update('#__extensions AS e');
		$query->set('e.params = ' . $db->quote((string) $params));
		$query->where('e.element = ' . $db->quote(static::$componentName));

		// Execute the query
		$db->setQuery($query);
		$db->query();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		}
	}

	/**
	 * Creates a new parameter
	 *
	 * @param $paramName
	 * @param $paramValue
	 */
	public static function setParam($paramName, $paramValue)
	{
		// Get the params and set the new values
		$params = JComponentHelper::getParams(static::$componentName);

		// Set parameter
		$params->set($paramName, $paramValue);

		// Get the database object
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Build the query
		$query->update('#__extensions AS e');
		$query->set('e.params = ' . $db->quote((string) $params));
		$query->where('e.element = ' . $db->quote(static::$componentName));

		// Execute the query
		$db->setQuery($query);
		$db->query();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		}
	}

	public static function renameParam($paramName)
	{
		// TODO implement me
		return false;
	}
} 