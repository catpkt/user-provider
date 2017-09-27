User Provider
================================

Providing third part users for other projects.


# Usage

```php
$userProvider= new UserProvider( $api_uri, $api_key/*, $method='AES-256-CBC'*/ );

$response= $userProvider->getToken( $user->id );

if( 200===$response.status ){
	return $token= $response->json;
}
```
