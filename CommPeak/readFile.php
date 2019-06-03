<?php
//convert csv file to array
function read_csv($fileName){
   $rows = array();
   foreach(file($fileName, FILE_IGNORE_NEW_LINES) as $line){
     $rows[] = str_getcsv($line);
   }
   return $rows;
 }
?>
