<?php
/**
* @package   yoo_explorer Template
* @version   1.5.2 2010-01-03 14:20:02
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>

<div class="joomla <?php echo $this->params->get('pageclass_sfx')?>">
	<div class="blog">

		<?php if ($this->params->get('show_page_title', 1)) : ?>
		<h1 class="pagetitle">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h1>
		<?php endif; ?>
		
		<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) :?>
		<div class="description">
			<?php if ($this->params->get('show_description_image') && $this->section->image) : ?>
				<img class="<?php echo $this->section->image_position;?>" src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->section->image;?>" align="<?php echo $this->section->image_position;?>" alt="" />
			<?php endif; ?>
			<?php if ($this->params->get('show_description') && $this->section->description) : ?>
				<?php echo $this->section->description; ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		
		<?php if ($this->params->def('num_leading_articles', 1)) : ?>
		<div class="leadingarticles">
			<?php for ($i = $this->pagination->limitstart; $i < ($this->pagination->limitstart + $this->params->get('num_leading_articles')); $i++) : ?>
			<?php if ($i >= $this->total) : break; endif; ?>
				<?php
					$this->item =& $this->getItem($i, $this->params);
					echo $this->loadTemplate('item');
				?>
		<?php endfor; ?>
		</div>
		<?php else : $i = $this->pagination->limitstart; endif; ?>

		<?php
		if ($i < $this->total) {

			// init vars
			$count   = min($this->params->get('num_intro_articles', 4), ($this->total - $i));
			$rows    = ceil($count / $this->params->get('num_columns', 2));
			$columns = array();
			$row     = 0;
			$column  = 0;
			
			// create intro columns
			for ($j = 0; $j < $count; $j++, $i++) { 

				if ($this->params->get('multi_column_order', 1) == 0) {
					// order down
					if ($row >= $rows) {
						$column++;
						$row  = 0;
						$rows = ceil(($count - $j) / ($this->params->get('num_columns', 2) - $column));
					}
					$row++;
				} else {
					// order across
					$column = $j % $this->params->get('num_columns', 2);
				}

				if (!isset($columns[$column])) {
					$columns[$column] = '';
				}

				$this->item =& $this->getItem($i, $this->params);
				$columns[$column] .= $this->loadTemplate('item');
			}

			// render intro columns
			$count = count($columns);
			if ($count) {
				if ($count != 1) {
					echo '<div class="teaserarticles multicolumns">';
				} else {
					echo '<div class="teaserarticles">';
				}
				for ($j = 0; $j < $count; $j++) {
					$firstlast = "";
					if ($count != 1) {
						if ($j == 0) $firstlast = "first";
						if ($j == $count - 1) $firstlast = "last";
					}
					echo '<div class="'.$firstlast.' float-left width'.intval(100 / $count).'">'.$columns[$j].'</div>';
				}
				echo '</div>';
			}
		}
		?>

		<?php if ($this->params->def('num_links', 4) && ($i < $this->total)) : ?>
		<div class="morearticles">
			<?php
				$this->links = array_splice($this->items, $i - $this->pagination->limitstart);
				echo $this->loadTemplate('links');
			?>
		</div>
		<?php endif; ?>

		<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="results">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<?php endif; ?>
		
	</div>
</div>