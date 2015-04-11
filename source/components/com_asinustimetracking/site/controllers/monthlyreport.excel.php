<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class AsinusTimeTrackingControllerMonthlyreport extends JControllerForm
{
	public function generate()
	{
		// Check if PHPExcel is installed
		$this->checkIfPHPExcelIsInstalled();

		// Get model
		$timeTrackModel = $this->getModel('TimeTrack');

		$view = $this->getView('MonthlyReport', 'excel');
		$view->setModel($timeTrackModel, true );
		$view->display();
	}

	protected function checkIfPHPExcelIsInstalled()
	{
		if (self::isPHPExcelInstalled() === false)
		{
			// Redirect to login page.
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_ASINUSTIMETRACKING_ERROR_PHPEXCEL_NOT_FOUND'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_asinustimetracking', false));
			return true;
		}
	}

	/**
	 * Checks if PHPExcel is installed.
	 *
	 * @return bool
	 */
	protected static function isPHPExcelInstalled()
	{
		// Import PHPExcel library
		jimport('phpexcel.library.PHPExcel');

		jimport('phpexcel.library.PHPExcel.IOFactory');

		$PHPExcel = new PHPExcel();

		return ($PHPExcel instanceof PHPExcel) ? true : false;
	}
}
