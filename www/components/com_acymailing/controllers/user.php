<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class UserController extends acymailingController{
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerDefaultTask('subscribe');
	}
	function confirm(){
		$config = acymailing::config();
		$app =& JFactory::getApplication();
		$redirectUrl = $config->get('confirm_redirect');
		if(empty($redirectUrl)){
			$redirectUrl = acymailing::completeLink('lists',false,true);
		}
		$this->setRedirect($redirectUrl);
		$userClass = acymailing::get('class.subscriber');

		$user = $userClass->identify();
		if(empty($user)) return false;
		if($user->confirmed){
			if($config->get('confirmation_message',1)){
				$app->enqueueMessage(JText::_('ALREADY_CONFIRMED'));
			}
			return false;
		}
		if($config->get('confirmation_message',1)){
			$app->enqueueMessage(JText::_('SUBSCRIPTION_CONFIRMED'));
		}
		$userClass->confirmSubscription($user->subid);
		return true;
	}//endfct
	function modify(){
		$userClass = acymailing::get('class.subscriber');
		$user = $userClass->identify();
		if(empty($user)) return $this->subscribe();
		JRequest::setVar( 'layout', 'modify'  );
		return parent::display();
	}
	function subscribe(){
		$user = JFactory::getUser();
		$userClass = acymailing::get('class.subscriber');
		if(!empty($user->id) AND $userClass->identify(true)){ return $this->modify(); }
		$config = acymailing::config();
		$allowvisitor = $config->get('allow_visitor',1);
		if(empty($allowvisitor)){
			$app =& JFactory::getApplication();
			$app->enqueueMessage(JText::_('ONLY_LOGGED'),'notice');
			$app->redirect(acymailing::completeLink('lists',false,true));
			return;
		}
		JRequest::setVar( 'layout', 'modify'  );
		return parent::display();
	}
	function unsub(){
		$userClass = acymailing::get('class.subscriber');
		$user = $userClass->identify();
		if(empty($user)) return false;
		JRequest::setVar( 'layout', 'unsub'  );
		return parent::display();
	}
	function saveunsub(){
		acymailing::checkRobots();
		$app =& JFactory::getApplication();
		$subscriberClass = acymailing::get('class.subscriber');
		$listsubClass = acymailing::get('class.listsub');
		$config = acymailing::config();
		$subscriber = null;
		$subscriber->subid = acymailing::getCID('subid');
		$user = $subscriberClass->identify();
		if($user->subid != $subscriber->subid){
			echo "<script>alert('ERROR : You are not allowed to modify this user'); window.history.go(-1);</script>";
			exit;
		}
		$refusemails = JRequest::getInt('refuse');
		$unsuball = JRequest::getInt('unsuball');
		$unsublist = JRequest::getInt('unsublist');
		$mailid = JRequest::getInt('mailid');
		$oldUser = $subscriberClass->get($subscriber->subid);
		if($refusemails){
			$subscriber->accept = 0;
			$subscriberClass->save($subscriber);
			$app->enqueueMessage(JText::_('CONFIRM_UNSUB_FULL'));
			$notifyUsers = $config->get('notification_refuse');
			if(!empty($notifyUsers)){
				$mailer = acymailing::get('helper.mailer');
				$mailer->report = false;
				$mailer->autoAddUser = true;
				$mailer->checkConfirmField = false;
				$mailer->addParam('user:name',$oldUser->name);
				$mailer->addParam('user:email',$oldUser->email);
				$allUsers = explode(',',$notifyUsers);
				foreach($allUsers as $oneUser){
					$mailer->sendOne('notification_refuse',$oneUser);
				}
			}
		}elseif($unsuball){
			$notifyUsers = $config->get('notification_unsuball');
			if(!empty($notifyUsers)){
				$mailer = acymailing::get('helper.mailer');
				$mailer->autoAddUser = true;
				$mailer->checkConfirmField = false;
				$mailer->report = false;
				$mailer->addParam('user:name',$oldUser->name);
				$mailer->addParam('user:email',$oldUser->email);
				$allUsers = explode(',',$notifyUsers);
				foreach($allUsers as $oneUser){
					$mailer->sendOne('notification_unsuball',$oneUser);
				}
			}
		}
		$incrementUnsub = false;
		if($refusemails OR $unsuball){
			$subscription = $subscriberClass->getSubscriptionStatus($subscriber->subid);
			$updatelists = array();
			foreach($subscription as $listid => $oneList){
				if($oneList->status != -1){
					$updatelists[-1][] = $listid;
				}
			}
			if(!empty($updatelists)){
				$status = $listsubClass->updateSubscription($subscriber->subid,$updatelists);
				$app->enqueueMessage(JText::_('CONFIRM_UNSUB_ALL'));
				$incrementUnsub = true;
			}else{
				$app->enqueueMessage(JText::_('ERROR_NOT_SUBSCRIBED'));
			}
		}elseif($unsublist){
			$subscription = $subscriberClass->getSubscriptionStatus($subscriber->subid);
			$db = JFactory::getDBO();
			$db->setQuery('SELECT b.listid, b.name, b.type FROM '.acymailing::table('listmail').' as a LEFT JOIN '.acymailing::table('list').' as b on a.listid = b.listid WHERE a.mailid = '.$mailid);
			$allLists = $db->loadObjectList();
			if(empty($allLists)){
				echo "<script>alert('ERROR : Could not get the list for the mailing $mailid'); window.history.go(-1);</script>";
				exit;
			}
			$campaignList = array();
			$unsubList = array();
			foreach($allLists as $oneList){
				if(isset($subscription[$oneList->listid]) AND $subscription[$oneList->listid]->status != -1){
					if($oneList->type == 'campaign'){
						$campaignList[] = $oneList->listid;
					}else{
						$unsubList[$oneList->listid] = $oneList;
					}
				}
			}
			if(!empty($campaignList)){
				$db->setQuery('SELECT b.listid, b.name, b.type FROM '.acymailing::table('listcampaign').' as a LEFT JOIN '.acymailing::table('list').' as b on a.listid = b.listid WHERE a.campaignid IN '.implode(',',$campaignList));
				$otherLists = $db->loadObjectList();
				if(!empty($otherLists)){
					foreach($otherLists as $oneList){
						if(isset($subscription[$oneList->listid]) AND $subscription[$oneList->listid]->status != -1){
							$unsubList[$oneList->listid] = $oneList;
						}
					}
				}
			}
			if(!empty($unsubList)){
				$updatelists = array();
				$updatelists[-1] = array_keys($unsubList);
				$status = $listsubClass->updateSubscription($subscriber->subid,$updatelists);
				$app->enqueueMessage(JText::_('CONFIRM_UNSUB_CURRENT'));
				$incrementUnsub = true;
			}else{
				$app->enqueueMessage(JText::_('ERROR_NOT_SUBSCRIBED_CURRENT'));
			}
		}
		$redirectUnsub = $config->get('unsub_redirect');
		if(empty($redirectUnsub)){
			$redirectUnsub = acymailing::completeLink('lists',false,true);
		}
		if($incrementUnsub){
			$db=& JFactory::getDBO();
			$db->setQuery('UPDATE '.acymailing::table('stats').' SET `unsub` = `unsub` +1 WHERE `mailid` = '.(int)$mailid);
			$db->query();
		}
		$this->setRedirect($redirectUnsub);
	}
	function savechanges(){
		JRequest::checkToken() or die( 'Please make sure your cookies are enabled' );
		acymailing::checkRobots();
		$app =& JFactory::getApplication();
		$subscriberClass = acymailing::get('class.subscriber');
		$status = $subscriberClass->saveForm();
		if($status){
			$app->enqueueMessage(JText::_( 'JOOMEXT_SUCC_SAVED' ), 'message');
		}elseif($subscriberClass->requireId){
			$app->enqueueMessage(JText::_( 'IDENTIFICATION_SENT' ), 'notice');
		}else{
			$app->enqueueMessage(JText::_( 'ERROR_SAVING' ), 'error');
		}
		if($subscriberClass->identify(true)) return $this->modify();
		return $this->subscribe();
	}
}