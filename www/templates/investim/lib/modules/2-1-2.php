<?php
/**
* @package   yoo_explorer Template
* @version   1.5.2 2010-01-03 14:20:02
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// example: rider

?>
<div class="module <?php echo $style; ?> <?php echo $color; ?> <?php echo $yootools; ?> <?php echo $first; ?> <?php echo $last; ?>">

	<?php echo $badge; ?>
	
	<div class="box-t1">
		<div class="box-t2"></div>
	</div>
	
	<div class="box-1 deepest">
	
		<?php if ($showtitle) : ?>
		<h3 class="header"><span class="header-2"><span class="header-3"><?php echo $title; ?></span></span></h3>
		<?php endif; ?>
		
		<?php echo $content; ?>
		
	</div>

	<div class="box-b1">
		<div class="box-b2"></div>
	</div>

</div>