-- Populate `date` column
create table tmp_ints ( i tinyint ); 
insert into tmp_ints values (0),(1),(2),(3),(4),(5),(6),(7),(8),(9); 
insert into sample (date) select date('2010-01-01') + interval a.i*10000 + b.i*1000 + c.i*100 + d.i*10 + e.i day from tmp_ints a join tmp_ints b join tmp_ints c join tmp_ints d join tmp_ints e where (a.i*10000 + b.i*1000 + c.i*100 + d.i*10 + e.i) <= (select datediff('2018-12-31','2010-01-01')) order by 1;
drop table tmp_ints;

-- update month, day & weekday
update sample set month = MONTH(`date`), day = DAY(`date`), weekday = WEEKDAY(`date`);