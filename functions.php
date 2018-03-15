<?php

function findByWord($word,$hasNot=false){
  $key = $word[0];
  $retorno = [];
  $path = __DIR__ ."/json/$key.json";
  if (is_file($path)){
    $str = file_get_contents($path);
    $content = json_decode($str, true);
    if (isset($content[$word]))
      foreach ($content[$word] as $key => $value)
        $retorno[] = ["file"=>$value["file"],"times"=>$value["times"]];
  }
  return $hasNot? invert($retorno) : $retorno;
}
function getJSONLines(){
  $str = file_get_contents(__DIR__ .'/json/lines.json');
  return json_decode($str, true);
}
function invert($files){
  $found = [];
  $return  = [];
  foreach ($files as $key => $value) $found [] = $value["file"];
  foreach (getFiles() as $value){
    $check = explode("/",$value);
    $check = $check[count($check)-1];
    if (!in_array($check,$found)) $return[] = ["file"=>$check,"times"=>0];
  }
  return $return;
}
function findWord($word){
  $response = ["type"=>"Word search","term"=>$word,"files"=>[]];
  $hasNot=($word[0]==="!");
  $word = preg_replace('/!/', '', $word);
  $response["files"] = findByWord($word,$hasNot);
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

function filterFileName($v1,$v2){ return strcmp($v1['file'], $v2['file']); }

function doOper($termA,$termB,$oper){
  if (strcmp($oper, '&&') === 0 ) return intersect($termA,$termB);
  else if (strcmp($oper, '||') === 0 ) return  union($termA,$termB);
  else if (strcmp($oper, '!') === 0 ) return  invert($termA);
}

function search($term){
    return (strpos($term, '"') === false)? findWord($term):findTerm($term);
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

function getRand(){
  $seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
  shuffle($seed);
  $rand = '';
  foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];
  return $rand;
}

function translate(&$string){
  $operators =["||","&&"];
  $translate = [];
  while(strpos($string, "(")!==false) {
    preg_match('/\(([^)]+)\)/', $string, $match);
    $repl = getRand();
    $string = str_replace($match[0],$repl,$string);
    $translate[$repl] = makeArray($match[1]);
  }
  return $translate;
}
function normalizeString($string){
  $string = preg_replace('{\s+(?!([^"]*"[^"]*")*[^"]*$)}',"~~",$string);
  $string = preg_replace('/\s+/', '', $string);
  $string = preg_replace('%\s+%', ' ', $string);
  $string = str_replace("||"," || ",$string);
  $string = str_replace("&&"," && ",$string);
  return  trim(strtolower($string));
}
function makeArray($string){
  $output  = explode(" ",$string);
  foreach($output as &$value) $value = str_replace("~~"," ",$value);
  return $output;
}

function processTerms($arrString,&$translate,&$searchArr){
  $operators =["||","&&"];
  foreach ($arrString as $word){
    $noNot= preg_replace('/!/', '', $word);
    if(!in_array($word,$operators)){
      if (isset($translate[$word])) processTerms($translate[$word],$translate,$searchArr);
      else if (isset($translate[$noNot])) processTerms($translate[$noNot],$translate,$searchArr);
      else $searchArr[$word] = search($word);
    }
  }
}
function checkIfResult($first,&$translate,&$searchArr){
  if (!isset($searchArr[$first])){
    $hasNot=($first[0]==="!");
    $word = preg_replace('/!/', '', $first);
    $searchArr[$word] = finalSearch($translate[$word],$translate,$searchArr);
    if ($hasNot){
      $searchArr[$first] = $searchArr[$word];
      $searchArr[$first]["files"] = invert($searchArr[$first]["files"]);
    }
  }
}
function finalSearch($arrString,&$translate,&$searchArr){
  $first = array_shift($arrString); //Get first term
  checkIfResult($first,$translate,$searchArr); //If it is a generated term and we dont have it searched, searchit
  if(!$arrString) return $searchArr[$first];//If itś a sole term
  while ($arrString){
    $oper = array_shift($arrString);
    $second = array_shift($arrString);
    checkIfResult($second,$translate,$searchArr);
    $response = (isset($response)) ?
    doOper($response,$searchArr[$second],$oper):
    doOper($searchArr[$first],$searchArr[$second],$oper);
  }
  return $response;
}
