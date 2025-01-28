<?php

namespace App\Libraries;

class WHO_ICD
{
    private $client;
    private $token;
    private $clientId;
    private $clientSecret;
    private $baseUrl = 'https://id.who.int/icd/api/';

    public function __construct()
    {
        $this->client = \Config\Services::curlrequest();
        $this->clientId = 'your_client_id';
        $this->clientSecret = 'your_client_secret';
        $this->token = $this->getToken();
    }

    private function getToken()
    {
        try {
            $response = $this->client->post($this->baseUrl . 'oauth2/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['access_token'] ?? null;
        } catch (\Exception $e) {
            log_message('error', 'WHO ICD API Token Error: ' . $e->getMessage());
            return null;
        }
    }

    public function searchICD10($query)
    {
        if (!$this->token) {
            return ['error' => 'Unable to authenticate with WHO ICD API'];
        }

        try {
            $response = $this->client->get($this->baseUrl . 'search', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en'
                ],
                'query' => [
                    'q' => $query,
                    'releaseId' => 'ICD10',
                    'flatResults' => 'true'
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            log_message('error', 'WHO ICD API Search Error: ' . $e->getMessage());
            return ['error' => 'Error searching ICD-10 codes'];
        }
    }

    public function getICD10Details($icdCode)
    {
        if (!$this->token) {
            return ['error' => 'Unable to authenticate with WHO ICD API'];
        }

        try {
            $response = $this->client->get($this->baseUrl . 'entity/' . $icdCode, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en'
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            log_message('error', 'WHO ICD API Details Error: ' . $e->getMessage());
            return ['error' => 'Error fetching ICD-10 details'];
        }
    }
}
