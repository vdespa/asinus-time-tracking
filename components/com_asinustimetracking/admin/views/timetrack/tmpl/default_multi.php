<?php
/**
 * Timetrack - Admin component
 *
 * PHP version 5
 *
 * @category  Component
 * @package   TimeTrack
 * @author    Ralf Nickel <info@itrn.de>
 * @copyright 2011 Ralf Nickel
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version   SVN: $Id$
 * @link      http://www.itrn.de
 */

defined('_JEXEC') or die('Restricted access');

// jimport('joomla.utilities.date');

?>

<form action="index.php" method="post" name="adminForm">
	<!-- <div id="editcell"> -->

	<!-- Search Header Table -->
	<table class="admintable">
		<tr style="border-bottom: 1px solid gray;">
			<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_STARTDATE"); ?>:</td>
			<td><?php echo JHTML::calendar(
                        date('d.m.Y',
                                $this->ctStartDate ? $this->ctStartDate
                                        : date(time('d.m.Y'))), 'ct_startdate',
                        'ct_calstart', ('%d.%m.%Y'), 'size=12 class=inputbox');
                ?></td>
			<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_ENDDATE"); ?>:</td>
			<td><?php echo JHTML::calendar(
                        date('d.m.Y',
                                $ctEndDate ? $ctEndDate : date(time('d.m.Y'))),
                        'ct_enddate', 'ct_calend', ('%d.%m.%Y'),
                        'size=12 class=inputbox');
                ?></td>
		</tr>
		<tr>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_USER"); ?>:</td>
			<td class='value'><?php
                              // USers list
                              $selectAll->cuid = -1;
                              $selectAll->name = JText::_("COM_ASINUSTIMETRACKING_ALL");
                              array_unshift($this->userList, $selectAll);
                              echo JHTML::_('select.genericlist',
                                      $this->userList, 'ct_ulist', null,
                                      'cuid', 'name', $this->ctUlist);
                              ?>
			</td>
			<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_SERVICE"); ?>:</td>
			<td class='value'><?php
                              // Services list
                              $selectAll = null;
                              $selectAll->csid = -1;
                              $selectAll->description = JText::_(
                                      "COM_ASINUSTIMETRACKING_ALL");
                              array_unshift($this->servicesList, $selectAll);
                              echo JHTML::_('select.genericlist',
                                      $this->servicesList, 'ct_svlist', null,
                                      'csid', 'description', $svlist);
                              ?>
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_ROLE"); ?>:</td>
			<td class='value'><?php
                              // Roles list
                              $selectAll = null;
                              $selectAll->crid = -1;
                              $selectAll->description = JText::_(
                                      "COM_ASINUSTIMETRACKING_ALL");
                              array_unshift($this->rolesList, $selectAll);
                              echo JHTML::_('select.genericlist',
                                      $this->rolesList, 'ct_rlist', null,
                                      'crid', 'description', $rlist);
                              ?>
			</td>
			<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_COSTUNIT"); ?>:</td>
			<td class='value'><?php
                              // costunits list
                              $selectAll = null;
                              $selectAll->cc_id = -1;
                              $selectAll->description = JText::_(
                                      "COM_ASINUSTIMETRACKING_ALL");
                              array_unshift($this->costUnitsList, $selectAll);
                              echo JHTML::_('select.genericlist',
                                      $this->costUnitsList, 'ct_costunit',
                                      null, 'cc_id', 'description', $ccid);
                              ?>
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_PROJECT"); ?>:</td>
			<td class='value'><?php
                              // projects / selections list 
                              $selectAll = null;
                              $selectAll->cg_id = -1;
                              $selectAll->description = JText::_(
                                      "COM_ASINUSTIMETRACKING_ALL");
                              array_unshift($this->selectionsList, $selectAll);
                              echo JHTML::_('select.genericlist',
                                      $this->selectionsList, 'ct_rlist', null,
                                      'cg_id', 'description', $sllist);
                              ?>
			</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan=3></td>
			<td align='right'><input type='button' class='button'
				value="<?php
                       echo JText::_("COM_ASINUSTIMETRACKING_REFRESH");
                       ?>"
				onclick='javascript:filterList()' /></td>
		</tr>
	</table>

	<!-- Data Table -->
	<h2><?php echo $this->ctUser->name; ?></h2>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20"></th>
				<th width="100"><?php echo JText::_("COM_ASINUSTIMETRACKING_DATE"); ?></th>
				<th width="250"><?php echo JText::_("COM_ASINUSTIMETRACKING_SERVICE"); ?></th>
				<th width="100"><?php echo JText::_("COM_ASINUSTIMETRACKING_START"); ?></th>
				<th width="100"><?php echo JText::_("COM_ASINUSTIMETRACKING_END"); ?></th>
				<th width="150"><?php echo JText::_(
                                        "COM_ASINUSTIMETRACKING_PAUSE_FROMTO");
                                ?></th>
				<th width="150"><?php echo JText::_("COM_ASINUSTIMETRACKING_PRICE"); ?></th>
				<th width="150"><?php echo JText::_("COM_ASINUSTIMETRACKING_QTY"); ?></th>
				<th width="150"><?php echo JText::_("COM_ASINUSTIMETRACKING_SUM"); ?></th>
				<th width="150"><?php echo JText::_("COM_ASINUSTIMETRACKING_NOTICE"); ?></th>
				<th width="*"></th>
			</tr>
		</thead>
		<tbody><?php $k = 0;
               //    List of user's entries
               for ($i = 0, $n = count($this->entries); $i < $n; $i++) {
                   $entry = &$this->entries[$i];
                   $checked = JHTML::_('grid.id', $i, $entry->ct_id);

                   $link = JRoute::_(
                           "index.php?option=com_asinustimetracking&task=timetrackedit&cid[]="
                                   . $entry->ct_id . "&ct_startdate="
                                   . $startdate . "&ct_enddate=" . $enddate
                                   . "&ct_sllist=" . $sllist . "&ct_svlist="
                                   . $svlist);

                   $a_service = $this->model->getServiceById($entry->cs_id);
               ?>
			<tr class="row<?php echo $k; ?>">

				<!-- Checkbox -->
				<td><?php echo $checked; ?></td>

				<!-- Entry date -->
				<td valign="top"><?php echo date('d.m.Y', $entry->entry_date); ?></td>

				<!-- Service -->
				<td><a href="<?php echo $link; ?>">
				<?php echo $a_service->description; ?></a><br>(<?php echo $this
                                                                           ->model
                                                                           ->getSelectionById(
                                                                                   $entry
                                                                                           ->cg_id)
                                                                           ->description
                                                               ?>
					- <?php echo $this->model->getCostUnitById($entry->cc_id)
                                  ->description;
                      ?>)
				</td>

				<!-- Start -->
				<td valign='top'><?php echo $a_service->is_worktime ? date(
                                                     'H:i', $entry->start_time)
                                                     . " "
                                                     . JText::_(
                                                             "COM_ASINUSTIMETRACKING_OCLOCK")
                                             : "&nbsp;";
                                 ?></td>
				<!-- End -->
				<td valign='top'><?php echo $a_service->is_worktime ? date(
                                                     'H:i', $entry->end_time)
                                                     . " "
                                                     . JText::_(
                                                             "COM_ASINUSTIMETRACKING_OCLOCK")
                                             : "&nbsp;";
                                 ?></td>
				<!-- Pause -->
				<td valign='top'><?php echo $a_service->is_worktime ? date(
                                                     'H:i',
                                                     $entry->pause_start) . " "
                                                     . JText::_(
                                                             "COM_ASINUSTIMETRACKING_OCLOCK")
                                                     . " - "
                                                     . date('H:i',
                                                             $entry->pause_end)
                                                     . " "
                                                     . JText::_(
                                                             "COM_ASINUSTIMETRACKING_OCLOCK")
                                             : "&nbsp;";
                                 ?></td>
				<!-- Price -->
				<td align=right><?php echo number_format(
                                            (double) $entry->price, 2) . " "
                                            . $cfg['currency'];
                                ?></td>

				<!-- Qty --><?php if ($entry->is_worktime) {
                                    $q = round(
                                            (($entry->end_time
                                                    - $entry->start_time)
                                                    - ($entry->end_pause
                                                            - $entry
                                                                    ->start_pause))
                                                    / 3600, 2);
                                } else {
                                    $q = $entry->qty;
                                }
                            ?>
                <td align=right><?php echo number_format($q, 2); ?> x</td>
                
				<!-- Rowsum -->
                <?php $rowsum = (double) $entry->price * $q; ?>
                <td align=right><?php echo number_format($rowsum, 2) . " "
                                            . $cfg['currency'];
                                ?></td>
				
				<!-- Remark -->
				<td>
					<textarea class="inputbox" rows="2" cols="30" readonly="readonly">
					<?php echo $entry->remark; ?></textarea>
				</td>
			</tr>
			<?php
                     $k = 1 - $k;
                     $usersum = round((double) $usersum + (double) $rowsum, 2);

                     if ($entry->entry_date <> $list[$i + 1]->entry_date) {
                         // user's end
                 ?>
		        	<tr style="background-color: #c1c1c1;">
		        		<td colspan="11" align="right">
		        			<b><?php echo JText::_("COM_ASINUSTIMETRACKING_SUM"); ?>: <?php echo number_format(
                                                                                           $usersum,
                                                                                           2)
                                                                                           . " "
                                                                                           . $cfg['currency'];
                                                                           ?></b>
		        		</td>
		        	</tr>
		        <?php
                                 // reset usersum 
                                 $usersum = 0;
                             } // End Row

                             if ($result->itemsums[$a_service->description]
                                     && (count(
                                             $result
                                                     ->itemsums[$a_service
                                                             ->description])
                                             > 0)) {
                                 $result->itemsums[$a_service->description]
                                         ->value = $result
                                         ->itemsums[$a_service->description]
                                         ->value + (double) $rowsum;
                                 $result->itemsums[$a_service->description]
                                         ->qty = $result
                                         ->itemsums[$a_service->description]
                                         ->qty + (double) $q;
                             } else {
                                 $result->itemsums[$a_service->description]
                                         ->desc = $a_service->description;
                                 $result->itemsums[$a_service->description]
                                         ->value = (double) $rowsum;
                                 $result->itemsums[$a_service->description]
                                         ->qty = (double) $q;
                             }
                         }
                         ?>
	</tbody>
	</table>

	<!-- </div> -->
	<!-- Submit Fields -->
	<input type="hidden" name="option" value="com_asinustimetracking"> <br />
	<input type="hidden" name="task" value="default" /> <br /> <input
		type="hidden" value="0" name="boxchecked" />
</form>
