<?php

    /***
    * @desc Returns the last index of $needle in $haystack
    * @param $haystack string String to search in
    * @param $needle string String to search for
    */
  function lastIndexOf($haystack, $needle) {
    $index        = strpos(strrev($haystack), strrev($needle));
    $index        = strlen($haystack) - strlen($needle) - $index;
    return $index;
  }
?>
