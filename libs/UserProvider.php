<?php

namespace CatPKT\UserProvider;

use FenzHTTP\HTTP;

////////////////////////////////////////////////////////////////

class UserProvider
{

	/**
	 * Var uri
	 *
	 * @access private
	 *
	 * @var    string
	 */
	private $uri;

	/**
	 * Var kei
	 *
	 * @access private
	 *
	 * @var    string
	 */
	private $key;

	/**
	 * Var method
	 *
	 * @access private
	 *
	 * @var    string
	 */
	private $method;

	/**
	 * Method __construct
	 *
	 * @access public
	 *
	 * @param  string $uri
	 * @param  string $key
	 * @param  string $method
	 */
	public function __construct( string$uri,string$key, string$method='AES-256-CBC' )
	{
		$this->uri= $uri;
		$this->key= $key;
		$this->method= $method;
	}

	/**
	 * Method getToken
	 *
	 * @access public
	 *
	 * @param  string $thirdId
	 * @param  string $introducer
	 *
	 * @return string
	 */
	public function getToken( string$thirdId, string$introducer=null ):string
	{
		return HTTP::url($this->uri)->post($this->encrypt(
			[ 'thirdId'=>$thirdId, ]+($introducer? [ 'introducer'=>$introducer, ] : [] )
		));
	}

	/**
	 * Method encrypt
	 *
	 * @access private
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	private function encrypt( $data ):void
	{
		$iv= random_bytes($this->ivLength);

		$value= openssl_decrypt( serialize($data), $this->method, $this->key, 0, $iv );

		$iv= base64_encode($iv);

		$mac= hash_hmac( 'sha256', $iv.$value, $this->key );

		return base64_encode(json_encode([ 'iv'=>$iv, 'value'=>$value, 'mac'=>$mac, ]));
	}

}
