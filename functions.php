<?php

// ===============================================================================
// rows to show per page  
// ===============================================================================

function rowsPerpage() {
	return 10;
}

// ===============================================================================
// Redirect pages or URL  
// ===============================================================================
function redirectTo($location=NULL) {
  if ($location != NULL) {
   header("location:{$location}");
//echo "<script>location.href='$location';</script>";
    exit;
  }
}

// ===============================================================================
// Show any message 
// ===============================================================================
function outputMessage($message="") {
  if (!empty($message)) { 
    return "<p class=\"message\">{$message}</p>";
  } else {
    return "";
  }
}
// ===============================================================================
// Select aboslute path from root directory by putting ../
// ===============================================================================
function FindRoot() {
		
	$times = substr_count($_SERVER['PHP_SELF'],"/");
	$rootaccess = "";
	$i = 3;		//if you're working on local computer set it 2, if its on live server set this value to 1
	while ($i < $times)
		{
			$rootaccess .= "../";
			$i++;
		
	}
	
	return $rootaccess;
		
}
$root = FindRoot();
//	Alternative function for getting absolute path of home directory
function rootPath() {
	 
	/*$url = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	$urlParts = explode('/', str_ireplace(array('http://', 'https://'), '', $url));
	
	return 'http://'.$_SERVER["HTTP_HOST"].'/'.$urlParts[1].'/';
	 
	 */
 	
	$url = "http://localhost/";
	//$url  = "Your Website URL";
	return $url;
	
}
$siteURL = rootPath();
// ===============================================================================
// It displays current page URL ../
// ===============================================================================
function curPageURL() {
	$pageURL = 'http';
	// if ($_SERVER["HTTPS"] == true) {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	return $pageURL;
}

// ===============================================================================
// Handles non-declared variables
// ===============================================================================
function __autoload($class_name) 
{
	$class_name = strtolower($class_name);
	$path = "{$class_name}.php";
	if(file_exists($path)) 
		require_once($path);
	else 
		die("The file {$class_name}.php could not be found.");
}

// ===============================================================================
// This will return Date and Time i.e. January 12, 2011 at 02:22:12
// ===============================================================================
function datetime_to_text($datetime="") 
{
	 /* $unixdatetime = strtotime($datetime);
	 return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
	 */ 
	$GMTObj = new DateTime($datetime, new DateTimeZone("GMT"));
	$LocalObj = $GMTObj;
	if(isset($_SESSION['timezone']))
	{
		$timezone=$_SESSION['timezone']; //cookie is defined in header
	}
	else
	{
		$timezone='asia/karachi';
	}
	
	$LocalObj->setTimezone(new DateTimeZone($timezone));
	return $LocalObj->format("F d, Y   h:i:s a"); //%B %d, %Y at %I:%M %p
	
	//return date('F d  h:i:s a', strtotime($LocalObj->date));
}

// ===============================================================================
// This will return only Date i.e. January 12, 2011
// ===============================================================================
function date_to_text($date="") {
  $unixdatetime = strtotime($date);
  return strftime("%b %d, %Y", $unixdatetime);
}
function day_to_text($day="") {
  $unixdatetime = strtotime($day);
  return strftime("%d", $unixdatetime);
}
function month_to_text($month="") {
  $unixdatetime = strtotime($month);
  return strftime("%B", $unixdatetime);
}
function year_to_text($year="") {
  $unixdatetime = strtotime($year);
  return strftime("%Y", $unixdatetime);
}

// ===============================================================================
// Random encrypted activation key for Account activation after account has been created
// ===============================================================================
function actKey ($getStr) {
	$actKey = sha1(mt_rand(10000,99999).time().$getStr);
	return $actKey;
}
function actKeyDatetime($getStr) {
	return actkey($getStr).'_'.base64_encode(time());
	
}

function randomProId ($getStr) {
	$actKey = sha1(mt_rand(100,999).$getStr);
	return $actKey;
}

// ===============================================================================
// Formate the date by removing zero
// ===============================================================================
function strip_zeros_from_date( $marked_string="" ) {
  // first remove the marked zeros
  $no_zeros = str_replace('*0', '', $marked_string);
  // then remove any remaining marks
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}



//	function to return the pagination string
//	for more visit http://www.strangerstudios.com/sandbox/pagination/diggstyle.php
function getPaginationString($page = 1, $totalitems, $limit = 15, $adjacents = 1, $targetpage = "/", $pagestring = "?page=")
{		
	//defaults
	if(!$adjacents) $adjacents = 1;
	if(!$limit) $limit = 15;
	if(!$page) $page = 1;
	if(!$targetpage) $targetpage = "/";
	
	//other vars
	$prev = $page - 1;									//previous page is page - 1
	$next = $page + 1;									//next page is page + 1
	$lastpage = ceil($totalitems / $limit);				//lastpage is = total items / items per page, rounded up.
	$lpm1 = $lastpage - 1;								//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div id=\"paginationGallery\"";
		$margin = ""; $padding = "";
		if($margin || $padding)
		{
			$pagination .= " style=\"";
			if($margin)
				$pagination .= "margin: $margin;";
			if($padding)
				$pagination .= "padding: $padding;";
			$pagination .= "\"";
		}
		$pagination .= ">";
		$pagination .= "<ul class=\"pagination margin-top-20 \">";

		//previous button
		if ($page > 1) 
			$pagination .= "<li><a class=\"disabled btnPrev\" href=\"$targetpage$pagestring$prev\">Prev</a></li>"; // << backBtn
		else
			$pagination .= "<li><a style='display:none'><span class=\"disabled btnPrev\">Prev</span></a></li>";	// << backBtn
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination .= "<li class='active '><a >$counter</span></li>";
				else
					$pagination .= "<li><a   href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";					
			}
		}
		elseif($lastpage >= 7 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 3))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination .= "<li class='active '><a >$counter</a></li>";
					else
						$pagination .= "<li><a  href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";					
				}
				$pagination .= "<li><span class=\"elipses\">...</span></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "1\">1</a></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "2\">2</a></li>";
				//$pagination .= "<li><span class=\"elipses\">...</span>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination .= "<li  class=\"active \"><a >$counter</a></li>";
					else
						$pagination .= "<li><a  href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";					
				}
				
				$pagination .= "<li><a  href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a></li>";
				$pagination .= "<li><a  href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination .= "<li><a  href=\"" . $targetpage . $pagestring . "1\">1</a></li>";
				$pagination .= "<li><a  href=\"" . $targetpage . $pagestring . "2\">2</a></li>";
				$pagination .= "<li><span class=\"elipses\"> ... </span>";
				for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination .= "<li class=\"active  \"><a >$counter</a></li>";
					else
						$pagination .= "<li><a  href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination .= "<li><a class=\"disabled btnNext\" href=\"" . $targetpage . $pagestring . $next . "\">Next </a></li>";// >> BtnNext
		else
			$pagination .= "<li><a style='display:none'><span  class=\"disabled btnNext\">Next</span></a></li>";// >> BtnNext
	    $pagination .= "</ul>\n";
		$pagination .= "</div>\n";
	}
	
	return $pagination;

}
	
	//=======================================================
	//			Date of birth to years
	//=======================================================
	
	function dobToYears($dob)
	{	 
		$currentDate=date('Y-m-d');
		
		$d1 = new DateTime($dob);
		$d2 = new DateTime($currentDate);
		
		$diff = $d2->diff($d1);
		
		return $diff->y." years old <br />";	
	}
	
	//=======================================================
	//			TIME Ago 
	//=======================================================

	function timeAgo($time_ago){
	$cur_time 		= time();
	$time_elapsed 	= $cur_time - $time_ago;
	$seconds 		= $time_elapsed ;
	$minutes 		= round($time_elapsed / 60 );
	$hours 			= round($time_elapsed / 3600);
	$days 			= round($time_elapsed / 86400 );
	$weeks 			= round($time_elapsed / 604800);
	$months 		= round($time_elapsed / 2600640 );
	$years 			= round($time_elapsed / 31207680 );
	// Seconds
	if($seconds <= 60){
		echo "$seconds seconds ago";
	}
	//	Minutes
	else if($minutes <=60)
	{
		if($minutes==1)
		{
			echo "one minute ago";
		}
		else
		{
			echo "$minutes minutes ago";
		}
	}
	//	Hours
	else if($hours <=24)
	{
		if($hours==1)
		{
			echo "an hour ago";
		}
		else
		{
			echo "$hours hours ago";
		}
	}
	//	Days
	else if($days <= 7)
	{
		if($days==1)
		{
			echo "yesterday";
		}
		else
		{
			echo "$days days ago";
		}
	}
	//	Weeks
	else if($weeks <= 4.3)
	{
		if($weeks==1)
		{
			echo "a week ago";
		}
		else
		{
			echo "$weeks weeks ago";
		}
	}
	//	Months
	else if($months <=12)
	{
		if($months==1)
		{
			echo "a month ago";
		}
		else
		{
			echo "$months months ago";
		}
	}
	//	Years
	else{
		if($years==1)
		{
			echo "one year ago";
		}
		else
		{
			echo "$years years ago ";
		}
	}
  }
 	//=======================================================
	//			find i.p adress
	//=======================================================
	
	function ip()
	{
		$server='localhost'; //for live server set $server="live";
		if($server=="localhost")
		{
			$ip="192.168.1.1"; //dummy ip
			return $ip;
		}
		if($server=="live")
		{
			$ip=(string)$_SERVER['REMOTE_ADDR'];
			return $ip;
		}
	}
	// ===============================================================================
	// Generate valid URL title ; Ref: http://cubiq.org/the-perfect-php-clean-url-generator
	// ===============================================================================
	setlocale(LC_ALL, 'en_US.UTF8');
	function setUrlTitle($str, $replace=array(), $delimiter='-') {
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
	
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}
  
	// ===============================================================================
	// change number to (thousand, million, billion, trillion ) [t,m,b,t]
	// ===============================================================================
    function easyReadNumber($n) {
        // first strip any formatting;
        $n = (0+str_replace(",","",$n));
        
        // is this a number?
        if(!is_numeric($n)) return false;
        
        // now filter it;
        if($n>1000000000000) return round(($n/1000000000000),1).' t';
        else if($n>1000000000) return round(($n/1000000000),1).' b';
        else if($n>1000000) return round(($n/1000000),1).' m';
        else if($n>1000) return round(($n/1000),1).' k';
        
        return number_format($n);
    }
 
//	show the variable in posted form data
function esc_attr($string) {
	return htmlspecialchars($string, ENT_QUOTES,"UTF-8");
}

// convert string to stars
//	e.g $a = "12345"; will b equal to **345
function string_to_stars($string='',$first=0,$last=0,$rep='*'){
  $begin  = substr($string,0,$first);
  $middle = str_repeat($rep,strlen(substr($string,$first,$last)));
  $end    = substr($string,$last);
  $stars  = $begin.$middle.$end;
  return $stars;
}
 
function addBreak($numBreaks = "") {
	$breakTag = '<br>';
	if(!$numBreaks) {
		echo $breakTag;
	} else {
		$breaks ="";
		for($i = 0; $i < $numBreaks; $i++) {
			$breaks .= $breakTag;
		}
		echo $breaks;
	}
}

function curDateTime() {
	return strftime("%Y-%m-%d %H:%M:%S", time());
}
function isAdmin() {
	global $session;
	if(isset($session->userId)) {
		$userObj = User::findById($session->userId);
		$role = array_search($userObj->role, User::$rolesArr);
		if($role == 'administrator') 
			return true;
		else
			return false;
		unset($userObj);
	} 
}



/**
 * Private helper function for checked, selected, and disabled.
 *
 * Compares the first two arguments and if identical marks as $type
 *
 * @since 2.8.0
 * @access private
 *
 * @param mixed  $helper  One of the values to compare
 * @param mixed  $current (true) The other value to compare if not just true
 * @param bool   $echo    Whether to echo or just return the string
 * @param string $type    The type of checked|selected|disabled we are doing
 * @return string html attribute or empty string
 */
function checked_selected_helper( $helper, $current, $echo, $type ) {
	if ( (string) $helper === (string) $current )
		$result = " $type='$type'";
	else
		$result = '';

	if ( $echo )
		echo $result;

	return $result;
}
function selected( $selected, $current = true, $echo = true ) {
	return checked_selected_helper( $selected, $current, $echo, 'selected' );
}
function disabled( $disabled, $current = true, $echo = true ) {
	return checked_selected_helper( $disabled, $current, $echo, 'disabled' );
}
function checked( $checked, $current = true, $echo = true ) {
	return checked_selected_helper( $checked, $current, $echo, 'checked' );
}


function getDaysList() {
	ob_start();
	// Get days in a month
	$currentDate  = date("d"); 
	$currentMonth = date("m"); 
	$currentYear  = date("Y"); 
	$calDaysInMonth = cal_days_in_month(CAL_GREGORIAN,$currentMonth,$currentYear); 
	for($i=1; $i <= $calDaysInMonth; $i++) {
		echo " '".$i."' => '".$i."' ,"."<br/>";
	}
	$fillDaysArr = ob_get_clean();

	// OS Font Defaults
	$daysList = array($fillDaysArr);
	return $daysList;
}

/**
 * Serialize data, if needed.
 *
 *
 * @param string|array|object $data Data that might be serialized.
 * @return mixed A scalar data
 */
function maybeSerialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) )
		return serialize( $data );

	// Double serialization is required for backward compatibility.
	// See https://core.trac.wordpress.org/ticket/12930
	// Also the world will end. See WP 3.6.1.
	if ( isSerialized( $data, false ) )
		return serialize( $data );

	return $data;
}
/**
 * Unserialize value only if it was serialized.
 *
 * @since 2.0.0
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
function maybeUnserialize( $original ) {
	if ( isSerialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 *
 * @param string $data   Value to check to see if was serialized.
 * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
 * @return bool False if not serialized and true if it was.
 */
function isSerialized( $data, $strict = true ) {
	// if it isn't a string, it isn't serialized.
	if ( ! is_string( $data ) ) {
		return false;
	}
	$data = trim( $data );
 	if ( 'N;' == $data ) {
		return true;
	}
	if ( strlen( $data ) < 4 ) {
		return false;
	}
	if ( ':' !== $data[1] ) {
		return false;
	}
	if ( $strict ) {
		$lastc = substr( $data, -1 );
		if ( ';' !== $lastc && '}' !== $lastc ) {
			return false;
		}
	} else {
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		// Either ; or } must exist.
		if ( false === $semicolon && false === $brace )
			return false;
		// But neither must be in the first X characters.
		if ( false !== $semicolon && $semicolon < 3 )
			return false;
		if ( false !== $brace && $brace < 4 )
			return false;
	}
	$token = $data[0];
	switch ( $token ) {
		case 's' :
			if ( $strict ) {
				if ( '"' !== substr( $data, -2, 1 ) ) {
					return false;
				}
			} elseif ( false === strpos( $data, '"' ) ) {
				return false;
			}
			// or else fall through
		case 'a' :
		case 'O' :
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b' :
		case 'i' :
		case 'd' :
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	}
	return false;
}
 
//http://www.phpro.org/examples/Country-Array.html

/*
$countries = countryArray();
foreach($countries as $key => $country) {
	//from
	//'AF'=>'Afghanistan',
	echo "'".$country."'"." => "."'".$country."'"." ,"."<br />";
	
	// to 
	// 'Afghanistan'=>'Afghanistan',
}
*/
 
function countryArray(){

	return array(
		'Afghanistan' => 'Afghanistan' ,
		'Albania' => 'Albania' ,
		'Algeria' => 'Algeria' ,
		'American Samoa' => 'American Samoa' ,
		'Andorra' => 'Andorra' ,
		'Angola' => 'Angola' ,
		'Anguilla' => 'Anguilla' ,
		'Antarctica' => 'Antarctica' ,
		'Antigua And Barbuda' => 'Antigua And Barbuda' ,
		'Argentina' => 'Argentina' ,
		'Armenia' => 'Armenia' ,
		'Aruba' => 'Aruba' ,
		'Australia' => 'Australia' ,
		'Austria' => 'Austria' ,
		'Azerbaijan' => 'Azerbaijan' ,
		'Bahamas' => 'Bahamas' ,
		'Bahrain' => 'Bahrain' ,
		'Bangladesh' => 'Bangladesh' ,
		'Barbados' => 'Barbados' ,
		'Belarus' => 'Belarus' ,
		'Belgium' => 'Belgium' ,
		'Belize' => 'Belize' ,
		'Benin' => 'Benin' ,
		'Bermuda' => 'Bermuda' ,
		'Bhutan' => 'Bhutan' ,
		'Bolivia' => 'Bolivia' ,
		'Bosnia And Herzegovina' => 'Bosnia And Herzegovina' ,
		'Botswana' => 'Botswana' ,
		'Bouvet Island' => 'Bouvet Island' ,
		'Brazil' => 'Brazil' ,
		'British Indian Ocean Territory' => 'British Indian Ocean Territory' ,
		'Brunei' => 'Brunei' ,
		'Bulgaria' => 'Bulgaria' ,
		'Burkina Faso' => 'Burkina Faso' ,
		'Burundi' => 'Burundi' ,
		'Cambodia' => 'Cambodia' ,
		'Cameroon' => 'Cameroon' ,
		'Canada' => 'Canada' ,
		'Cape Verde' => 'Cape Verde' ,
		'Cayman Islands' => 'Cayman Islands' ,
		'Central African Republic' => 'Central African Republic' ,
		'Chad' => 'Chad' ,
		'Chile' => 'Chile' ,
		'China' => 'China' ,
		'Christmas Island' => 'Christmas Island' ,
		'Cocos (Keeling) Islands' => 'Cocos (Keeling) Islands' ,
		'Columbia' => 'Columbia' ,
		'Comoros' => 'Comoros' ,
		'Congo' => 'Congo' ,
		'Cook Islands' => 'Cook Islands' ,
		'Costa Rica' => 'Costa Rica' ,
		"Cote D'Ivorie (Ivory Coast)" => "Cote D'Ivorie (Ivory Coast)" ,
		'Croatia (Hrvatska)' => 'Croatia (Hrvatska)' ,
		'Cuba' => 'Cuba' ,
		'Cyprus' => 'Cyprus' ,
		'Czech Republic' => 'Czech Republic' ,
		'Democratic Republic Of Congo (Zaire)' => 'Democratic Republic Of Congo (Zaire)' ,
		'Denmark' => 'Denmark' ,
		'Djibouti' => 'Djibouti' ,
		'Dominica' => 'Dominica' ,
		'Dominican Republic' => 'Dominican Republic' ,
		'East Timor' => 'East Timor' ,
		'Ecuador' => 'Ecuador' ,
		'Egypt' => 'Egypt' ,
		'El Salvador' => 'El Salvador' ,
		'Equatorial Guinea' => 'Equatorial Guinea' ,
		'Eritrea' => 'Eritrea' ,
		'Estonia' => 'Estonia' ,
		'Ethiopia' => 'Ethiopia' ,
		'Falkland Islands (Malvinas)' => 'Falkland Islands (Malvinas)' ,
		'Faroe Islands' => 'Faroe Islands' ,
		'Fiji' => 'Fiji' ,
		'Finland' => 'Finland' ,
		'France' => 'France' ,
		'France, Metropolitan' => 'France, Metropolitan' ,
		'French Guinea' => 'French Guinea' ,
		'French Polynesia' => 'French Polynesia' ,
		'French Southern Territories' => 'French Southern Territories' ,
		'Gabon' => 'Gabon' ,
		'Gambia' => 'Gambia' ,
		'Georgia' => 'Georgia' ,
		'Germany' => 'Germany' ,
		'Ghana' => 'Ghana' ,
		'Gibraltar' => 'Gibraltar' ,
		'Greece' => 'Greece' ,
		'Greenland' => 'Greenland' ,
		'Grenada' => 'Grenada' ,
		'Guadeloupe' => 'Guadeloupe' ,
		'Guam' => 'Guam' ,
		'Guatemala' => 'Guatemala' ,
		'Guinea' => 'Guinea' ,
		'Guinea-Bissau' => 'Guinea-Bissau' ,
		'Guyana' => 'Guyana' ,
		'Haiti' => 'Haiti' ,
		'Heard And McDonald Islands' => 'Heard And McDonald Islands' ,
		'Honduras' => 'Honduras' ,
		'Hong Kong' => 'Hong Kong' ,
		'Hungary' => 'Hungary' ,
		'Iceland' => 'Iceland' ,
		'India' => 'India' ,
		'Indonesia' => 'Indonesia' ,
		'Iran' => 'Iran' ,
		'Iraq' => 'Iraq' ,
		'Ireland' => 'Ireland' ,
		'Israel' => 'Israel' ,
		'Italy' => 'Italy' ,
		'Jamaica' => 'Jamaica' ,
		'Japan' => 'Japan' ,
		'Jordan' => 'Jordan' ,
		'Kazakhstan' => 'Kazakhstan' ,
		'Kenya' => 'Kenya' ,
		'Kiribati' => 'Kiribati' ,
		'Kuwait' => 'Kuwait' ,
		'Kyrgyzstan' => 'Kyrgyzstan' ,
		'Laos' => 'Laos' ,
		'Latvia' => 'Latvia' ,
		'Lebanon' => 'Lebanon' ,
		'Lesotho' => 'Lesotho' ,
		'Liberia' => 'Liberia' ,
		'Libya' => 'Libya' ,
		'Liechtenstein' => 'Liechtenstein' ,
		'Lithuania' => 'Lithuania' ,
		'Luxembourg' => 'Luxembourg' ,
		'Macau' => 'Macau' ,
		'Macedonia' => 'Macedonia' ,
		'Madagascar' => 'Madagascar' ,
		'Malawi' => 'Malawi' ,
		'Malaysia' => 'Malaysia' ,
		'Maldives' => 'Maldives' ,
		'Mali' => 'Mali' ,
		'Malta' => 'Malta' ,
		'Marshall Islands' => 'Marshall Islands' ,
		'Martinique' => 'Martinique' ,
		'Mauritania' => 'Mauritania' ,
		'Mauritius' => 'Mauritius' ,
		'Mayotte' => 'Mayotte' ,
		'Mexico' => 'Mexico' ,
		'Micronesia' => 'Micronesia' ,
		'Moldova' => 'Moldova' ,
		'Monaco' => 'Monaco' ,
		'Mongolia' => 'Mongolia' ,
		'Montserrat' => 'Montserrat' ,
		'Morocco' => 'Morocco' ,
		'Mozambique' => 'Mozambique' ,
		'Myanmar (Burma)' => 'Myanmar (Burma)' ,
		'Namibia' => 'Namibia' ,
		'Nauru' => 'Nauru' ,
		'Nepal' => 'Nepal' ,
		'Netherlands' => 'Netherlands' ,
		'Netherlands Antilles' => 'Netherlands Antilles' ,
		'New Caledonia' => 'New Caledonia' ,
		'New Zealand' => 'New Zealand' ,
		'Nicaragua' => 'Nicaragua' ,
		'Niger' => 'Niger' ,
		'Nigeria' => 'Nigeria' ,
		'Niue' => 'Niue' ,
		'Norfolk Island' => 'Norfolk Island' ,
		'North Korea' => 'North Korea' ,
		'Northern Mariana Islands' => 'Northern Mariana Islands' ,
		'Norway' => 'Norway' ,
		'Oman' => 'Oman' ,
		'Pakistan' => 'Pakistan' ,
		'Palau' => 'Palau' ,
		'Panama' => 'Panama' ,
		'Papua New Guinea' => 'Papua New Guinea' ,
		'Paraguay' => 'Paraguay' ,
		'Peru' => 'Peru' ,
		'Philippines' => 'Philippines' ,
		'Pitcairn' => 'Pitcairn' ,
		'Poland' => 'Poland' ,
		'Portugal' => 'Portugal' ,
		'Puerto Rico' => 'Puerto Rico' ,
		'Qatar' => 'Qatar' ,
		'Reunion' => 'Reunion' ,
		'Romania' => 'Romania' ,
		'Russia' => 'Russia' ,
		'Rwanda' => 'Rwanda' ,
		'Saint Helena' => 'Saint Helena' ,
		'Saint Kitts And Nevis' => 'Saint Kitts And Nevis' ,
		'Saint Lucia' => 'Saint Lucia' ,
		'Saint Pierre And Miquelon' => 'Saint Pierre And Miquelon' ,
		'Saint Vincent And The Grenadines' => 'Saint Vincent And The Grenadines' ,
		'San Marino' => 'San Marino' ,
		'Sao Tome And Principe' => 'Sao Tome And Principe' ,
		'Saudi Arabia' => 'Saudi Arabia' ,
		'Senegal' => 'Senegal' ,
		'Seychelles' => 'Seychelles' ,
		'Sierra Leone' => 'Sierra Leone' ,
		'Singapore' => 'Singapore' ,
		'Slovak Republic' => 'Slovak Republic' ,
		'Slovenia' => 'Slovenia' ,
		'Solomon Islands' => 'Solomon Islands' ,
		'Somalia' => 'Somalia' ,
		'South Africa' => 'South Africa' ,
		'South Georgia And South Sandwich Islands' => 'South Georgia And South Sandwich Islands' ,
		'South Korea' => 'South Korea' ,
		'Spain' => 'Spain' ,
		'Sri Lanka' => 'Sri Lanka' ,
		'Sudan' => 'Sudan' ,
		'Suriname' => 'Suriname' ,
		'Svalbard And Jan Mayen' => 'Svalbard And Jan Mayen' ,
		'Swaziland' => 'Swaziland' ,
		'Sweden' => 'Sweden' ,
		'Switzerland' => 'Switzerland' ,
		'Syria' => 'Syria' ,
		'Taiwan' => 'Taiwan' ,
		'Tajikistan' => 'Tajikistan' ,
		'Tanzania' => 'Tanzania' ,
		'Thailand' => 'Thailand' ,
		'Togo' => 'Togo' ,
		'Tokelau' => 'Tokelau' ,
		'Tonga' => 'Tonga' ,
		'Trinidad And Tobago' => 'Trinidad And Tobago' ,
		'Tunisia' => 'Tunisia' ,
		'Turkey' => 'Turkey' ,
		'Turkmenistan' => 'Turkmenistan' ,
		'Turks And Caicos Islands' => 'Turks And Caicos Islands' ,
		'Tuvalu' => 'Tuvalu' ,
		'Uganda' => 'Uganda' ,
		'Ukraine' => 'Ukraine' ,
		'United Arab Emirates' => 'United Arab Emirates' ,
		'United Kingdom' => 'United Kingdom' ,
		'United States' => 'United States' ,
		'United States Minor Outlying Islands' => 'United States Minor Outlying Islands' ,
		'Uruguay' => 'Uruguay' ,
		'Uzbekistan' => 'Uzbekistan' ,
		'Vanuatu' => 'Vanuatu' ,
		'Vatican City (Holy See)' => 'Vatican City (Holy See)' ,
		'Venezuela' => 'Venezuela' ,
		'Vietnam' => 'Vietnam' ,
		'Virgin Islands (British)' => 'Virgin Islands (British)' ,
		'Virgin Islands (US)' => 'Virgin Islands (US)' ,
		'Wallis And Futuna Islands' => 'Wallis And Futuna Islands' ,
		'Western Sahara' => 'Western Sahara' ,
		'Western Samoa' => 'Western Samoa' ,
		'Yemen' => 'Yemen' ,
		'Yugoslavia' => 'Yugoslavia' ,
		'Zambia' => 'Zambia' ,
		'Zimbabwe' => 'Zimbabwe'
	);
}
// ===============================================================================
// Security Question etc for access restricted module or user
// ===============================================================================
function securityArr() {
	//	User account status whehter activated or deactivated
	return array(
		'What was your childhood nickname?' => '1',
		'What is the name of your favorite childhood friend?' => '2',
		'What was the last name of your third grade teacher?' => '3',
		'In what city or town was your first job?' => '4',
		'What is you favourite place?' => '5', 
	);
}
// ===============================================================================
// Print_r Whole array 
// ===============================================================================
function pr($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
// ===============================================================================
// Month nama in English
// ===============================================================================
function monthName(){
	$monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
	return $monthNames;
}
// ===============================================================================
// Get User Ip Address
// ===============================================================================
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}
// ===============================================================================
// Get User Browser (Chorme, Mozila, Sfari etc)?
// ===============================================================================
function getBrowser(){

	$agent = $_SERVER['HTTP_USER_AGENT'];
	$name = 'NA';

	if (preg_match('/MSIE/i', $agent) && !preg_match('/Opera/i', $agent)) {
	    $name = 'Internet Explorer';
	} elseif (preg_match('/Firefox/i', $agent)) {
	    $name = 'Mozilla Firefox';
	} elseif (preg_match('/Chrome/i', $agent)) {
	    $name = 'Google Chrome';
	} elseif (preg_match('/Safari/i', $agent)) {
	    $name = 'Apple Safari';
	} elseif (preg_match('/Opera/i', $agent)) {
	    $name = 'Opera';
	} elseif (preg_match('/Netscape/i', $agent)) {
	    $name = 'Netscape';
	}

	return $name;
}
// ===============================================================================
// Generate Random Number only Integer
// By taking a value in int to check how much length.
// ===============================================================================
function randomNumber($x){
	//$x = 5; // Amount of digits
    $min = pow(10,$x);
    $max = pow(10,$x+1)-1;
    return rand($min, $max);
}
// ===============================================================================
// Generate Random Number with String like (ab12cd3e9g).
// By taking a value in int to check how much length.
// ===============================================================================
function randomMixture($random_string_length){
	$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $string = '';
    $max = strlen($characters) - 1;
    //$random_string_length = 8;
    for ($i = 0; $i < $random_string_length; $i++) {
      $string .= $characters[mt_rand(0, $max)];
    }
    return $string;
    /*$pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));

    for($i=0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;*/
}
?>