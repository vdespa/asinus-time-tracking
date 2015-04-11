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

defined( '_JEXEC' ) or die;


class AsinusTimeTrackingModelRoles extends JModelLegacy
{
	var $_roles;

	function getRoles(){
		$query = "SELECT * FROM #__asinustimetracking_roles ORDER BY crid";
		$_roles = $this->_getList($query);

		return $_roles;
	}
}