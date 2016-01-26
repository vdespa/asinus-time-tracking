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

class AsinusTimeTrackingViewPriceRange extends JViewLegacy
{
	protected $priceRange;

	/**
	 * @var int
	 */
	protected $cpid;

	/**
	 * @var int
	 */
	protected $cuid;

	/**
	 * @var int
	 */
	protected $csid;

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

		$this->cpid = JRequest::getInt('ct_cpid', -1);
		$this->cuid = JRequest::getInt('ct_cuid', -1);
		$this->csid = JRequest::getInt('ct_csid', -1);

		$model = $this->getModel();

		$this->priceRange = $model->getById($this->cpid);

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
		JToolbarHelper::title(JText::_('COM_ASINUSTIMETRACKING_EDIT_PROJECT'), 'cube module');
		JToolBarHelper::cancel('useredit', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper::save('savepricerange', JText::_('COM_ASINUSTIMETRACKING_SAVE'));
	}

	/**
	 * Deprecated display method
	 *
	 * @deprecated
	 * @param null|string $tpl
	 */
	function displayLegacy($tpl = 'legacy')
	{
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_EDIT_PROJECT'), 'generic.png');
		JToolBarHelper:: cancel('useredit', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper:: save('savepricerange', JText::_('COM_ASINUSTIMETRACKING_SAVE'));

		$cpid = JRequest::getInt('ct_cpid', -1);
		$cuid = JRequest::getInt('ct_cuid', -1);
		$csid = JRequest::getInt('ct_csid', -1);

		$model = $this->getModel();

		$item = $model->getById($cpid);

		$this->assignRef('item', $item);
		$this->assignRef('cuid', $cuid);
		$this->assignRef('csid', $csid);
		$this->assignRef('cpid', $cpid);

		parent::display($tpl);
	}

}