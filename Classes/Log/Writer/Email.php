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

/**
 * LogWriter for the TYPO3 Logging API.
 * Sends Log records via E-Mail
 *
 * @TODO Are t3lib_mail_Message setters safe against control characters injection?
 * @TODO Make use of some templating
 */
class Tx_LogWriteremail_Log_Writer_Email extends t3lib_log_writer_Abstract {

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
	 * @param t3lib_log_Record $record
	 * @return Tx_LogWriteremail_Log_Writer_Email
	 */
	public function writeLog(t3lib_log_Record $record) {
		if (empty($this->recipient) || empty($this->sender)) {
			return $this;
		}

		$this->subject =
			'[' . t3lib_div::getHostname() . '] ' .
			'[' . t3lib_log_Level::getName($record->getLevel()) . '] ' .
			'in ' . $record->getComponent() . ': ' .
			$record->getMessage()
		;
		$this->subject = t3lib_div::fixed_lgd_cs($this->subject, $this->cropLength);
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

			/** @var $mail t3lib_mail_Message */
		$mail = t3lib_div::makeInstance('t3lib_mail_Message');
		$mail->addTo($this->recipient);
		$mail->setFrom($this->sender);
		$mail->setSubject($this->subject);
		$mail->setBody($this->body);

		try {
			$mail->send();
		} catch (Exception $e) {
			t3lib_log_LogManager::getLogger(__CLASS__)->warning($e);
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