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

defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.date' );

// TimeTrack Config
require_once(JPATH_COMPONENT . '/models/preferences.php');

// require_once(JPATH_COMPONENT_ADMINISTRATOR . '/lib/tcpdf/config/lang/ger.php');
// require_once(JPATH_COMPONENT_ADMINISTRATOR . '/lib/tcpdf/tcpdf.php');

jimport('jb.report.jbreport');

/**
* timetrack report view
*
* @category Class
* @package  TimeTrack
* @author   Ralf Nickel <rn@itrn.de>
* @license  GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
* @link     http://www.itrn.de
*/
class AsinusTimeTrackingViewTimeTrackList extends JView
{
	function display($tpl = null){
		//$this->_generatePDFHeader();

		// Config
		$cfg_model = new TimeTrackModelPreferences;
		$cfg = $cfg_model->getPreferences();

// 		$pdf = new TTPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new JBReport('PDF');
		$html = "";
		
// 		$pdf->setPrintHeader(false);
// 		$pdf->SetFont('helvetica', 'B', 12);

// 		$pdf->AddPage('P');

		// Set date selection, by default list last month
		$ct_startdate = JRequest::getVar('ct_startdate', null, 'get');
		if(!$ct_startdate){
			$ct_startdate = strtotime(date('Y-m') . '-' . $cfg['first_day']);
		}

		$ct_enddate = JRequest::getVar('ct_enddate', null, 'get');
		if(!$ct_enddate){
			$ct_enddate = time();
		}

		$ct_cc = JRequest::getVar('ct_cc', 0, 'get');
		if(!$ct_cc){
			$ct_cc = -1;
		}

		// Data
		$user 	=& JFactory::getUser();
		$model 		=& $this->getModel();
		$ctUser 	= $model->getCtUser();
		$list = $model->getEntriesList($ctUser->cuid, 0, $ct_startdate, $ct_enddate, -1, -1, $ct_cc, -1);

		$fill = 0;
// 		$pdf->Cell(200, 6, JText::_("COM_TIMETRACK_ACCOUNTING_FOR") . ": " . $user->name , '', 0, 'L', $fill);
		$pdf->appendContent( htmlspecialchars(JText::_("COM_TIMETRACK_ACCOUNTING_FOR")) . ": " . $user->name . "<br/>");
// 		$pdf->Ln();

// 		$pdf->Cell(200, 6, JText::_("COM_TIMETRACK_ACCOUNTING_PERIOD") . ": " . date('d.m.Y', $ct_startdate) . " - " . date('d.m.Y', $ct_enddate), '', 0, 'L', $fill);
		$pdf->appendContent(JText::_("COM_TIMETRACK_ACCOUNTING_PERIOD") . ": " . date('d.m.Y', $ct_startdate) . " - " . date('d.m.Y', $ct_enddate) . "<br/>");
// 		$pdf->Ln();

		if($ct_cc >= 0){
// 			$pdf->Cell(200, 6, JText::_("COM_TIMETRACK_CUSTOMER") . ": " . $model->getCostUnitById($ct_cc)->description, '', 0, 'L', $fill);
			$pdf->appendContent(JText::_("COM_TIMETRACK_CUSTOMER") . ": " . $model->getCostUnitById($ct_cc)->description . "<br/>");
		}

		$table = $this->_createTableContent($list, $model, $ctUser, $cfg, $pdf);

// 		$pdf->writeHTML($this->_printTableRow(array("<hr/>"), 1), true, false, true, false, '');
		$pdf->appendContent("<hr/>");

// 		$pdf->writeHTML($this->_printTableRow(array(JText::_("COM_TIMETRACK_ALL_SUM"), number_format($table->gsum, 2, ",", ".") . ' ' . $cfg['currency']), 2), true, false, true, false, '');
		$pdf->appendContent(JText::_("COM_TIMETRACK_ALL_SUM") . " " . number_format($table->gsum, 2, ",", ".") . ' ' . $cfg['currency'] . "<br/>");		

		if($cfg['print_tax']){
			$tax = round($table->gsum / 100 * $cfg['tax'], 2);
// 			$pdf->writeHTML($this->_printTableRow(array(JText::_("COM_TIMETRACK_TAX") . ' ('. $cfg['tax'] . ' %)', number_format($tax, 2, ",", ".") . ' ' . $cfg['currency']), 2), true, false, true, false, '');
			$pdf->appendContent(JText::_("COM_TIMETRACK_TAX") . ' ('. $cfg['tax'] . ' %)' . " " .  number_format($tax, 2, ",", ".") . ' ' . $cfg['currency'] . "<br/>");
// 			$pdf->writeHTML($this->_printTableRow(array(JText::_("COM_TIMETRACK_TAX_INCL"), number_format($tax + $table->gsum, 2, ",", ".") . ' ' . $cfg['currency']), 2), true, false, true, false, '');
			$pdf->appendContent(JText::_("COM_TIMETRACK_TAX_INCL") . " " . number_format($tax + $table->gsum, 2, ",", ".") . ' ' . $cfg['currency'] . "<br/>" );
		}

// 		ob_end_clean();
// 		$pdf->Output('report.pdf', 'D');
        $pdf->create()->download();
	}

	function _createTableContent($list, $model, $ctUser, $cfg, &$pdf){

		// init
		$result->gsum = 0;
		$result->content = "";
		$usersum = 0;
		$result->timeuser = 0;
		$result->servuser = 0;
		$result->itemsums = array();

// 		$pdf->SetFont('helvetica', 'B', 10);

		$rowsum = 0;

		$pdf->appendContent($this->_printTableRow(array(JText::_("COM_TIMETRACK_DATE"),
		JText::_("COM_TIMETRACK_SERVICE") . '(' . JText::_("COM_TIMETRACK_PROJECT") . ')',
		JText::_("COM_TIMETRACK_TIME"),
		JText::_("COM_TIMETRACK_QTY"),
		JText::_("COM_TIMETRACK_PRICE"),
		JText::_("COM_TIMETRACK_SUM"))
		));

// 		$pdf->SetFont('helvetica', '', 10);

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

			// Data
			########################################################################
			$dataArray = array();

			$dataArray[0] = date('d.m.Y', $entry->entry_date);
			$dataArray[1] = $a_service->description;
			$dataArray[2] = $a_service->is_worktime ? $st->toFormat('%H:%M') . " " . JText::_("COM_TIMETRACK_OCLOCK") . " - " . $et->toFormat('%H:%M') . " ". JText::_("COM_TIMETRACK_OCLOCK") : "&nbsp;";
			if($entry->is_worktime){
				$q = round((($entry->end_time - $entry->start_time) - ($entry->end_pause - $entry->start_pause)) / 3600, 2);
			} else {
				$q = $entry->qty;
			}
			$dataArray[3] = number_format($q, 2, ",", ".");
			$dataArray[4] = number_format((double)$entry->price, 2, ",", ".") ." ". $cfg['currency'];
			$rowsum = round((double)$entry->price * $q, 2);
			$dataArray[5] = "<b>" . number_format($rowsum, 2, ",", ".") . " ". $cfg['currency'] . "</b>";

			$pdf->appendContent($this->_printTableRow($dataArray), true, false, true, false, '');
			// Pause
			########################################################################
			$dataArray = array();
			$dataArray[0] = "";
			$dataArray[1] = "(" . $model->getSelectionById($entry->cg_id)->description .")";
			$dataArray[2] = $a_service->is_worktime && $cfg['print_pause'] ? "(". JText::_("COM_TIMETRACK_PAUSE") . " " . $sp->toFormat('%H:%M') . " - " . $ep->toFormat('%H:%M') . " " . JText::_("COM_TIMETRACK_OCLOCK") .")" : "";
			$dataArray[3] = "";
			$dataArray[4] = "";
			$dataArray[5] = "";

			$pdf->appendContent($this->_printTableRow($dataArray), true, false, true, false, '');
			// Bemerkung
			########################################################################
			if($cfg['print_notice']){
				$dataArray = array();
				$dataArray[0] = strlen($entry->remark) > 0 ? JText::_("COM_TIMETRACK_NOTICE") . ": " . $entry->remark : "&nbsp;";
				//$result->content .= $this->_printTableRow($dataArray, 1);
				$pdf->appendContent($this->_printTableRow($dataArray, 1), true, false, true, false, '');
			}

			// End Table Row

			$usersum = round((double)$usersum + (double)$rowsum,2);
			$result->gsum = ((double)$result->gsum + (double)$rowsum * 1);

			if($entry->is_worktime){
				$result->timeuser = round((double)$result->timeuser + (double)$rowsum, 2);
			} else {
				$result->servuser = round((double)$result->servuser + (double)$rowsum, 2);
			}

			$k = 1 - $k;

			// Summe
			########################################################################
			if($entry->entry_date <> $list[$i+1]->entry_date){
				$dataArray = array();
				$dataArray[0] = JText::_("COM_TIMETRACK_SUM");
				$dataArray[1] = number_format($usersum, 2, ",", ".") . " ". $cfg['currency'];
					
				$pdf->appendContent($this->_printTableRow($dataArray, 2), true, false, true, false, '');
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
		$pdf->appendContent($this->_printTableRow(array("<hr/>"), 1), true, false, true, false, '');

		return $result;

	}

	private function _printTableRow($ar = array(0), $layout = 0){

		switch ($layout) {
			case 1:
				// Remark
				$result = '<table style="width: 100%; font-size: small;" border="0">
						<tr>
							<td style="width: 100%">' . $ar[0] . '&nbsp;</td>
						</tr>
					</table>';
				break;
			case 2:
				// Footer
				$result = '<table style="width: 100%; font-size: small; font-weight: bold; background-color: #ccc;" border="0">
						<tr>
							<td style="width: 37%;">&nbsp;</td>
							<td style="width: 47%; text-align: left;">' . $ar[0] . '&nbsp;</td>
							<td style="width: 15%; text-align: right;">' . $ar[1] . '&nbsp;</td>
						</tr>
					</table>';
				break;
			case 3:
				// Footer All
				$result = '<table style="width: 100%; font-size: small; background-color: #ccc;" border="0">
						<tr>
							<td style="width: 37%;">&nbsp;</td>
							<td style="width: 22%; text-align: left;">' . $ar[0] . '&nbsp;</td>
							<td style="width: 10%; text-align: right;">' . $ar[1] . '&nbsp;</td>
							<td style="width: 30%; text-align: right;">' . $ar[2] . '&nbsp;</td>
						</tr>
					</table>';
				break;
			default:
				$result = '<table style="width: 100%; font-size: small;" border="0">
						<tr>
							<td style="width: 15%">' . $ar[0] . '&nbsp;</td>
							<td style="width: 22%">' . $ar[1] . '&nbsp;</td>
							<td style="width: 22%">' . $ar[2] . '&nbsp;</td>
							<td style="width: 10%; text-align: right;">' . $ar[3] . '&nbsp;</td>
							<td style="width: 15%; text-align: right;">' . $ar[4] . '&nbsp;</td>
							<td style="width: 15%; text-align: right;">' . $ar[5] . '&nbsp;</td>
						</tr>
					</table>';
				break;
		}

		return $result;
	}

// 	private function _generatePDFHeader(){
// 		header( "Content-Type: $this->application" );
// 		header( "Content-Disposition: attachment; filename=report.pdf");
// 		header( "Content-Description: pdf File" );
// 		header( "Pragma: no-cache" );
// 		header( "Expires: 0" );
// 	}

}

// class TTPdf extends TCPDF
// {
// 	// Page footer
// 	public function Footer() {
// 		// Position at 15 mm from bottom
// 		$this->SetY(-15);
// 		// Set font
// 		$this->SetFont('helvetica', 'I', 8);
// 		// Page number
// 		$this->Cell(0, 10, 'Seite '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
// 	}
// }