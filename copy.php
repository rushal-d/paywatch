<?php
$calculate_total_work_hour = "UPDATE fetch_attendances
set total_work_hour = COALESCE((COALESCE(CAST((TIMESTAMPDIFF(MINUTE,punchin_datetime, punchout_datetime) / 60) as decimal(10,2)),0) - COALESCE(CAST((TIMESTAMPDIFF(MINUTE,personalout_datetime, personalin_datetime) / 60) as decimal(10,2)),0)),0)
WHERE
date(punchin_datetime) >= '2020-05-14' AND punchin_datetime <= '2020-06-14' AND branch_id = 3";
