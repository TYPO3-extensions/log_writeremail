<?php

$extensionClassPath = \TYPO3\CMS\Core\Extension\ExtensionManager::extPath('log_writeremail') . 'Classes/';

return array(
	'tx_logwriteremail_log_writer_email' => $extensionClassPath . 'Log/Writer/Email.php',
);

?>