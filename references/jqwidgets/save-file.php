<?php

function saveFile($filename, $format, $content) {
    $fl = fopen($filename . '.' . $format, 'w');
    fwrite($fl, $content);
    fclose($fl);
}

$mimeTypes = array('xls' => 'application/vnd.ms-excel', 'xml' => 'application/xml', 'html' => 'text/html', 'cvs' => 'text/plain', 'tsv' => 'text/plain', 'json' => 'text/plain',  'array' => 'text/plain');

if (isset($_POST['format']) && isset($_POST['filename']) && isset($_POST['content'])) {
    $filename = $_POST['filename'];
    $format = $_POST['format'];
    $content = stripslashes($_POST['content']);
	
	if ($format == 'pdf')
	{
		require_once('tcpdf/config/lang/eng.php');
		require_once('tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray($l);
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('times', '', 13, '', true);
		$pdf->AddPage();
		$html = $content;
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output($filename . '.pdf', 'D');
		return;
	}
	
    $fullName = $filename . '.' . $format;
    saveFile($filename, $format, $content);
    header('Pragma: public');
    header('Expires: 0'); 
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
    header('Cache-Control: private', false);
    header('Content-Type: ' . $mimeTypes[$format]);
    header('Content-Disposition: attachment; filename="' . $fullName . '"');
    header('Content-Length: ' . filesize($fullName));     
    readfile($fullName);
    unlink($fullName);
}

?>
