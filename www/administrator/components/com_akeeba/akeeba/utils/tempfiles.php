<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 * @version $Id: tempfiles.php 78 2010-03-04 17:59:01Z nikosdion $
 */

// Protection against direct access
defined('AKEEBAENGINE') or die('Restricted access');

/**
 * Temporary files management class. Handles creation, tracking and cleanup.
 */
class AEUtilTempfiles
{

	/**
	 * Creates a randomly-named temporary file, registers it with the temporary
	 * files management and returns its absolute path
	 * @return string The temporary file name
	 */
	static function createRegisterTempFile()
	{
		// Create a randomly named file in the temp directory
		$registry =& AEFactory::getConfiguration();
		$tempFile = tempnam($registry->get('akeeba.basic.temporary_directory'),'ak');
		// Register it and return its absolute path
		$tempName = basename($tempFile);
		return AEUtilTempfiles::registerTempFile($tempName);
	}

	/**
	 * Registers a temporary file with the Akeeba Engine, storing the list of temporary files
	 * in another temporary flat database file.
	 *
	 * @param	string	$fileName The path of the file, relative to the temporary directory
	 * @return	string	The absolute path to the temporary file, for use in file operations
	 */
	static function registerTempFile( $fileName )
	{
		$configuration =& AEFactory::getConfiguration();
		$tempFiles = $configuration->get('volatile.tempfiles', false);
		if($tempFiles===false)
		{
			$tempFiles = array();
		}
		else
		{
			$tempFiles = unserialize($tempFiles);
		}

		if(!in_array($fileName, $tempFiles))
		{
			$tempFiles[] = $fileName;
			$configuration->set('volatile.tempfiles', serialize($tempFiles));
		}

		return AEUtilFilesystem::TranslateWinPath($configuration->get('akeeba.basic.temporary_directory').DS.$fileName);
	}

	/**
	 * Unregister and delete a temporary file
	 * @param $fileName The filename to unregister and delte
	 * @param $removePrefix The prefix to remove
	 */
	static function unregisterAndDeleteTempFile( $fileName, $removePrefix = false )
	{
		$configuration =& AEFactory::getConfiguration();

		if($removePrefix)
		{
			$fileName = str_replace( AEUtilFilesystem::TranslateWinPath($configuration->get('akeeba.basic.temporary_directory')) , '', $fileName);
			if( (substr($fileName, 0, 1) == '/') || (substr($fileName, 0, 1) == '\\') )
			{
				$fileName = substr($fileName, 1 );
			}
			if( (substr($fileName, -1) == '/') || (substr($fileName, -1) == '\\') )
			{
				$fileName = substr($fileName, 0, -1 );
			}
		}

		$tempFiles = $configuration->get('volatile.tempfiles', false);
		if( $tempFiles !== false )
		{
			$tempFiles = unserialize($tempFiles);
			$newTempFiles = array();

			if(is_array($tempFiles) && !empty($tempFiles))
			{
				$aFile = array_shift($tempFiles);
				while( !is_null($aFile) )
				{
					if($aFile != $fileName) $newTempFiles[] = $aFile;
					$aFile = array_shift($tempFiles);
				}
			}

			$configuration->set('volatile.tempfiles', serialize($tempFiles));
		}

		$file = $configuration->get('akeeba.basic.temporary_directory').DS.$fileName;
		$file = AEUtilFilesystem::TranslateWinPath($file);
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Removing temporary file $fileName" );
		$platform = strtoupper(PHP_OS);
		if( (substr($platform,0,6) == 'CYGWIN') || (substr($platform,0,3) == 'WIN') )
		{
			// On Windows we have to chwon() the file first to make it owned by Nobody
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Windows hack: chowning $fileName" );
			@chown($file, 600);
		}
		return @unlink($file);
	}


	/**
	 * Deletes all temporary files
	 */
	static function deleteTempFiles()
	{
		$configuration =& AEFactory::getConfiguration();

		$tempFiles = $configuration->get('volatile.tempfiles', false);
		if($tempFiles === false) {
			$tempFiles = array();
		} else {
			$tempFiles = unserialize($tempFiles);
		}
		if(!empty($tempFiles)) foreach($tempFiles as $fileName)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Removing temporary file $filename" );
			$file = AEUtilFilesystem::TranslateWinPath($configuration->get('akeeba.basic.temporary_directory').DS.$fileName);
			$platform = strtoupper(PHP_OS);
			if( (substr($platform,0,6) == 'CYGWIN') || (substr($platform,0,3) == 'WIN') )
			{
				// On Windows we have to chwon() the file first to make it owned by Nobody
				@chown($file, 600);
			}
			@unlink($file);
		}

		$tempFiles = array();
		$configuration->set('volatile.tempfiles', serialize($tempFiles));
	}
}