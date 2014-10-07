<?php
//Testeado totalmente en FF 3.6 e IE, falla con Konqueror o.O

/*
Copyright (c) 2005 Paul James
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. Neither the name of the Paul James nor the names of its contributors
   may be used to endorse or promote products derived from this software
   without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
SUCH DAMAGE.
*/

/** HTTP Digest authentication class */
class HTTPDigest
{
var $error_log = "/logs/error.log";
    /** The Digest opaque value (any string will do, never sent in plain text over the wire).
     * @var str
     */
    var $opaque = 'famalda412';

    /** The authentication realm name.
     * @var str
     */    
    var $realm = 'funiber.org';
    
    /** The base URL of the application, auth data will be used for all resources under this URL.
     * @var str
     */
    var $baseURL = '/';
    
    /** Are passwords stored as an a1 hash (username:realm:password) rather than plain text.
     * @var str
     */
    var $passwordsHashed = TRUE;
    
    /** The private key.
     * @var str
     */
    var $privateKey = 'mastropiero';
    
    /** The life of the nonce value in seconds
     * @var int
     */
    var $nonceLife = 300;

    /** Send HTTP Auth header */
    function send()
    {
        header($this->get_header());
        header('HTTP/1.1 401 Unauthorized');
    }

    function get_header(){
        $header = 'WWW-Authenticate: Digest '.
            'realm="'.$this->realm.'", '.
            'domain="'.$this->baseURL.'", '.
            'qop=auth, '.
            'algorithm=MD5, '.
            'nonce="'.$this->getNonce().'", '.
            'opaque="'.$this->getOpaque().'"';
        return $header;
    }

    /** Authenticate the user and return username on success.
     * @param str[] users Array of username/password pairs
     * @return str
     */
    function authenticate($users)
    {
    $this->error_log = dirname(__FILE__)."".$this->error_log;
        if (isset($_SERVER['Authorization'])) {
            $authorization = $_SERVER['Authorization'];
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $authorization = $headers['Authorization'];
            }//Sino, es que el navegador no soporta Digest
        } else {
            trigger_error('HTTP Digest headers not being passed to PHP by the server, unable to authenticate user'); exit;
        }

	error_log("authorization: ".$authorization."\n",3, $this->error_log);

        if (isset($authorization)) {
            if (substr($authorization, 0, 5) == 'Basic') {
                trigger_error('You are trying to use HTTP Basic authentication but I am expecting HTTP Digest');
                exit;
            }
            if (
                preg_match('/username="([^"]+)"/', $authorization, $username) &&
                preg_match('/nonce="([^"]+)"/', $authorization, $nonce) &&
                preg_match('/response="([^"]+)"/', $authorization, $response) &&
                preg_match('/opaque="([^"]+)"/', $authorization, $opaque) &&
                preg_match('/uri="([^"]+)"/', $authorization, $uri)
            ) {
                $username = $username[1];
                $requestURI = $_SERVER['REQUEST_URI'];
                if (strpos($requestURI, '?') !== FALSE) { // hack for IE which does not pass querystring in URI element of Digest string or in response hash
                    $requestURI = substr($requestURI, 0, strlen($uri[1]));
                }
                if (
                    isset($users[$username]) &&
                    $opaque[1] == $this->getOpaque() &&
                    $uri[1] == $requestURI &&
                    $nonce[1] == $this->getNonce()
                ) {
                    $passphrase = $users[$username];
                    if ($this->passwordsHashed) {
                        $a1 = $passphrase;
                    } else {
                        $a1 = md5($username.':'.$this->getRealm().':'.$passphrase);
                    }
error_log("a1: ".$a1."\n",3, $this->error_log);
                    $a2 = md5($_SERVER['REQUEST_METHOD'].':'.$requestURI);
error_log("a2: ".$a2."\n",3, $this->error_log);
                    if (
                        preg_match('/qop="?([^,\s"]+)/', $authorization, $qop) &&
                        preg_match('/nc="?([^,\s"]+)/', $authorization, $nc) &&
                        preg_match('/cnonce="?([^"]+)"/', $authorization, $cnonce)
                    ) {
                        $expectedResponse = md5($a1.':'.$nonce[1].':'.$nc[1].':'.$cnonce[1].':'.$qop[1].':'.$a2);
error_log("md5 largo: ".$expectedResponse."\n",3, $this->error_log);
                    } else {
                        $expectedResponse = md5($a1.':'.$nonce[1].':'.$a2);
error_log("md5 corto: ".$expectedResponse." qop ".$qop[1]." nc ".$nc[1]." y cnonce ".$cnonce[1]."\n",3, $this->error_log);
                    }
			error_log("Response enviado ".$response[1]." y response esperado ".$expectedResponse."\n",3,$this->error_log);
                    if ($response[1] == $expectedResponse) {
                        return $username;
                    }
                }
            }
        }
        return NULL;
    }

    /** Get nonce value for HTTP Digest.
     * @return str
     */
    function getNonce() {
	return "9907dbfc03c1f258c28f1a312ca678c7";
        //$time = ceil(time() / $this->nonceLife) * $this->nonceLife;
        //return md5(date('Y-m-d H:i', $time).':'.$_SERVER['REMOTE_ADDR'].':'.$this->privateKey);
    }

    /** Get opaque value for HTTP Digest.
     * @return str
     */
    function getOpaque()
    {
        return md5($this->opaque);
    }

    /** Get realm for HTTP Digest taking PHP safe mode into account.
     * @return str
     */
    function getRealm()
    {
        if (ini_get('safe_mode')) {
            return $this->realm.'-'.getmyuid();
        } else {
            return $this->realm;
        }
    }

}


//*/

?>
