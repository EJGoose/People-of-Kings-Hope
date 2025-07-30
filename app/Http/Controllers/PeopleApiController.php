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

    //make addresses human readable
    private function formatAddress($address){
        $combinedAddress = "";
        $addressUrl ="http://maps.apple.com/?q=";
        $i = 0;

        foreach($address as $line){
            if ($i == 5) break; //don't process past line 5
            if($line != ""){
                //create human readable addresss and apple maps url
                $combinedAddress = $i < 1 ? $line : $combinedAddress . ", " . $line;
                $lineNoSpaces = str_replace(" ", "+", $line);
                $addressUrl =  $i < 1 ? $addressUrl . $lineNoSpaces : $addressUrl . "%2C+" . $lineNoSpaces;
                $i++;
            } else {
                $i++;
                continue;
            }
        }
        //if the postcode exsists add that before the country
        if ($address['postcode'] != ""){
            $combinedAddress = $combinedAddress . ", " . $address['postcode'];
            $lineNoSpaces = str_replace(" ", "+", $address['postcode']);
            $addressUrl =  $addressUrl . "%2C+" . $lineNoSpaces;
        }
        //if the country exsists add that last
        if ($address['country'] != ""){
            $combinedAddress = $combinedAddress . ", " . $address['country'];
            $lineNoSpaces = str_replace(" ", "+", $address['country']);
            $addressUrl =  $addressUrl . "%2C+" . $lineNoSpaces;
        }

        return ["address" => $combinedAddress, "url" => $addressUrl];
    }

    private function handleError($errorDetails){
        Log::error($errorDetails);
        //retrieve error details
        $processedMessage = json_decode($errorDetails['message'], true);
        $status = $processedMessage['error']['status'];
        $message = $processedMessage['error']['message'];

        //pass error details back to the front end
        $processedError = ["error" => "We have an encountered an error, error code: $status details: $message"];

        return $processedError;
    }

    private function formatData($apiData){
        $contactList = [];

        if(isset($apiData["error"])){
            //if there has been an error, handle it
            $contactList = $this->handleError($apiData['error']);
        } elseif(isset($apiData["pagination"]) && isset($apiData["data"]))  {
            //if the data has been returned correctly break it down into its component parts
            $pageData = $apiData["pagination"];
            $addressBook = $apiData["data"];                   
        } else {
            //if the data hasn't raised an error but doesn't contain what we need inform the user
            $contactList = ["error" => "No data returned for this request"];
        }

        if(isset($addressBook)) {
            foreach($addressBook as $key => $contact) {
                //check the privacy status for each contact
                $showName = $contact['privacy']['name_visible'];
                $showAddress = $contact['privacy']['address_visible'];
                $showMobile = $contact['privacy']['mobile_visible'];
                $showEmail = $contact['privacy']['email_visible'];
                $showTelephone = $contact['privacy']['telephone_visible'];

                if($showName != true){
                    //ignore private contacts
                    continue;
                } else {
                    //thumbnail and initials template
                    $thumbnail = "";
                    $initials = substr($contact['first_name'],0,1) . substr($contact['last_name'],0,1);

                    //if they have an image add that
                    if($contact['image'] != NULL) $thumbnail = $contact['image']['thumbnail'];

                    $contactDetails = [
                        //fill out contact details if we have permission to do so
                        "id" => $contact['id'],
                        "name" => $contact['first_name'] . " " . $contact['last_name'],
                        "initials" => $initials,
                        "email" => $showEmail ? $contact['email'] : "", 
                        "telephone" => $showTelephone ? $contact['telephone'] : "",
                        "mobile" => $showMobile ? $contact['mobile'] : "",
                        //if we are allowed to show the address and it is set format it.
                        "address" => $showAddress && isset($contact['address']) ? $this->formatAddress($contact['address']) : "",
                        "image" => $thumbnail,
                    ];
                    //add each contact to the array
                    array_push($contactList, $contactDetails);
                }

            
            }
        }

        //prepare the contact data and page data to send to Alpine
        $processedData = ['data' => $contactList, 'pagination' => $pageData];


        return $processedData;
    }

    //handle a search or a new page request
    public function search(Request $request){
        //get specifics of new data request
        $q = htmlspecialchars($request->q);
        $page = $request->p;
        //pass the request to the get contacts function
        $data = $this->apiService->getContacts($page, $q);
        //return the processed data to alpine
        $processedData = $this->formatData($data);

        return ['apiResponse' => $processedData];
    }

    //initial get data
    public function getData($page = NULL, $q = NULL)
    { //request data and then process it
        $data = $this->apiService->getContacts($page, $q);
        
        $processedData = $this->formatData($data);

        //pass data back to the view
        return view('index', ['apiResponse' => $processedData]);
    }
}
?>