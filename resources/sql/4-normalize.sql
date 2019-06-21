-- Create table for normalized sample
DROP TABLE IF EXISTS `sample_norm`;
CREATE TABLE `sample_norm` (
  `month` decimal(6,5),
  `day` decimal(6,5),
  `weekday` decimal(6,5),
  `t_min_n` decimal(6,5),
  `t_max_n` decimal(6,5),
  `t_min_7d_n` decimal(6,5),
  `t_max_7d_n` decimal(6,5),
  `wind_min_n` decimal(6,5),
  `wind_max_n` decimal(6,5),
  `wind_min_7d_n` decimal(6,5),
  `wind_max_7d_n` decimal(6,5),
  `gust_wind_min_n` decimal(6,5),
  `gust_wind_max_n` decimal(6,5),
  `hu_min_n` decimal(3,2),
  `hu_max_n` decimal(3,2),
  `rain_n` decimal(6,5),
  `rain_14d_n` decimal(6,5),
  `rain_1m_n` decimal(6,5),
  `rain_6m_n` decimal(6,5),
  `fire` int(1)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- MinMax normalization
SET @min_t = (select min(t_min) from sample);
SET @max_t = (select max(t_max) from sample);
SET @min_wind = (select min(wind_min) from sample);
SET @max_wind = (select max(gust_wind_max) from sample);
SET @max_rain = (select max(rain) from sample);
SET @max_rain_14d = (select max(rain_14d) from sample);
SET @max_rain_1m = (select max(rain_1m) from sample);
SET @max_rain_6m = (select max(rain_6m) from sample);
INSERT INTO sample_norm (
    SELECT cast((month-1)/11 as decimal(6,5)) as month_n
        , cast((day-1)/30 as decimal(6,5)) as day_n
        , cast(weekday/6 as decimal(6,5)) as weekday_n
        , cast((t_min - @min_t) / (@max_t - @min_t) as decimal(6,5)) as t_min_n
        , cast((t_max - @min_t) / (@max_t - @min_t) as decimal(6,5)) as t_max_n
        , cast((t_min_7d - @min_t) / (@max_t - @min_t) as decimal(6,5)) as t_min_7d_n
        , cast((t_max_7d - @min_t) / (@max_t - @min_t) as decimal(6,5)) as t_max_7d_n
        , cast((wind_min - @min_wind) / (@max_wind - @min_wind) as decimal(6,5)) as wind_min_n
        , cast((wind_max - @min_wind) / (@max_wind - @min_wind) as decimal(6,5)) as wind_max_n
        , cast((wind_min_7d - @min_wind) / (@max_wind - @min_wind) as decimal(6,5)) as wind_min_7d_n
        , cast((wind_max_7d - @min_wind) / (@max_wind - @min_wind) as decimal(6,5)) as wind_max_7d_n
        , cast((gust_wind_min - @min_wind) / (@max_wind - @min_wind) as decimal(6,5)) as gust_wind_min_n
        , cast((gust_wind_max - @min_wind) / (@max_wind - @min_wind) as decimal(6,5)) as gust_wind_max_n
        , hu_min/100 as hu_min_n
        , hu_max/100 as hu_max_n
        , cast(rain/@max_rain as decimal(6,5)) as rain_n
        , cast(rain_14d/@max_rain_14d as decimal(6,5)) as rain_14d_n
        , cast(rain_1m/@max_rain_1m as decimal(6,5)) as rain_1m_n
        , cast(rain_6m/@max_rain_6m as decimal(6,5)) as rain_6m_n
        , fire
    FROM `sample` 
    WHERE rain_6m is not null
);

-- Then export the table as CSV...