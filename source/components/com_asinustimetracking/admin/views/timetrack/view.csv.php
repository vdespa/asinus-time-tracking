<?php
/**
 * @package        Joomla.Administrator
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

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.utilities.date');

class AsinusTimeTrackingViewTimeTrack extends JViewLegacy
{
	function display($tpl = null)
	{

		$model =& $this->getModel();

		$ctUlist  = JRequest::getInt('ct_ulist', -1);
		$ctSllist = JRequest::getInt('ct_sllist', -1);
		$ctSvlist = JRequest::getInt('ct_svlist', -1);
		$ctRlist  = JRequest::getInt('ct_rlist', -1);
		$ct_fm    = JRequest::getVar('ct_startdate', 0);
		$ct_tm    = JRequest::getVar('ct_enddate', 0);
		$ct_cc    = JRequest::getInt('ct_cc', 0);

		// send header
		$application = "text/csv charset=Windows-1252"; // ;
		header("Content-Type: $application");
		header("Content-Disposition: attachment; filename=export.csv");
		header("Content-Description: csv File");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo "name;date;service;costunit;project;start;end;pause_start;pause_end;qty;price;sum;note\n";

		if ($ctUlist == -1)
		{
			$cuserlist = $model->getUserList();
			$sumsum    = 0;
			$timesum   = 0;
			$servsum   = 0;

			foreach ($cuserlist as $cuser)
			{
				//if($ctRlist > 0 && $cuser->crid == $ctRlist){
				if ($ctRlist == -1 || $cuser->crid == $ctRlist)
				{
					$list = $model->getEntriesList($cuser->cuid, 0, $ct_fm, $ct_tm, $ctSvlist, $ctSllist, $ct_cc);
					// only process if user has entries
					if (count($list) > 0)
					{
						$table = $this->_createTableContent($list, $model, $cuser->cuid, $ctSllist, $ctSvlist, $cuser->name);
						echo $table->content;

						$sumsum  = (double) $sumsum + (double) $table->gsum;
						$timesum = (double) $timesum + (double) $table->timeuser;
						$servsum = (double) $servsum + (double) $table->servuser;
					}
				}
			}

		}
		else
		{
			$cuser = $model->getCtUserById($ctUlist);

			$list = $model->getEntriesList($ctUlist, 0, $ct_fm, $ct_tm, $ctSvlist, $ctSllist, $ct_cc);

			$table = $this->_createTableContent($list, $model, $ctUlist, $ctSllist, $ctSvlist, $cuser->name);
			echo $table->content;
		}
	}

	function _createTableContent($list, $model, $ctUser, $sllist, $svlist, $name)
	{
		$deli = ';';
		// init
		$result->gsum     = 0;
		$result->content  = "";
		$usersum          = 0;
		$rowsum           = 0;
		$result->timeuser = 0;
		$result->servuser = 0;

		$k = 0;
		// List of user's entries
		for ($i = 0, $n = count($list); $i < $n; $i++)
		{
			$entry =& $list[$i];

			$st = new JDate($entry->start_time);
			$et = new JDate($entry->end_time);
			$d  = new JDate($entry->entry_date);
			$sp = new JDate($entry->start_pause);
			$ep = new JDate($entry->end_pause);

			$tstamp = $entry->timestamp;

			$a_service = $model->getServiceById($entry->cs_id);

			// Row

			// Name
			$result->content .= "\"" . mb_convert_encoding($name, "Windows-1252", "UTF-8") . "\"$deli";
			// Date
			$result->content .= date('d.m.Y', $entry->entry_date) . "$deli";

			// Service
			$result->content .= "\"" . mb_convert_encoding($a_service->description, "Windows-1252", "UTF-8") . "\"$deli";

			// Kunde
			$result->content .= "\"" . mb_convert_encoding($model->getCostUnitById($entry->cc_id)->description, "Windows-1252", "UTF-8") . "\"$deli";

			// Selektion.
			$a_sel = $model->getSelectionById($entry->cg_id);
			$result->content .= "\"" . mb_convert_encoding($a_sel->description, "Windows-1252", "UTF-8") . "\"$deli";

			// Zeit
			if ($a_service->is_worktime)
			{
				$result->content .= $st->toFormat('%H:%M:%S') . "$deli" . $et->toFormat('%H:%M:%S') . "$deli";
				$result->content .= $sp->toFormat('%H:%M:%S') . "$deli" . $ep->toFormat('%H:%M:%S') . "$deli";
			}
			else
			{
				$result->content .= "$deli$deli$deli$deli";
			}

			// Anzahl
			if ($entry->is_worktime)
			{
				$q = round((($entry->end_time - $entry->start_time) - ($entry->end_pause - $entry->start_pause)) / 3600, 2);
			}
			else
			{
				$q = $entry->qty;
			}
			$result->content .= number_format($q, 2, ",", "") . "$deli";

			// Preis
			$result->content .= number_format($entry->price, 2, ",", "") . "$deli";

			// Zeilensumme
			$rowsum = (double) $entry->price * $q;
			$result->content .= number_format($rowsum, 2, ",", "") . $deli;

			// Bemerkung
			//$result->content .= "\"" . $entry->remark ."\"\n";
			$result->content .= "\"" . mb_convert_encoding($entry->remark, "Windows-1252", "UTF-8") . "\"\n";

			// End Table Row

			$usersum      = round((double) $usersum + (double) $rowsum, 2);
			$result->gsum = ((double) $result->gsum + (double) $rowsum * 1);

			if ($entry->is_worktime)
			{
				$result->timeuser = round((double) $result->timeuser + (double) $rowsum, 2);
			}
			else
			{
				$result->servuser = round((double) $result->servuser + (double) $rowsum, 2);
			}

			$k = 1 - $k;
		}

		return $result;

	}
}