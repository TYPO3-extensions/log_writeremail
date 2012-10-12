<?php
/***************************************************************
* Copyright notice
*
* (c) 2012 T3DD12 Logging Workshop Team:
*   Jochem de Groot <jochemdegroot@roquin.nl>
*   Marco Huber <>
*   Steffen Müller <typo3@t3node.com>
*
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

#use \TYPO3\CMS\Core\Log as Log;

/**
 * LogWriter for the TYPO3 Logging API.
 * Sends Log records via E-Mail
 *
 * @TODO Make use of some templating
 */
class Tx_LogWriteremail_Log_Writer_Email extends \TYPO3\CMS\Core\Log\Writer\AbstractWriter {

		/** @var string */
	protected $recipient = '';

		/** @var string */
	protected $sender = '';

		/** @var string */
	protected $subject = '';

		/** @var string */
	protected $body = '';

		/** @var int */
	protected $cropLength = 76;


	/**
	 * Renders the E-Mail
	 *
	 * @param \TYPO3\CMS\Core\Log\LogRecord $record
	 * @return Tx_LogWriteremail_Log_Writer_Email
	 */
	public function writeLog(\TYPO3\CMS\Core\Log\LogRecord $record) {
		if (empty($this->recipient) || empty($this->sender)) {
			return $this;
		}

		$this->subject =
			'[' . \TYPO3\CMS\Core\Utility\GeneralUtility::getHostname() . '] ' .
			'[' . \TYPO3\CMS\Core\Log\LogLevel::getName($record->getLevel()) . '] ' .
			'in ' . $record->getComponent() . ': ' .
			$record->getMessage()
		;
		$this->subject = \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($this->subject, $this->cropLength);
		$this->body = $record->getMessage() . print_r($record->getData(), TRUE);

		$this->sendMail();

		return $this;
	}

	/**
	 * Send a mail using the SwiftMailer API
	 *
	 * @return void
	 */
	protected function sendMail() {

			/** @var \TYPO3\CMS\Core\Mail\MailMessage $mail */
		$mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
		$mail->addTo($this->recipient);
		$mail->setFrom($this->sender);
		$mail->setSubject($this->subject);
		$mail->setBody($this->body);

		try {
			$mail->send();
		} catch (Exception $e) {
			\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__)->warning($e);
		}
	}

	/**
	 * Sets the E-Mail address of the sender
	 *
	 * @param string $sender
	 * @return void
	 */
	public function setSender($sender) {
		$this->sender = $sender;
	}

	/**
	 * Sets the E-Mail address of the recipient
	 *
	 * @param string $recipient
	 * @return void
	 */
	public function setRecipient($recipient) {
		$this->recipient = $recipient;
	}

}

?>