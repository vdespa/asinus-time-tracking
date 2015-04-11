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

jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');

require_once JPATH_SITE . '/components/com_asinustimetracking/helpers/AsinustimetrackingHelper.php';


class AsinustimetrackingViewMonthlyreport extends JViewLegacy
{
	protected $state;

	public function display($tpl = null)
	{
		$lastMonth = date_create()->modify('-1 month');
		$this->state = new stdClass();
		$this->state->filter_month = $lastMonth->format('m');
		$this->state->filter_year = $lastMonth->format('Y');

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_MONTHLYREPORT'), 'banners.png');
		JToolBarHelper::apply('monthlyreport.generate');
		JToolBarHelper::cancel('monthlyreport.cancel');
	}
}