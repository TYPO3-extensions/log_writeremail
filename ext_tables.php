<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Extension\ExtensionManager::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'LogWriter E-Mail');

?>