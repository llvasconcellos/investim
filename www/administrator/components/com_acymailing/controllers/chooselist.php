<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class ChooselistController extends acymailingController{
	function customfields(){
		JRequest::setVar( 'layout', 'customfields'  );
		return parent::display();
	}
}