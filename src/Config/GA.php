<?php

namespace ReactMoreTech\Analytics\Config;

use CodeIgniter\Config\BaseConfig;

class GA extends BaseConfig
{
    public string $view_id = '215165900';

    public string $service_account_credentials_json = __DIR__ . '/../../credentials/service-account-credentials.json';
}
