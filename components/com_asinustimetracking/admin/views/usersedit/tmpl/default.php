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

JHTML::_('behavior.modal');
//JHTML::_('script', 'pricerange.js', 'administrator/components/com_asinustimetracking/js/')
JHTML::_('stylesheet', 'asinustimetracking.css', 'components/com_asinustimetracking/assets/css/');

$document = JFactory::getDocument();

$js_code = "
    function deleteEntry(id, uid){
    	if(confirm('" . JText::_('COM_ASINUSTIMETRACKING_DELETE_PRICEENTRY') . "')){
			window.location = 'index.php?option=com_asinustimetracking&task=pricerangedelete&ct_cpid=' + id + '&ct_cuid=' + uid;
    	}
    	return false;
    }";

$document->addScriptDeclaration($js_code);


?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="col100">
		<fieldset class="adminform"><legend><?php echo JText::_( 'COM_ASINUSTIMETRACKING_DETAILS' ); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key"><label for="description"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_USERNAME' ); ?></label></td>
					<td><?php echo $this->item->name; ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_ASINUSTIMETRACKING_ADMIN'); ?></td>
					<td><input type="checkbox" id="is_admin" name="is_admin" value="1"
							<?php if($this->item->is_admin == 1){ echo "checked=checked"; }?>></input></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_ASINUSTIMETRACKING_ROLE'); ?></td>
					<td width="235px"><select class="inputbox" name="crid" id="crid">
							<?php
							$selected = '';
							// load user's services
							foreach($this->model->getRoles() as $roles){
								if($this->item->crid == $roles->crid){
									$selected = 'selected';
								}
								echo "<option value='$roles->crid' $selected label='$roles->description'>" . $roles->description . "</option>";
								$selected = '';
							};
							?>
						</select></td>
				</tr>
			</table>
			<p><strong><?php echo JText::_("COM_ASINUSTIMETRACKING_REMARK_USERSERVICE"); ?></strong></p>
			<table class="adminlist">
				<thead>
				<tr>
					<th width="5"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_ID' ); ?></th>
					<th width="150"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_SERVICE' ); ?></th>
					<th width="70"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_WORKTIME' ); ?></th>
					<th width="100"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_PRICE' ); ?></th>
					<th width="100"><?php echo JText::_('COM_ASINUSTIMETRACKING_PRICERANGE')?></th>
					<th width="10"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_NEW' ); ?></th>
					<th width="*"></th>
				</tr>
				</thead>
				<?php
				$k = 0;
				$serv = $this->model->getServices();

				for($i=0, $n=count($serv); $i < $n; $i++){
					$row 		=& $serv[$i];

					$priceRange = $this->prModel->getListByUserService($this->item->cuid, $row->csid);

					?>
					<tr class='<?php echo "row$k"; ?> ' valign="top">
						<td><?php echo $row->csid; ?></td>

						<td><?php echo $row->description; ?></td>
						<td align="center"><input type="checkbox"
												  id="is_worktime<?php echo $i; ?>" name="is_worktime" value="1"
												  readonly="readonly" disabled="disabled"
								<?php if($row->is_worktime == 1){ echo "checked=checked"; }?>> </input>
						</td>
						<td><input type="text" size="10"
								   name="cpreis[<?php echo $row->csid; ?>]" id="cpreis<?php echo $i; ?>"
								   value="<?php echo number_format($this->model->getUserPrice($this->item->cuid, $row->csid), 2, ",", ""); ?>"></input><small>(<?php echo JText::_("COM_ASINUSTIMETRACKING_FORMAT"); ?>:
								#0,00)</small></small></td>
						<td><?php if(count($priceRange) > 0){
								?>
								<div id="priceSlide<?php echo $row->csid; ?>">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr style='background-color: #cfcfcf;'>
											<th><?php echo JText::_('COM_ASINUSTIMETRACKING_STARTDATE'); ?></th>
											<th><?php echo JText::_('COM_ASINUSTIMETRACKING_ENDDATE'); ?></th>
											<th><?php echo JText::_('COM_ASINUSTIMETRACKING_PRICE'); ?></th>
											<th><?php echo JText::_('COM_ASINUSTIMETRACKING_REMOVE'); ?></th>
										</tr>
										<?php foreach ($priceRange as $priceValue){?>
											<tr>
												<td><?php echo strftime('%d.%m.%Y',strtotime($priceValue->start_time)); ?></td>
												<td><?php echo strftime('%d.%m.%Y', strtotime($priceValue->end_time))?></td>
												<td align="right"><a
														href="index.php?option=com_asinustimetracking&task=pricerange&ct_cpid=<?php echo $priceValue->cp_id; ?>&ct_cuid=<?php echo $this->item->cuid; ?>&ct_csid=<?php echo $row->csid; ?>"><?php echo number_format($priceValue->price,2); ?></a>
												</td>
												<td align="center"><img src="../components/com_asinustimetracking/icons/delete.gif" class="ttbutton"
																		onclick="deleteEntry(<?php echo $priceValue->cp_id; ?>, <?php echo $this->item->cuid; ?>)" /></td>
											</tr>
										<?php
										}?>
									</table>
								</div>
							<?php
							}  ?></td>
						<td><a
								href="index.php?option=com_asinustimetracking&task=pricerange&ct_cuid=<?php echo $this->item->cuid; ?>&ct_csid=<?php echo $row->csid; ?>"
								class="button"><img src="../components/com_asinustimetracking/assets/images/edit.gif"
													alt="<?php echo JText::_('COM_ASINUSTIMETRACKING_CREATE'); ?>" /></a></td>
						<td></td>
					</tr>
					<?php

					$k = 1 - $k;
				}
				?>
			</table>


		</fieldset>
	</div>
	<input type="hidden" name="task" value="services" /> <input
		type="hidden" name="cuid" value="<?php echo $this->item->cuid;?>" /></input>
	<input type="hidden" name="option" value="com_asinustimetracking" /></form>


