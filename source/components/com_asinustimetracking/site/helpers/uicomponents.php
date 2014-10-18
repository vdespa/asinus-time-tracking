<?php
/**
 * @package		TimeTrack for Joomla! 1.5
 * @version 	$Id: uicomponents.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

class UIComponents {

	function __construct(){
		;
	}

	static function getMonthSelector($c_name, $selectid){
		$months = array(JText::_("COM_ASINUSTIMETRACKING_JANUARY"), JText::_("COM_ASINUSTIMETRACKING_FEBRUARY"), JText::_("COM_ASINUSTIMETRACKING_MARCH"), JText::_("COM_ASINUSTIMETRACKING_APRIL"), JText::_("COM_ASINUSTIMETRACKING_MAY"), JText::_("COM_ASINUSTIMETRACKING_JUNE"), JText::_("COM_ASINUSTIMETRACKING_JULY"), JText::_("COM_ASINUSTIMETRACKING_AUGUST"), JText::_("COM_ASINUSTIMETRACKING_SEPTEMBER"), JText::_("COM_ASINUSTIMETRACKING_OCTOBER"), JText::_("COM_ASINUSTIMETRACKING_NOVEMBER"), JText::_("COM_ASINUSTIMETRACKING_DECEMBER"));
		$selected = "";
		$result = "<select class='inputbox' name='$c_name'>";

		for ($midx = 0; $midx < count($months); $midx++) {
			if($midx == $selectid-1){
				$selected = "selected";
			}
			$result .= "<option value='" . ($midx + 1) ."' $selected>$months[$midx]</option> ";
			$selected = "";
		}

		$result .= "</select>";

		return $result;
	}


	static function getYearSelector ($c_name, $selectid){
		$selected = "";
		$years = array();

		$maxy = date('Y', time());

		for($idx = $maxy - 3; $idx <= $maxy; $idx++){
			$years[] = $idx;
		}

		arsort($years);

		$result = "<select class='inputbox' name='$c_name'>";

		foreach ($years as $year) {
			if($year == $selectid){
				$selected = "selected";
			}
			$result .= "<option value='$year' $selected>$year</option>";
			$selected = "";

		}
		$result .= "</select>";

		return $result;
	}


	static function getMinuteSelector($c_name, $selectid=0){
		$selected = "";
		$minutes = array();
		$fmin = '';

		$result =  "<select class='inputbox' name='$c_name' id='$c_name'>";

		for ($index = 0; $index <= 55; $index++) {
			if($selectid == $index){
				$selected = "selected";
			}
			$result .= "<option value='$index' $selected>";
			if($index < 10){
				$fmin = "0$index";
			} else {
				$fmin = "$index";
			}

			$result .=  "$fmin</option>";
			$index += 4;
			$selected = '';
		}

		$result .= "</select>";

		return $result;

	}


	static function getHourSelector($c_name, $selectid=8){
		$selected = "";
		$hours = array();
		$fhour = '';

		$result = "<select class='inputbox' name='$c_name' id='$c_name'>";

		for($index = 1; $index <= 24; $index++){
			if($selectid == $index){
				$selected = "selected";
			}
			$result .= "<option value='$index' $selected>";
			if($index < 10){
				$fmin = "0$index";
			} else {
				$fmin = "$index";
			}

			$result .=  "$fmin</option>";

			$selected = '';
		}

		$result .= "</select>";

		return $result;

	}
	
} ?>
