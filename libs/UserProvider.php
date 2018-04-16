<?php

namespace CatPKT\UserProvider;

use FenzHTTP\{  HTTP,  Response  };
use CatPKT\Encryptor as CE;

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
	 * Var encryptor
	 *
	 * @access private
	 *
	 * @var    CE\IEncryptor
	 */
	private $encryptor;

	/**
	 * Method __construct
	 *
	 * @access public
	 *
	 * @param  string $uri
	 * @param  CE\IEncryptor $encryptor
	 */
	public function __construct( string$uri, CE\IEncryptor$encryptor )
	{
		$this->uri= rtrim( $uri, '/' );
		$this->encryptor= $encryptor;
	}

	/**
	 * Method getToken
	 *
	 * @access public
	 *
	 * @param  string $thirdId
	 * @param  string $label
	 *
	 * @return Response
	 */
	public function getToken( string$thirdId, string$label ):Response
	{
		return HTTP::url( "$this->uri/token" )->post( $this->encryptor->encrypt(
			[ 'third_id'=>$thirdId, 'label'=>$label, ]
		) );
	}

}
