<?php
function sorter($key, $sort) {
    return function ($a, $b) use ($key, $sort) {
        if ($sort == SORT_DESC) {
            return ($b[$key] <=> $a[$key]);
        } else {
            return ($a[$key] <=> $b[$key]);
        }
    }; 
}

function arraySort(array $array, $sort = SORT_ASC, $key = 'sort'): array
{
  // function sorter($key, $sort) {
  //   return function ($a, $b) use ($key, $sort) {
  //       if ($sort == SORT_DESC) {
  //           return ($b[$key] <=> $a[$key]);
  //       } else {
  //           return ($a[$key] <=> $b[$key]);
  //       }
  //   }; 
  // }
  
  usort($array, sorter($key, $sort));
  
  return $array;
}
