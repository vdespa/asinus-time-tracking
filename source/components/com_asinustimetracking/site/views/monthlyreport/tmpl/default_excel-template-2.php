<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_asinustimetracking
 * @copyright	Copyright (c) 2014, Valentin Despa. All rights reserved.
 * @author		Valentin Despa - info@vdespa.de
 * @link		http://www.vdespa.de
 * @license 	GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

// Try to increase memory limit
ini_set('memory_limit', '128M');

/* @var PHPExcel $objPHPExcel */
$objPHPExcel = $this->loadPHPExcelFromTemplate();

/**
 * Header
 */

// Month / Year
$objPHPExcel->getActiveSheet()->setCellValue('A1', $this->meta->report_year . ' / ' . $this->meta->report_month);

// Employee Name and Id.
$objPHPExcel->getActiveSheet()->setCellValue('H1', JText::_('COM_ASINUSTIMETRACKING_EXCEL_EMPLOYEE_NAME'));
$objPHPExcel->getActiveSheet()->setCellValue('J1', $this->user->name);
$objPHPExcel->getActiveSheet()->setCellValue('H2', JText::_('COM_ASINUSTIMETRACKING_EXCEL_EMPLOYEE_ID'));
$objPHPExcel->getActiveSheet()->setCellValue('J2', $this->user->employee_id);

// Title
$objPHPExcel->getActiveSheet()->setCellValue('E1', $this->meta->title);

// Logo
if ($this->meta->show_logo === true)
{
	$maxWidth = 200;
	$maxHeight = 75;

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	$objDrawing->setName("Logo");
	$objDrawing->setDescription("Company Logo");
	$objDrawing->setPath($this->meta->logo_path);
	$objDrawing->setCoordinates('Q1');
	$objDrawing->setHeight($maxHeight);
	$offsetX = round(($maxWidth - $objDrawing->getWidth()) / 2);
	$objDrawing->setOffsetX($offsetX);
}


/**
 * Body
 */
$currentRow = 6;

$totalWorkTime = 0;

if (is_array($this->items) && ! empty($this->items))
{
	foreach ($this->items as $item)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $currentRow, $item->entry_date->format('d.m.Y'));
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $currentRow, $item->entry_date->format('W'));

		// Worktime #1
		if (array_key_exists(0, $item->periods))
		{
			$period = $item->periods[0];
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $currentRow, $period->start_time->format('H:i'));
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $currentRow, $period->end_time->format('H:i'));
		}

		// Worktime #2
		if (array_key_exists(1, $item->periods))
		{
			$period = $item->periods[1];
			$objPHPExcel->getActiveSheet()->setCellValue('E' . $currentRow, $period->start_time->format('H:i'));
			$objPHPExcel->getActiveSheet()->setCellValue('F' . $currentRow, $period->end_time->format('H:i'));
		}

		// Worktime #3
		if (array_key_exists(2, $item->periods))
		{
			$period = $item->periods[2];
			$objPHPExcel->getActiveSheet()->setCellValue('G' . $currentRow, $period->start_time->format('H:i'));
			$objPHPExcel->getActiveSheet()->setCellValue('H' . $currentRow, $period->end_time->format('H:i'));
		}

		// Total work time (including pause)
		$objPHPExcel->getActiveSheet()->setCellValue('I' . $currentRow, $item->work_time_with_pause->format('H:i'));

		// Pause
		$objPHPExcel->getActiveSheet()->setCellValue('J' . $currentRow, $item->pause_time->format('H:i'));

		// Total work time (subtracting pause)
		$objPHPExcel->getActiveSheet()->setCellValue('K' . $currentRow, (int) $item->work_time->format('H') + (($item->work_time->format('i') * 100) / 60) / 100);
		$totalWorkTime += (int) $item->work_time->format('H') + (($item->work_time->format('i') * 100) / 60) / 100;

		// Project
		$objPHPExcel->getActiveSheet()->setCellValue('L' . $currentRow, $item->project_name);

		// Comments
		$objPHPExcel->getActiveSheet()->setCellValue('Q' . $currentRow, $item->remark);

		// Increment row
		$currentRow++;
	}
}

/**
 * Footer
 */

// Contractor
$objPHPExcel->getActiveSheet()->setCellValue('B41', $this->user->name);

// Print date
$objPHPExcel->getActiveSheet()->setCellValue('B42', date_create()->format('d.m.Y'));

// Client
if (is_array($this->items) && ! empty($this->items))
{
	$objPHPExcel->getActiveSheet()->setCellValue('H43',reset($this->items)->customer_name);
}

// Monthly total work time
$objPHPExcel->getActiveSheet()->setCellValue('N38', $totalWorkTime);


/**
 * Formatting
 */

// Daily total work time
$objPHPExcel->getActiveSheet()
	->getStyle("K6:K36")
	->getNumberFormat()
	->setFormatCode('#,##0.00');

// Monthly total work time
$objPHPExcel->getActiveSheet()
	->getStyle("N38:N38")
	->getNumberFormat()
	->setFormatCode('#,##0.00');

/**
 * Write document
 */
$this->outputToBrowser($objPHPExcel);