<?

header('Content-Type: text/html; charset=utf-8');
ini_set("memory_limit", "1024M");


function prepFile($name) {
  $file = file($name);
  $file = array_map(function($line) {
    return explode("\t", trim($line));
  }, $file);
  $file = array_filter($file);
  return $file;
}

  
$readings = prepFile('readings.txt');
$numerals = prepFile('numerals.txt');
$standard = prepFile('standard.txt');

$nums = [];
foreach($numerals as $line):
  $nums[$line[0]][$line[1]] = $line[2];
  $nums[$line[0]] = [$line[1], $line[2]];
endforeach;
$stds = [];
foreach($standard as $line):
  $stds[$line[0]][$line[1]] = $line[2];
  $stds[$line[0]] = [$line[1], $line[2]];
endforeach;

$dict = [];
$simp = [];
$trad = [];
foreach($readings as $key=>$line):
  // if (!$stds[$line[0]]) {
  //   continue;
  // }
  $dict[$line[0]][$line[1]] = $line[2];
  if ($nums[$line[0]]) {
    $num = $nums[$line[0]];
    $dict[$line[0]][$num[0]] = $num[1];
  }
  if ($stds[$line[0]]) {
    $std = $stds[$line[0]];
    if (strcmp($std[0], 'traditional') === 0) {
      $simp[$line[0]] = $dict[$line[0]];
      $dict[$line[0]]['set'] = 'simp';
    }
    if (strcmp($std[0], 'simplified') === 0) {
      $trad[$line[0]] = $dict[$line[0]];
      $dict[$line[0]]['set'] = 'trad';
    }
  }
endforeach;

$names = [];
foreach($dict as $key=>$line):
  $res = $key; 
  // if (!$line['pinyin'] || !$line['english']) {continue;}
  // if (!$line['set']) {continue;}
  $res .= "\t".$line['pinyin'];
  $res .= "\t".$line['set'];
  // if ($line['english']) {}
  $res .= "\t".$line['english'];
  $res = preg_replace('/\s/', '_', $res);
  $res = substr($res, 0, 50);
  if (strlen($res) > 49 && strcmp($res[strlen($res)-1], "_") !== 0) {
    $res = explode("_", $res);
    array_pop($res);
    $res = implode("_", $res);
  }
  $res = rtrim($res, "_");
  $res = preg_replace('/_+/', '_', $res);
  
  $names[$key] = $res;
endforeach;


function reset_dir($dir) {
  foreach(glob("{$dir}/*") as $file) {
    if(is_dir($file)) { 
      recursiveRemoveDirectory($file);
    } 
    else {
      unlink($file);
    }
  }
  rmdir($dir);
  mkdir($dir);
}


$dir = 'svg';

reset_dir($dir);



$file = file("strokes.txt");
foreach($file as $key=>&$line):
  $line = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $line);
  $line = str_replace("_", " ", $line);
  $line = explode(';', $line);
  $line[0] = strtoupper(substr($line[0], 3, -1));
  $line[1] = json_decode($line[1], true);
  $line[2] = json_decode($line[0]);
  $line[3] = utf8_encode(json_decode($line[0]));
endforeach;

foreach($file as $line):
  $name = $names[trim($line[0])];
  if (!name) {continue;}
  $i++;
  echo $name."\n"; 
  $name = 'u'.strtolower($name);
  $svg = "";
  $svg .= "<!-- ".$name." -->\n";
  $svg .= "<!-- ".$line[0]." -->\n";
  $svg .= "<!-- ".$line[2]." -->\n";
  $svg .= "<!-- ".$line[3]." -->\n";
  $svg .= "<svg viewBox=\"0 0 1024 1024\">\n";
  $svg .= "  <g transform=\"scale(1, -1) translate(0, -900)\">\n";
foreach($line[1] as $stroke):
  $svg .= "    <path fill=\"#FFFFFF\" d=\"$stroke\"></path>\n";
endforeach;
  $svg .= "  </g>\n";
  $svg .= "</svg>\n";
  if (strcmp($name, "u") === 0) {continue;}
  $f = fopen("$dir/$name.svg", 'w+');
  fwrite($f, $svg);
  fclose($f);
endforeach;


echo $i."\n"; 