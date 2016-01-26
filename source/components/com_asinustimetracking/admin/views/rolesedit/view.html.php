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

class AsinusTimeTrackingViewRolesedit extends JViewLegacy
{
	protected $role;

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

		$crid = JRequest::getVar('cid', array(0), 'get');
		$model = $this->getModel();
		$this->role = $model->getById((int) $crid[0]);

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
		JFactory::getApplication()->input->set('hidemainmenu', true);
		JToolbarHelper::title(JText::_('COM_ASINUSTIMETRACKING_EDIT_ROLE'), 'users');
		JToolBarHelper::cancel('roles', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper::save('saveroles', JText::_('COM_ASINUSTIMETRACKING_SAVE'));
	}

	/**
	 * Deprecated display method
	 *
	 * @deprecated
	 * @param null|string $tpl
	 */
	function displayLegacy($tpl = 'legacy')
	{
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_EDIT_ROLE'), 'generic.png');
		JToolBarHelper:: cancel('roles', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper:: save('saveroles', JText::_('COM_ASINUSTIMETRACKING_SAVE'));

		$model = $this->getModel();

		$crid = JRequest::getVar('cid', array(0), 'get');
		$item = $model->getById((int) $crid[0]);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}
}