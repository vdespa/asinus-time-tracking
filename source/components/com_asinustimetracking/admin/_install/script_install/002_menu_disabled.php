<?php
/**
 * @package      Asinus Time-Tracking
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


$db    = JFactory::getDbo();
$query = $db->getQuery(true);

// Delete backend menu item if it exists
// This should help to avoid duplicate entries
$query->delete('#__menu')
      ->where('title = ' . $db->quote('asinustimetracking'))
      ->where('client_id = 1');

$db->setQuery($query);
$db->execute();

$query->clear();
$query->delete('#__menu')
      ->where('title = ' . $db->quote('com_asinustimetracking'))
      ->where('menutype = ' . $db->quote('main'));

$db->setQuery($query);
$db->execute();


// Check if a projectfork menu already exists
$query->clear();
$query->select('COUNT(id)')
      ->from('#__menu_types')
      ->where('menutype = ' . $db->quote('asinustimetracking'));

$db->setQuery((string) $query);
$menu_exists = (int) $db->loadResult();


// Do nothing if the menu exists
if ($menu_exists) return true;


// Get the Menu model
JLoader::register('MenusModelMenu', JPATH_ADMINISTRATOR . '/components/com_menus/models/menu.php');
$menu_model = new MenusModelMenu(array('ignore_request' => true));

// Create the menu
$data = array('title'       => 'Asinus Time-Tracking',
              'menutype'    => 'asinustimetracking',
              'description' => 'Asinus Time-Tracking Menu');

$success = $menu_model->save($data);

