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

// Imports
require_once(JPATH_COMPONENT . '/models/pricerange.php');

class AsinusTimeTrackingViewUsersedit extends JViewLegacy
{
	protected $item;

	protected $model;

	/**
	 * @var AsinusTimeTrackingModelPriceRange
	 */
	protected $priceRangeModel;

	/**
	 * @param null|string $tpl
	 *
	 * @return mixed|void
	 */
	function display($tpl = null)
	{
		if (AsinustimetrackingBackendHelper::isLegacyVersion() === true)
		{
			$this->displayLegacy();
			return;
		}

		// Initialiase variables.
		$this->model   = $this->getModel();
		$csid    = JRequest::getVar('cid', array(0), 'get');
		$this->item = $this->model->getById((int) $csid[0]);
		$this->priceRangeModel = new AsinusTimeTrackingModelPriceRange();

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		JToolbarHelper::title(
			JText::_('COM_ASINUSTIMETRACKING_EDIT_USER'),
			'user'
		);
		JToolBarHelper:: cancel('users', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper:: save('saveuser', JText::_('COM_ASINUSTIMETRACKING_SAVE'));
	}

	/**
	 * Deprecated display method
	 *
	 * @deprecated
	 * @param null|string $tpl
	 */
	function displayLegacy($tpl = 'legacy')
	{
		// ToolBar
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_EDIT_USER'), 'generic.png');
		JToolBarHelper:: cancel('users', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper:: save('saveuser', JText::_('COM_ASINUSTIMETRACKING_SAVE'));

		$model   = $this->getModel();
		$csid    = JRequest::getVar('cid', array(0), 'get');
		$prModel = new AsinusTimeTrackingModelPriceRange();

		$item = $model->getById((int) $csid[0]);

		$this->assignRef('item', $item);
		$this->assignRef('model', $model);
		$this->assignRef('prModel', $prModel);

		parent::display($tpl);
	}
}