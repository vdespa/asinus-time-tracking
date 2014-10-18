<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_asinustimetracking
 * @copyright	Copyright (c) 2014, Valentin Despa. All rights reserved.
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Valentin Despa - info@vdespa.de
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.vdespa.de
 * @license 	GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

/**
 * Main controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_asinustimetracking
 */
class AsinusTimeTrackingController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar('view', JRequest::getVar('view', 'timetrack'));
		parent::display();
	}

	function overview()
	{
		$this->display();
	}

	function export()
	{
		$ctUlist = JRequest::getInt('ct_ulist', -1);
		$ctSllist = JRequest::getInt('ct_sllist', -1);
		$ctSvlist = JRequest::getInt('ct_svlist', -1);
		$ctRlist = JRequest::getInt('ct_rlist', -1);
		$ct_fm = JRequest::getInt('ct_fm', 0);
		$ct_tm = JRequest::getInt('ct_tm', 0);

		$this
			->setRedirect(
				"index.php?option=com_asinustimetracking&format=pdf&ct_ulist=$ctUlist&ct_sllist=$ctSllist&ct_svlist=$ctSvlist&ct_fm=$ct_fm&ct_tm=$ct_tm&ct_rlist=$ctRlist");
	}

	function costunits()
	{
		JRequest::setVar('view', 'costunits');
		$this->display();
	}

	function costunitsedit()
	{
		JRequest::setVar('view', 'costunitsedit');
		$this->display();
	}

	/**
	 * Show PriceRange
	 */
	function pricerange()
	{
		JRequest::setVar('view', 'pricerange');
		$this->display();
	}

	function pricerangedelete()
	{
		$cpid = JRequest::getInt('ct_cpid', -1);
		$cuid = JRequest::getInt('ct_cuid', -1);

		$model = &$this->getModel('pricerange');

		$model->remove($cpid);

		$this
			->setRedirect(
				"index.php?option=com_asinustimetracking&cid[]=" . (int) $cuid
				. "&task=useredit");

	}

	function csv()
	{
		$ctUlist = JRequest::getInt('ct_ulist', -1);
		$ctSllist = JRequest::getInt('ct_sllist', -1);
		$ctSvlist = JRequest::getInt('ct_svlist', -1);
		$ctRlist = JRequest::getInt('ct_rlist', -1);
		$ct_fm = JRequest::getInt('ct_fm', 0);
		$ct_tm = JRequest::getInt('ct_tm', 0);

		$this
			->setRedirect(
				"index.php?option=com_asinustimetracking&format=csv&ct_ulist=$ctUlist&ct_sllist=$ctSllist&ct_svlist=$ctSvlist&ct_fm=$ct_fm&ct_tm=$ct_tm&ct_rlist=$ctRlist");
	}
	/*
	 * Projekt
	 */

	function selections()
	{
		JRequest::setVar('view', 'selections');
		$this->display();
	}

	function submit()
	{
		jimport('joomla.utilities.date');
		$usermodel = $this->getModel();

		$db = &JFactory::getDBO();

		$ctid = JRequest::getInt('ct_id', 0);
		$entrydate = JRequest::getString('ct_entrydate', '');
		$service = JRequest::getInt('ct_service', 0);
		$sh = JRequest::getInt('ct_sh', 0);
		$sm = JRequest::getInt('ct_sm', 0);
		$eh = JRequest::getInt('ct_eh', 0);
		$em = JRequest::getInt('ct_em', 0);
		$psh = JRequest::getInt('ct_psh', 0);
		$psm = JRequest::getInt('ct_psm', 0);
		$peh		= JRequest::getInt('ct_peh', 0);
		$pem		= JRequest::getInt('ct_pem', 0);
		$qty = JRequest::getFloat('ct_qty', 0);
		$cg = JRequest::getInt('ct_selection', 0);
		$remark = JRequest::getString('ct_remark', '');
		$cc = JRequest::getInt('ct_costunit', 0);

		$fentrydate = new JDate($entrydate);

		if ($ctid == 0 or $ctid == 'undefined') {
			JError::raiseError(500, 'No id found');
		} else {
			$query = "UPDATE #__timetrack_entries SET
				entry_date='" . $fentrydate->toMySQL()
				. "',
				cs_id=$service,
				cg_id=$cg,
				start_time='1970-1-1 $sh:$sm:00',
				end_time='1970-1-1 $eh:$em:00',
				start_pause='1970-1-1 $psh:$psm:00',
				end_pause='1970-1-1 $peh:$pem:00',
				qty=$qty,
				cc_id=$cc,
				remark='$remark'
				WHERE ct_id=" . $ctid;

			JRequest::setVar('ct_id');
		}

		$db->setQuery($query);

		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		}

		$this->display();
	}

	function selectionsedit()
	{
		JRequest::setVar('view', 'selectionsedit');
		$this->display();
	}

	function timetrackedit()
	{
		JRequest::setVar('view', 'timetrackedit');
		$this->display();
	}

	function removeselection()
	{
		$cgid = JRequest::getVar('cid');

		if ($cgid) {
			foreach ($cgid as $item) {
				$model = $this->getmodel('selectionsedit');
				$model->remove((int) $item);
			}
		}
		JRequest::setVar('view', 'selections');
		$this->display();
	}

	function removeentry()
	{
		$ctid = JREquest::getVar('cid');

		if ($ctid) {
			foreach ($ctid as $item) {
				$model = $this->getmodel('timetrackedit');
				$model->remove((int) $item);
			}
		}
		$this->display();
	}

	function saveselection()
	{
		$cgid = JRequest::getInt('cgid');
		$description = JRequest::getString('description');

		if ($cgid) {
			$model = $this->getmodel('selectionsedit');
			$model->merge($cgid, $description);
		} else {
			$model = $this->getmodel('selectionsedit');
			$model->create($description);
		}
		JRequest::setVar('view', 'selections');
		$this->display();

	}

	/*
	 * Services
	 */
	function services()
	{
		JRequest::setVar('view', 'services');
		$this->display();
	}

	function servicesedit()
	{
		JRequest::setVar('view', 'servicesedit');
		$this->display();
	}

	function removeservice()
	{
		$csid = JRequest::getVar('cid');

		if ($csid) {
			foreach ($csid as $item) {
				$model = $this->getmodel('servicesedit');
				$model->remove((int) $item);
			}
		}
		JRequest::setVar('view', 'services');
		$this->display();
	}

	function saveservice()
	{
		$csid = JRequest::getInt('csid');
		$description = JRequest::getString('description');
		$is_worktime = JRequest::getVar('is_worktime');

		if ($csid) {
			$model = $this->getmodel('servicesedit');
			$model->merge($csid, $description, $is_worktime);
		} else {
			$model = $this->getmodel('servicesedit');
			$model->create($description, $is_worktime);
		}
		JRequest::setVar('view', 'services');
		$this->display();
	}

	/*
	 * User
	 */
	function users()
	{
		JRequest::setVar('view', 'users');
		$this->display();
	}

	function useredit()
	{
		JRequest::setVar('view', 'usersedit');
		$this->display();
	}

	function saveuser()
	{
		$cuid = JRequest::getInt('cuid');
		$is_admin = JRequest::getVar('is_admin');
		$preise = JRequest::getVar('cpreis');
		$role = JRequest::getInt('crid');
		$employee_id = JRequest::getInt('employee_id');

		if ($cuid) {
			$model = $this->getmodel('usersedit');
			$model->merge($cuid, $role, $is_admin, $employee_id, $preise);
		}

		JRequest::setVar('view', 'users');
		$this->display();
	}

	/*
	 * Roles
	 */
	function roles()
	{
		JRequest::setVar('view', 'roles');
		$this->display();
	}

	function rolesedit()
	{
		JRequest::setVar('view', 'rolesedit');
		$this->display();
	}

	function removerole()
	{
		$crid = JRequest::getVar('cid');

		if ($crid) {
			foreach ($crid as $item) {
				$model = $this->getmodel('rolesedit');
				$model->remove((int) $item);
			}
		}
		JRequest::setVar('view', 'roles');
		$this->display();
	}

	function removecostunit()
	{
		$ccid = JRequest::getVar('cid');

		if ($ccid) {
			foreach ($ccid as $item) {
				$model = $this->getmodel('costunitsedit');
				$model->remove((int) $item);
			}
		}
		JRequest::setVar('view', 'costunits');
		$this->display();
	}

	function savecostunit()
	{
		$ccid = JRequest::getInt('ccid');
		$description = JRequest::getString('description');

		if ($ccid) {
			$model = $this->getModel('costunitsedit');
			$model->merge($ccid, '', $description);
		} else {
			$model = $this->getModel('costunitsedit');
			$model->create('', $description);
		}

		JRequest::setVar('view', 'costunits');
		$this->display();

	}

	function savepricerange()
	{
		$cpid = JRequest::getInt('ct_cpid', -1);
		$startdate = JRequest::getString('ct_startdate', '');
		$enddate = JRequest::getString('ct_enddate', '');
		$price = JRequest::getFloat('ct_price', 0);
		$cuid = JRequest::getInt('ct_cuid', -1);
		$csid = JRequest::getInt('ct_csid', -1);

		$model = $this->getModel('pricerange');

		if ($cpid >= 0) {
			$model->merge($cpid, $startdate, $enddate, $price);
		} else {
			$model->create($startdate, $enddate, $price, $cuid, $csid);
		}

		//		JRequest::setVar('view', 'usersedit');
		//		$this->display(
		$this
			->setRedirect(
				'index.php?option=com_asinustimetracking&task=useredit&cid[0]=' . $cuid);

	}

	function saveroles()
	{
		$crid = JRequest::getInt('crid');
		$description = JRequest::getString('description');

		if ($crid) {
			$model = $this->getmodel('rolesedit');
			$model->merge($crid, $description);
		} else {
			$model = $this->getmodel('rolesedit');
			$model->create($description);
		}
		JRequest::setVar('view', 'roles');
		$this->display();
	}

	function preferences()
	{
		JRequest::setVar('view', 'preferences');
		$this->display();
	}

	function savepreferences()
	{
		$model = &$this->getModel('preferences');

		$_data['currency'] = JRequest::getString('ct_currency', '');
		$_data['print_pause'] = JRequest::getString('ct_print_pause', '') == TRUE
			? 'TRUE' : 'FALSE';
		$_data['print_notice'] = JRequest::getString('ct_print_notice', '') == TRUE
			? 'TRUE' : 'FALSE';
		$_data['print_page_title'] = JRequest::getString('ct_print_page_title', '');
		$_data['first_day'] = JRequest::getInt('ct_first_day', 1);
		$_data['tax'] = JRequest::getFloat('ct_tax', 1);
		$_data['print_tax'] = JRequest::getVar('ct_print_tax', '') == TRUE ? 'TRUE'
			: 'FALSE';

		$model->save($_data);

		JRequest::setVar('view', '');
		$this->display();
	}
}