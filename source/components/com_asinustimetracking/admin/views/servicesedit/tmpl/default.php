<?php
/**
 * @package		TimeTrack
 * @version 	$Id: default.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

// HOTFIX
if (! $this->item)
{
	$this->item = new stdClass();
	$this->item->description = "";
	$this->item->is_worktime = 0;
	$this->item->csid = 0;
}

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'COM_TIMETRACK_DETAILS' ); ?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="description"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_TITLE' ); ?></label></td>
		<td><input class="text_area" type="text" name="description" size="32"
			maxlength="30" value="<?php echo ($this->item->description) ? $this->item->description : ''; ?>"></input></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_('COM_ASINUSTIMETRACKING_WORKTIME'); ?></td>
		<td><input type="checkbox" id="is_worktime" name="is_worktime"
			value="1"
			<?php if($this->item->is_worktime == 1){ echo "checked=checked"; }?>></input></td>
	</tr>
</table>
</fieldset>
</div>
<input type="hidden" name="task" value="services" /><input type="hidden"
	name="csid" value="<?php echo $this->item->csid;?>" /></input> <input
	type="hidden" name="option" value="com_asinustimetracking" /></form>
