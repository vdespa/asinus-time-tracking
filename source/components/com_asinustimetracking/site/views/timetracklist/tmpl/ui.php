<?php
/**
 * @package		TimeTrack for Joomla! 1.5
 * @version 	$Id: ui.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . '/helper/uicomponents.php');

class UI extends UIComponents
{
    function __construct()
    {
        ;
    }

    static function getJavaScripts($list, $model, $ctUser)
    {
        $result = "
			<script language='JavaScript' type='text/javascript'>
			function filterList(){
				document.form_timetracklist.view.value='timetracklist';
				document.form_timetracklist.submit();
			}
	
			function demodata(){
				document.form_timetracklist.task.value='demo';
				document.form_timetracklist.view.value='timetracklist';
				document.form_timetracklist.submit();
			}

			function openExportPopup(path,id){
    			var popup = window.open('', 'Export');
    			popup.document.write('');
			}
			</script>
			";

        return $result;
    }

    static function getSearchPanel($ctStartDate, $ctEndDate, $uid, $ct_fm, $ct_tm, $costList, $selcc)
    {
        $result = "<p>";

        $result .= "<table width='100%' border=0>";

        // date selection
        $result .= "<tr><td width='200px'>" . JText::_("COM_ASINUSTIMETRACKING_STARTDATE") . " ";
        $result .= JHTML::calendar(date('d.m.Y', $ctStartDate ? $ctStartDate : date(time('d.m.Y'))), "ct_startdate", 'ct_calstart', ('%d.%m.%Y'),
            'size=12 class=inputbox');
        $result .= "</td><td width='200px'>" . JText::_("COM_ASINUSTIMETRACKING_ENDDATE") . " "
            . JHTML::calendar(date('d.m.Y', $ctEndDate ? $ctEndDate : date(time('d.m.Y'))), "ct_enddate", 'ct_calend', ('%d.%m.%Y'),
                'size=12 class=inputbox');
        $result .= "</td>";

        // customer
        $result .= "<td  width='230px'>" . JText::_("COM_ASINUSTIMETRACKING_CUSTOMER") . "<select class='inputbox' name='ct_cc'>";

        if ($selcc == -1) {
            $selected = 'selected=selected';
        }
        $result .= "<option value='-1' $selected >" . JText::_("COM_ASINUSTIMETRACKING_ALL") . "</option>";

        $selected = '';
        foreach ($costList as $costUnit) {
            if ($selcc == $costUnit->cc_id) {
                $selected = 'selected=selected';
            }
            $result .= "<option value='$costUnit->cc_id' $selected >" . $costUnit->description . "</option>";
            $selected = '';
        }

        $result .= "</select></td>";
        // End Kunden

        $result .= "<td align='left'><a class='ttbutton' style='cursor:pointer; text-decoration: none;' onclick='javascript:filterList()' >"
            . JText::_("COM_ASINUSTIMETRACKING_REFRESH") . "</a></td>";
        $result .= "<td width='150px' align='right'><a class='ttbutton' style='text-decoration: none;' href='index.php?option=com_asinustimetracking&view=timetracklist&format=rep&ct_startdate=$ctStartDate&ct_enddate=$ctEndDate&ct_cc=$selcc' target='_blank'>"
            . JText::_("COM_ASINUSTIMETRACKING_EXPORT") . "</a></td>";
        $result .= "<td width='50px' align='right'><a class='ttbutton' style='text-decoration: none;' href='index.php?option=com_asinustimetracking&view=timetrack'>"
            . JText::_("COM_ASINUSTIMETRACKING_BACK") . "</td>";
        $result .= "</tr></table>";

        $result .= "</p>";
        return $result;
    }

    static function getDataTable($list, $model, $ctUser, $ctcc, $cfg)
    {
        $result->itemsums = array();
        $result->content = '<table width="100%" class="contentpane">
	<thead>
		<tr>
			<td class="sectiontableheader" width="100">' . JText::_("COM_ASINUSTIMETRACKING_DATE") . '</td>
			<td class="sectiontableheader" width="*">' . JText::_("COM_ASINUSTIMETRACKING_SERVICE") . '</td>
			<td class="sectiontableheader" width="150">' . JText::_("COM_ASINUSTIMETRACKING_TIME") . " / " . JText::_("COM_ASINUSTIMETRACKING_QTY")
            . '</td>
			<td class="sectiontableheader" width="100">' . JText::_("COM_ASINUSTIMETRACKING_PRICE") . '</td>
			<td class="sectiontableheader" width="100">' . JText::_("COM_ASINUSTIMETRACKING_TOTAL")
            . '</td>
			<td class="sectiontableheader" width="80"></td>
		</tr>
	</thead>
	<tbody>';

        //$list = $entriesDays;
        $usersum = 0;

        for ($ie = 0; $ie < count($list); $ie++) {
            $daysum = 0;
            $item = $list[$ie];
            $timeList = $model->getDayTimes($ctUser->cuid, $item->entry_date, $ctcc);
            $serviceList = $model->getDayServices($ctUser->cuid, $item->entry_date, $ctcc);

            $result->content .= "<tr><td><b>" . date('d.m.Y', strtotime($item->entry_date)) . "</b></td><td colspan='5'><hr></td></tr>";

            // Time
            for ($tli = 0; $tli < count($timeList); $tli++) {
                $ti = $timeList[$tli];

                // Description
                $result->content .= "<tr class='row" . $tli % 2 . "'>";
                $result->content .= "<td></td><td valign='top'>" . $ti->description . "</td>";

                // Timevalue in h
                $sumtime = round($ti->timevalue - $ti->pausevalue, 2);
                $result->content .= "<td>" . number_format(round($ti->timevalue, 2), 2) . " " . JText::_("COM_ASINUSTIMETRACKING_HOUR") . "<br />"
                    . 
                    // Sum h minus pause
                    number_format(round($ti->pausevalue, 2), 2) . " " . JText::_("COM_ASINUSTIMETRACKING_QTY_PAUSE") . "<br />" . "<hr>"
                    . number_format($sumtime, 2) . " " . JText::_("COM_ASINUSTIMETRACKING_QTY_TIME") . "<br />" . "<hr size=3></td>";

                // Price
                $result->content .= "<td valign='bottom' align='right'>" . number_format($ti->price, 2) . " " . $cfg['currency'] . "</td>";

                // Time sum
                $ts = round($sumtime * $ti->price, 2);
                $result->content .= "<td valign='bottom' align='right'>" . number_format($ts, 2) . " " . $cfg['currency'] . "</td>";

                $result->content .= "</tr>";
                $daysum += $ts;

                if ($itemsums[$ti->description] && (count($itemsums[$ti->description]) > 0)) {
                    $itemsums[$ti->description]->value = $itemsums[$ti->description]->value + (double) $ts;
                    $itemsums[$ti->description]->qty = $itemsums[$ti->description]->qty + (double) $sumtime;
                }
                else {
                    $itemsums[$ti->description]->desc = $ti->description;
                    $itemsums[$ti->description]->value = (double) $ts;
                    $itemsums[$ti->description]->qty = (double) $sumtime;
                }
            }

            // Services
            for ($sli = 0; $sli < count($serviceList); $sli++) {
                $result->content .= "<tr class='row" . $sli % 2 . "'>";

                // Description
                $result->content .= "<td></td><td valign='top'>" . $serviceList[$sli]->description . "</td>";

                // Qty
                $result->content .= "<td>" . $serviceList[$sli]->qty . "</td>";

                // Price
                $result->content .= "<td align='right'>" . number_format($serviceList[$sli]->price, 2) . " " . $cfg['currency'] . "</td>";

                // Sum service

                $sval = round($serviceList[$sli]->price * $serviceList[$sli]->qty, 2);
                $result->content .= "<td align='right'>" . number_format($sval, 2) . " " . $cfg['currency'] . "</td>";

                $result->content .= "</tr>";

                $daysum += $sval;

                if ($itemsums[$serviceList[$sli]->description] && (count($itemsums[$serviceList[$sli]->description]) > 0)) {
                    $itemsums[$serviceList[$sli]->description]->value = $itemsums[$serviceList[$sli]->description]->value
                        + (double) $serviceList[$sli]->price * $serviceList[$sli]->qty;
                    $itemsums[$serviceList[$sli]->description]->qty = $itemsums[$serviceList[$sli]->description]->qty
                        + (double) $serviceList[$sli]->qty;
                }
                else {
                    $itemsums[$serviceList[$sli]->description]->desc = $serviceList[$sli]->description;
                    $itemsums[$serviceList[$sli]->description]->value = (double) $serviceList[$sli]->price * $serviceList[$sli]->qty;
                    $itemsums[$serviceList[$sli]->description]->qty = (double) $serviceList[$sli]->qty;
                }
            }

            $result->itemsums = $itemsums;

            $result->content .= "<tr style='background-color: #E0E0E0;'><td colspan=4><b>" . JText::_("COM_ASINUSTIMETRACKING_SUM")
                . "</b></td><td align=right valign=bottom><b>" . number_format($daysum, 2) . " " . $cfg['currency'] . "</b></td><td></td></tr>";
            $usersum += $daysum;

        }

        foreach ($result->itemsums as $item) {
            $result->content .= "<tr style='font-weight: bold;'>" . "<td width='100'>&nbsp;</td>" . "<td width='*'>&nbsp;</td>" . "<td width='150'>"
                . JText::_("COM_ASINUSTIMETRACKING_SUM") . " " . $item->desc . "</td>" . "<td width='100' align='right'>" . number_format($item->qty, 2)
                . "x</td>" . "<td width='100' align='right'>" . number_format($item->value, 2) . " " . $cfg['currency'] . " </td>"
                . "<td width='80'>&nbsp;</td>" . "</tr>";

            //	echo $item->desc ."<br>";
        }

        # style='border-bottom: 3px double #333333; border-top: 1px solid #333333'

        $result->content .= "<tr style='background-color: #E0E0E0;'><td colspan=4><b>" . JText::_("COM_ASINUSTIMETRACKING_ALL_SUM")
            . "</b></td><td align=right valign=bottom><b>" . number_format($usersum, 2) . " " . $cfg['currency'] . "</b></td><td></td></tr>";

        if ($cfg['print_tax']) {
            $tax = round($usersum / 100 * $cfg['tax'], 2);
            $result->content .= '<tr><td align="left" colspan="4"><b>' . JText::_("COM_ASINUSTIMETRACKING_TAX") . ' (' . $cfg['tax']
                . ' %)</b></td><td width="200" align="right"><b> ' . number_format($tax, 2, ",", ".") . ' ' . $cfg['currency'] . '</b></td></tr>';
            $result->content .= '<tr style="background-color: #E0E0E0;"><td align="left" colspan="4"><b>' . JText::_("COM_ASINUSTIMETRACKING_TAX_INCL")
                . '</b></td><td width="200" align="right" style="border-bottom: 3px double #333333; border-top: 1px solid #333333"><b> '
                . number_format($tax + $usersum, 2, ",", ".") . ' ' . $cfg['currency'] . '</b></td><td width="80">&nbsp;</td></tr>';
        }

        $result->content .= "</tbody></table>";

        return $result;

    }
}

?>