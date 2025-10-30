<?php

$nwords = array(  "", "One", "Two", "Three", "Four", "Five", "Six", 
                    "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
                    "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
                   "Nineteen", "Twenty", 30 => "Thirty", 40 => "Forty",
                     50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eighty",
                     90 => "Ninety" );

function number_to_words($x)
{
	global $nwords;
	//return $x;
	if(!is_numeric($x))
	{
		$w = '#';
	}
	/*else if(fmod($x, 1) != 0)
	{
		$w = '#';
		
	}*/
	else
	{
		
		if($x < 0)
		{
			
			$w = 'minus ';
			$x = -$x;
		}
		else
		{
			
			$w = '';
		}
		
		if($x < 21)
		{
			$w .= $nwords[$x];
		}
		else if($x < 100)
		{
			$w .= $nwords[10 * floor($x/10)];
			$r = fmod($x, 10);
			if($r > 0)
			{
				$w .= ' '. $nwords[$r];
			}
		} 
		else if($x < 1000)
		{
			$w .= $nwords[floor($x/100)] .' Hundred';
			$r = fmod($x, 100);
			if($r > 0)
			{
				$w .= ' '. number_to_words($r);
			}
		} 
		else if($x < 100000)
		{
			
			$w .= number_to_words(floor($x/1000)) .' Thousands';
			$r = fmod($x, 1000);
			// echo $r;
			if($r > 0)
			{
				$w .= ' ';
				if($r < 100)
				{
					$w .= ' ';
				}
			$w .= number_to_words($r);
			}
		}
		else if($x < 10000000)
		{
			
			$w .= number_to_words(floor($x/100000)) .' Lakhs';
			$r = fmod($x, 100000);
			if($r > 0)
			{
				$w .= ' ';
				if($r < 100)
				{
					$w .= ' ';
				}
				$w .= number_to_words($r);
			}
		} 
		else 
		{
			//return "ten togvfsgfgtehgthsus";
			$w .= number_to_words(floor($x/10000000)) .' Crores';
			$r = fmod($x, 10000000);
			if($r > 0)
			{
				$w .= ' ';
				if($r < 100)
				{
					$word .= ' ';
				}
				$w .= number_to_words($r);
				}
			}
		}
	return $w;
}

?>