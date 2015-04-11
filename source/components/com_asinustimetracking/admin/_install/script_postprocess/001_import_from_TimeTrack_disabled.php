<?php
/**
 * @package      Projectfork
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die;

// App
$app    = JFactory::getApplication();

// Get database object
$db    = JFactory::getDbo();
$query = $db->getQuery(true);

// Check if com_timetrack already exists

$query->clear();
$query	->select('COUNT(extension_id)')
		->from('#__extensions')
		->where('element = ' . $db->quote('com_timetrack'));
$db->setQuery((string) $query);
$extension_exists = (int) $db->loadResult();

// Do nothing if the extension does not exist
if (! $extension_exists) return true;

/* Import old data */

$db->transactionStart();

$sqlImportGeneratedErrors = false;

$tables = array(
	'#__timetrack_config' => '#__asinustimetracking_config',
	'#__timetrack_costunit' => '#__asinustimetracking_costunit',
	'#__timetrack_entries' => '#__asinustimetracking_entries',
	'#__timetrack_pricerange' => '#__asinustimetracking_pricerange',
	'#__timetrack_roles' => '#__asinustimetracking_roles',
	'#__timetrack_selection' => '#__asinustimetracking_selection',
	'#__timetrack_services' => '#__asinustimetracking_services',
	'#__timetrack_userservices' => '#__asinustimetracking_userservices',
	'#__timetrack_user' => '#__asinustimetracking_user',

);

// Insert existing data
foreach ($tables as $old => $new)
{
	$query = "INSERT IGNORE `$new` SELECT * FROM `$old`;";
	$db->setQuery((string) $query);
	$result = $db->execute();
	if (! $result)
	{
		$message = "Trying to copy data from table $old failed with error: " . $db->getErrorMsg();
		$app->enqueueMessage(JText::_($message), 'error');

		$sqlImportGeneratedErrors = true;
	}
}

if ($sqlImportGeneratedErrors === false)
{
	$db->transactionCommit();

	// Notify the user about the data import
	$format = 'TimeTrack was discovered on your system. All the existing data was copied to Asinus Time-Tracking tables. Please check the imported data. After that, you can safely uninstall TimeTrack.';
	$app->enqueueMessage(JText::_($format));
}
else
{
	$db->transactionRollback();

	$message = 'TimeTrack was discovered on your system but existing data could NOT be copied to Asinus Time-Tracking tables due to SQL errors.';
	$app->enqueueMessage(JText::_($message), 'error');
}


