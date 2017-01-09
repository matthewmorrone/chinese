
<?


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
echo json_encode($file)."\n";
if (json_last_error()) {echo json_error()."\n";}



return;


$file = file('graphics_all.txt');
$count = count($file);

foreach($file as $key=>$line):
  if ($key >= $count) {break;}
  $map = [];
  $line = str_replace("_", " ", json_decode($line, true));
  // $map[json_encode($line['character'])] = $line['strokes'];
  // $map[] = array_merge([json_encode($line['character'])], $line['strokes']);
  echo json_encode($line['character']).";".json_encode($line['strokes']); 
  // echo str_replace(" ", "_", json_encode($map[0]));
  if ($key < $count - 1) echo "\n";
endforeach;
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
