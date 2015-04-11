<?php
defined('_JEXEC') or die;

/**
 * Class AsinustimetrackingControllerMonthlyreport
 *
 * TODO Rename model in frontend and unify controllers.
 */
class AsinustimetrackingControllerMonthlyreport extends JControllerLegacy
{
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_asinustimetracking');
		$this->redirect();
		return true;
	}

	public function generate()
	{
		$this->checkIfPHPExcelIsInstalled();
		self::loadLanguageFiles();

		$view = $this->getView('monthlyreport', 'excel');
		$model = $this->getModel('Monthlyreport');
		$view->setModel($model, true);
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

	protected static function loadLanguageFiles()
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_asinustimetracking', JPATH_SITE, $lang->getTag(), true);
	}
}