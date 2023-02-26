<?php

namespace ReactMoreTech\Analytics;

use CodeIgniter\Config\BaseConfig;
use Google\Client;
use Google\Service\Analytics as GA;
use ReactMoreTech\Analytics\Exceptions\GAException;

class Analytics
{
    private $google_analytics, $config;

    public function __construct(BaseConfig $config)
    {
        $this->config = $config;
        $this->guardAgainstInvalidConfiguration();
        $this->google_analytics = $this->createAuthenticatedGoogleClient();
    }

    public function createAuthenticatedGoogleClient()
    {
        $client = new Client();
        $client->setScopes([
            GA::ANALYTICS_READONLY,
        ]);

        $client->setAuthConfig($this->config->service_account_credentials_json);
        $analytics = new GA($client);

        return $analytics;
    }

    public function Fetching()
    {
        return new \ReactMoreTech\Analytics\Services\Fetch($this->createAuthenticatedGoogleClient(), $this->config->view_id);
    }

    protected function guardAgainstInvalidConfiguration(): void
    {
        if (empty($this->config->view_id) || empty($this->config->service_account_credentials_json)) {
            throw GAException::forInvalidConfiguration();
        }
    }
}
