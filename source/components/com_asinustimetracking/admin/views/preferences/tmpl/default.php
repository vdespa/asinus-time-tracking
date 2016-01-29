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
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="editcell">
<table class="admintable">
	<tr>
		<th width="150"><?php echo JText::_('COM_ASINUSTIMETRACKING_PREFERENCES_SETTING_LABEL'); ?></th>
		<th width="150"><?php echo JText::_('COM_ASINUSTIMETRACKING_PREFERENCES_VALUE_LABEL'); ?></th>
		<th width="*"></th>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_ASINUSTIMETRACKING_PREFERENCES_CURRENCY_SYMBOL_LABEL'); ?></td>
		<td><input class="text_area" type="text" name="ct_currency" size="12"
			maxlength="3" value="<?php echo $this->pr['currency']; ?>" /></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_TAX"); ?></td>
		<td><input class="text_area" type="text" name="ct_tax" size="12"
			maxlength="2" value="<?php echo $this->pr['tax']; ?>" ?/></td>
	</tr>

	<tr>
		<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_PRINT_TAX"); ?></td>
		<td><input type="checkbox" name="ct_print_tax"
		<?php if( $this->pr['print_tax'] ) { echo 'checked="checked"';} ?>
			value="1" /></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_PRINT_PDF_TITLE"); ?></td>
		<td><input class="text_area" type="text" name="ct_print_page_title"
			size="30" maxlength="50"
			value="<?php echo $this->pr['print_page_title']; ?>" /></td>
	</tr>

	<tr>
		<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_PREFERENCES_FIRST_BILLING_DAY_LABEL"); ?></td>
		<td><input class="text_area" type="text" name="ct_first_day" size="12"
			maxlength="3" value="<?php echo $this->pr['first_day']; ?>" /></td>
	</tr>
 
	<tr>
		<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_PREFERENCES_SHOW_PAUSE_IN_PRINT_LABEL"); ?></td>
		<td><input type="checkbox" name="ct_print_pause"
		<?php if( $this->pr['print_pause'] ) { echo 'checked="checked"';} ?>
			value="1" /></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_PREFERENCES_SHOW_COMMENTS_IN_PRINT_LABEL"); ?></td>
		<td><input type="checkbox" name="ct_print_notice"
		<?php if( $this->pr['print_notice'] ) { echo 'checked="checked"'; } ?>
			value="1" /></td>
	</tr>

</table>
</div>
<input type="hidden" name="task" value="preferences" /> <input
	type="hidden" name="option" value="com_asinustimetracking" /><input
	type="hidden" value="0" name="boxchecked" /></form>
