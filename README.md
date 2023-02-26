# Google Analytics Library Codeigniter 4 

## Installation 
```php
composer require reactmore-tech/google-analytics 
```

Configuration .env : 
```
VIEW_ID=215165900
SERVICE_CREDENTIALS_JSON= 'google_analytic_services.json'
```

## Usage :

```php

service('GoogleAnalytics');
$GA = service('GoogleAnalytics');

$Analytics = new Analytics();

echo '<pre>';
$GA->Fetching()->fetchTopBrowsers(PeriodTime::days(10)) //Result Array
echo '</pre>';

// Method 
$Analytics = new Analytics();
// Fetch Users and New Users
$GA->Fetching()->fetchUserTypes(Period::days(7));
// Get Data Top Browser used visitor
$GA->Fetching()->fetchTopBrowsers(Period::days(7));
// Get Data Refferer Page
$GA->Fetching()->fetchTopReferrers(Period::days(7));
// Populer Pages
$GA->Fetching()->fetchMostVisitedPages(Period::days(7));
// Get Visitor and Pageviews
$GA->Fetching()->fetchTotalVisitorsAndPageViews(Period::days(7));
```

Example Output Array : 
```array
array(2) {
  [0]=>
  array(2) {
    ["type"]=>
    string(11) "New Visitor"
    ["sessions"]=>
    int(2581)
  }
  [1]=>
  array(2) {
    ["type"]=>
    string(17) "Returning Visitor"
    ["sessions"]=>
    int(1215)
  }
}
```

```php
// Custom Query
$Analytics->Fetching()->performQuery($period, $metrix, $other = array());
// Example
$Analytics->Fetching()->performQuery(Period::days(7), 'ga:sessions', ['dimensions' => 'ga:country', 'sort' => '-ga:sessions'])->getRows();
```

Example Output Raw Array : 
```
array(30) {
  [0]=>
  array(2) {
    [0]=>
    string(9) "Indonesia"
    [1]=>
    string(4) "3534"
  }
  [1]=>
  array(2) {
    [0]=>
    string(11) "Afghanistan"
    [1]=>
    string(2) "96"
  }
  [2]=>
  array(2) {
    [0]=>
    string(13) "United States"
    [1]=>
    string(2) "88"
  } 
}
```

Explore Query on this App [ga-dev-tools](https://ga-dev-tools.web.app/query-explorer/)
