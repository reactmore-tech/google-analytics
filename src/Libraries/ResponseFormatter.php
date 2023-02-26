<?php

namespace ReactMoreTech\Analytics\Libraries;


/**
 * Class ResponseFormatter
 * @package Reactmore\GoogleAnalyticApi
 */
class ResponseFormatter
{
    public static function formatResponse($data, $code = 200, $status = 'success')
    {
        return [
            'status' => $status,
            'code' => $code,
            'data' => $data
        ];
    }
}
