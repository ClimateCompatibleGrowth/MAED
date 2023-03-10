<?php

// if ($handle = fopen("export_log.txt", "a+"))
// {
// 	date_default_timezone_set('UTC');
// 	$data = date(DATE_RFC822, $_SERVER['REQUEST_TIME']) . ", " . $_SERVER['REMOTE_ADDR'] . ", " 
// . $_SERVER['REMOTE_HOST'] ."\n";
// 	fputs($handle, $data);
// 	fflush($handle);
// 	fclose($handle);
// }


$content = (string) $_POST['content'];
$fname = (string) $_POST['fname'];

header("Content-Disposition: attachment; filename=\"$fname\"");
header("Content-Type: application/x-download");

echo base64_decode($content);

?>
