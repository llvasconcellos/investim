<?php defined('_JEXEC') or die('Restricted access'); 
$option = JRequest::getVar('option');
$view = JRequest::getVar('view');

JHTML::_('behavior.tooltip');
		$this->ordering = ($this->lists['order'] == 'ordering');	
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'filter.php' );
?>

<?php
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'menu_left.php' );
?>
<table width="100%">
	<tr>
		<td align="left" width="20%" valign="top">
<?php echo MenuLeft::ShowMenuLeft();?>

		</td>
        <td align="left" width="80%" valign="top">
        
<form action="index.php" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
        
        <td nowrap="nowrap">
            <?php echo Filter::FilterCategory( $this->items[0],'category','products' ); ?>
            </td>
            
		<td nowrap="nowrap">
		<?php       
			 echo $this->lists['state']; ?>
            <?php echo $this->lists['categories']; ?>
		</td>
	</tr>
</table>
<div id="editcell">
	<table class="adminlist">
<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->CatChild ); ?>);" />
			</th>
			<th  class="title">
				<?php echo JHTML::_('grid.sort',   'Category Name', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>            
			<th width="10" align="center">
				<?php echo JHTML::_('grid.sort',   'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
              <th width="100px" nowrap="nowrap">
            <?php echo JHTML::_('grid.sort',   'Order by', 'ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				<?php if ($this->ordering) echo JHTML::_('grid.order',  $this->items ); ?>
              </th>  
			
			<th width="5" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'ID', 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            
       
                
                
                
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0;
	$i=0;
	$rows = &$this->CatChild;	
	foreach ($rows as $row) :			
$link 		= JRoute::_( 'index.php?option='.$option.'&view='.$view.'&layout=form&task=edit&cid[]='. $row->id);
		
		$checked 	= JHTML::_('grid.id',  $i, $row->id );
		$published 	= JHTML::_('grid.published', $row, $i );
		
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td width="5%">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td width="5%">
				<?php echo $checked; ?>
			</td>
			<td>               
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit category' );?>::<?php echo $row->nombre; ?>">
				<a href="<?php echo $link  ?>">
					<?php echo $row->treename; ?></a></span>				
			</td>
			<td align="center">
				<?php echo $published;?>
			</td>
            
            
           
            
            <td class="order" nowrap="nowrap">
            
            
				<span><?php echo $this->pagination->orderUpIcon( $i, $row->parent == 0 || $row->parent == @$rows[$i-1]->parent, 'orderup', 'Move Up', $this->ordering); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, $row->parent == 0 || $row->parent == @$rows[$i+1]->parent, 'orderdown', 'Move Down', $this->ordering ); ?></span>
                
				<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>
                
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
			</td>
                   
				
					
			<td align="center">
				<?php echo $row->id; ?>
			</td>
            

            
            
		</tr>
		<?php
			$k = 1 - $k;
			$i++;		
		?>
		<?php endforeach; ?>
		
	</tbody>
    <tfoot>
    <tr>
      <td colspan="13"><?php echo $this->pagination->getListFooter(); ?></td>
    </tr>
  </tfoot>
	</table>
</div>

	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="table" value="<?php echo $TableName; ?>" />
    <input type="hidden" name="view" value="<?php echo $view; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="controller" value="<?php echo $view; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>	
<?php 
jimport( 'joomla.application.application' );
global $mainframe, $option;
$mainframe->setUserState("$option.filter_order", NULL);
?>
	</td>
		</tr>
			</table> 