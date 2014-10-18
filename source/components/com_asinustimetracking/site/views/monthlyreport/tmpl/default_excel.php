<?php
// Try to increase memory limit
ini_set('memory_limit', '128M');

$f = JPATH_ROOT . '/media/com_asinustimetracking/report-templates/montly-report-template.xlsx';

/** Load template to a PHPExcel Object  **/
$objPHPExcel = PHPExcel_IOFactory::load($f);

/**
 * Header
 */

// Month / Year
$objPHPExcel->getActiveSheet()->setCellValue('A1', $this->meta->report_year . ' / ' . $this->meta->report_month);

// Employee Name and Id.
$objPHPExcel->getActiveSheet()->setCellValue('T1', JText::_('COM_ASINUSTIMETRACKING_EXCEL_EMPLOYEE_NAME') . ': ' . $this->user->name);
$objPHPExcel->getActiveSheet()->setCellValue('T2', JText::_('COM_ASINUSTIMETRACKING_EXCEL_EMPLOYEE_ID') . ': ' . $this->user->employee_id);

// Title
$objPHPExcel->getActiveSheet()->setCellValue('E1', $this->meta->title);

// Logo
if ($this->meta->show_logo === true)
{
	$maxWidth = 700;
	$maxHeight = 40;

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	$objDrawing->setName("Logo");
	$objDrawing->setDescription("Company Logo");
	$objDrawing->setPath($this->meta->logo_path);
	$objDrawing->setCoordinates('E1');
	$objDrawing->setHeight($maxHeight);
	$offsetX = round(($maxWidth - $objDrawing->getWidth()) / 2);
	$objDrawing->setOffsetX($offsetX);
}


/**
 * Body
 */
$currentRow = 6;

if (is_array($this->items) & ! empty($this->items))
{
	foreach ($this->items as $item)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $currentRow, $item->entry_date->format('d.m.Y'));
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $currentRow, $item->entry_date->format('W'));

		// Worktime #1
		if (array_key_exists(0, $item->periods))
		{
			$period = $item->periods[0];
			$objPHPExcel->getActiveSheet()->setCellValue('E' . $currentRow, $period->start_time->format('H:i'));
			$objPHPExcel->getActiveSheet()->setCellValue('F' . $currentRow, $period->end_time->format('H:i'));
		}

		// Worktime #2
		if (array_key_exists(1, $item->periods))
		{
			$period = $item->periods[1];
			$objPHPExcel->getActiveSheet()->setCellValue('G' . $currentRow, $period->start_time->format('H:i'));
			$objPHPExcel->getActiveSheet()->setCellValue('H' . $currentRow, $period->end_time->format('H:i'));
		}

		// Worktime #3
		if (array_key_exists(2, $item->periods))
		{
			$period = $item->periods[2];
			$objPHPExcel->getActiveSheet()->setCellValue('I' . $currentRow, $period->start_time->format('H:i'));
			$objPHPExcel->getActiveSheet()->setCellValue('J' . $currentRow, $period->end_time->format('H:i'));
		}

		// Total work time (substracting pause)
		$objPHPExcel->getActiveSheet()->setCellValue('K' . $currentRow, $item->work_time->format('H:i'));

		// Pause
		$objPHPExcel->getActiveSheet()->setCellValue('L' . $currentRow, $item->pause_time->format('H:i'));

		// Comments
		$objPHPExcel->getActiveSheet()->setCellValue('T' . $currentRow, $item->remark);

		// Increment row
		$currentRow++;
	}
}


/**
 * Write document
 */
$this->outputToBrowser($objPHPExcel);