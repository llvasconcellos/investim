<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class queueClass extends acymailingClass{
	function delete($filters){
		$query = 'DELETE a.* FROM '.acymailing::table('queue').' as a';
		if(!empty($filters)){
			$query .= ' LEFT JOIN '.acymailing::table('subscriber').' as b on a.subid = b.subid';
			$query .= ' LEFT JOIN '.acymailing::table('mail').' as c on a.mailid = c.mailid';
			$query .= ' WHERE ('.implode(') AND (',$filters).')';
		}
		$this->database->setQuery($query);
		$this->database->query();
		return $this->database->getAffectedRows();
	}
	function nbQueue($mailid){
		$mailid = (int) $mailid;
		$this->database->setQuery('SELECT count(subid) FROM '.acymailing::table('queue').' WHERE mailid = '.$mailid.' GROUP BY mailid');
		return $this->database->loadResult();
	}
	function queue($mailid,$time){
		$mailid = intval($mailid);
		if(empty($mailid)) return false;
		$classLists = acymailing::get('class.listmail');
		$lists = $classLists->getReceivers($mailid);
		if(empty($lists)) return 0;
		$config = acymailing::config();
		$querySelect = 'SELECT DISTINCT a.subid,'.$mailid.','.$time.','.(int) $config->get('priority_newsletter',3);
		$querySelect .= ' FROM '.acymailing::table('listsub').' as a ';
		$querySelect .= 'LEFT JOIN '.acymailing::table('subscriber').' as b ON a.subid = b.subid ';
		$querySelect .= 'WHERE b.enabled = 1 AND b.accept = 1 ';
		$querySelect .= 'AND a.listid IN ('.implode(',',array_keys($lists)).') AND a.status = 1 ';
		$config = acymailing::config();
		if($config->get('require_confirmation','0')){ $querySelect .= 'AND b.confirmed = 1 '; }
		$query = 'INSERT IGNORE INTO '.acymailing::table('queue').' (subid,mailid,senddate,priority) '.$querySelect;
		$this->database->setQuery($query);
		$this->database->query();
		return $this->database->getAffectedRows();
	}
	function getReady($limit,$mailid = 0){
		$query = 'SELECT * FROM '.acymailing::table('queue').' as a';
		$query .= ' LEFT JOIN '.acymailing::table('mail').' as b on a.mailid = b.mailid ';
		$query .= ' WHERE a.senddate <= '.time().' AND b.published > 0';
		if(!empty($mailid)) $query .= ' AND a.mailid = '.$mailid;
		$query .= ' ORDER BY a.`priority` ASC, a.senddate ASC';
		if(!empty($limit)) $query .= ' LIMIT '.$limit;
		$this->database->setQuery($query);
		return $this->database->loadObjectList();
	}
	function queueStatus($mailid,$all = false){
		$query = 'SELECT a.mailid, count(a.subid) as nbsub,min(a.senddate) as senddate, b.subject FROM '.acymailing::table('queue').' as a';
		$query .= ' LEFT JOIN '.acymailing::table('mail').' as b on a.mailid = b.mailid';
		$query .= ' WHERE b.published > 0';
		if(!$all){
			$query .= ' AND a.senddate < '.time();
			if(!empty($mailid)) $query .= ' AND a.mailid = '.$mailid;
		}
		$query .= ' GROUP BY a.mailid';
		$this->database->setQuery($query);
		$queueStatus = $this->database->loadObjectList('mailid');
		return $queueStatus;
	}
}