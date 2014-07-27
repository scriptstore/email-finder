<?php
error_reporting(0);
//////////////////////////////////////////////////////////////////////////////////////////
// CLASS NAME      :  EMAIL PARSER                                                      //
// FILE NAME       :  EMAIL_PARSER.CLASS.PHP                                            //
// LANGUAGE        :  PHP                                                               //
// LANGUAGE VERSION: 5.0                                                                //
// AUTHOR          :  Mr. ali abbaspor                                                  //
// EMAIL           :  4vip.abbaspor@gmail.com                                           //
// VERSION         :  1.0                                                               //
// DATE            :  03/05/2008                                                        //
// LICENSE         :  GNU/GPL                                                           //
//////////////////////////////////////////////////////////////////////////////////////////
// What the class does:                                                                 //
//////////////////////////////////////////////////////////////////////////////////////////
// * Parse String Email Addresses from sequential web pages 			                //
//   * store parsed emails to a file or screen                                          //
//////////////////////////////////////////////////////////////////////////////////////////


class parser {

	// Privates values. 
	private $_pass;							// Password for processing section.
	private $parseform ;
	
	var $password; 							// Password for checking.
    var $url_prefix;						// URL prefix of website before ID s. including http://
    var $url_suffix	        = "";			// URL suffix of website after ID s.
    var $first_id			= 0 ;			// Starting point of IDs you want to parse.
    var $last_id			= 0 ;			// Last point of IDs you want to parse.
    var $filter				= "";			// Filter Email Addresses ( Email1; Email2 ; ... )
    var $write_to_file		= "N";			// Write results to file? (N / Y)
	var $file_prefix		= "result";		// Prefix of File name to store result.
	var $show_id			= "Y";			// Include # before results. (N / Y)
	var $show_pid			= "Y";			// Include Page ID before results. (N / Y)
    var $errmsg	    		= "";
    var $error	    		= FALSE;

// find and save emails
function crawl($url, $depth = 5) {
   // if ($depth == 0) {
        // analyze email files and download
   //     MailDownloader();
   // }
   
    if($depth > 0) {
        $html = $this->getHTML($url,2);

        preg_match_all('~<a.*?href="(.*?)".*?>~', $html, $matcheurl);

    // parse emails
    if (!empty($html)) {
        $res = preg_match_all("/[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}/i", $html, $matchemail );

    if ($res) {
        foreach(array_unique($matchemail[0]) as $email) {
           // if (filter_var($email, FILTER_VALIDATE_EMAIL))
           file_put_contents('results.txt', $email.PHP_EOL, FILE_APPEND);
    }
}}
  
  unset ($matchemail);

        foreach($matcheurl[1] as $newurl) {
            if (filter_var($newurl, FILTER_VALIDATE_URL))
            {
                $this->crawl($newurl, $depth - 1);        
                         
            }
        }
    }
}

// get page source with curl
function getHTML($url,$timeout)
{
       $ch = curl_init($url); // initialize curl with given url
       curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
       curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
       return @curl_exec($ch);
}
             

function parseEmails() {
	echo "<title>Email Parser v1.0 By Mr. ali abbaspor - 4vip.abbaspor@gmail.com</title>";
		$this->parseform = $this->unhtml($_REQUEST['parseform']);
		$this->password = $this->unhtml($_REQUEST['password']);
		$this->url_prefix = $this->unhtml($_REQUEST['prefixURL']);
		
	
	if ($this->parseform=="YES") {
	
		if (!isset($this->password)) {
			$this->errormsg = "No Password Entered!";
			$this->error = TRUE;
		} 
		else  {
		if (!$this->checkPassword($this->password)) {
			$this->errormsg = "Invalid Password";
			$this->error = TRUE;		
		}
		}
		if (strlen($this->url_prefix)<7) {
			$this->errormsg = "No URL Prefix Selected!";
			$this->error = TRUE;
		} 
		else { 
		if (strtoupper(substr($this->url_prefix,0,7)) != "HTTP://") {
			$this->url_prefix = "http://" . $this->url_prefix;	
		}
		}
		
        echo "<br /><table width='90%' border='0' align='center' style='border:1px dashed #999999;'>
			<tr><td valign='top' class='resulti'><div align='center'>Result</div></td></tr><tr><td valign='top' class='fonti'>";
		echo "Please wait...<br />";
		echo "Time Started: " . date("Y m d H:i:s") ."<br />";
		echo "<hr /><br />";
		
        if ($this->error == FALSE) {
		      $this->crawl($this->url_prefix, 10);
              echo  "<br/>File Created: <a href='results.txt'>Download emails file</a><br />";
		} else {	
		echo "<br /><table width='90%' border='0' align='center' style='border:1px dashed #999999;'>
		<tr><td valign='top' class='error'><div align='center'>Error</div></td></tr><tr><td valign='top' class='fonti'>";
		echo $this->errormsg;
		echo "</td></tr></table>";			
		}
	}
}

function unhtml($text)
		{
		
			return str_replace( array('<', '>', '"','$'),array('&lt;', '&gt;', '&quot;',''), $text);
		}





	function showForm() {
		
		echo "<title>Email Parser v1.0 By Mr. ali abbaspor - 4vip.abbaspor@gmail.com</title>";
		
	$text = "<style type='text/css'>
				<!--
				.fonti {font-family: tahoma, verdana; font-size: 12px; }
				.meddi {font-size: 12px}
				.grey {font-family: tahoma, verdana; font-size: 12px; color: #666666; }
				.error {font-family: tahoma, verdana; font-size: 12px; color: #ffffff; background-color:#990000 ; }
				.resulti {font-family: tahoma, verdana; font-size: 12px; color: #ffffff; background-color:#006699 ; }
				-->
			</style><form method='post' action='" . $_SERVER['PHP_SELF'] . "'>
			<table width='90%' border='0' align='center' style='border:1px dashed #999999;'>
			<tr>
			  <td colspan='2'></tr>
			<tr>
			  <td colspan='2'><a href='professional.pdf'><img src='theme/img/email_parser.png' alt='Email finder' width='468' height='60' border='0' /></a>  </tr>
			<tr>
			  <td bgcolor='#FFCC00'><span class='fonti'>Password:
			  </span>
			  <td bgcolor='#FFCC00'><input name='password' type='password' id='pass' value='" . $_REQUEST['password'] . "' /></tr>
			<tr>
			  <td colspan='2'><span class='meddi'></span></tr>
			<tr>
			  <td colspan='2'><span class='fonti'>Please enter  URL to parse (including http://):
			  </span></tr>
			  <tr>
				<td><span class='meddi'></span></td>
				<td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
				  <tr>
					<td class='fonti'><input type='text' name='prefixURL' size='65' value='" . $_REQUEST['prefixURL'] . "'/></td>
					
				  </tr>
				  
				  <tr>
					<td class='grey'>http://www.your-domain.com/</td>
					
				  </tr>
				</table></td>
			  </tr>
			  
			  <tr>
				<td colspan='2'><span class='meddi'></span></td>
			  </tr>
			  <tr>
				<td colspan='2'><div align='center' class='fonti'><input type='submit' value='Parse Emails' /> 
				  <input type='hidden' name='parseform' value='YES' /> 
				  <a href='" . $_SERVER['PHP_SELF'] . "'>Refresh</a></div></td>
			  </tr>
			  <tr>
				<td></td>
				<td></td>
			  </tr>
			</table>
			
			</form>";
# Please keep this copyright intact.
$text .=  "<div align='center' class='grey'>EMAIL FINDER v1.0 - By <a href='mailto:4vip.abbaspor@gmail.com' >Ali abbaspor</a></div>";
	print $text;
	}


	function setPassword($pass="password") {
		if (isset($pass)) {
			$this->_pass = md5("ep" . $pass);
		}
	}

	private function checkPassword ($password="") {
		if (isset($password)) {						
			if ($this->_pass == md5("ep" . $password)) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
				return FALSE;
		}
	}	

}

?>
