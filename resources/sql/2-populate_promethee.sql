-- fire_starts & fire_surface
update sample s set s.fire_starts = (select count(surface) from promethee where cast(alerte as date) = s.date), s.fire_surface = (select sum(surface) from promethee where cast(alerte as date) = s.date)

-- fire
update sample set fire = 0;
update sample set fire = 1 where fire_surface > 0;