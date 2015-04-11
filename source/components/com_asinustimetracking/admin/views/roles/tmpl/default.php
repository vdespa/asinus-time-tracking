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
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_ID' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th width="150"><?php echo JText::_('COM_ASINUSTIMETRACKING_ROLE'); ?></th>
			<th width="*"></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for($i=0, $n=count($this->items); $i < $n; $i++){
		$row =& $this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->crid );
		$link 		= JRoute::_( 'index.php?option=com_asinustimetracking&task=rolesedit&cid[]='. $row->crid );
		?>
	<tr class='<?php echo "row$k"; ?>'>
		<td><?php echo $row->crid; ?></td>
		<td><?php echo $checked; ?></td>
		<td><a href='<?php echo $link; ?>'><?php echo $row->description; ?></a></td>
		<td></td>
	</tr>
	<?php

	$k = 1 - $k;
	}
	?>
</table>
</div>
<input type="hidden" name="task" value="roles" /> <input type="hidden"
	name="option" value="com_asinustimetracking" /><input type="hidden" value="0"
	name="boxchecked" /></form>
