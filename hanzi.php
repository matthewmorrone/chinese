<?
header('Content-Type: text/html; charset=utf-8');
ini_set("memory_limit", "1024M");

$file = file('readings.txt');
$file = array_map(function($line) {
  return explode("\t", trim($line));
}, $file);
$file = array_filter($file);

$dict = [];

foreach($file as $line):
  $dict[$line[0]][$line[1]] = $line[2];
endforeach;

$names = [];
foreach($dict as $key=>$line):
  $res = $key; 
  if ($line['pinyin']) {
    $res .= "\t".$line['pinyin'];
  }
  if ($line['english']) {
    $res .= "\t".$line['english'];
  }
  $res = preg_replace('/\s/', '_', $res);
  $names[$key] = $res;
endforeach;

$dir = 'svg';
@mkdir($dir);

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
  $name = $names[$line[0]];
  if (!name) continue;
  $name = 'u'.strtolower($name);
  $svg = "";
  $svg .= "<!-- ".$name." -->\n";
  $svg .= "<!-- ".$line[0]." -->\n";
  $svg .= "<!-- ".$line[2]." -->\n";
  $svg .= "<!-- ".$line[3]." -->\n";
  $svg .= "<svg viewBox=\"0 0 1024 1024\">\n";
  $svg .= "  <g transform=\"scale(1, -1) translate(0, -900)\">\n";
foreach($line[1] as $stroke):
  $svg .= "    <path d=\"$stroke\"></path>\n";
endforeach;
  $svg .= "  </g>\n";
  $svg .= "</svg>\n";
  $f = fopen("$dir/$name.svg", 'w+');
  fwrite($f, $svg);
  fclose($f);
endforeach;








return;

$file = file("top_3000_simplified.csv");


foreach($file as $line):
  echo trim($line)."\t"; 
  echo strtoupper(substr(json_encode(trim($line)), 3, -1))."\n"; 
endforeach;


print_r($file); 

return;

foreach($file as $line):
  echo "$line\n"; 
endforeach;

$file = array_map(function($line) {
  return $line;
}, $file);


$file = file_get_contents("graphics_json.txt");
$file = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $file);
$file = str_replace("_", " ", $file);

$file = json_decode($file)."\n";
print_r($file);

if (json_last_error()) {echo json_error()."\n";}

return;




$file = str_replace("_", " ", $file);
echo json_encode($file)."\n";
if (json_last_error()) {echo json_error()."\n";}



return;


$file = file('graphics_all.txt');
$count = count($file); // $count = 5;

// echo "{[\n"; 
foreach($file as $key=>$line):
  if ($key >= $count) {break;}
  $map = [];
  $line = str_replace("_", " ", json_decode($line, true));
  // $map[json_encode($line['character'])] = $line['strokes'];
  // $map[] = array_merge([json_encode($line['character'])], $line['strokes']);


  echo json_encode($line['character']).";".json_encode($line['strokes']); 
  // echo str_replace(" ", "_", json_encode($map[0]));
  if   ($key < $count - 1) /*echo ","; */ echo "\n";
endforeach;
// echo "]}\n"; 


return;


















$file = array_map(function($a) {return json_decode($a);}, $file);
echo json_encode(array_slice($file, 0, 100));

$i = 0;
array_walk($file, function($a) {
  global $i;
  if ($i > 10) {die();}
  $i++;
  echo json_encode(json_decode($a));
});

function json_error() {
  switch (json_last_error()) {
    case JSON_ERROR_NONE:
      // echo 'No errors';
      break;
    case JSON_ERROR_DEPTH:
      echo 'Maximum stack depth exceeded';
      break;
    case JSON_ERROR_STATE_MISMATCH:
      echo 'Underflow or the modes mismatch';
      break;
    case JSON_ERROR_CTRL_CHAR:
      echo 'Unexpected control character found';
      break;
    case JSON_ERROR_SYNTAX:
      echo 'Syntax error, malformed JSON';
      break;
    case JSON_ERROR_UTF8:
      echo 'Malformed UTF-8 characters, possibly incorrectly encoded';
      break;
    default:
      echo 'Unknown error';
      break;
  }
}

function json_decode_nice($json, $assoc = FALSE){ 
    $json = str_replace(array("\n","\r"),"",$json); 
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$json);
    $json = preg_replace('/(,)\s*}$/','}',$json);
    return json_decode($json,$assoc); 
}
