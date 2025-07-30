<?php
namespace app\Services;

//import guzzle and League\Oauth2
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\GenericProvider;

class ChurchSuiteApiService{
    protected $provider;
    protected $accessToken;

    public function __construct(){
        //construct a new provider with relevant details
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

            // Try to get an access token using the client credentials above.
            $accessToken = $this->provider->getAccessToken('client_credentials', ['scope' => 'full_access']);
            return $accessToken;

        } catch(\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

            // if authenticaiton fials, then return the error details
            return ['error' => $e->getMessage()];
        }
    }

    public function getContacts($page, $q) {
        //create a new client and handle null query parameters
        $client = new Client();
        $query = $page != NULL ? '?'. "page=$page" : '?';
        $query = $q != NULL ? $query . "&q=$q" : $query;

        try{
            //if there is a query, make that request
            if($query != '?'){
                $response = $client->request('GET', 'https://api.churchsuite.com/v2/addressbook/contacts' . "$query", [
                'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken->getToken(),
                ],
            ]);
            } else {
                // if there are no query parameters make a basic request
                $response = $client->request('GET', 'https://api.churchsuite.com/v2/addressbook/contacts', [
                    'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken->getToken(),
                    ],
                ]);
            }
            //return the decoded response
            return json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            //pass details of any errors to the controller
            $response = $e->getResponse();
            $responseString = $response->getBody()->getContents();
            $status = $response->getStatusCode();

            return ['error' => ['status' => $status, 'message' => $responseString]];
        }
    }
}









?>