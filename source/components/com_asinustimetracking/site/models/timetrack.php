<?php
/**
 * @package        Joomla.Site
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2016, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010 - 2011, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * TimeTrack Model
 */
class AsinusTimeTrackingModelTimeTrack extends JModelList
{

	/**
	 * Gets list of user's entries
	 *
	 * @param int       $userId only entries for selected userId
	 * @param timestamp $maxage timestamp of max time difference
	 *
	 * @return void
	 */
	function getEntriesList($userId, $maxage)
	{
		$database = JFactory::getDBO();

		$query = "SELECT ct_id, cu_id, cs_id, cg_id, cc_id,"
			. " UNIX_TIMESTAMP(entry_date) as entry_date,"
			. " UNIX_TIMESTAMP(start_time) as start_time,"
			. " UNIX_TIMESTAMP(end_time) as end_time,"
			. " UNIX_TIMESTAMP(start_pause) as start_pause,"
			. " UNIX_TIMESTAMP(end_pause) as end_pause, UNIX_TIMESTAMP(timestamp) as timestamp,"
			. " qty, remark" . " from #__asinustimetracking_entries where cu_id="
			. (int) $userId;

		if ($maxage)
		{
			$query .= " AND UNIX_TIMESTAMP(timestamp) >" . (int) $maxage;
		}

		$query .= " ORDER BY entry_date DESC, start_time";
		/*
		 if ($max > 0) {
			$query .= " LIMIT " . $max ;
			}
		 */
		$database->setQuery($query);
		$result = $database->loadObjectList();

		return $result;
	}

	public function getUser($userId = null)
	{
		if ((int) $userId === 0)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		// Query
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select all articles for users who have a username which starts with 'a'.
		// Order it by the created date.
		// Note by putting 'a' as a second parameter will generate `#__content` AS `a`
		$query
			->select($db->quoteName(array('u.name', 'u.username', 'u.email')))
			->select($db->quoteName(array('au.employee_id')))
			->from($db->quoteName('#__users', 'u'))
			->join('INNER', $db->quoteName('#__asinustimetracking_user', 'au') . ' ON (' . $db->quoteName('au.uid') . ' = ' . $db->quoteName('u.id') . ')')
			->where($db->quoteName('u.id') . '=' . (int) $userId)
			->order($db->quoteName('u.id') . ' DESC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// echo $query->dump();

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$result = $db->loadObject();

		return $result;
	}

	/**
	 * Get entry by entryId
	 *
	 * @param int $entryId entry id
	 *
	 * @return Ambiguous
	 */
	function getEntryById($entryId = -1)
	{
		$database = JFactory::getDBO();

		$query = "SELECT ct_id, cu_id, cs_id, cg_id, cc_id,"
			. " UNIX_TIMESTAMP(entry_date) as entry_date,"
			. " UNIX_TIMESTAMP(start_time) as start_time,"
			. " UNIX_TIMESTAMP(end_time) as end_time,"
			. " UNIX_TIMESTAMP(start_pause) as start_pause,"
			. " UNIX_TIMESTAMP(end_pause) as end_pause,"
			. " UNIX_TIMESTAMP(timestamp) as timestamp, qty, remark"
			. " FROM #__asinustimetracking_entries where ct_id=" . (int) $entryId;

		$database->setQuery($query);

		$result = $database->loadObject();

		return $result;
	}

	/**
	 * Get last timetrack entry
	 *
	 * @return object <mixed, NULL>
	 */
	function getLastEntry()
	{
		$database = JFactory::getDBO();
		$user     = $this->getCtUser();

		$query = "SELECT max(ct_id) as id from #__asinustimetracking_entries WHERE cu_id ="
			. (int) $user->cuid;

		$database->setQuery($query);

		$max = $database->loadObject();

		$query = "SELECT cs_id, cg_id, cc_id FROM #__asinustimetracking_entries where ct_id="
			. (int) $max->id;

		$database->setQuery($query);

		$result = $database->loadObject();

		//$result->

		return $result;
	}

	/**
	 * get timetrack user object by logged on joomla user
	 *
	 * @return object
	 */
	function getCtUser($userId = null)
	{
		if ((int) $userId === 0)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		$query = "SELECT * FROM #__asinustimetracking_user WHERE uid= " . (int) $userId;

		$_result = $this->_getlist($query);

		if ($_result)
		{
			return $_result[0];
		}

	}

	/**
	 * get Service by id
	 *
	 * @param int $serviceId service id
	 *
	 * @return object
	 */
	function getServiceById($serviceId)
	{
		$database = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_services where csid=" . (int) $serviceId;

		$database->setQuery($query);
		$result = $database->loadObject();

		return $result;

	}

	/**
	 * get selection / project by id
	 *
	 * @param int $selectionId selection id
	 *
	 * @return object of selection
	 *
	 */
	function getSelectionById($selectionId)
	{
		$query = "SELECT * FROM #__asinustimetracking_selection WHERE cg_id=" . (int) $selectionId;

		$_result = $this->_getList($query);

		return $_result[0];
	}

	/**     *
	 * Get list of services assigned to user
	 *
	 * @param int $uid timetrack user id
	 *
	 * @return array of services
	 */
	function getServicesListByUser($uid)
	{
		$database = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_userservices u, #__asinustimetracking_services s"
			. " WHERE u.csid = s.csid AND u.cu_id=" . (int) $uid
			. " ORDER BY s.csid";

		$database->setQuery($query);

		$result = $database->loadObjectList();

		return $result;

	}

	/**
	 * Get list of selections
	 *
	 * @return array()
	 */
	function getSelectionsList()
	{
		$database = JFactory::getDBO();

		if (JRequest::getCmd('task') == 'edit')
		{
			$query = "SELECT * from #__asinustimetracking_selection ORDER BY cg_id";
		}
		else
		{
			$query = "SELECT * from #__asinustimetracking_selection WHERE state = 1 ORDER BY cg_id";
		}

		$database->setQuery($query);

		$result = $database->loadObjectList();

		return $result;
	}

	/**
	 * get list of costunits
	 *
	 * @return array costunits
	 */
	function getCostUnitsList()
	{
		$database = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_costunit ORDER BY cc_id";

		$database->setQuery($query);

		$result = $database->loadObjectList();

		return $result;

	}

	/**
	 * get costunit by id
	 *
	 * @param int $costunitId costunit id
	 *
	 * @return object costunit
	 */
	function getCostUnitById($costunitId)
	{
		$query = "SELECT * from #__asinustimetracking_costunit where cc_id=" . (int) $costunitId;

		$_result = $this->_getList($query);

		return $_result[0];
	}

	public function getItemsGroupedByDate()
	{
		$groups = array();

		$items = $this->getItems();

		foreach ($items as $item)
		{
			$groupKey = $item->entry_date->format('d.m.Y');

			if (!array_key_exists($groupKey, $groups))
			{
				$groups[$groupKey]                       = new stdClass();
				$groups[$groupKey]->entry_date           = clone $item->entry_date;
				$groups[$groupKey]->periods              = array();
				$groups[$groupKey]->work_time            = clone $item->entry_date;
				$groups[$groupKey]->pause_time           = clone $item->entry_date;
				$groups[$groupKey]->work_time_with_pause = clone $item->entry_date;
			}

			array_push($groups[$groupKey]->periods, $item);

			$groups[$groupKey]->work_time->add($item->work_time_interval);
			$groups[$groupKey]->work_time_interval = $item->entry_date->diff($groups[$groupKey]->work_time);

			$groups[$groupKey]->pause_time->add($item->pause_time_interval);

			$groups[$groupKey]->work_time_with_pause->add($item->work_time_interval)->add($item->pause_time_interval);

			if (property_exists($groups[$groupKey], 'remark') === false)
			{
				$groups[$groupKey]->remark = '';
			}
			$groups[$groupKey]->remark .= $item->remark;

			if (property_exists($groups[$groupKey], 'project_name') === false)
			{
				$groups[$groupKey]->project_name = '';
			}
			// Make sure you are not adding the same string over again.
			if (property_exists($groups[$groupKey], 'project_name') === false || strpos($groups[$groupKey]->project_name, $item->project_name) === false)
			{
				$groups[$groupKey]->project_name .= $item->project_name;
			}

			$groups[$groupKey]->customer_name = $item->customer_name;
		}

		return $groups;
	}

	public function getItems()
	{
		$items = parent::getItems();

		$items = self::postProcessItems($items);

		$items = self::computeWorkTime($items);

		return $items;
	}

	protected static function postProcessItems($items)
	{
		// Create a DateTime Objects
		$dateFields = array(
			'entry_date',
			'start_time',
			'end_time',
			'start_pause',
			'end_pause',
			'timestamp'
		);

		foreach ($items as $item)
		{
			foreach ($dateFields as $dateField)
			{
				$item->{$dateField} = self::createDateFromString($item->{$dateField});
			}
		}

		return $items;
	}

	protected static function createDateFromString($string)
	{
		$date = new DateTime($string);

		return $date;
	}

	protected static function computeWorkTime($items)
	{
		foreach ($items as $item)
		{
			$startWork    = clone ($item->start_time);
			$endWork      = clone ($item->end_time);
			$workInterval = $startWork->diff($endWork);

			$startPause    = clone ($item->start_pause);
			$endPause      = clone $item->end_pause;
			$pauseInterval = $startPause->diff($endPause);

			$item->work_time = clone $item->entry_date;
			/* @var $item ->work_time DateTime */
			$item->work_time->add($workInterval);
			$item->work_time->sub($pauseInterval);
			$item->work_time_interval = $item->entry_date->diff($item->work_time);

			$item->pause_time = clone $item->entry_date;
			$item->pause_time->add($pauseInterval);
			$item->pause_time_interval = $pauseInterval;

			$item->work_time_with_pause = clone $item->entry_date;
			$item->work_time_with_pause->add($workInterval);
			$item->work_time_with_pause->add($pauseInterval);
		}

		return $items;
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 */
	protected function getListQuery()
	{
		$this->populateState();

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('e.ct_id, e.entry_date, e.start_time, e.end_time, e.start_pause, e.end_pause, e.timestamp, e.qty, e.remark');
		$query->from($db->quoteName('#__asinustimetracking_entries') . ' AS e');

		// Join over the services.
		$query->select('cs.description AS service_name');
		$query->join('LEFT', '#__asinustimetracking_services AS cs ON cs.csid = e.cs_id');

		// Join over projects
		$query->select('cp.description AS project_name');
		$query->join('LEFT', '#__asinustimetracking_selection AS cp ON cp.cg_id = e.cg_id');

		// Join over customers
		$query->select('cu.description AS customer_name');
		$query->join('LEFT', '#__asinustimetracking_costunit AS cu ON cu.cc_id = e.cc_id');

		// Filter by year and month.cg_id
		$firstDayInMonth = new DateTime($this->getState('filter.year') . '-' . $this->getState('filter.month') . '-15');
		$firstDayInMonth->modify('first day of this month');
		if ($firstDayInMonth instanceof DateTime)
		{
			$query->where('e.entry_date BETWEEN "' . $firstDayInMonth->format('Y-m-d') . '" AND LAST_DAY("' . $firstDayInMonth->format('Y-m-d') . '")');
		}

		// Filter by user
		$userId   = $this->getState('filter.user');
		$cuUserId = (int) $this->getCtUser($userId)->cuid;
		$query->where('e.cu_id = ' . $cuUserId);

		// Filter by customer
		$customerId = $this->getState('filter.customer');
		if ($customerId > 0)
		{
			$query->where('e.cc_id = ' . (int) $customerId);
		}

		$query->order($this->getState('filter.order'));

		return $query;
	}

	/**
	 * Auto-populate the model state
	 *
	 * @param null $ordering
	 * @param null $direction
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('site');

		// Load the filter state.
		$month = $this->getUserStateFromRequest($this->context . '.filter.month', 'filter_month', date('m'));
		$this->setState('filter.month', $month);

		$year = $this->getUserStateFromRequest($this->context . '.filter.year', 'filter_year', date('Y'));
		$this->setState('filter.year', $year);

		$orderBy = $this->getUserStateFromRequest($this->context . '.filter.order', 'filter_order', 'e.entry_date DESC, e.start_time ASC');
		$this->setState('filter.order', $orderBy);

		$user = $this->getUserStateFromRequest($this->context . '.filter.user', 'filter_user', JFactory::getUser()->id);
		$this->setState('filter.user', $user);

		$customer = $this->getUserStateFromRequest($this->context . '.filter.customer', 'filter_customer', 0);
		$this->setState('filter.customer', $customer);

		// Override "Configuration" - "Site" - "List limit" setting
		JRequest::setVar('limit', 500);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_asinustimetracking');
		$this->setState('params', $params);

		// List state information.
		parent::populateState();
	}
}