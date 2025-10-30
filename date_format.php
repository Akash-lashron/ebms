<?php

function getAllDatesBetweenTwoDates($strDateFrom,$strDateTo)
{
	$aryRange=array();

	$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	if ($iDateTo>=$iDateFrom)
	{
		array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
		while ($iDateFrom<$iDateTo)
		{
			$iDateFrom+=86400; // add 24 hours
			array_push($aryRange,date('Y-m-d',$iDateFrom));
		}
	}
	return $aryRange;
}
			
function dt_enter($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yyyy=$dt[2];
	
	return $yyyy .'-'. $dd .'-'.$mm;
}
function dt_format($ddmmyyyy)
{
	$dt=explode('/',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	
	return $yy .'-'. $mm .'-'.$dd;
}

function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'/'. $mm .'/'.$yy;
}

function dt_display_full_email($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	
	return $day.'-'.$month.'-'.$year;
}



function dt_display_full($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$time=substr($ddmmyyyyhis,11,8);
	
	return $day.'-'.$month.'-'.$year.' - '.$time;
}
function time_display_full_only($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$time=substr($ddmmyyyyhis,11,2);
	
	return $time;
}
function minutes_display($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$time=substr($ddmmyyyyhis,14,2);
	
	return $time;
}
function hoursminutes_display($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$time=substr($ddmmyyyyhis,11,2).':'.substr($ddmmyyyyhis,14,2);
	
	return $time;
}

function dt_display_full_only($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$time=substr($ddmmyyyyhis,11,8);
	
	return $day.'/'.$month.'/'.$year;
}
function dt_display_date_hi($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$h=substr($ddmmyyyyhis,11,2);
	$i=substr($ddmmyyyyhis,14,2);
	
	return $day.'-'.$month.'-'.$year.' (' . $h . $i . ' HOURS)';
}

function dt_display_hoursminutes_only($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$h=substr($ddmmyyyyhis,11,2);
	$i=substr($ddmmyyyyhis,14,2);
	
	return $h.':'.$i;
}

function dt_display_hoursonly($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$h=substr($ddmmyyyyhis,11,2);
	$i=substr($ddmmyyyyhis,14,2);
	
	return $h;
}
function dt_display_unformat_x($ddmmyyyy)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	
	//return $year.'-'.$month.'-'.$day. ' ' . $h . ':' . $i . ':' . $s;
	return $year.'-'.$month.'-'.$day;
}

function dt_display_unformat($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$h=substr($ddmmyyyyhis,11,2);
	$i=substr($ddmmyyyyhis,14,2);
	$s=substr($ddmmyyyyhis,17,3);
	
	//return $year.'-'.$month.'-'.$day. ' ' . $h . ':' . $i . ':' . $s;
	return $year.'-'.$month.'-'.$day;
}
function dt_timedisplay_hoursminutesseconds($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$h=substr($ddmmyyyyhis,11,2);
	$i=substr($ddmmyyyyhis,14,2);
	$s=substr($ddmmyyyyhis,17,3);
	
	return  $h . ':' . $i . ':' . $s;
}
function dt_display_databaseformat($ddmmyyyy)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$h=substr($ddmmyyyyhis,11,2);
	$i=substr($ddmmyyyyhis,14,2);
	$s=substr($ddmmyyyyhis,17,3);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	
	return $yyyy.'-'.$mm.'-'.$dd;
}
	
/*function dt_display_hms($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	$h=substr($ddmmyyyyhis,11,2);
	$i=substr($ddmmyyyyhis,14,2);
	$s=substr($ddmmyyyyhis,17,2)
	
	/*$dt=explode('/',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	
	return $yy .'-'. $mm .'-'.$dd;
	
	return $year.'-'.$month.'-'.$day;
}*/
function dt_display_date($ddmmyyyyhis)
{
	$day=substr($ddmmyyyyhis,8,2);
	$month=substr($ddmmyyyyhis,5,2);
	$year=substr($ddmmyyyyhis,0,4);
	
	return $day.'-'.$month.'-'.$year;
}


function dt_display_hi($ddmmyyyyhis)
{
	$h=substr($ddmmyyyyhis,11,2);
	$i=substr($ddmmyyyyhis,14,2);
	
	return $h . $i;
}


function dt_format_ms_access($ddmmyyyy)
{
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return  $mm.'/'.$dd.'/'.$yy;
}

function dt_format_ms_access_query($ddmmyyyy)
{
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	
	if($mm<10)
	{	
		$m=substr($mm,1,1);
		return  $m.'/'.$dd.'/'.$yy;
	}
	else
	{
		return  $mm.'/'.$dd.'/'.$yy;
	}
}


function dt_display_ms_access($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	$yy=$dt[0];
	$mm=$dt[1];
	$dd=substr($dt[2],0,2);
	return  $dd.'/'.$mm.'/'.$yy;
}

function dt_display_ms_access_report($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	$yy=$dt[0];
	$mm=$dt[1];
	$dd=substr($dt[2],0,2);
	return  $dd.'-'.$mm.'-'.$yy;
}
function twentyfourtotwevelhours($time)
{
	//return date('g:i:sa',strtotime($time));
	//return date('g:i',strtotime($time));
	 // return date('r',strtotime($time));
	 return date('g',strtotime($time));
}


?>