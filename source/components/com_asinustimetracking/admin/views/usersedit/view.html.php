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

class AsinusTimeTrackingViewUsersedit extends JViewLegacy
{
	function display($tpl = null)
	{
		// imports
		require_once(JPATH_COMPONENT . '/models/pricerange.php');

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