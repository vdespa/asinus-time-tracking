<?php
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
			JText::_('COM_ASINUSTIMETRACKING_USER'),
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
	}
}
