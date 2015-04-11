<?php
/**
 * @package        Joomla.Site
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2015, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.date' );

// TimeTrack Config
require_once(JPATH_COMPONENT . '/models/preferences.php');

class AsinusTimeTrackingViewTimeTrackList extends JViewLegacy
{
	function display($tpl = null){
		// Config
		$cfg_model = new TimeTrackModelPreferences;
		$cfg = $cfg_model->getPreferences();

		$document = & JFactory::getDocument();

		$document->setTitle($cfg['print_page_title']);

		// Set date selection, by default list last month
		$ct_startdate = JRequest::getVar('ct_startdate', null);
		if(!$ct_startdate){
			//$ct_startdate = strtotime("-1 Month");
			$ct_startdate = strtotime(date('Y-m') . '-' . $cfg['first_day']);
		}

		$ct_enddate = JRequest::getVar('ct_enddate', null);
		if(!$ct_enddate){
			$ct_enddate = time();
		}

		$ct_cc = JRequest::getInt('ct_cc', 0);
		if(!$ct_cc){
			$ct_cc = -1;
		}

		$model 		=& $this->getModel();

		$ctUser 	= $model->getCtUser();

		$list = $model->getEntriesList($ctUser->cuid, 0, $ct_startdate, $ct_enddate, -1, -1, $ct_cc, -1);

		$user 	=& JFactory::getUser();

		$table = $this->_createTableContent($list, $model, $ctUser, $cfg);

		echo "<h2>" . JText::_("COM_TIMETRACK_ACCOUNTING_FOR") . ": " . $user->name  . "</h2>";
		echo "<h3>" . JText::_("COM_TIMETRACK_ACCOUNTING_PERIOD") . ": " . date('d.m.Y', $ct_startdate) . " - " . date('d.m.Y', $ct_enddate) . "</h3>";
			
		if($ct_cc >= 0){
		 echo JText::_("COM_TIMETRACK_CUSTOMER") . ": " . $model->getCostUnitById($ct_cc)->description;
		}

		echo "<br/><small>";
		echo $table->content;

		foreach ($table->itemsums as $sitem) {
			echo "<tr><td width='295'>&nbsp;</td><td width='225'>" . JText::_("COM_TIMETRACK_SUM") . " " . $sitem->desc . "</td><td width='100' align='right'>" . number_format($sitem->qty, 2, ",", ".") . " x </td><td width='100' align='right'>" . number_format($sitem->value, 2, ",", ".") . " ". $cfg['currency'] . "</td></tr>";
		}

		echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>' . JText::_("COM_TIMETRACK_ALL_SUM") . '</b></td><td width="200" align="right"><b> ' . number_format($table->gsum, 2, ",", ".") . ' '. $cfg['currency'] . '</b></td></tr>';

		if($cfg['print_tax']){
			$tax = round($table->gsum / 100 * $cfg['tax'], 2);
			echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>' . JText::_("COM_TIMETRACK_TAX") . ' ('. $cfg['tax'] . ' %)</b></td><td width="200" align="right"><b> ' . number_format($tax, 2, ",", ".") . ' ' . $cfg['currency'] . '</b></td></tr>';
			echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>' . JText::_("COM_TIMETRACK_TAX_INCL") . '</b></td><td width="200" align="right"><b> ' . number_format($tax + $table->gsum, 2, ",", ".") . ' ' . $cfg['currency'] . '</b></td></tr>';
		}

		echo '</table><hr><hr></small>';

	}

	function _createTableContent($list, $model, $ctUser, $cfg){

		// init
		$result->gsum = 0;
		$result->content = "";
		$usersum = 0;
		$result->timeuser = 0;
		$result->servuser = 0;
		$result->itemsums = array();

		$rowsum = 0;

		$result->content = '<table border=0 width="100%"><tr><th width="120px">'. JText::_("COM_TIMETRACK_DATE") .'</th><th width="175px">' . JText::_("COM_TIMETRACK_SERVICE") . '(' . JText::_("COM_TIMETRACK_PROJECT") . ')</th><th width="150px">' . JText::_("COM_TIMETRACK_TIME") . '</th><th align="right" width="75px">'. JText::_("COM_TIMETRACK_QTY") .'</th><th align="right" width="100px">'. JText::_("COM_TIMETRACK_PRICE") .'</th><th align="right" width="100px">'. JText::_("COM_TIMETRACK_SUM") .'</th></tr>';

		$k = 0;
		// List of user's entries
		for($i=0, $n=count($list); $i < $n; $i++){
			$entry =& $list[$i];

			$st = new JDate($entry->start_time);
			$et = new JDate($entry->end_time);
			$d = new JDate($entry->entry_date);
			$sp = new JDate($entry->start_pause);
			$ep = new JDate($entry->end_pause);

			$tstamp = $entry->timestamp;

			$a_service = $model->getServiceById($entry->cs_id);

			// Table Row
			$result->content .= "<tr>";

			// Date
			$result->content .= "<td valign='top' width='120px'>" . date('d.m.Y', $entry->entry_date) ."</td>";

			// Service
			$result->content .= "<td width='175px'>" . $a_service->description . "</td>";

			// Time
			$result->content .= "<td valign='top' width='150px'>&nbsp;";
			if ($a_service->is_worktime) {
				$result->content .= $st->toFormat('%H:%M') . " " . JText::_("COM_TIMETRACK_OCLOCK") . " - " . $et->toFormat('%H:%M') . " ". JText::_("COM_TIMETRACK_OCLOCK") . "</td>";
			}
			$result->content .= "</td>";

			// Qty
			if($entry->is_worktime){
				$q = round((($entry->end_time - $entry->start_time) - ($entry->end_pause - $entry->start_pause)) / 3600, 2);
			} else {
				$q = $entry->qty;
			}
			$result->content .= "<td align='right' width='75px'>" . number_format($q, 2, ",", ".") . "</td>";

			// Price
			$result->content .= "<td align='right' width='100px'>". number_format((double)$entry->price, 2, ",", ".") ." ". $cfg['currency'] . " </td>";

			// Row sum
			$rowsum = round((double)$entry->price * $q, 2);
			$result->content .= "<td align='right' width='100px'><b>" . number_format($rowsum, 2, ",", ".") . " ". $cfg['currency'] . "</b></td>";

			$result->content .= "</tr>";

			// Pause
			if($cfg['print_pause']){
				if ($a_service->is_worktime) {
					$result->content .= "<tr>";
					$result->content .= "<td width='120px'>&nbsp;</td><td width='175px'>(" . $model->getSelectionById($entry->cg_id)->description . ")</td><td width='150px'>(". JText::_("COM_TIMETRACK_PAUSE") . " " . $sp->toFormat('%H:%M') . " - " . $ep->toFormat('%H:%M') . " " . JText::_("COM_TIMETRACK_OCLOCK") .")";
					$result->content .= "</td></tr>";
				} else {
					$result->content .= "<tr>";
					$result->content .= "<td width='120px'>&nbsp;</td><td width='175px'>(" . $model->getSelectionById($entry->cg_id)->description . ")</td>";
					$result->content .= "</td></tr>";
				}
			}
			
			// Bemerkung
			$notice = "&nbsp;";
			if($cfg['print_notice']){
				if(strlen($entry->remark) > 0){
					$notice = JText::_("COM_TIMETRACK_NOTICE") . ": " . $entry->remark;
				}
				$result->content .= "<tr><td width=710px style='border: 1px solid #ffffff;'>" . $notice . "</td></tr>";
			}
			//$result->content .= "</small>";

			// End Table Row

			$usersum = round((double)$usersum + (double)$rowsum,2);
			$result->gsum = ((double)$result->gsum + (double)$rowsum * 1);

			if($entry->is_worktime){
				$result->timeuser = round((double)$result->timeuser + (double)$rowsum, 2);
			} else {
				$result->servuser = round((double)$result->servuser + (double)$rowsum, 2);
			}

			$k = 1 - $k;

			if($entry->entry_date <> $list[$i+1]->entry_date){
				$result->content .=  "<tr><td width=295>&nbsp;</td><td width=150><b>" . JText::_("COM_TIMETRACK_SUM") . "</td><td width=75>&nbsp;</td><td align='right'width=200> " . number_format($usersum, 2, ",", ".") . " ". $cfg['currency'] . "</b></td></tr>";
				$result->content .= "<tr><td><hr /></td></tr></table>";
				$usersum = 0;
			}

			// Aufsummieren der Summen des Benutzers
			if($result->itemsums[$a_service->description] && (count($result->itemsums[$a_service->description]) > 0))
			{
				$result->itemsums[$a_service->description]->value = $result->itemsums[$a_service->description]->value + (double)$rowsum;
				$result->itemsums[$a_service->description]->qty = $result->itemsums[$a_service->description]->qty + (double)$q;
			} else {
				$result->itemsums[$a_service->description]->desc = $a_service->description ;
				$result->itemsums[$a_service->description]->value = (double)$rowsum;
				$result->itemsums[$a_service->description]->qty = (double)$q;
			}

		}

		$result->content .= "</table>";

		return $result;

	}

}