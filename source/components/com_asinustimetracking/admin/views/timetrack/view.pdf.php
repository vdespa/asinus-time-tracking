<?php
/**
 * @package		TimeTrack
 * @version 	$Id: view.pdf.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.utilities.date' );

class AsinusTimeTrackingViewTimeTrack extends JView{
	function display($tpl = null){
		$model 		=& $this->getModel();

		require_once(JPATH_COMPONENT.DS.'models'.DS.'preferences.php');

		$cfg_model = new TimeTrackModelPreferences;
		$cfg = $cfg_model->getPreferences();

		$ctUlist 	= JRequest::getInt('ct_ulist', -1);
		$ctSllist	= JRequest::getInt('ct_sllist', -1);
		$ctSvlist	= JRequest::getInt('ct_svlist', -1);
		$ctRlist	= JRequest::getInt('ct_rlist', -1);
		$ct_fm 		= JRequest::getInt('ct_startdate', 0);
		$ct_tm 		= JRequest::getInt('ct_enddate', 0);
		$ct_cc		= JRequest::getInt('ct_cc', 0);


		$document = &JFactory::getDocument();

		// set document information
		$document->setTitle('TimeTrack - Administrator');
		$document->setName('');
		$document->setDescription('TimeTrack Export - www.itrn.de');

		if($ct_cc >= 0){
		 echo "<h2>" .  JText::_("COM_ASINUSTIMETRACKING_COSTUNIT") . ": " . $model->getCostUnitById($ct_cc)->description . "</h2>";
		}
		// TODO: DateFormat from preferences
		echo "<h2>" . JText::_("COM_ASINUSTIMETRACKING_ACCOUNTING_PERIOD") . ": " . date('d.m.Y', $ct_fm) . " - " . date('d.m.Y', $ct_tm) . "</h2>";

		if($ctUlist == -1){
			$cuserlist = $model->getUserList();
			$sumsum = 0;
			$timesum = 0;
			$servsum = 0;

			foreach ($cuserlist as $cuser) {
				$list = $model->getEntriesList($cuser->cuid, 0, $ct_fm, $ct_tm, $ctSvlist, $ctSllist, $ct_cc);
				if (($ctRlist == -1 || $cuser->crid == $ctRlist) && count($list) > 0 ){
					echo "<h2>$cuser->name</h2>";
					$table = $this->_createTableContent($list, $model, $cuser->cuid, $ctSllist, $ctSvlist, $cfg);
					echo "<br />" . $table->content;

					echo '<small><table width=100%>';

					foreach ($table->itemsums as $sitem) {
						echo "<tr><td width='295'>&nbsp;</td><td width='225'>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . " " . $sitem->desc . "</td><td width='100' align='right'>" . number_format($sitem->qty, 2, ",", ".") . " x </td><td width='100' align='right'>" . number_format($sitem->value, 2, ",", ".") . " " . $cfg['currency'] . " </td></tr>";

						// Gesamtsummen der Leistungen aller Benutzer
						if($itemsums[$sitem->desc] && (count($itemsums[$sitem->desc]) > 0))
						{
							$itemsums[$sitem->desc]->value = $itemsums[$sitem->desc]->value + (double)$sitem->value;
							$itemsums[$sitem->desc]->qty = $itemsums[$sitem->desc]->qty + (double)$sitem->qty;
						} else {
							$itemsums[$sitem->desc]->desc = $sitem->desc;
							$itemsums[$sitem->desc]->value = (double)$sitem->value;
							$itemsums[$sitem->desc]->qty = (double)$sitem->qty;
						}
					}

					echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TOTAL_USER") . ' ' . $cuser->name . '</b></td><td width="200" align="right"><b> ' . number_format($table->gsum, 2, ",", ".") . ' '. $cfg['currency'] . ' </b></td></tr>';

					if($cfg['print_tax']){
						$tax = $table->gsum / 100 * $cfg['tax'];
						echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TAX") . ' ('. $cfg['tax'] . ' %)</b></td><td width="200" align="right"><b> ' . number_format($tax, 2, ",", ".") . ' ' . $cfg['currency'] . '</b></td></tr>';
						echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TAX_INCL") . '</b></td><td width="200" align="right"><b> ' . number_format($tax + $table->gsum, 2, ",", ".") . ' ' . $cfg['currency'] . '</b></td></tr>';
					}

					echo '</table></small><hr><hr>';
					$sumsum = (double)$sumsum + (double)$table->gsum;
				}
			}
			echo '<small><table width=100%>';

			foreach ($itemsums as $sitem) {
				echo "<tr><td width='240'>&nbsp;</td><td width='280'>".JText::_("COM_ASINUSTIMETRACKING_TOTAL") . " $sitem->desc</td><td align='right' width='100'>" . number_format($sitem->qty,2, ",", ".") . " x </td><td width='100' align='right'>" . number_format($sitem->value,2, ",", ".") . " " . $cfg['currency'] . " </td></tr>";
			}

			echo '<tr><td align="right" width="240">&nbsp;</td><td width="280"><b>' . JText::_("COM_ASINUSTIMETRACKING_TOTAL_ALL_USER") . '</b></td><td width="200" align="right"><b> ' . number_format($sumsum, 2, ",", ".") . ' '. $cfg['currency'] . ' </b></td></tr><tr><td><hr /></td></tr><tr><td><hr /></td></tr></table></small>';
		} else {
			$cuser = $model->getCtUserById($ctUlist);
			echo "<h2>$cuser->name</h2>";

			$list = $model->getEntriesList($ctUlist, 0, $ct_fm, $ct_tm, $ctSvlist, $ctSllist, $ct_cc);
			$table = $this->_createTableContent($list, $model, $ctUlist, $ctSllist, $ctSvlist, $cfg);
			echo "<br />" . $table->content;
			echo '<small><table width=100%>';

			foreach ($table->itemsums as $sitem) {
				echo "<tr><td width='295'>&nbsp;</td><td width='225'>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . " $sitem->desc</td><td width='100' align='right'>" . number_format($sitem->qty, 2, ",", ".") . " x </td><td width='100' align='right'>" . number_format($sitem->value, 2, ",", ".") . " " . $cfg['currency'] . " </td></tr>";
			}

			echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>'.JText::_("COM_ASINUSTIMETRACKING_TOTAL_USER") .' ' . $cuser->name . '</b></td><td width="200" align="right"><b> ' . number_format($table->gsum, 2, ",", ".") . ' '. $cfg['currency'] . ' </b></td></tr>';

			if($cfg['print_tax']){
				$tax = $table->gsum / 100 * $cfg['tax'];
				echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TAX") . ' ('. $cfg['tax'] . ' %)</b></td><td width="200" align="right"><b> ' . number_format($tax, 2, ",", ".") . ' ' . $cfg['currency'] . '</b></td></tr>';
				echo '<tr><td width="295">&nbsp;</td><td width=225 align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TAX_INCL") . '</b></td><td width="200" align="right"><b> ' . number_format($tax + $table->gsum, 2, ",", ".") . ' ' . $cfg['currency'] . '</b></td></tr>';
			}

			echo '</table><hr><hr></small>';
		}

	}

	function _createTableContent($list, $model, $ctUser, $sllist, $svlist, $cfg){

		// init
		$result->gsum = 0;
		$result->content = "";
		$usersum = 0;
		$result->timeuser = 0;
		$result->servuser = 0;
		$result->itemsums = array();

		$rowsum = 0;

		$result->content = '<table border=0 width="100%"><tr><th width="120px">' . JText::_('COM_ASINUSTIMETRACKING_DATE') . '</th><th width="175px">' . JText::_('COM_ASINUSTIMETRACKING_DATE') .  '(' . JText::_('COM_ASINUSTIMETRACKING_PROJECT') . ')</th><th width="150px">' . JText::_('COM_ASINUSTIMETRACKING_TIME') . '</th><th align="right" width="75px">' . JText::_('COM_ASINUSTIMETRACKING_QTY') . '</th><th align="right" width="100px">' . JText::_('COM_ASINUSTIMETRACKING_PRICE') . '</th><th align="right" width="100px">' . JText::_('COM_ASINUSTIMETRACKING_SUM') . '</th></tr>';

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
			$result->content .= "<small><tr>";

			// Date
			$result->content .= "<td valign='top' width='120px'>" . date('d.m.Y', $entry->entry_date) . "</td>";

			// Service
			$result->content .= "<td width='175px'>" . $a_service->description . "</td>";

			// Zeit
			$result->content .= "<td valign='top' width='150px'>&nbsp;";
			if ($a_service->is_worktime) {
				$result->content .= $st->toFormat('%H:%M') . " Uhr - " . $et->toFormat('%H:%M') . " Uhr</td>";
			}
			$result->content .= "</td>";
			// </tr><tr><td width='420px'>&nbsp;</td>
			// Anzahl
			if($entry->is_worktime){
				$q = round((($entry->end_time - $entry->start_time) - ($entry->end_pause - $entry->start_pause)) / 3600, 2);
			} else {
				$q = $entry->qty;
			}
			$result->content .= "<td align='right' width='75px'>" . number_format($q, 2, ",", ".") . "</td>";

			// Preis
			$result->content .= "<td align='right' width='100px'>". number_format((double)$entry->price, 2, ",", ".") ." " . $cfg['currency'] . " </td>";

			// Zeilensumme
			$rowsum = (double)$entry->price * $q;
			$result->content .= "<td align='right' width='100px'><b>" . number_format($rowsum, 2, ",", ".") . " " . $cfg['currency'] . "</b></td>";

			$result->content .= "</tr>";

			// Pause
			$notice = "&nbsp;";
			if(strlen($entry->remark) > 0 && $cfg['print_notice']){
				$notice = "Bemerkung: " . $entry->remark;
			}

			if ($a_service->is_worktime) {
				$pstr = $cfg['print_pause'] ? "(" . JText::_('COM_ASINUSTIMETRACKING_PAUSE') . " " . $sp->toFormat('%H:%M') . " - " . $ep->toFormat('%H:%M') . " ". JText::_('COM_ASINUSTIMETRACKING_OCLOCK') .")" : "";

				$result->content .= "<tr>";
				$result->content .= "<td width='120px'>&nbsp;</td><td width='175px'>(" . $model->getSelectionById($entry->cg_id)->description . ")</td><td width='150px'>" . $pstr;
				$result->content .= "</td></tr>";
			} else {
				$result->content .= "<tr>";
				$result->content .= "<td width='120px'>&nbsp;</td><td width='175px'>(" . $model->getSelectionById($entry->cg_id)->description . ")</td>";
				$result->content .= "</td></tr>";
					
			}

			$result->content .= "<tr><td width='710px'>" . $notice . "</td></tr>";



			$result->content .= "</small>";

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
				$result->content .=  "<tr><td width=295>&nbsp;</td><td width=150><b>". JText::_("COM_ASINUSTIMETRACKING_TOTAL") . "</td><td width=75>&nbsp;</td><td align='right'width=200> " . number_format($usersum, 2, ",", ".") . " " . $cfg['currency'] . "</b></td></tr>";
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