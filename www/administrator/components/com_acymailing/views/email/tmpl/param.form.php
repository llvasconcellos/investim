<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
   <div>
  <?php echo $this->tabs->startPane( 'mail_tab');?>
  	<?php echo $this->tabs->startPanel(JText::_('INFOS'),'mail_infos');?>
	  	<table class="paramlist admintable" width="100%">
		<tr>
			<td class="paramlist_key">
				<label for="subject">
					<?php echo JText::_( 'JOOMEXT_SUBJECT' ); ?>
				</label>
			</td>
			<td>
				<input type="text" name="data[mail][subject]" id="subject" class="inputbox" style="width:80%" value="<?php echo $this->escape(@$this->mail->subject); ?>" />
			</td>
		</tr>
		<tr>
			<td class="paramlist_key">
				<?php echo JText::_( 'SEND_HTML' ); ?>
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist', "data[mail][html]" , 'onchange="updateEditor(this.value)"',$this->mail->html); ?>
			</td>
		</tr>
	</table>
  	<?php echo $this->tabs->endPanel(); ?>
 	<?php echo $this->tabs->startPanel(JText::_( 'ATTACHMENTS' ), 'mail_attachments');?>
    <?php if(!empty($this->mail->attach)){?>
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'ATTACHED_FILES' ); ?></legend>
      <?php
	      	foreach($this->mail->attach as $idAttach => $oneAttach){
	      		$idDiv = 'attach_'.$idAttach;
	      		echo '<div id="'.$idDiv.'">'.$oneAttach->filename.' ('.(round($oneAttach->size/1000,1)).' Ko)';
	      		echo $this->toggleClass->delete($idDiv,$this->mail->mailid.'_'.$idAttach,'mail');
				echo '</div>';
	      	}
		?>
		</fieldset>
    <?php } ?>
    <div id="loadfile">
    	<input type="file" size="30" name="attachments[]" />
    </div>
    <a href="javascript:void(0);" onclick='addFileLoader()'><?php echo JText::_('ADD_ATTACHMENT'); ?></a>
    	<?php echo JText::sprintf('MAX_UPLOAD',$this->values->maxupload);?>
    <?php echo $this->tabs->endPanel(); echo $this->tabs->startPanel(JText::_( 'SENDER_INFORMATIONS' ), 'mail_sender');?>
		<table width="100%" class="paramlist admintable">
			<tr>
		    	<td class="paramlist_key">
		    		<?php echo JText::_( 'FROM_NAME' ); ?>
		    	</td>
		    	<td class="paramlist_value">
		    		<input class="inputbox" type="text" name="data[mail][fromname]" size="40" value="<?php echo $this->escape($this->mail->fromname); ?>" />
		    	</td>
		    </tr>
			<tr>
		    	<td class="paramlist_key">
		    		<?php echo JText::_( 'FROM_ADDRESS' ); ?>
		    	</td>
		    	<td class="paramlist_value">
		    		<input class="inputbox" type="text" name="data[mail][fromemail]" size="40" value="<?php echo $this->escape($this->mail->fromemail); ?>" />
		    	</td>
		    </tr>
		    <tr>
				<td class="paramlist_key">
					<?php echo JText::_( 'REPLYTO_NAME' ); ?>
		    	</td>
		    	<td class="paramlist_value">
		    		<input class="inputbox" type="text" name="data[mail][replyname]" size="40" value="<?php echo $this->escape($this->mail->replyname); ?>" />
		    	</td>
		    </tr>
		    <tr>
				<td class="paramlist_key">
					<?php echo JText::_( 'REPLYTO_ADDRESS' ); ?>
		    	</td>
		    	<td class="paramlist_value">
		    		<input class="inputbox" type="text" name="data[mail][replyemail]" size="40" value="<?php echo $this->escape($this->mail->replyemail); ?>" />
		    	</td>
			</tr>
		</table>
<?php echo $this->tabs->endPanel(); echo $this->tabs->endPane(); ?>
  </div>