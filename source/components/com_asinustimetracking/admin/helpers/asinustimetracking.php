<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2016, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

/**
 * Component helper.
 */
class AsinustimetrackingBackendHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 *
	 * @return	void
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_ASINUSTIMETRACKING_OVERVIEW'),
			'index.php?option=com_asinustimetracking&view=timetrack',
			$vName == 'timetrack'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_ASINUSTIMETRACKING_USERS'),
			'index.php?option=com_asinustimetracking&view=users',
			$vName == 'users'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_ASINUSTIMETRACKING_USERROLES'),
			'index.php?option=com_asinustimetracking&view=roles',
			$vName == 'roles'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_ASINUSTIMETRACKING_SERVICES'),
			'index.php?option=com_asinustimetracking&view=services',
			$vName == 'services'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_ASINUSTIMETRACKING_PROJECTS'),
			'index.php?option=com_asinustimetracking&view=selections',
			$vName == 'selections'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_ASINUSTIMETRACKING_COSTUNITS'),
			'index.php?option=com_asinustimetracking&view=costunits',
			$vName == 'costunits'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_ASINUSTIMETRACKING_PREFERENCES'),
			'index.php?option=com_asinustimetracking&view=preferences',
			$vName == 'preferences'
		);
	}

	/**
	 * @return boolean
	 */
	public static function isLegacyVersion()
	{
		return (boolean) version_compare(JVERSION, '3.0.0', '<');
	}
}
