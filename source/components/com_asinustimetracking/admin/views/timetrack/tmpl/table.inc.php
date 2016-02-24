<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2016, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

function _createTableContent($list, $model, $ctUser, $startdate, $enddate, $sllist, $svlist, $cfg){
	// init
	$result = new stdClass();
	$result->gsum = 0;
	$result->content = "";
	$usersum = 0;
	$result->timeuser = 0;
	$result->servuser = 0;
	$result->itemsums = array();

	$rowsum = 0;

	$result->content = '
<table class="table table-striped">
	<thead>
		<tr>
			<th width="20"></th>
			<th width="100">' . JText::_("COM_ASINUSTIMETRACKING_DATE") . '</th>
			<th width="250">' . JText::_("COM_ASINUSTIMETRACKING_SERVICE") . '</th>
			<th width="100">' . JText::_("COM_ASINUSTIMETRACKING_START") . '</th>
			<th width="100">' . JText::_("COM_ASINUSTIMETRACKING_END") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_PAUSE_FROMTO") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_PRICE") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_QTY") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_SUM") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_NOTICE") . '</th>
			<th width="*"></th>
		</tr>
	</thead>
	<tbody>';

	$k = 0;
	// List of user's entries
	for($i=0, $n=count($list); $i < $n; $i++){


		$entry =& $list[$i];

		$checked 	= JHTML::_('grid.id',   $i, $entry->ct_id );
		// TODO: date selection
		$link = JRoute::_( "index.php?option=com_asinustimetracking&task=timetrackedit&cid[]=" . $entry->ct_id . "&ct_startdate=" . $startdate . "&ct_enddate=" . $enddate . "&ct_sllist=" . $sllist . "&ct_svlist=" . $svlist);

		$a_service = $model->getServiceById($entry->cs_id);


		// Table Row
		$result->content .= "<tr class='row$k'>";
		$result->content .= "<td>$checked</td>";
		$result->content .= "<td valign='top'>" . date('d.m.Y', $entry->entry_date) ."</td>";
		$result->content .= "<td><a href=$link>$a_service->description</a><br>(" . $model->getSelectionById($entry->cg_id)->description . " - " . $model->getCostUnitById($entry->cc_id)->description . ")</td>";

		//
		$result->content .= "<td valign='top'>";
		if ($a_service->is_worktime) {
			$result->content .= date('H:i', $entry->start_time) . " " . JText::_("COM_ASINUSTIMETRACKING_OCLOCK");
		} else {
			$result->content .= "&nbsp;";
		}
		$result->content .= "</td>";

		// Arbeitszeit
		$result->content .= "<td valign='top'>";
		if ($a_service->is_worktime) {
			$result->content .= date('H:i', $entry->end_time) . " " . JText::_("COM_ASINUSTIMETRACKING_OCLOCK");
		}
		$result->content .= "</td>";

		// Pausenzeit
		$result->content .= "<td valign='top'>";
		if ($a_service->is_worktime) {
			$result->content .= date('H:i', $entry->start_pause) . " - " . date('H:i', $entry->end_pause) . " " . JText::_("COM_ASINUSTIMETRACKING_OCLOCK");
		}
		$result->content .= "</td>";

		// Preis
		$result->content .= "<td align=right>". number_format((double) $entry->price, 2) ." " . $cfg['currency'] . " </td>";

		// Anzahl
		if($entry->is_worktime){
			$q = round((($entry->end_time - $entry->start_time) - ($entry->end_pause - $entry->start_pause)) / 3600, 2);
		} else {
			$q = $entry->qty;
		}

		$result->content .= "<td align=right>" . number_format($q, 2) . " x </td>";

		// Zeilensumme
		$rowsum = (double)$entry->price * $q;
		$result->content .= "<td align=right>" . number_format($rowsum, 2) . " " . $cfg['currency'] . " </td>";


		// Bemerkung
		$result->content .= "<td><textarea class='inputbox' rows=2 cols=30 readonly=readonly>" . $entry->remark . "</textarea></td>";

		$result->content .= '<td valign="bottom">';
		$result->content .= "</td></tr>";

		// End Table Row

		$usersum = round((double)$usersum + (double)$rowsum,2);
		$result->gsum = ((double)$result->gsum + (double)$rowsum * 1);

		$k = 1 - $k;

		// HOTFIX
		if (array_key_exists($i+1, $list))
		{
			if($entry->entry_date <> $list[$i+1]->entry_date){
				$result->content .=  "<tr style=background-color: #c1c1c1;><td colspan=11 align=right><b>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . ": " . number_format($usersum, 2) . " " . $cfg['currency'] . " </b></td></tr>";
				$usersum = 0;
			}
		}


		if(array_key_exists($a_service->description, $result->itemsums) && $result->itemsums[$a_service->description] && (count($result->itemsums[$a_service->description]) > 0))
		{
			$result->itemsums[$a_service->description]->value = $result->itemsums[$a_service->description]->value + (double)$rowsum;
			$result->itemsums[$a_service->description]->qty = $result->itemsums[$a_service->description]->qty + (double)$q;
		} else {
			// hotfix
			if (! $a_service)
			{
				$a_service = new stdClass();
				$a_service->description = '';
			}
			// HOTIFX @
			@$result->itemsums[$a_service->description]->desc = $a_service->description;
			$result->itemsums[$a_service->description]->value = (double)$rowsum;
			$result->itemsums[$a_service->description]->qty = (double)$q;
		}

	}

	$result->content .= "<tr><td colspan=11><hr /></td></tr></tbody></table>";

	return $result;
}
