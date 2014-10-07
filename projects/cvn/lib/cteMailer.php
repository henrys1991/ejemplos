<?php
/**
 * -------------------------
 * Libreria cteMailer
 *
 * @author: Ronald Gonzales (RG)
 * @comentarios: Clase para customizar la libreria PHPMailer (PHP class for email creation and sending)
 * http://sourceforge.net/projects/phpmailer/
 * http://code.google.com/a/apache-extras.org/p/phpmailer/wiki/HowToExtend
 * -------------------------
 */

//require_once('../config/lang/eng.php');
require_once('phpmailer/class.phpmailer.php');

class cteMailer extends PHPMailer {
	
	// Set default variables for all new objects
	public $Mailer = "smtp"; 					// Alternative to IsSMTP() : telling the class to use SMTP
	public $SMTPKeepAlive = true;
	public $SMTPAuth = true;                  	// enable SMTP authentication
	public $Timeout = 60;
	
	public $Host = "smtp.funiber.org";  		// SMT Server
	public $Username = 'ctfuniber';  			// SMTP account username
	public $Password = '10tOrNiLlO06';			// SMTP account password
	

	
	
	
}