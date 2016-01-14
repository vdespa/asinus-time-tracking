<?php
// Images
$imagesLocation = JURI::base() . 'components/com_asinustimetracking/assets/images/';

// Delete price entry dialog
$document = JFactory::getDocument();
$js_code = "
    function deleteEntry(id, uid){
    	if(confirm('" . JText::_('COM_ASINUSTIMETRACKING_PRICEENTRY_DELETE_MSG') . "')){
			window.location = 'index.php?option=com_asinustimetracking&task=pricerangedelete&ct_cpid=' + id + '&ct_cuid=' + uid;
    	}
    	return false;
    }";
$document->addScriptDeclaration($js_code);
?>
<p><strong><?php echo JText::_("COM_ASINUSTIMETRACKING_REMARK_USERSERVICE"); ?></strong></p>
<table class="table table-striped">
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

		$priceRange = $this->priceRangeModel->getListByUserService($this->item->cuid, $row->csid);

		?>
		<tr class='<?php echo "row$k"; ?> ' valign="top">
			<td><?php echo $row->csid; ?></td>

			<td><?php echo $row->description; ?></td>
			<td align="center"><input type="checkbox"
					id="is_worktime<?php echo $i; ?>" name="is_worktime" value="1"
					readonly="readonly" disabled="disabled"
					<?php if($row->is_worktime == 1){ echo "checked=checked"; }?>> </input>
			</td>
			<td><input type="text" size="5"
					name="cpreis[<?php echo $row->csid; ?>]" id="cpreis<?php echo $i; ?> " class="inputbox span10"
					value="<?php echo number_format($this->model->getUserPrice($this->item->cuid, $row->csid), 2, ",", ""); ?>"/><small>(<?php echo JText::_("COM_ASINUSTIMETRACKING_FORMAT"); ?>:
					#0,00)</small></small></td>
			<td><?php if(count($priceRange) > 0){
					?>
					<div id="priceSlide<?php echo $row->csid; ?>">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped">
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
									<td align="center"><img src="<?php echo $imagesLocation . 'delete.gif'; ?>" class="ttbutton"
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
					class="button"><img src="<?php echo $imagesLocation . 'edit.gif'; ?>"
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