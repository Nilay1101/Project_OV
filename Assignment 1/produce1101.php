<?php

function myDebug()
{
	return false;
}

 $getFieldLCarray = array ( "company"=>"Online Visas","firstname"=>"Ben","lastname"=>"Hunt","title"=>"Mr.", "job"=>"CTO", "citizen"=>"England", "gender"=>"M");

	//include('functions.php') ;

	//$dev_mode = ($_ENV['RDS_DB_NAME'] == 'ov-live') ? False : True ;

	//if (isSet($_GET['test'])) {
		echo '
			<form action="produce1101.php" method="post">
				Doc name: <input type="text" name="doc_name" value="h1builder1101"><br>
				Case ID: <input type="text" name="case_id" value="713"><br>
				Firm ID: <input type="text" name="firm_id" value="1"><br>
				File type: <input type="text" name="file_type" value="rtf"><br>
				<input type="Submit">
			</form>
		' ;
		//exit ;
	//}

	if (isSet($_REQUEST['doc_name'])) {
		$doc_name = $_REQUEST['doc_name'] ;
	}
	else {
		echo 'Error: No document type set.' ;
		exit ;
	}

	if (isSet($_REQUEST['case_id'])) {
		$case_id = $_REQUEST['case_id'] ;
	}
	else {
		echo 'Error: No Case ID set.' ;
		exit ;
	}

	if (isSet($_REQUEST['firm_id'])) {
		$firm_id = $_REQUEST['firm_id'] ;
	}
	else {
		echo 'Error: No Firm set.' ;
		exit ;
	}

	if (isSet($_REQUEST['file_type'])) {
		$file_type = $_REQUEST['file_type'] ;
	}
	else {
		echo 'Error: No file type set.' ;
		exit ;
	}
	
	if (False === file_exists('document-templates')) {
		echo 'Error: Could not find document templates directory.' ;
		exit ;
	}

	$docPath = 'document-templates/' . $doc_name . '.php' ;
	myDebug('$docPath = ' . $docPath) ;

	if (False === file_exists($docPath)) {
		echo '<p>Error: File template could not be found.</p>' ;
		echo '<pre>' . $doc_name . '</pre>' ;
		exit ;
	}


	// Temporary local file name
	$tempFilename =  $doc_name . '.' . $file_type ;
	myDebug('Temporary filename = ' . $tempFilename) ; 

	// Common content
	                                                                                                                       //$petLogo = getFieldLC('petitioner_logo') ;
	$petCo = $getFieldLCarray['company'] ;
	// Beneficiary name formats
	$benNames = $getFieldLCarray['firstname'] . ' ' . $getFieldLCarray['lastname'] ;
	$benTitle = $getFieldLCarray['title'];
	// Add a space if we know title.
	if ($benTitle) $benTitle = trim($benTitle) . ' ' ;
	$benShort = $benTitle . $getFieldLCarray['firstname'] ;
	$benFull = $benNames;
	$jobTitle = $getFieldLCarray['job'];
	$benCitizen = $getFieldLCarray['citizen'] ;
	$benGender = $getFieldLCarray['gender'] ;
	switch (strtoupper($benGender)) {
		case 'M' :
			$genderSubject = 'he' ;
			$genderObject = 'him' ;
			$genderPossessive = 'his' ;
			$genderIsAre = 'is' ;
			break ;
		case 'F' :
			$genderSubject = 'she' ;
			$genderObject = 'her' ;
			$genderPossessive = 'her' ;
			$genderIsAre = 'is' ;
			break ;
		default :
			$genderSubject = 'they' ;
			$genderObject = 'them' ;
			$genderPossessive = 'their' ;
			$genderIsAre = 'are' ;
			break ;
	}
	// End common content

	switch ($file_type) {

		case 'html' :
			include($docPath) ;
			file_put_contents($tempFilename, $ch) ;
			break ;

		case 'pdf' :
			require('SetaPDF/Autoload.php') ;
			myDebug('Library loaded OK.') ;
			$writer = new SetaPDF_Core_Writer_File($tempFilename) ;
			// Run the custom build
			myDebug('Including: ' . $docPath . '...') ;
			include($docPath) ;
			// Save
			myDebug('Saving.') ;
			$document->save(SetaPDF_Core_Document::SAVE_METHOD_REWRITE) ;
			$document->finish() ;
			$efs = array() ;
			break ;

		case 'rtf' :
			require_once 'PHPRtfLite/PHPRtfLite.php' ;
			PHPRtfLite::registerAutoloader() ;
			$rtf = new PHPRtfLite() ;
			setlocale(LC_MONETARY, 'en_US') ; // May be overridden
			$money_template = '%(#10n' ;
			//$firmFullName = getFirmCustomField($firm_id, 'firm_full_name') ;
			$firmFullName = 'Online Visas' ;

			// Set up font styles
			$fontB = new PHPRtfLite_Font(12, 'Serif', '#000000') ;
			$fontB->setBold() ;

			$fontR = new PHPRtfLite_Font(12, 'Serif', '#000000') ;

			$fontAlert = new PHPRtfLite_Font(12, 'Serif', '#990000') ;
			$fontAlert->setBold() ;

			$fontSm = new PHPRtfLite_Font(9, 'Serif', '#333333') ;

			$fontT = new PHPRtfLite_Font(12.5, 'Serif', '#000000') ;
			$fontT->setBold() ;
			$fontT->setUnderline() ;

			$fontT2 = new PHPRtfLite_Font(16, 'Serif', '#222222') ;
			$fontT2->setBold() ;

			$fontU = new PHPRtfLite_Font(12, 'Serif', '#000000') ;
			$fontU->setUnderline() ;

			$fontI = new PHPRtfLite_Font(12, 'Serif', '#000000') ;
			$fontI->setItalic() ;

			// Set up Paragraph styles
			$paraL = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT) ;
			$paraL->setSpaceBefore(3) ;
			$paraL->setSpaceAfter(4) ;

			$paraI = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT) ;
			$paraI->setSpaceBefore(3) ;
			$paraI->setSpaceAfter(4) ;
			$paraI->setIndentLeft(1.5);

			$paraR = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_RIGHT) ;
			$paraR->setSpaceBefore(3) ;
			$paraR->setSpaceAfter(4) ;

			$paraC = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER) ;
			$paraC->setSpaceBefore(3) ;
			$paraC->setSpaceAfter(5) ;

			$paraBQ = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT) ;
			$paraBQ->setSpaceBefore(4) ;
			$paraBQ->setSpaceAfter(7) ;
			$paraBQ->setIndentLeft(1.5) ;

			// Centered Titles
			$paraT = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER) ;
			$paraT->setSpaceBefore(3) ;
			$paraT->setSpaceAfter(4) ;

			$borderDot = PHPRtfLite_Border::create($rtf, 1, '#dddddd', PHPRtfLite_Border_Format::TYPE_DOT, 0.5) ;

			$section = $rtf->addSection() ;
			$section->setNoBreak() ;

			// Run the custom build
			include($docPath) ;

			//echo $tempFilename ; exit ;

			if (False === @$rtf->save($tempFilename)) {
				echo 'Error: Failed to save local file (' . $tempFilename . ').' ;
				exit ;
			}
			break ;
		
		default :
			echo 'Error: No file type set.' ;
			exit ;

	}
	
    $localPath = $dir = dirname(__FILE__) . '/' . $tempFilename ;
	myDebug('Local path = ' . $localPath) ;


	/* // Save to S3
    require('S3.php') ;
    $s3 = new S3($awsAccessKey, $awsSecretKey) ;
    try {
    	@S3::putObject( S3::inputFile($localPath, false), $awsCaseDocumentsBucket, $tempFilename, S3::ACL_PUBLIC_READ) ;
    }
    catch (S3Exception $oops) {
    	echo 'Error: Failed to save to secure storage.' ;
    	exit ;
    }
    myDebug('Saved OK to S3.') ;

    // Success
    // Bump the index number
    $S3_full_path = $awsRoot . '/' . $awsCaseDocumentsBucket . '/' . $tempFilename ;
    bumpCaseDocIndex($case_id, $doc_name, $latestIndex, $S3_full_path) ; */
	
	$rtf->save('production.rtf');
    
    // Delete local file
    unlink($localPath) ;

    // Add link to page (pass note back to JS return function)
    echo 'Success' ;
    exit ;


	/* FUNCTIONS */

	function writeText($link_code, $form_field_id, $ucfirst = True) {
		// Fills a text field in a PDF form.
		GLOBAL $efs ;
		$value = getFieldLC($link_code) ;
		if ($value) $efs[$form_field_id] = ucfirst($value) ;
	}	

	function checkBox($link_code, $mapping) {
		// Sets check box in PDF.
		GLOBAL $efs ;
		$value = getFieldLC($link_code) ;
		foreach ($mapping as $option=>$field) {
			$optionValue = trim(getDDOptionNumber($option)) ;
			// echo $optionValue . ' =? ' . trim($value) . " // \n" ;
			if (
					$optionValue == trim($value)
					||
					$optionValue == getDDOptionNumber($value)
				) {
				$efs[$field] = true ;
			}
		}
	}

	/*function getFieldLC($linkcode, $returnAll = False) {
		// Gets the first value found for a specific link code
		GLOBAL $case_id ;
		$SQL_get_field = 'SELECT value
			FROM tPetitionSteps
			WHERE value != ""
			AND petition_id = ' . $case_id . '
			AND 
				(
					link_code = "' . trim($linkcode) . '"
					OR link_code LIKE "' . trim($linkcode) . '_#%"
				)
			' ;
		if ($returnAll)
		{
			$SQL_get_field .= ' ORDER BY sort_order ' ;
		}
		else
		{
			$SQL_get_field .= ' LIMIT 1 ' ;
		}
		
		$RES_get_field = runSQL($SQL_get_field) ;
		if ($returnAll) {
			$fieldValues = array() ;
			while ($posText = $RES_get_field->fetch_assoc() ) {
				$fieldValues[] = $posText['value'] ;
			}
			return $fieldValues ;
		}
		else if (mysqli_num_rows($RES_get_field) == 1) {
			$ROW_get_field = $RES_get_field->fetch_assoc() ;
			return trim($ROW_get_field['value']) ;
		}
		else {
			return Null ;
		}
	} */

	function addFooter($snippet, $paraAlign = Null, $style = Null) {
		GLOBAL $section ;
		GLOBAL $fontB, $fontR, $fontSm, $fontT, $paraL, $paraC ;
		GLOBAL $dev_mode ;
		$footerText = getSnippetText($snippet) ;
		if (False !== $footerText && False !== is_null($section)) {
			$footer = $section->addFooter() ;
			switch ($style) {
				case 'small' :
					$style = $fontSm ;
					break ;
				default :
					$style = $fontR ;
					break ;
			}
			switch ($paraAlign) {
				case 'center' :
					$footer->writetext($footerText, $style, $paraC) ;
					break ;
				default :
					$footer->writetext($footerText, $style, $paraL) ;
					break ;
			}
		}
		else if ($dev_mode) {
			echo 'Warning: Footer not found: ' . $snippet . "\n" ;
		}
	}

	function getSnippetText($snippet) {
		GLOBAL $dev_mode ;
		$SQL_get_snippet = 'SELECT snippet_content from tDocumentSnippets WHERE snippet_code = "' . $snippet . '" ; ' ;
		$RES_get_snippet = runSQL($SQL_get_snippet, 'reader') ;
		if (mysqli_num_rows($RES_get_snippet) == 1) {
			$ROW_get_snippet = $RES_get_snippet->fetch_assoc() ;
			return $ROW_get_snippet['snippet_content'] ;
		}
		else if ($dev_mode) {
			echo 'Warning: Snippet not found: ' . $snippet . "\n" ;
		}
	}

	function writeSnippet($snippet, $paraAlign = Null) {
		GLOBAL $section, $fontB, $fontR, $fontT, $paraL, $paraC, $paraR;
		$chunk = @getSnippetText($snippet) ;
		if ($chunk) {
			switch ($paraAlign) {
				case 'center' :
					$section->writetext($chunk, $fontR, $paraC) ;
					break ;
				case 'right' :
					$section->writetext($chunk, $fontR, $paraR) ;
					break ;
				case 'left' :
				default :
					$section->writetext($chunk, $fontR, $paraL) ;
					break ;
			}
		}
	}

	function addNewLine($num = 1) {
		GLOBAL $section;
		while ($num>0) {
			$section->writetext('<br> ') ;
			$num-- ;
		}
	}

	function showSignatureBlock() {
		GLOBAL $section ;
		GLOBAL $petitionInfo ;
		GLOBAL $case_id ;
		GLOBAL $firmFullName ;
		GLOBAL $fontB, $fontR, $fontT, $paraL ;
		GLOBAL $dev_mode ;

		$table = $section->addTable() ;
		$table->addRow() ;
		$table->addColumn(7) ;
		$table->addColumn(7) ;

		$cell1 = $table->getCell(1, 1) ;
		$cell1->setFont($fontR) ;
		$cell2 = $table->getCell(1, 2) ;
		$cell2->setFont($fontR) ;

		// Firm signature
		$firmSig = 'Agreed to:<br>' . $firmFullName ;
		$firmSig .= '<br><br><br><br><br><br>By:<br><br><b>' . getAttorneyNames() . '</b>' ;
		$firmSig .= '<br><br>Date: ' . date('F d, Y') ;

		$cell1->writeText($firmSig) ;

		// Other party signature(s)

		$signatoryNameResults = getCaseLinkCodeValue($case_id, 'signatory_name') ;
		if (False === $signatoryNameResults && $dev_mode) {
			echo 'Warning: Could not get code signatory_name results for case #' . $case_id ;
			return False ;
		}
		else if (mysqli_num_rows($signatoryNameResults) < 1) {
			echo 'Warning: No signatory_name results found for case #' . $case_id ;
			return False ;
		}
		$sigNames = '' ;
		while ($sigRow = $signatoryNameResults->fetch_assoc() ) {
			if (strLen($sigNames)) $sigNames .= ' / ' ;
			$sigNames .= $sigRow['value'] ;
		}
		$otherSig = 'Agreed to:<br>' . $sigNames ;
		$otherSig .= '<br><br><br><br>' ;
		// Show signature block for each (Beneficiary?)
		$beneficiary_info = docGetStakeholdersByRole('beneficiary') ;
		if (False === $beneficiary_info && $dev_mode) {
			echo 'Warning: Could not retrieve beneficiary info.' ;
		}
		else {
			while($benRow = $beneficiary_info->fetch_assoc()) {
				$otherSig .= '<br><br>By:<br><br>' ;
				$otherSig .= '<b>' . $benRow['first_name'] . ' ' . $benRow['last_name'] . '</b><br><br>' ;
				$otherSig .= 'Date: ........................' ;
			}
		}
		$cell2->writeText($otherSig) ;
		return ;
	}

	function buildComposite() {
		// Pass a series of field names (link codes) and returns a space-separated string.
		$fields = func_get_args() ;
		$compositeValue = '' ;
		foreach ($fields as $field) {
			$fieldValue = getFieldLC($field) ;
			if ($fieldValue) {
				if (strlen($compositeValue)) $compositeValue .= ' ' ;
				$compositeValue .= $fieldValue ;
			}
		}
		return $compositeValue ;
	}

	function getBeneficiary1() {
		$ben1 = docGetStakeholdersByRole('beneficiary')->fetch_assoc() ;
		$b1['fn'] = $ben1['first_name'] ;
		$b1['ln'] = $ben1['last_name'] ;
		return $b1 ;
	}

	function getPetitioningCompany() {
		$petCo = getFieldLC('petitioner_company_name') ;
		return $petCo ;
	}

	function getPetitioner() {
		$pet = docGetStakeholdersByRole('petitioner_primary_contact')->fetch_assoc() ;
		$p1['fn'] = $pet['first_name'] ;
		$p1['ln'] = $pet['last_name'] ;
		return $p1 ;
	}

	function showLogo($imageURL, $width=7) {
		GLOBAL $section ;
		GLOBAL $dev_mode ;
		$paraLogo = new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER) ;
		$paraLogo->setSpaceBefore(10) ;
		$paraLogo->setSpaceAfter(30) ;
		if (file_exists($imageURL)) {
			$image = $section->addImage($imageURL, $paraLogo) ;
			$image->setWidth($width) ;
			$section->writetext('<br>') ;
		}
		else if ($dev_mode) {
			echo 'Warning: Could not load image file: ' . $imageURL ;
		}
	}

	function showFees($mode = 'contract') {
		GLOBAL $section, $case_id, $money_template ;
		GLOBAL $dev_mode ;
		$contract_fees = getContractFees($case_id, $mode) ;
		if (False === $contract_fees && $dev_mode) {
			echo 'Warning: Could not load contract fees.' ;
			return ;
		}
		$fees_total = 0 ;

		$tableF = $section->addTable() ;
		$tableF->addRow() ;
		$tableF->addColumn(11) ;
		$tableF->addColumn(3) ;
		$tableF->writeToCell(1,1,'<b>DESCRIPTION</b>') ;
		$tableF->writeToCell(1,2,'<b>AMOUNT</b>') ;
		//$cellAmt = $tableF->getCell(1, 2) ;
		//$cellAmt->setTextAlignment(TEXT_ALIGN_RIGHT) ;
		$currentRow = 2 ;

		while($feesRow = $contract_fees->fetch_assoc()) {
			$tableF->addRow() ;
			$tableF->writeToCell($currentRow, 1, $feesRow['step_desc']) ;
			if (is_numeric($feesRow['step_notes'])) {
				$tableF->writeToCell($currentRow, 2, money_format($money_template, $feesRow['step_notes'])) ;
				$fees_total += floatval($feesRow['step_notes']) ;
			}
			else {
				$tableF->writeToCell($currentRow, 2, $feesRow['step_notes']) ;
			}
			//$cellAmt = $tableF->getCell($currentRow, 2) ;
			//$cellAmt->setTextAlignment(TEXT_ALIGN_RIGHT) ;
			$currentRow++ ;
		}
		$tableF->addRow() ;
		$tableF->writeToCell($currentRow, 1, '<b>TOTAL</b>') ;
		$tableF->writeToCell($currentRow, 2,  '<b>' . money_format($money_template, $fees_total) . '</b>' ) ;
		$tableF->setBackgroundForCellRange('#DDDDDD', $currentRow, 1, $currentRow, 2) ;
		return ;
	}

	function getAttorneyNames() {
		GLOBAL $section, $case_id ;
		GLOBAL $dev_mode ;
		$attorneyInfo = getStakeholderContactDetails($case_id, 'attorney') ;
		if (False === $attorneyInfo && $dev_mode) {
			echo 'Warning: Could not load attorney info for case #' . $case_id ;
			return ;
		}
		$attHTML = '' ;
		while($attyRow = $attorneyInfo->fetch_assoc()) {
			if (strlen($attHTML)) $attHTML .= '<br>' ;
			$attHTML .= $attyRow['first_name'] . ' ' . $attyRow['last_name'] ;
		}
		return $attHTML ;
	}

	function docGetStakeholdersByRole($roleDesc) {
		GLOBAL $case_id ;
		GLOBAL $dev_mode ;
		$returnVal = getStakeholderContactDetails($case_id, $roleDesc) ;
		if (False === $returnVal && $dev_mode) {
			echo 'Warning: Could not find ' . $roleDesc . ' stakeholders for case #' . $case_id ;
			return False ;
		}
		return $returnVal ;
	}

	function docShowStakeholderInfo($roleDesc, $showLabel) {
		GLOBAL $section ;
		GLOBAL $fontB, $fontR, $fontT, $paraL ;
		GLOBAL $dev_mode ;
		$doc_sh_info = docGetStakeholdersByRole($roleDesc) ;
		if ( (False === $doc_sh_info || mysqli_num_rows($doc_sh_info) == 0 ) ) {
			if ($dev_mode) echo 'Warning: No ' . $roleDesc . ' stakeholders found.' ;
			return ;
		}
		while($shRow = $doc_sh_info->fetch_assoc()) {
			$section->writeText($showLabel . ': ' . $shRow['first_name'] . ' ' . $shRow['last_name'], $fontB, $paraL ) ;
			if (isSet($shRow['email'])) {
				$section->writeText('<br>' . $shRow['email'], $fontR) ;
			}
			if (isSet($shRow['contact_phone'])) {
				$section->writeText($shRow['contact_phone'], $fontR) ;
			}
		}
	}

	function showDate() {
		GLOBAL $section, $fontB, $fontR, $fontT, $paraL, $paraR, $dev_mode ;
		$section->writeText(date('F d, Y'), $fontR, $paraR) ;
	}

?>