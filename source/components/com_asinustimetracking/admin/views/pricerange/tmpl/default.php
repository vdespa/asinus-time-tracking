<?php
/**
 * @package		TimeTrack
 * @version 	$Id: 	default.php 1 29.09.2010 ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform"><legend><?php echo JText::_( 'COM_ASINUSTIMETRACKING_DETAILS' ); ?>: <?php printf("%s, %s", $this->item->name, $this->item->description); ?></legend>
<div id="editcell">
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key">Start</td>
		<td class="value"><?php echo JHTML::calendar(date('d.m.Y', $this->cpid > - 1 ? strtotime($this->item->start_time) : strtotime("first day of last month")), "ct_startdate", 'ct_cal_start', ('%d.%m.%Y'), 'size=12 class=inputbox'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Ende</td>
		<td><?php echo JHTML::calendar(date('d.m.Y', $this->cpid > -1 ? strtotime($this->item->end_time) : strtotime("last day of last month")), "ct_enddate", 'ct_cal_end', ('%d.%m.%Y'), 'size=12 class=inputbox'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">Preis</td>
		<td><input type="text" value="<?php echo $this->item->price; ?>"
			name="ct_price"></td>
	</tr>
</table>

</div>
<input type="hidden" name="task" value="pricerange" /> <input
	type="hidden" name="option" value="com_asinustimetracking" /><input
	type="hidden" value="0" name="boxchecked" /> <input type="hidden"
	value="<?php echo $this->cpid; ?>" name="ct_cpid"> <input type="hidden"
	value="<?php echo $this->csid; ?>" name="ct_csid"> <input type="hidden"
	value="<?php echo $this->cuid; ?>" name="cid[]"> <input type="hidden"
	value="<?php echo $this->cuid; ?>" name="ct_cuid"> <br />
</fieldset>
</form>
