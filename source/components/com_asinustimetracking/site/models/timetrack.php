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
 * Model of timetrack view
 *
 * @category Class
 * @package  TimeTrack
 * @author   Ralf Nickel <rn@itrn.de>
 * @license  GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.itrn.de
 */
class AsinusTimeTrackingModelTimeTrack extends JModel
{
    /**
     * Gets list of user's entries
     * 
     * @param int       $userId only entries for selected userId
     * @param timestamp $maxage timestamp of max timedifference
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

        if ($maxage) {
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

    /**
     * get timetrack user object by logged on joomla user
     *  
     * @return object
     */
    function getCtUser()
    {
        $user = JFactory::getUser();

        $query = "SELECT * FROM #__asinustimetracking_user WHERE uid= " . (int) $user->id;

        $_result = $this->_getlist($query);

		if ($_result)
        	return $_result[0];

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

        $query = "SELECT ct_id, cu_id, cs_id, cg_id,"
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
        $user = $this->getCtUser();

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
     * get Service by id
     *  
     * @param int $serviceId service id
     * 
     * @return object 
     */
    function getServiceById($serviceId)
    {
        $database = JFactory::getDBO();

        $query = "SELECT * from #__asinustimetracking_services where csid="
            . (int) $serviceId;

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
        $query = "SELECT * FROM #__asinustimetracking_selection WHERE cg_id="
            . (int) $selectionId;

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

        $query = "SELECT * from #__asinustimetracking_selection ORDER BY cg_id";

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
        $query = "SELECT * from #__asinustimetracking_costunit where cc_id="
            . (int) $costunitId;

        $_result = $this->_getList($query);

        return $_result[0];
    }

}

?>