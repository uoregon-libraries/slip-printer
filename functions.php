<?php

require_once('vars.php');

// GET or POST to API
function get_result($url, $type="get") {
  $ch = curl_init();
  if ($ch === FALSE) {
    error_log("Unable to initialize curl: " . curl_error($ch));
    die("Error trying to pull data from Ex Libris. Try again or contact support.");
  }

  if (curl_setopt($ch, CURLOPT_URL, $url) === FALSE) {
    error_log("Unable to set URL on curl: " . curl_error($ch));
    die("Error trying to pull data from Ex Libris. Try again or contact support.");
  }
  if ($type == 'post') {
    if (curl_setopt($ch, CURLOPT_POST, 1) === FALSE) {
      error_log("Unable to set method to POST on curl: " . curl_error($ch));
      die("Error trying to pull data from Ex Libris. Try again or contact support.");
    }
  }
  if (curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) === FALSE) {
      error_log("Unable to set return transfer to 1 on curl: " . curl_error($ch));
      die("Error trying to pull data from Ex Libris. Try again or contact support.");
  }

  $output = curl_exec($ch);
  if ($output === FALSE) {
    error_log("Unable to execute curl operation: " . curl_error($ch));
    die("Error trying to pull data from Ex Libris. Try again or contact support.");
  }
  curl_close($ch);

  libxml_use_internal_errors(true);
  $data = simplexml_load_string($output);
  if ($data === FALSE) {
    error_log("Unable to parse XML");
    foreach(libxml_get_errors() as $error) {
        error_log("--> XML error: " . $error->message);
    }
    die("Error trying to pull data from Ex Libris. Try again or contact support.");
  }
  return $data;
}

// Get printers
function get_printers() {
  $printers_url = API_SERVER . '/almaws/v1/conf/printers?printout_queue=true&apikey=' . API_KEY;
  $printers_xml = get_result($printers_url);
  $printers = array();
  foreach ($printers_xml->printer as $printer) {
    $id = (string) $printer->id;
    $name = (string) $printer->name;
    $printers[$id] = $name;
  }
  return $printers;
}
?>
