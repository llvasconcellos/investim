<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
	$db =& JFactory::getDBO();
	$db->setQuery('SELECT count(*) FROM '.acymailing::table('ccnewsletter_subscribers',false));
	$resultUsers = $db->loadResult();
?>
There are <?php echo $resultUsers ?> users in ccNewsletter.