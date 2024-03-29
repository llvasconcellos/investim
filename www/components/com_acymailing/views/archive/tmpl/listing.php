<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php if($this->values->show_page_title){ ?>
<div class="contentheading<?php echo $this->values->suffix; ?>"><?php echo $this->values->page_title; ?></div>
<?php } ?>
<form action="<?php echo acymailing::completeLink('archive&listid='.$this->list->listid); ?>" method="post" name="adminForm">
	<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->values->suffix; ?>">
	<?php if($this->values->show_description){ ?>
		<tr>
			<td width="60%" valign="top" class="contentdescription<?php echo $this->values->suffix; ?>" colspan="2">
				<?php echo $this->list->description; ?>
			</td>
		</tr>
	<?php } ?>
		<tr>
			<td>
				<?php echo $this->loadTemplate('newsletters'); ?>
			</td>
		</tr>
	</table>
	<?php
		if($this->access->frontEndManament){
	?>
		<a href="<?php echo acymailing::completeLink('newsletter&task=add&listid='.$this->list->listid); ?>"><img src="<?php echo JURI::base(true); ?>/images/M_images/new.png"/></a>
	<?php } ?>
	<?php if(!empty($this->values->itemid)){ ?>
		<input type="hidden" name="Itemid" value=<?php echo $this->values->itemid; ?> />
	<?php } ?>
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="archive" />
	<input type="hidden" name="filter_order" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
</form>
