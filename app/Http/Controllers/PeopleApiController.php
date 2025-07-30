<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChurchSuiteApiService;
use Illuminate\Support\Facades\Log;

class PeopleApiController extends Controller{
    protected $apiService;

    public function __construct(ChurchSuiteApiService $ChurchSuiteApiService)
    {
        $this->apiService = $ChurchSuiteApiService;
    }

    private function formatAddress($address){
        $combinedAddress = "";
        $addressUrl ="http://maps.apple.com/?q=";
        $i = 0;

        foreach($address as $line){
            if ($i == 5) break;
            if($line != ""){
                $combinedAddress = $i < 1 ? $line : $combinedAddress . ", " . $line;
                $lineNoSpaces = str_replace(" ", "+", $line);
                $addressUrl =  $i < 1 ? $addressUrl . $lineNoSpaces : $addressUrl . "%2C+" . $lineNoSpaces;
            } else {
                $i++;
                continue;
            }
            $i++;
        }
        if ($address['postcode'] != ""){
            $combinedAddress = $combinedAddress . ", " . $address['postcode'];
            $lineNoSpaces = str_replace(" ", "+", $address['postcode']);
            $addressUrl =  $addressUrl . "%2C+" . $lineNoSpaces;
        }
        if ($address['country'] != ""){
            $combinedAddress = $combinedAddress . ", " . $address['country'];
            $lineNoSpaces = str_replace(" ", "+", $address['country']);
            $addressUrl =  $addressUrl . "%2C+" . $lineNoSpaces;
        }

        return ["address" => $combinedAddress, "url" => $addressUrl];
    }

    private function handleError($errorDetails){
        Log::error($errorDetails);
        $processedMessage = json_decode($errorDetails['message'], true);
        $status = $processedMessage['error']['status'];
        $message = $processedMessage['error']['message'];

        $processedError = ["error" => "We have an encountered an error, error code: $status details: $message"];

        return $processedError;
    }

    private function formatData($apiData){
        $contactList = [];

        if(isset($apiData["error"])){
            $contactList = $this->handleError($apiData['error']);
        } elseif(isset($apiData["pagination"]) && isset($apiData["data"]))  {
            $pageData = $apiData["pagination"];
            $addressBook = $apiData["data"];                   
        } else {
            $contactList = ["error" => "No Data Returned"];
        }

        if(isset($addressBook)) {
            foreach($addressBook as $key => $contact) {
                $showName = $contact['privacy']['name_visible'];
                $showAddress = $contact['privacy']['address_visible'];
                $showMobile = $contact['privacy']['mobile_visible'];
                $showEmail = $contact['privacy']['email_visible'];
                $showTelephone = $contact['privacy']['telephone_visible'];

                if($showName != true){
                    continue;
                } else {
                    $thumbnail = "";
                    $initials = substr($contact['first_name'],0,1) . substr($contact['last_name'],0,1);

                    //handle privacy matters here
                    if($contact['image'] != NULL) $thumbnail = $contact['image']['thumbnail'];

                    $contactDetails = [
                        "id" => $contact['id'],
                        "name" => $contact['first_name'] . " " . $contact['last_name'],
                        "initials" => $initials,
                        "email" => $showEmail ? $contact['email'] : "", 
                        "telephone" => $showTelephone ? $contact['telephone'] : "",
                        "mobile" => $showMobile ? $contact['mobile'] : "",
                        "address" => $showAddress && isset($contact['address']) ? $this->formatAddress($contact['address']) : "",
                        "image" => $thumbnail,
                    ];

                    array_push($contactList, $contactDetails);
                }

            
            }
        }


        $processedData = ['data' => $contactList, 'pagination' => $pageData];


        return $processedData;
    }

    public function search(Request $request){
        Log::info($request->all());
        $q = $request->q;
        $page = $request->p;
        $data = $this->apiService->getContacts($page, $q);
        $processedData = $this->formatData($data);

        return ['apiResponse' => $processedData];
    }

    public function getData($page = NULL, $q = NULL)
    {
        $data = $this->apiService->getContacts($page, $q);
        
        $processedData = $this->formatData($data);

        return view('index', ['apiResponse' => $processedData]);
    }
}
?>