<?php
/**
* @package   yoo_explorer Template
* @version   1.5.2 2010-01-03 14:20:02
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<?php if ( ( $this->contact->params->get( 'address_check' ) > 0 ) &&  ( $this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode ) ) : ?>
<table cellpadding="2" cellspacing="2" border="0" class="joomlatable">
	<?php if ( $this->contact->params->get( 'address_check' ) > 0 ) : ?>
	<tr>
		<td rowspan="6" valign="top" width="<?php echo $this->contact->params->get( 'column_width' ); ?>" >
			<?php echo $this->contact->params->get( 'marker_address' ); ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->address && $this->contact->params->get( 'show_street_address' ) ) : ?>
	<tr>
		<td>
			<?php echo nl2br($this->escape($this->contact->address)); ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->suburb && $this->contact->params->get( 'show_suburb' ) ) : ?>
	<tr>
		<td>
			<?php echo $this->escape($this->contact->suburb); ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->state && $this->contact->params->get( 'show_state' ) ) : ?>
	<tr>
		<td>
			<?php echo $this->escape($this->contact->state); ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->postcode && $this->contact->params->get( 'show_postcode' ) ) : ?>
	<tr>
		<td>
			<?php echo $this->escape($this->contact->postcode); ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->country && $this->contact->params->get( 'show_country' ) ) : ?>
	<tr>
		<td>
			<?php echo $this->escape($this->contact->country); ?>
		</td>
	</tr>
	<?php endif; ?>
</table>
<?php endif; ?>

<?php if ( ($this->contact->email_to && $this->contact->params->get( 'show_email' )) || 
			($this->contact->telephone && $this->contact->params->get( 'show_telephone' )) || 
			($this->contact->fax && $this->contact->params->get( 'show_fax' )) || 
			($this->contact->mobile && $this->contact->params->get( 'show_mobile' )) || 
			($this->contact->webpage && $this->contact->params->get( 'show_webpage' )) ) : ?>
<table cellpadding="2" cellspacing="2" border="0" class="joomlatable">
	<?php if ( $this->contact->email_to && $this->contact->params->get( 'show_email' ) ) : ?>
	<tr>
		<td width="<?php echo $this->contact->params->get( 'column_width' ); ?>" >
			<?php echo $this->contact->params->get( 'marker_email' ); ?>
		</td>
		<td>
			<?php echo $this->contact->email_to; ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->telephone && $this->contact->params->get( 'show_telephone' ) ) : ?>
	<tr>
		<td width="<?php echo $this->contact->params->get( 'column_width' ); ?>" >
			<?php echo $this->contact->params->get( 'marker_telephone' ); ?>
		</td>
		<td>
			<?php echo nl2br($this->contact->telephone); ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->mobile && $this->contact->params->get( 'show_mobile' ) ) :?>
	<tr>
		<td width="<?php echo $this->contact->params->get( 'column_width' ); ?>" >
		<?php echo $this->contact->params->get( 'marker_mobile' ); ?>
		</td>
		<td>
			<?php echo nl2br($this->escape($this->contact->mobile)); ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->fax && $this->contact->params->get( 'show_fax' ) ) : ?>
	<tr>
		<td width="<?php echo $this->contact->params->get( 'column_width' ); ?>" >
			<?php echo $this->contact->params->get( 'marker_fax' ); ?>
		</td>
		<td>
			<?php echo nl2br($this->escape($this->contact->fax)); ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->contact->webpage) : ?>
	<tr>
		<td width="<?php echo $this->contact->params->get( 'column_width' ); ?>" >
			<a onclick="window.open('http://settings.messenger.live.com/Conversation/IMMe.aspx?invitee=17CF42928B36DF00@apps.messenger.live.com&amp;mkt=pt-BR', '_blank', 'height=300px,width=300px');" href="Javascript: return false;" tabindex="-1">
				<img src="http://messenger.services.live.com/users/17CF42928B36DF00@apps.messenger.live.com/presenceimage?mkt=pt-BR" style="border-style: none;">
			</a>
		</td>
		<td>
			<a href="Javascript: return false;" onclick="window.open('http://settings.messenger.live.com/Conversation/IMMe.aspx?invitee=17CF42928B36DF00@apps.messenger.live.com&mkt=pt-BR', '_blank', 'height=300px,width=300px');"> 
            	<?php echo $this->escape($this->contact->webpage); ?>
			</a>
		</td>
	</tr>
	<?php endif; ?>
</table>
<?php endif; ?>

<?php if ( $this->contact->misc && $this->contact->params->get( 'show_misc' ) ) : ?>
<table cellpadding="0" cellspacing="0" border="0" class="joomlatable">
	<tr>
		<td width="<?php echo $this->contact->params->get( 'column_width' ); ?>" valign="top" >
			<?php echo $this->contact->params->get( 'marker_misc' ); ?>
		</td>
		<td>
			<?php echo nl2br($this->contact->misc); ?>
		</td>
	</tr>
</table>
<?php endif; ?>