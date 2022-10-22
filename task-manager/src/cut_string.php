<?php
function cutString($line, $length = 15, $appends = '...'): string
{
  $string = (strlen($line) > $length) ? mb_substr ($line, 0, 12, 'utf-8') . $appends : $line;
  
  return $string;
}
