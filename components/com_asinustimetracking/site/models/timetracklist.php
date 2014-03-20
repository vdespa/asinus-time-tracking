<?php
/**
 * TimeTrack, Frontend Component
 *
 * PHP version 5
 *
 * @category  Component
 * @package   TimeTrack
 * @author    Ralf Nickel <info@itrn.de>
 * @copyright 2011 Ralf Nickel
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version   SVN: $Id$
 * @link      http://www.itrn.de
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * Model of timetrack report
 *
 * @category Class
 * @package  TimeTrack
 * @author   Ralf Nickel <rn@itrn.de>
 * @license  GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.itrn.de
 */
class AsinusTimeTrackingModelTimeTrackList extends JModel
{

    /**
     *
     *Gets list of user's entries
     */
    function getEntriesList($uid, $max = 0, $startdate, $enddate, $service = -1,
        $selection = -1, $costunit = -1, $day = 1)
    {
        $database = &JFactory::getDBO();

        $query = "SELECT ct_id, cs_id, cg_id,"
            . " UNIX_TIMESTAMP(entry_date) as entry_date,"
            . " UNIX_TIMESTAMP(start_time) as start_time,"
            . " UNIX_TIMESTAMP(end_time) as end_time,"
            . " UNIX_TIMESTAMP(start_pause) as start_pause,"
            . " UNIX_TIMESTAMP(end_pause) as end_pause,"
            . " UNIX_TIMESTAMP(timestamp) as timestamp, e.qty,s.is_worktime, e.remark, e.cc_id, u.* "
            . " from #__asinustimetracking_entries e, #__asinustimetracking_services s, #__asinustimetracking_userservices u "
            . " where e.cs_id = s.csid AND (u.cu_id = e.cu_id AND u.csid=e.cs_id) AND u.cu_id ="
            . $uid;

        if ($service >= 0) {
            $query .= " AND s.csid=$service";
        }

        if ($costunit >= 0) {
            $query .= " AND e.cc_id=$costunit";
        }

        if ($selection >= 0) {
            $query .= " AND e.cg_id=$selection";
        }

        if ($startdate > 0) {
            $query .= " AND UNIX_TIMESTAMP(entry_date) >= " . $startdate;
        }

        if ($enddate > 0) {
            $query .= " AND UNIX_TIMESTAMP(entry_date) <= " . $enddate;
        }

        $query .= " ORDER BY entry_date DESC, s.is_worktime DESC, cs_id ASC";

        if ($max > 0) {
            $query .= " LIMIT " . $max;
        }

        $database->setQuery($query);
        $result = $database->loadObjectList();

        // replace alternative prices if exist
        for ($i = 0; $i < count($result); $i++) {
            $prices = $this
                ->_getPriceRangeValue($uid, $result[$i]->cs_id,
                    $result[$i]->entry_date);
            if ($prices != null) {
                $result[$i]->price = $prices->price;
            }
        }

        return $result;
    }

    /**
     * Get timetrack user object
     * 
     * @return Ambigous <>
     */
    function getCtUser()
    {
        $user = &JFactory::getUser();

        $query = "SELECT * FROM #__asinustimetracking_user WHERE uid=$user->id";

        $_result = $this->_getlist($query);

        return $_result[0];

    }

    /**
     * Get the list of days
     */
    function getEntriesDays($uid, $max, $startdate, $enddate, $costUnit = -1)
    {
        $database = &JFactory::getDBO();

        $query = "SELECT entry_date FROM #__asinustimetracking_entries WHERE cu_id=$uid";

        if ($startdate > 0) {
            $query .= " AND UNIX_TIMESTAMP(entry_date) >= " . $startdate;
        }

        if ($enddate > 0) {
            $query .= " AND UNIX_TIMESTAMP(entry_date) <= " . $enddate;
        }

        if ($costUnit >= 0) {
            $query .= " AND cc_id=$costUnit";
        }

        $query .= " GROUP BY entry_date ORDER BY entry_date DESC";

        if ($max > 0) {
            $query .= " LIMIT " . $max;
        }

        $database->setQuery($query);
        $result = $database->loadObjectList();

        return $result;

    }

    /**
     * get all times of a day
     * 
     * @return object of(description, price, start_time, end_time, timevalue, start_pause, end_pause, pausevalue
     */
    function getDayTimes($uid, $date, $costUnit = -1)
    {
        $database = &JFactory::getDBO();

//         $query = "SELECT s.description, s.csid, (SELECT u.price FROM #__asinustimetracking_userservices AS u WHERE u.csid = s.csid AND u.cu_id=e.cu_id ) as price,"
//             . " start_time, end_time, sum(((MINUTE(end_time) / 60 + HOUR ( end_time)) - (MINUTE(start_time) / 60 + HOUR(start_time)))) AS timevalue,"
//             . " sum(start_pause) as start_pause, sum(end_pause) as end_pause"
//             . " FROM #__asinustimetracking_entries e, #__asinustimetracking_services s"
//             . " WHERE e.cs_id = s.csid AND e.cu_id =" . $uid . " AND entry_date = '"
//             . $date . "' AND s.is_worktime=1 ";

        $query = "SELECT s.description, s.csid, (SELECT u.price FROM #__asinustimetracking_userservices AS u WHERE u.csid = s.csid AND u.cu_id=e.cu_id ) as price,"
            . " start_time, end_time, sum(((MINUTE(end_time) / 60 + HOUR ( end_time)) - (MINUTE(start_time) / 60 + HOUR(start_time)))) AS timevalue,"
            . " start_pause, end_pause, sum(((MINUTE(end_pause) / 60 + HOUR (end_pause)) - (MINUTE(start_pause) / 60 + HOUR(start_pause)))) AS pausevalue"
            . " FROM #__asinustimetracking_entries e, #__asinustimetracking_services s"
            . " WHERE e.cs_id = s.csid AND e.cu_id =" . $uid . " AND entry_date = '"
            . $date . "' AND s.is_worktime=1 ";

        if ($costUnit >= 0) {
            $query .= " AND cc_id=$costUnit";
        }

        $query .= " GROUP BY e.entry_date, cs_id ORDER BY s.description ASC, cs_id ASC";

        $database->setQuery($query);
        $result = $database->loadObjectList();

        // replace alternative prices if exist
        for ($i = 0; $i < count($result); $i++) {
            $prices = $this
                ->_getPriceRangeValue($uid, $result[$i]->csid, strtotime($date));
            if ($prices != null) {
                $result[$i]->price = $prices->price;
            }
        }

        return $result;
    }

    function _getPriceRangeValue($cuid = -1, $csid = -1, $aktdate)
    {
        $query = "SELECT * FROM #__asinustimetracking_pricerange" . " WHERE cu_id="
            . (int) $cuid . " AND cs_id=" . (int) $csid
            . " AND UNIX_TIMESTAMP(start_time) <= " . $aktdate
            . " AND UNIX_TIMESTAMP(end_time) >= " . $aktdate;

        $_result = $this->_getList($query);

        return $_result[0];
    }

    /**
     * Get Services of a day by user
     * 
     * @return object description, price, qty, nsum
     */
    function getDayServices($uid, $date, $costUnit = -1)
    {

        $database = &JFactory::getDBO();

        $query = "SELECT ( SELECT u.price FROM #__asinustimetracking_userservices AS u"
            . " WHERE u.csid = s.csid AND u.cu_id=e.cu_id ) AS price,"
            . " s.description, s.csid, SUM(e.qty ) as qty "
            . " FROM #__asinustimetracking_entries e, #__asinustimetracking_services s"
            . " WHERE e.cs_id = s.csid AND e.cu_id=$uid AND"
            . " entry_date = '$date' AND" . " s.is_worktime=0";

        if ($costUnit >= 0) {
            $query .= " AND cc_id=$costUnit";
        }
        $query .= " GROUP BY e.entry_date, cs_id"
            . " ORDER BY s.description ASC, cs_id ASC";

        $database->setQuery($query);
        $result = $database->loadObjectList();

        // replace alternative prices if exist
        for ($i = 0; $i < count($result); $i++) {
            $prices = $this->_getPriceRangeValue($uid, $result[$i]->csid, $date);
            if ($prices != null) {
                $result[$i]->price = $prices->price;
            }
        }

        return $result;
    }

    /**
     * Get selection/project by id
     * 
     * @param int $id
     * 
     * @return object selection
     */
    function getSelectionById($id)
    {
        $query = "SELECT * FROM #__asinustimetracking_selection WHERE cg_id=$id";

        $_result = $this->_getList($query);

        return $_result[0];
    }

    /**
     * Get service by id
     */

    function getServiceById($sid)
    {
        $database = &JFactory::getDBO();

        $query = "SELECT * from #__asinustimetracking_services where csid=" . $sid;

        $database->setQuery($query);
        $result = $database->loadObject();

        return $result;

    }

    /**
     *
     * Get list of services assigned to user
     */
    function getServicesListByUser($uid)
    {
        $database = &JFactory::getDBO();

        $query = "SELECT * from #__asinustimetracking_userservices u, #__asinustimetracking_services s WHERE u.csid = s.csid AND u.cu_id="
            . $uid;

        $database->setQuery($query);

        $result = $database->loadObjectList();

        return $result;

    }

    /**
     * Get list of selections
     */
    function getSelectionsList()
    {
        $database = &JFactory::getDBO();

        $query = "SELECT * from #__asinustimetracking_selection";

        $database->setQuery($query);

        $result = $database->loadObjectList();

        return $result;
    }

    function getCostUnitsList()
    {
        $database = &JFactory::getDBO();

        $query = "SELECT * from #__asinustimetracking_costunit ORDER BY cc_id";

        $database->setQuery($query);

        $result = $database->loadObjectList();

        return $result;

    }

    function getCostUnitById($id)
    {
        //         $database = &JFactory::getDBO();

        $query = "SELECT * from #__asinustimetracking_costunit where cc_id=$id";

        $_result = $this->_getList($query);

        return $_result[0];
    }

}
