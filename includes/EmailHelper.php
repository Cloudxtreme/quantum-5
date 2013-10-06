<?php

/**
 * Email Helper.
 *
 * @package Quantum
 * @author GAbor Klausz
 */
class EmailHelper
{
	/** @var string   To. */
	public $to = '';

	/** @var string   Subject. */
	public $subject = '';

	/** @var string   Message. */
	public $message = '';

	/** @var string   Additional header. */
	public $additionalHeaders = '';

	/**
	 * Constructor
	 *
	 * @param string $to        To.
	 * @param string $subject   Subject.
	 * @param string $message   Message.
	 *
	 * @return void
	 */
	public function __construct($from, $to, $subject, $message) {
		$this->to      = $to;
		$this->subject = $subject;
		$this->message = $message;

		$this->additionalHeaders  = 'MIME-Version: 1.0' . "\r\n";
		$this->additionalHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$this->additionalHeaders .= 'From: ' . $from . "\r\n";
		$this->additionalHeaders .= 'Reply-To: ' . $from . "\r\n";
	}

	/**
	 * Param setter.
	 *
	 * @param string $param   Params.
	 *                        - to
	 *                        - subject
	 *                        - message
	 *                        - $additionalHeaders
	 *                        - $additionalParameters
	 * @param string $value   Value.
	 *
	 * @return void
	 */
	public function setParam($param, $value) {
		$this->$param = $value;
	}

	/**
	 * Email address valid checker.
	 *
	 * @param string $email   Email address.
	 *
	 * @return bool
	 */
	public static function isValid($email)
	{
		$return = false;
		$regexp = '/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/';

		if (preg_match($regexp, $email)) {
			$return = true;
		}

		return $return;
	}

	/**
	 * Sender.
	 *
	 * @return void
	 */
	public function send() {
		mail(
			$this->to,
			$this->subject,
			$this->message,
			$this->additionalHeaders
		);
	}
}