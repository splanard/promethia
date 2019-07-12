-- t_min
update sample s inner join (select cast(`date` as date) as dt, min(cast(tn12 as decimal(5,2))) as tmin from meteo_france_synop where tn12 != 'mq' group by cast(`date` as date)) x set s.t_min = x.tmin where s.date = x.dt;
update sample s inner join (select cast(`date` as date) as dt, min(cast(t as decimal(5,2))) as tmin from meteo_france_synop where t != 'mq' group by cast(`date` as date)) x set s.t_min = x.tmin where s.t_min is null and s.date = x.dt;

-- t_max
update sample s inner join (select cast(`date` as date) as dt, max(cast(tx12 as decimal(5,2))) as tmax from meteo_france_synop where tx12 != 'mq' group by cast(`date` as date)) x set s.t_max = x.tmax where s.date = x.dt;
update sample s inner join (select cast(`date` as date) as dt, max(cast(t as decimal(5,2))) as tmax from meteo_france_synop where t != 'mq' group by cast(`date` as date)) x set s.t_max = x.tmax where s.t_max is null and s.date = x.dt;

-- t_min_7d
update sample u inner join (select s.date, (select cast(avg(t_min) as decimal(5,2)) from sample where date between date_sub(s.date, interval 7 day) and date_sub(s.date, interval 1 day) ) as `avg` from sample s where exists (select 1 from sample e where e.date = date_sub(s.date, interval 7 day))) x on x.date = u.date set u.t_min_7d = x.avg;

-- t_max_7d
update sample u inner join (select s.date, (select cast(avg(t_max) as decimal(5,2)) from sample where date between date_sub(s.date, interval 7 day) and date_sub(s.date, interval 1 day) ) as `avg` from sample s where exists (select 1 from sample e where e.date = date_sub(s.date, interval 7 day))) x on x.date = u.date set u.t_max_7d = x.avg;

-- wind_min & wind_max
update sample s inner join (select cast(`date` as date) as dt, min(cast(ff as decimal(3,1))) as wmin, max(cast(ff as decimal(3,1))) as wmax from meteo_france_synop where ff != 'mq' group by cast(`date` as date)) x set s.wind_min = x.wmin, s.wind_max = x.wmax where s.date = x.dt;

-- wind_min_7d
update sample u inner join (select s.date, (select cast(avg(wind_min) as decimal(3,1)) from sample where date between date_sub(s.date, interval 7 day) and date_sub(s.date, interval 1 day) ) as `avg` from sample s where exists (select 1 from sample e where e.date = date_sub(s.date, interval 7 day))) x on x.date = u.date set u.wind_min_7d = x.avg;

-- wind_max_7d
update sample u inner join (select s.date, (select cast(avg(wind_max) as decimal(3,1)) from sample where date between date_sub(s.date, interval 7 day) and date_sub(s.date, interval 1 day) ) as `avg` from sample s where exists (select 1 from sample e where e.date = date_sub(s.date, interval 7 day))) x on x.date = u.date set u.wind_max_7d = x.avg;

-- gust_wind_min & gust_wind_max
update sample s inner join (select cast(`date` as date) as dt, min(cast(rafper as decimal(3,1))) as rafmin, max(cast(rafper as decimal(3,1))) as rafmax from meteo_france_synop group by cast(`date` as date)) x set s.gust_wind_min = x.rafmin, s.gust_wind_max = x.rafmax where s.date = x.dt;

-- hu_min & hu_max
update sample s inner join (select cast(`date` as date) as dt, min(cast(u as int)) as umin, max(cast(u as int)) as umax from meteo_france_synop group by cast(`date` as date)) x set s.hu_min = x.umin, s.hu_max = x.umax where s.date = x.dt;

-- rain
update sample s inner join ( select date(date_sub(cast(`date` as datetime), interval 1 hour)) as dt, sum(cast(rr3 as decimal(4,1))) as r from meteo_france_synop where rr3 != 'mq' group by date(date_sub(cast(`date` as datetime), interval 1 hour)) ) x on x.dt = s.date set s.rain = x.r

-- rain_14d
update sample u inner join (select s.date, (select sum(rain) from sample where date between date_sub(s.date, interval 14 day) and date_sub(s.date, interval 1 day) ) as r14 from sample s where exists (select 1 from sample e where e.date = date_sub(s.date, interval 14 day))) x on x.date = u.date set u.rain_14d = x.r14;

-- rain_1m
update sample u inner join (select s.date, (select sum(rain) from sample where date between date_sub(s.date, interval 1 month) and date_sub(s.date, interval 1 day) ) as r1m from sample s where exists (select 1 from sample e where e.date = date_sub(s.date, interval 1 month))) x on x.date = u.date set u.rain_1m = x.r1m;

-- rain_6m
update sample u inner join (select s.date, (select sum(rain) from sample where date between date_sub(s.date, interval 6 month) and date_sub(s.date, interval 1 day) ) as r6m from sample s where exists (select 1 from sample e where e.date = date_sub(s.date, interval 6 month))) x on x.date = u.date set u.rain_6m = x.r6m;