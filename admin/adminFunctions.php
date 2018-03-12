<?php

function getFiles(){ return glob(__DIR__ ."/../files/*"); }

function createJSON(){
  foreach(getFiles() as $file){
    $filesLines [$file] = breakLines($file);
    $filesWords [$file] = breakWords($file);
 }
 $fp = fopen(__DIR__ .'/../json/lines.json', 'w+');
 fwrite($fp, json_encode($filesLines));
 fclose($fp);
 $fp = fopen(__DIR__ .'/../json/words.json', 'w+');
 fwrite($fp, json_encode($filesWords));
 fclose($fp);
}
function breakLines($file){
  $str="";
  foreach(file($file) as $line)
    $str.=trim(preg_replace('/[^a-zA-Z0-9-\'_]/', ' ', strtolower($line)))." ";
  return rtrim($str);
}

function breakWords($file){
  foreach(file($file) as $line){
  $line = trim(preg_replace('/[^a-zA-Z0-9-\'_]/', ' ', strtolower($line)));
  $line = explode(" ",$line);
  foreach ($line as $word) if($word) $tmparray[] = $word;
  }
  return  isset($tmparray)?array_count_values($tmparray):[];
}
