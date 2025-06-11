<?php

namespace App\Modules\Employee\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ApiService
{
    protected $client;
    protected $kalikaDomainName;

    public function __construct()
    {
        $this->client = new Client();
        $this->kalikaDomainName = 'http://127.0.0.1:9000/';
    }

    /**
     * send all Organization hrms to erp
     *
     */
    public function sendOrganizationData($data, $hostName)
    {
        $domainName =  $this->checkHostName($hostName);
        Log::info('info',['error'=>$domainName]);
        $url = $domainName."api/v1/organization";
        try {
            $response = $this->client->post($url, [
                'form_params' => $data,
                'auth' => [
                    'bin',
                    'bin'
                ],
                'headers' => [
                    'Http-Bidhee-Auth' => 'hrms-info-sys',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Error sending data to API: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * send all Organization hrms to erp
     *
     */
    public function sendAllOrganizationData($data, $hostName)
    {
        // Log::info('info',['data'=>$hostName]);

        // dd($data, $hostName);
        $domainName =  $this->checkHostName($hostName);
        $url = $domainName."api/v1/organization/all-data";
        try {
            $response = $this->client->post($url, [
                'form_params' => $data,
                'auth' => [
                    'bin',
                    'bin'
                ],
                'headers' => [
                    'Http-Bidhee-Auth' => 'hrms-info-sys',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Error sending data to API: ' . $e->getMessage());
            return null;
        }
    }


    /**
     * send all employee hrms to erp
     *
     */
    public function sendEmployeeData($data, $hostName)
    {
        $domainName =  $this->checkHostName($hostName);
        $url = $domainName."api/v1/employee";
        try {
            $response = $this->client->post($url, [
                'form_params' => $data,
                'auth' => [
                    'bin',
                    'bin'
                ],
                'headers' => [
                    'Http-Bidhee-Auth' => 'hrms-info-sys',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Error sending data to API: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * send all employee hrms to erp
     *
     */
    public function sendAllEmployeeData($data, $hostName)
    {
        // Log::info('info',['data'=>$hostName]);
        $domainName =  $this->checkHostName($hostName);
        $url = $domainName."api/v1/employee/all-data";
        try {
            $response = $this->client->post($url, [
                'form_params' => $data,
                'auth' => [
                    'bin',
                    'bin'
                ],
                'headers' => [
                    'Http-Bidhee-Auth' => 'hrms-info-sys',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Error sending data to API: ' . $e->getMessage());
            return null;
        }
    }

    public function checkHostName($hostName){
        return rtrim($hostName, '/') . '/';

    }

}
