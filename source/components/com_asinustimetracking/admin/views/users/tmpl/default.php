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
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_ID' ); ?></th>
<th width="150"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_NAME' ); ?></th>
<th width="150"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_USERNAME' ); ?></th>
<th width="50"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_ADMIN' ); ?></th>
<th width="100"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_ROLE' )?></th>
<th width="*"></th>
</tr>
</thead>
<?php
$k = 0;
for($i=0, $n=count($this->items); $i < $n; $i++){
	$row =& $this->items[$i];
	$ctUser = $this->model->getOrCreateCtUserByUid($row->id);
	$link 		= JRoute::_( 'index.php?option=com_asinustimetracking&task=useredit&cid[]='. $ctUser->cuid );
	?>
	<tr class='<?php echo "row$k"; ?> '>
		<td><?php echo $row->id; ?></td>
		<td><a href='<?php echo $link; ?>'><?php echo $row->name; ?></a></td>
		<td><?php echo $row->username; ?></td>
		<td align="center"><input type="checkbox" id="is_worktime"
				name="is_worktime" value="1" readonly="readonly"
				<?php if($ctUser->is_admin == 1){ echo "checked=checked"; }?>></input></td>
		<td><?php echo $ctUser->roledesc; ?></td>
		<td></td>

	</tr>
	<?php

	$k = 1 - $k;
}
?>

</table>

<input type="hidden" name="task" value="users" /> <input type="hidden"
	name="option" value="com_asinustimetracking" /> <input type="hidden"
	name="boxchecked" value="0" />

</form>