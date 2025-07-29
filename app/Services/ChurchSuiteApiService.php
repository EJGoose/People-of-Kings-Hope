<?php
namespace app\Services;

use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\GenericProvider;

class ChurchSuiteApiService{
    protected $provider;
    protected $accessToken;

    public function __construct(){
        $this->provider = new GenericProvider([
            'clientId' => env('CS_CLIENT_ID'),
            'clientSecret' => env('CS_SECRET'),
            'redirectUri' => route('main'),
            'urlAuthorize' => 'https://login.churchsuite.com/oauth2/authorize',
            'urlAccessToken' => 'https://login.churchsuite.com/oauth2/token',
            'urlResourceOwnerDetails' => 'https://api.churchsuite.com/v2/'
        ]);

        $this->accessToken = $this->retrieveAccessToken();
    }


    private function retrieveAccessToken(){
        try {

            // Try to get an access token using the client credentials grant.
            $accessToken = $this->provider->getAccessToken('client_credentials', ['scope' => 'full_access']);
            return $accessToken;

        } catch(\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

            // Failed to get the access token
            return ['error' => $e->getMessage()];
        }
    }

    public function getContacts($page, $q) {
        $client = new Client();
        $query = $page != NULL ? '?'. "page=$page" : '?';
        $query = $q != NULL ? $query . "&q=$q" : $query;

        try{
            if($query != '?'){
                $response = $client->request('GET', 'https://api.churchsuite.com/v2/addressbook/contacts' . "$query", [
                'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken->getToken(),
                ],
            ]);
            } else {

                $response = $client->request('GET', 'https://api.churchsuite.com/v2/addressbook/contacts', [
                    'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken->getToken(),
                    ],
                ]);
            }

            return json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            //handle any errors that occur during the request
            //400 invalid request
            //401 unauthorised
            //403 Forbidden
            //429 Rate limit exceeded

            $response = $e->getResponse();
            $responseString = $response->getBody()->getContents();
            $status = $response->getStatusCode();

            return ['error' => ['status' => $status, 'message' => $responseString]];
        }
    }
}









?>