<?php

namespace ReactMoreTech\Analytics\Services;

use Exception;
use ReactMoreTech\Analytics\Support\Collection;
use ReactMoreTech\Analytics\Support\Traits\Macroable;
use ReactMoreTech\Analytics\Libraries\PeriodTime;
use ReactMoreTech\Analytics\Libraries\ResponseFormatter;

class Fetch extends MakeQuery
{

    use Macroable;


    public function __construct($credential, $view_id)
    {
        $this->service = $credential;
        $this->view_id = $view_id;

        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    public function fetchTotalVisitorsAndPageViews(PeriodTime $period)
    {
        try {
            $response = $this->performQuery(
                $period,
                'ga:users,ga:pageviews',
                ['dimensions' => 'ga:date'],
            );

            return collect($response['rows'] ?? [])->map(fn (array $dateRow) => [
                'date' => date("M-d", strtotime($dateRow[0])),
                'visitors' => (int) $dateRow[1],
                'pageViews' => (int) $dateRow[2],
            ])->toArray();
        } catch (Exception $e) {
            return ResponseFormatter::formatResponse([
                'error' => $e->getMessage()
            ], 400, 'failed');
        }
    }

    public function fetchVisitorsAndPageViews(PeriodTime $period)
    {
        try {
            $response = $this->performQuery(
                $period,
                'ga:users,ga:pageviews',
                ['dimensions' => 'ga:date,ga:pageTitle'],
            );

            return collect($response['rows'] ?? [])->map(fn (array $dateRow) => [
                'date' => date("y-m-d", strtotime($dateRow[0])),
                'pageTitle' => $dateRow[1],
                'visitors' => (int) $dateRow[2],
                'pageViews' => (int) $dateRow[3],
            ])->toArray();
        } catch (Exception $e) {
            return ResponseFormatter::formatResponse([
                'error' => $e->getMessage()
            ], 400, 'failed');
        }
    }

    public function fetchMostVisitedPages(PeriodTime $period, int $maxResults = 20)
    {
        try {
            $response = $this->performQuery(
                $period,
                'ga:pageviews',
                [
                    'dimensions' => 'ga:pagePath,ga:pageTitle',
                    'sort' => '-ga:pageviews',
                    'max-results' => $maxResults,
                ],
            );

            return collect($response['rows'] ?? [])->map(fn (array $pageRow) => [
                'url' => $pageRow[0],
                'pageTitle' => $pageRow[1],
                'pageViews' => (int) $pageRow[2],
            ])->toArray();
        } catch (Exception $e) {
            return ResponseFormatter::formatResponse([
                'error' => $e->getMessage()
            ], 400, 'failed');
        }
    }

    public function fetchTopReferrers(PeriodTime $period, int $maxResults = 20)
    {
        try {
            $response = $this->performQuery(
                $period,
                'ga:pageviews',
                [
                    'dimensions' => 'ga:fullReferrer',
                    'sort' => '-ga:pageviews',
                    'max-results' => $maxResults,
                ],
            );

            return collect($response['rows'] ?? [])->map(fn (array $pageRow) => [
                'url' => $pageRow[0],
                'pageViews' => (int) $pageRow[1],
            ])->toArray();
        } catch (Exception $e) {
            return ResponseFormatter::formatResponse([
                'error' => $e->getMessage()
            ], 400, 'failed');
        }
    }

    public function fetchUserTypes(PeriodTime $period)
    {
        try {
            $response = $this->performQuery(
                $period,
                'ga:sessions',
                [
                    'dimensions' => 'ga:userType',
                ],
            );

            return collect($response->rows ?? [])->map(fn (array $userRow) => [
                'type' => $userRow[0],
                'sessions' => (int) $userRow[1],
            ])->toArray();
        } catch (Exception $e) {
            return ResponseFormatter::formatResponse([
                'error' => $e->getMessage()
            ], 400, 'failed');
        }
    }

    public function fetchTopBrowsers(PeriodTime $period, int $maxResults = 10)
    {
        $response = $this->performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:browser',
                'sort' => '-ga:sessions',
            ],
        );

        $topBrowsers = collect($response['rows'] ?? [])->map(fn (array $browserRow) => [
            'browser' => $browserRow[0],
            'sessions' => (int) $browserRow[1],
        ]);

        if ($topBrowsers->count() <= $maxResults) {
            return $topBrowsers->toArray();
        }

        return $this->summarizeTopBrowsers($topBrowsers, $maxResults)->toArray();
    }

    protected function summarizeTopBrowsers(Collection $topBrowsers, int $maxResults)
    {
        return $topBrowsers
            ->take($maxResults - 1)
            ->push([
                'browser' => 'Others',
                'sessions' => $topBrowsers->splice($maxResults - 1)->sum('sessions'),
            ]);
    }

    public function performQuery(PeriodTime $period, string $metrics, array $others = [])
    {
        return $this->Query(
            $this->view_id,
            $period->startDate,
            $period->endDate,
            $metrics,
            $others,
        );
    }
}
