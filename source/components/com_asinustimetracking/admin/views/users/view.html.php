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

class AsinusTimeTrackingViewUsers extends JViewLegacy
{
	/**
	 * @var array
	 */
	protected $items = array();

	/**
	 * @var AsinusTimeTrackingModelUsers
	 */
	protected $model;

	/**
	 * @inheritdoc
	 */
	function display($tpl = null)
	{
		if (AsinustimetrackingBackendHelper::isLegacyVersion() === true)
		{
			$this->displayLegacy();
			return;
		}

		// Add message explaining that to create new users you first need to create them in Joomla.
		JFactory::getApplication()->enqueueMessage(
			JText::_('COM_ASINUSTIMETRACKING_USERS_HOW_TO_ADD_NEW_USER'), 'notice'
		);

		$this->items = $this->get('Users');
		$this->model = $this->getModel();

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
		JToolbarHelper::title(JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_USER'), 'users user');
	}

	/**
	 * Deprecated display method
	 *
	 * @deprecated
	 * @param null|string $tpl
	 */
	function displayLegacy($tpl = 'legacy')
	{
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_USER'), 'generic.png');
		JToolBarHelper:: custom('overview', 'ctoverview.png', 'ctoverview.png', JText::_('COM_ASINUSTIMETRACKING_OVERVIEW'), false);
		JToolBarHelper:: spacer();
		JToolBarHelper:: custom('users', 'ctuser.png', 'ctuser.png', JText::_('COM_ASINUSTIMETRACKING_USER'), false);
		JToolBarHelper:: custom('services', 'ctservice.png', 'ctservice.png', JText::_('COM_ASINUSTIMETRACKING_SERVICES'), false);
		JToolBarHelper:: custom('roles', 'ctroles.png', 'ctroles.png', JText::_('COM_ASINUSTIMETRACKING_USERROLES'), false);
		JToolBarHelper:: custom('selections', 'ctselection.png', 'ctselection.png', JText::_('COM_ASINUSTIMETRACKING_PROJECTS'), false);
		JToolBarHelper:: custom('costunits', 'costunit', 'costunit', JText::_('COM_ASINUSTIMETRACKING_COSTUNITS'), false);

		$items = $this->get('Users');
		$model = $this->getModel();

		$this->assignRef('items', $items);
		$this->assignRef('model', $model);

		parent::display($tpl);
	}
}