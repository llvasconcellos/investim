<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class JElementListid extends JElement
{
	function fetchElement($name, $value, &$node, $control_name)
	{
		include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php');
		$listType = acymailing::get('type.lists');
		array_shift($listType->values);
		return $listType->display($control_name.'[listid]',(int) $value,false);
	}
}