<html lang="pl">
<meta charset='utf-8'>
<?php
$sortQuery = array(
    "alphaAsc" => "ORDER BY `nazwa` ASC",
    "alphaDesc" => "ORDER BY `nazwa` DESC",
    "dateDesc" => "ORDER BY `id` DESC",
    "dateAsc" => "ORDER BY `id` ASC"
);
$post = "alphaDesc";
echo $sortQuery[$post];
?>