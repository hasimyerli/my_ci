<?php
function get_class_name_by_uri($uriStrings = [], $className)
{
  // if (strpos(uri_string(), $uriString) !== false) {
  //   return $className;
  // }
  foreach ($uriStrings as $uriString) {
    if (strpos(uri_string(), $uriString) !== false) {
      return $className;
    }
  }
  return "";
}
?>
