<?php
defined('_JEXEC') or die;

$files = array(
	'/components/com_asinustimetracking/admin/views/monthlyreport/tmpl/default_excel.php',
	'/components/com_asinustimetracking/media/report-templates/montly-report-template.xlsx',
	'/components/com_asinustimetracking/site/views/monthlyreport/tmpl/default_excel.php'
);

$folders = array(
);

foreach ($files as $file) {
	if (JFile::exists(JPATH_ROOT . $file) && !JFile::delete(JPATH_ROOT . $file)) {
		echo JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $file).'<br />';
	}
}

foreach ($folders as $folder) {
	if (JFolder::exists(JPATH_ROOT . $folder) && !JFolder::delete(JPATH_ROOT . $folder)) {
		echo JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $folder).'<br />';
	}
}



