<?php
function findWord($word){
  $files = getJSONWords();
  $response = ["type"=>"Word search","term"=>$word,"files"=>[]];
  foreach ($files as $path=>$file)
    if(isset($file[$word]))
      $response["files"][] = ["file"=>$path, "times"=> $file[$word]];
  return $response;
}
function findTerm($term){
  $term = trim(preg_replace('/[^a-zA-Z0-9-\'_]/', ' ', $term));
  $files = getJSONLines();
  $response = ["type"=>"Phrase search","term"=>$term,"files"=>[]];
  foreach ($files as $path=>$file)
    if (strpos($file, $term) !== false)
      $response["files"][] = ["file"=>$path, "times"=> "found"];
  return $response;
}

function getFiles(){ return glob(__DIR__ ."/files/*"); }

function getJSONLines(){
  $str = file_get_contents(__DIR__ .'/json/lines.json');
  return json_decode($str, true);
}

function filterFileName($v1,$v2){
	return strcmp($v1['file'], $v2['file']);
}

function doOper($termA,$termB,$oper){
  if (strcmp($oper, '&&') === 0 ) return intersect($termA,$termB);
  else if (strcmp($oper, '||') === 0 ) return  union($termA,$termB);
}

function search($term){
    if (strpos($term, '"') === false) return findWord($term);
    else return findTerm($term);
}

function intersect($arrA,$arrB){
  $terms = $arrA["term"] .", ".$arrB["term"];
  $merged = array_uintersect($arrA["files"], $arrB["files"], "filterFileName");
  $response = ["type"=>"Intersect","term"=>$terms,"files"=>$merged];
  return $response;
}

function union($arrA,$arrB){
  $terms = $arrA["term"] .", ".$arrB["term"];
  $merged = array_merge($arrA["files"],$arrB["files"]);
  $merged = array_intersect_key($merged, array_unique(array_column($merged, 'file')));
  $response = ["type"=>"Union","term"=>$terms,"files"=>$merged];
  return $response;
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

function getJSONWords(){
  $str = file_get_contents(__DIR__ .'/json/words.json');
  return json_decode($str, true);
}


function getRand(){
  $seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
  shuffle($seed);
  $rand = '';
  foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];
  return $rand;
}

function translate(&$string){
  $translate = [];
  while(strpos($string, "(")!=false) {
    preg_match('/\(([^)]+)\)/', $string, $match);
    $repl = getRand();
    $string = str_replace($match[0],$repl,$string);
    $translate[$repl] = makeArray($match[1]);
  }
  return $translate;
}

function makeArray($string){
  $string = str_replace("||"," || ",$string);
  $string = str_replace("&&"," && ",$string);
  $string = str_replace("!"," ! ",$string);
  $output = preg_replace('!\s+!', ' ', $string);
  return explode(" ",trim($output));
}

function processTerms($arrString,&$translate,&$searchArr){
  $operators =["||","&&"];
  foreach ($arrString as $val)
    if(!in_array($val,$operators))
      if (isset($translate[$val])) processTerms($translate[$val],$translate,$searchArr);
      else $searchArr[$val] = search($val);
}
function finalSearch($arrString,&$translate,&$searchArr){
  $first = array_shift($arrString);
  if (!isset($searchArr[$first])) $searchArr[$first] = finalSearch($translate[$first],$translate,$searchArr);
  if(!$arrString) return $searchArr[$first];//If itś a sole term
  while ($arrString){
    $oper = array_shift($arrString);
    $second = array_shift($arrString);
    if (!isset($searchArr[$second]))
      $searchArr[$second] = finalSearch($translate[$second],$translate,$searchArr);

    if (isset($response)) $response = doOper($response,$searchArr[$second],$oper);
    else $response = doOper($searchArr[$first],$searchArr[$second],$oper);
  }
  return $response;
}

function createJSON(){
  foreach(getFiles() as $file){
    $filesLines [$file] = breakLines($file);
    $filesWords [$file] = breakWords($file);
 }
 $fp = fopen(__DIR__ .'/json/lines.json', 'w+');
 fwrite($fp, json_encode($filesLines));
 fclose($fp);
 $fp = fopen(__DIR__ .'/json/words.json', 'w+');
 fwrite($fp, json_encode($filesWords));
 fclose($fp);
}
