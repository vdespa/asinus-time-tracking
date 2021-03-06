<?php
/**
 * @package        Joomla.Site
 * @subpackage     com_asinustimetracking
 * @copyright      Copyright (c) 2014 - 2015, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.environment.browser');

// Load helper.
require_once JPATH_SITE . '/components/com_asinustimetracking/helpers/AsinustimetrackingHelper.php';
require_once JPATH_SITE . '/components/com_asinustimetracking/helpers/AsinustimetrackingUpdateHelper.php';

class AsinustimetrackingViewMonthlyreport extends JViewLegacy
{
	protected $user;

	protected $items;

	protected $settings;

	/**
	 * @var stdClass
	 * @deprecated Use $settings
	 */
	protected $meta;

	public function display($tpl = null)
	{
		// Set model state
		JRequest::setVar('filter_order', 'e.entry_date ASC, e.start_time ASC');

		// Get user
		$this->model = $this->getModel();
		$this->user  = $this->model->getUser(JRequest::getInt('filter_user'));

		// Get items
		$this->items = $this->get('ItemsGroupedByDate');

		// Meta data
		$this->meta               = new stdClass();
		$this->meta->report_year  = JRequest::getInt('filter_year');
		$this->meta->report_month = JRequest::getInt('filter_month');
		$this->meta->filename     = $this->meta->report_year . '-' . $this->meta->report_month . '-' . $this->user->name;
		$this->meta->logo_path    = JPATH_SITE . '/' . AsinustimetrackingHelper::getParameter('report_pdf_logo');
		$this->meta->logo         = AsinustimetrackingHelper::getParameter('report_pdf_logo');
		$this->meta->title        = AsinustimetrackingHelper::getParameter('report_pdf_title');
		$this->meta->show_logo    = AsinustimetrackingHelper::getParameter('report_pdf_show_title') == 2 ? true : false;

		// Settings
		$this->settings               = new stdClass();
		$this->settings->save_to_temp = AsinustimetrackingHelper::getParameter('report_save_to_temp') == 1 ? true : false;

		//echo '<pre>'; print_r($this->items);die;

		$excelTemplate = AsinustimetrackingHelper::getParameter('report_excel_template', 'FILE_NOT_FOUND');

		// Fix missing report
		if ($excelTemplate === 'FILE_NOT_FOUND')
		{
			$excelTemplate = AsinustimetrackingUpdateHelper::fixMissingExcelReportSetting();
		}

		// TODO - Refactor this code, maybe with a regex.
		// Remove prefix
		$viewName = str_replace('monthly-report-', '', $excelTemplate);
		// Remove OVERRIDE
		$viewName = str_replace('-OVERRIDE', '', $viewName);
		// Remove file extension
		$viewName = str_replace('.xlsx', '', $viewName);

		parent::display('excel-' . $viewName);
	}

	/**
	 * Output to brower the generated Excel file
	 *
	 * @param PHPExcel_Writer_Excel2007 $objWriter
	 */
	protected function outputToBrowser(PHPExcel $objPHPExcel)
	{
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		// We'll be outputting an excel file
		header('Content-type: application/vnd.ms-excel');

		// It will be called file.xls
		header('Content-Disposition: attachment;' . self::getContentDisposition($this->meta->filename));

		/**
		 * Save the file locally and later output to browser
		 * This is in place to fix the error:  Could not close zip file php://output.500
		 */
		if ($this->settings->save_to_temp === true)
		{
			$tmpfname = tempnam(JPATH_ROOT . '/tmp/', "ATT");
			$objWriter->save($tmpfname);
			readfile($tmpfname);
			unlink($tmpfname);
		}
		/*
		 * Direct browser output
		 */
		else
		{
			// Write file to the browser
			$objWriter->save('php://output');
		}
	}

	/**
	 * Figure a cross browser compatible way to get a non-ASCII file name to display properly.
	 *
	 * @param $filename
	 *
	 * @return string
	 */
	protected static function getContentDisposition($filename)
	{
		$browser = JBrowser::getInstance()->getBrowser();

		switch ($browser)
		{
			// Works in FF32
			case 'mozilla':
				$content = 'filename="' . $filename . '.xlsx"';
				break;
			// Works in IE11
			case 'msie':
			// Works in Chrome 37
			case 'chrome':
			default:
				$content = 'filename="' . rawurlencode($filename) . '.xlsx"';
		}

		return $content;
	}

	/**
	 * @return PHPExcel
	 */
	protected function loadPHPExcelFromTemplate()
	{
		// Get value from configuration
		$excelTemplate     = AsinustimetrackingHelper::getParameter('report_excel_template', 'FILE_NOT_FOUND');
		$excelTemplateFile = JPATH_ROOT . '/media/com_asinustimetracking/report-templates/' . $excelTemplate;

		if (!JFile::exists($excelTemplateFile))
		{
			// Try to fix issues
			AsinustimetrackingUpdateHelper::fixMissingExcelReportSetting();

			// Retry
			return $this->loadPHPExcelFromTemplate();
		}

		// Load template to a PHPExcel Object
		$objPHPExcel = PHPExcel_IOFactory::load($excelTemplateFile);

		return $objPHPExcel;
	}
}