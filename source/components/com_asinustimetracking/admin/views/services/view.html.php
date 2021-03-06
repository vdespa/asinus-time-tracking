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

class AsinusTimeTrackingViewServices extends JViewLegacy
{
	/**
	 * @var
	 */
	protected $services;

	/**
	 * @inheritdoc
	 */
	function display($tpl = null)
	{
		if (AsinustimetrackingBackendHelper::isLegacyVersion() === true)
		{
			$this->displayLegacy();
			return true;
		}

		$this->services = $this->get('Services');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * @inheritdoc
	 */
	protected function addToolbar()
	{
		JToolbarHelper::title(JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_SERVICES'), 'cube module');
		JToolBarHelper::addNew('servicesedit', JText::_("COM_ASINUSTIMETRACKING_NEW"));
		JToolBarHelper::deleteList(JText::_('COM_ASINUSTIMETRACKING_Q_REMOVE'), 'removeservice', JText::_('COM_ASINUSTIMETRACKING_REMOVE'));
	}

	/**
	 * Deprecated display method
	 *
	 * @deprecated
	 * @param null|string $tpl
	 */
	function displayLegacy($tpl = 'legacy')
	{
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_SERVICES'), 'generic.png');
		JToolBarHelper:: addNew('servicesedit', JText::_("COM_ASINUSTIMETRACKING_NEW"));
		JToolBarHelper:: deleteList(JText::_('COM_ASINUSTIMETRACKING_Q_REMOVE'), 'removeservice', JText::_('COM_ASINUSTIMETRACKING_REMOVE'));
		JToolBarHelper:: spacer();
		JToolBarHelper:: custom('overview', 'ctoverview.png', 'ctoverview.png', JText::_('COM_ASINUSTIMETRACKING_OVERVIEW'), false);
		JToolBarHelper:: spacer();
		JToolBarHelper:: custom('users', 'ctuser.png', 'ctuser.png', JText::_('COM_ASINUSTIMETRACKING_USER'), false);
		JToolBarHelper:: custom('services', 'ctservice.png', 'ctservice.png', JText::_('COM_ASINUSTIMETRACKING_SERVICES'), false);
		JToolBarHelper:: custom('roles', 'ctroles.png', 'ctroles.png', JText::_('COM_ASINUSTIMETRACKING_USERROLES'), false);
		JToolBarHelper:: custom('selections', 'ctselection.png', 'ctselection.png', JText::_('COM_ASINUSTIMETRACKING_PROJECTS'), false);
		JToolBarHelper:: custom('costunits', 'costunit', 'costunit', JText::_('COM_TIMETRACK_COSTUNITS'), false);

		$items = $this->get('Services');

		$this->assignRef('items', $items);

		parent::display($tpl);
	}
}