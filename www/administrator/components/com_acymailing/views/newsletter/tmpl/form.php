<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="iframedoc" id="iframedoc"></div>
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=newsletter" method="post" name="adminForm" autocomplete="off" enctype="multipart/form-data">
	<table width="100%">
		<tr>
			<td valign="top">
				<?php include(dirname(__FILE__).DS.'info.'.basename(__FILE__)); ?>
				<fieldset class="adminform" width="100%" id="htmlfieldset">
					<legend><?php echo JText::_( 'HTML_VERSION' ); ?></legend>
					<?php echo $this->editor->display(); ?>
				</fieldset>
				<fieldset class="adminform" >
					<legend><?php echo JText::_( 'TEXT_VERSION' ); ?></legend>
					<textarea style="width:100%" rows="20" name="data[mail][altbody]" id="altbody" ><?php echo @$this->mail->altbody; ?></textarea>
				</fieldset>
			</td>
			<td width="350" valign="top">
				<?php include(dirname(__FILE__).DS.'param.'.basename(__FILE__)); ?>
			</td>
		</tr>
  	</table>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo @$this->mail->mailid; ?>" />
	<input type="hidden" id="tempid" name="data[mail][tempid]" value="<?php echo @$this->mail->tempid; ?>" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="data[mail][type]" value="news" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="newsletter" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>