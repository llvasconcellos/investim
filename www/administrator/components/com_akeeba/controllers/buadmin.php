<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2010 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $id$
 * @version $Id: buadmin.php 71 2010-02-22 22:17:01Z nikosdion $
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.controller');

/**
 * The Backup Administrator class
 *
 */
class AkeebaControllerBuadmin extends JController
{
	/**
	 * Show a list of backup attempts
	 *
	 */
	public function display()
	{
		parent::display();
	}

	/**
	 * Downloads the backup file of a specific backup attempt,
	 * if it's available
	 *
	 */
	public function download()
	{
		$cid = JRequest::getVar('cid',array(),'default','array');
		$id = JRequest::getInt('id');
		$part = JRequest::getInt('part',-1);

		if(empty($id))
		{
			if(is_array($cid) && !empty($cid))
			{
				$id = $cid[0];
			}
			else
			{
				$id = -1;
			}
		}

		if($id <= 0)
		{
			$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
			parent::display();
			return;
		}

		$stat = AEPlatform::get_statistics($id);
		$allFilenames = AEUtilStatistics::get_all_filenames($stat);

		// Check single part files
		if( (count($allFilenames) == 1) && ($part == -1) )
		{
			$filename = array_shift($allFilenames);
		}
		elseif( (count($allFilenames) > 0) && (count($allFilenames) > $part) && ($part >= 0) )
		{
			$filename = $allFilenames[$part];
		}
		else
		{
			$filename = null;
		}

		if(is_null($filename) || empty($filename) || !@file_exists($filename) )
		{
			$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDDOWNLOAD'), 'error');
			parent::display();
			return;
		}
		else
		{
			$basename = @basename($filename);
			$filesize = @filesize($filename);
			$extension = strtolower(str_replace(".", "", strrchr($filename, ".")));

			JRequest::setVar('format','raw');
			@ob_end_clean();
			@clearstatcache();
			// Send MIME headers
			header('MIME-Version: 1.0');
			header('Content-Disposition: attachment; filename='.$basename);
			header('Content-Transfer-Encoding: binary');
			switch($extension)
			{
				case 'zip':
					// ZIP MIME type
					header('Content-Type: application/zip');
					break;

				default:
					// Generic binary data MIME type
					header('Content-Type: application/octet-stream');
					break;
			}
			// Notify of filesize, if this info is available
			if($filesize > 0) header('Content-Length: '.@filesize($filename));
			// Disable caching
			header('Expires: Mon, 20 Dec 1998 01:00:00 GMT');
			header('Cache-Control: no-cache, must-revalidate');
			header('Pragma: no-cache');
			@readfile($filename); die();
		}

	}

	/**
	 * Deletes one or several backup statistics records and their associated backup files
	 */
	public function remove()
	{
		$cid = JRequest::getVar('cid',array(),'default','array');
		$id = JRequest::getInt('id');
		if(empty($id))
		{
			if(!empty($cid) && is_array($cid))
			{
				foreach ($cid as $id)
				{
					$result = $this->_remove($id);
					if(!$result) $this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
				}
			}
			else
			{
				$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
				return;
			}
		}
		else
		{
			$result = $this->_remove($id);
			if(!$result) $this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
		}

		$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_MSG_DELETED'));

		parent::display();
	}

	/**
	 * Deletes backup files associated to one or several backup statistics records
	 */
	public function deletefiles()
	{
		$cid = JRequest::getVar('cid',array(),'default','array');
		$id = JRequest::getInt('id');
		if(empty($id))
		{
			if(!empty($cid) && is_array($cid))
			{
				foreach ($cid as $id)
				{
					$result = $this->_removeFiles($id);
					if(!$result) $this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
				}
			}
			else
			{
				$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
				return;
			}
		}
		else
		{
			$result = $this->_remove($id);
			if(!$result) $this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
		}

		$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_MSG_DELETEDFILE'));

		parent::display();
	}

	/**
	 * Removes the backup file linked to a statistics entry and the entry itself
	 *
	 * @return bool True on success
	 */
	private function _remove($id)
	{
		if($id <= 0)
		{
			$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
			return;
		}

		$model =& $this->getModel('statistics');
		return $model->delete($id);
	}

	/**
	 * Removes only the backup file linked to a statistics entry
	 *
	 * @return bool True on success
	 */
	private function _removeFiles($id)
	{
		if($id <= 0)
		{
			$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
			return;
		}

		$model =& $this->getModel('statistics');
		return $model->deleteFile($id);
	}

	public function showcomment()
	{
		$cid = JRequest::getVar('cid',array(),'default','array');
		$id = JRequest::getInt('id');

		if(empty($id))
		{
			if(is_array($cid) && !empty($cid))
			{
				$id = $cid[0];
			}
			else
			{
				$id = -1;
			}
		}

		if($id <= 0)
		{
			$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', JText::_('STATS_ERROR_INVALIDID'), 'error');
			parent::display();
			return;
		}

		JRequest::setVar('id', $id);

		parent::display();
	}


	/**
	 * Save an edited backup record
	 */
	public function save()
	{
		$id = JRequest::getInt('id');
		$description = JRequest::getString('description');
		$comment = JRequest::getVar('comment',null,'default','string',4);

		$statistic = AEPlatform::get_statistics(JRequest::getInt('id'));
		$statistic['description']	= $description;
		$statistic['comment']		= $comment;
		AEPlatform::set_or_update_statistics(JRequest::getInt('id'),$statistic,$self);

		if( !$this->getError() ) {
			$message = JText::_('STATS_LOG_SAVEDOK');
			$type = 'message';
		} else {
			$message = JText::_('STATS_LOG_SAVEERROR');
			$type = 'error';
		}
		$this->setRedirect(JURI::base().'index.php?option=com_akeeba&view=buadmin', $message, $type);
	}
}