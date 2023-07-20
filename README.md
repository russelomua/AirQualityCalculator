# AirQualityCalculator v2

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/ca26d473f53d432a8cb1bd4e82dfe236)](https://app.codacy.com/manual/russelomua/AirQualityCalculator?utm_source=github.com&utm_medium=referral&utm_content=russelomua/AirQualityCalculator&utm_campaign=Badge_Grade_Dashboard)

Air Quality Index Calculator - is a tool for calculating Air Quality Index for PM2.5, PM19, CO, SO2, NO2, O3 (ozone)


## Installation

```bash
composer require utg/air-quality-calculator
```

## Upgrading from v1
Now require PHP 8.2 or higher
Fully incompatible with v1, see Usage section for more details

## Usage

```php
// Must contains min 2 values of measurements
// good to use 12 or 24 values for PM25 and PM10
$concentrations = [13, 16, 10, 21, 74, 64, 53, 82, 90, 75, 80, 50];

$nowCast = new \AirQuality\NowCast($concentrations);

// NowCast value only
echo $nowCast->getValue();

$pollutant = new \AirQuality\Pollutants\PM25Pollutant();
$calculator = $nowCast->createCalculator($pollutant);

// Full quality object
$quality = $calculator->getQuality($concentrations, $pollutant);
// Index only quality object
$index = $calculator->getIndex();

// NowCast
echo $quality->nowCast;

// AQI
echo $quality->index->value;

// AQI category enum
// has methods for getting category name and color
echo $quality->index->category?->value;
echo $quality->index->category?->getName();
echo $quality->index->category?->getHexColor();

// Or all together
var_dump($quality->jsonSerialize());
```

## References
*  [Reference calculator](https://www3.epa.gov/airnow/aqicalctest/nowcast.htm)
*  [Technical Assistance Document for the Reporting of Daily Air Quality](https://www.airnow.gov/sites/default/files/2020-05/aqi-technical-assistance-document-sept2018.pdf)
*  [Communicating Daily and Real-time Air Quality with the Air Quality Index and the NowCast](http://airnowtech.org/Resources/NACAANowCastPresentation.pdf)
*  [NowCast](https://cran.r-project.org/web/packages/PWFSLSmoke/vignettes/NowCast.html)
*  [Transitioning to a new NowCast Method](https://www3.epa.gov/airnow/ani/pm25_aqi_reporting_nowcast_overview.pdf)
