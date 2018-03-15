<?php

function getFiles(){ return glob(__DIR__ ."/../files/*"); }
function getStorageFiles(){
  $indexed = getFiles();
  foreach ($indexed as &$value) $value = str_replace("/files/","/storage/",$value);
  $storage = glob(__DIR__ ."/../storage/*");
  return  array_diff($storage, $indexed);

}

function cleanDB(){
  $files = glob(__DIR__ .'/../json/*');
foreach($files as $file)
  if(is_file($file))
    unlink($file);

}
function createJSON(){
  cleanDB();
  foreach(getFiles() as $file){
    $tmp = explode("/",$file);
    $nfile = $tmp[count($tmp)-1];
    $filesLines [$nfile] = breakLines($file);
    $words = breakWords($file);
    foreach ($words as $key => $value) {
      if (isset($filesWords[$key[0]][$key])) $filesWords[$key[0]][$key][] = ["file"=>$nfile,"times"=>$value];
      else  $filesWords[$key[0]][$key] = [["file"=>$nfile,"times"=>$value]];
    }
 }
 $fp = fopen(__DIR__ .'/../json/lines.json', 'w+');
 fwrite($fp, json_encode($filesLines));
 fclose($fp);

 foreach ($filesWords as $key => $value) {
   $fp = fopen(__DIR__ .'/../json/'.$key.'.json', 'w+');
   fwrite($fp, json_encode($value));
   fclose($fp);
 }
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
