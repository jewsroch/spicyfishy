function getQueryString ()
{
 var querystring = new Array;
 
 // parse current url into an array with the keys/values
 var q = String (document.location).split ('?')[1];
 if (!q) return false;
 q = q. split ('&');

 for (var i = 0 ; i < q.length; i++)
 {
   // for each key/value, split them at the '='
   // and add them to the qerystring array
   var o = q[i].split('=');
   querystring[o[0]] = o[1];
 }

 // return the querystring
 return querystring;
}