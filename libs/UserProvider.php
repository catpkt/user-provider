<?php

namespace CatPKT\UserProvider;

use FenzHTTP\{  HTTP,  Response  };

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
	 * Var key
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
		$this->uri= rtrim( $uri, '/' );
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
	 * @return Response
	 */
	public function getToken( string$thirdId, string$introducer=null ):Response
	{
		return HTTP::url( "$this->uri/token" )->post( $this->encrypt(
			[ 'third_id'=>$thirdId, ]+($introducer? [ 'introducer'=>$introducer, ] : [] )
		) );
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
	private function encrypt( $data )
	{
		$iv= random_bytes(16);

		$value= openssl_encrypt( serialize($data), $this->method, $this->key, 0, $iv );

		if( $value===false )
		{
			throw new \Exception('Could not encrypt the data.');
		}

		$iv= base64_encode($iv);

		$mac= hash_hmac( 'sha256', $iv.$value, $this->key );

		return base64_encode(json_encode([ 'iv'=>$iv, 'value'=>$value, 'mac'=>$mac, ]));
	}

}
