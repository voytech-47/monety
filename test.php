<html lang="pl">
<meta charset='utf-8'>
<?php
$str = 'orzeł';
 
if (preg_match('/^(?!.*\borzeł\b).*$/u', $str))
{   echo 'pass';
}
else
{   echo 'no pass';
}
?>