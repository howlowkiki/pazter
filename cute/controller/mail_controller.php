<?
session_start();
	require($_SERVER["DOCUMENT_ROOT"].'/controller/db_config.php');
//	require($_SERVER["DOCUMENT_ROOT"].'/pazter/controller/db_config.php');

class mailer{
	
	public function send_mail($to="",$subject="",$title="親愛的會員您好：",$content=""){
				
		if( $to=="" || $subject=="" || $content=="" ){
			return false;
		}else{			
			$content=stripslashes($content);
			$subject="=?UTF-8?B?". base64_encode($subject)."?=";
												
			$message =	'
			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>PAZTER.COM</title>
			</head>
			<body bgcolor="#F7F7F7">
			<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F7F7F7">
			  <tr>
			    <td><table width="700" height="120" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
			      <tr>
			        <td height="50" background="http://pazter.com/images/header_bg.gif">&nbsp;			          </td>
			        <td height="50" background="http://pazter.com/images/header_bg.gif"><a href="http://pazter.com"><img src="http://pazter.com/images/logo.png" alt="pazter_home" border="0" /></a></td>
			      </tr>
			      <tr>
			        <td width="44" height="37" bgcolor="#000000">&nbsp;</td>
			        <td width="656" bgcolor="#000000"><span style="font-size:12px; color:#CCCCCC;">'.$title.'</span></td>
			      </tr>
			      <tr>
			        <td colspan="2">'.$content.'</td>
		          </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td><div align="center"><img src="http://pazter.com/images/email_footer.jpg" border="0"></div></td>
			  </tr>
			</table>
			</body>
			</html>
			';

			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type:text/html; charset=utf-8\r\n";
			$headers .= "From:".mb_encode_mimeheader('PAZTER SERVICE', 'UTF-8')." < service@pazter.com >\r\n"; //optional headerfields

			if(mail($to, $subject, $message, $headers)) return true;
			
		}	
	}

	public function get_preview_mail($to="",$subject="",$title="親愛的會員您好：",$content=""){
				
		if( $to=="" || $subject=="" || $content=="" ){
			return false;
		}else{
											
			$message =	'
			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>PAZTER.COM</title>
			</head>
			<body bgcolor="#F7F7F7">
			<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F7F7F7">
			  <tr>
			    <td><table width="700" height="120" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
			      <tr>
			        <td height="50" background="http://pazter.com/images/header_bg.gif">&nbsp;			          </td>
			        <td height="50" background="http://pazter.com/images/header_bg.gif"><a href="http://pazter.com"><img src="http://pazter.com/images/logo.png" alt="pazter_home" border="0" /></a></td>
			      </tr>
			      <tr>
			        <td width="44" height="37" bgcolor="#000000">&nbsp;</td>
			        <td width="656" bgcolor="#000000"><span style="font-size:12px; color:#CCCCCC;">'.$title.'</span></td>
			      </tr>
			      <tr>
			        <td colspan="2">'.$content.'</td>
		          </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td><div align="center"><img src="http://pazter.com/images/email_footer.jpg" border="0"></div></td>
			  </tr>
			</table>
			</body>
			</html>
			';
			return $message;
			
		}		
	}
	
	public function get_user_mail_list(){
		$sql="SELECT user_email FROM news_list WHERE status='1'";
//		$sql="SELECT user_email FROM user_test WHERE user_confirm='1'";
		$rs=mysql_query($sql);		
		
		if(mysql_num_rows($rs)>0){
			return $rs;
		}else{
			return false;
		}
	}
	
	public function send_news_mail($subject="",$title="親愛的會員您好：",$content=""){
				
		if($content=="" ){
			return false;
		}else{
			
			$subject="=?UTF-8?B?". base64_encode($subject)."?=";
			$content=stripslashes($content);
			$message =	'
			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>PAZTER.COM</title>
			</head>
			<body bgcolor="#F7F7F7">
			<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F7F7F7">
			  <tr>
			    <td><table width="700" height="120" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
			      <tr>
			        <td height="50" background="http://pazter.com/images/header_bg.gif">&nbsp;			          </td>
			        <td height="50" background="http://pazter.com/images/header_bg.gif"><a href="http://pazter.com"><img src="http://pazter.com/images/logo.png" alt="pazter_home" border="0" /></a></td>
			      </tr>
			      <tr>
			        <td width="44" height="37" bgcolor="#000000">&nbsp;</td>
			        <td width="656" bgcolor="#000000"><span style="font-size:12px; color:#CCCCCC;">'.$title.'</span></td>
			      </tr>
			      <tr>
			        <td colspan="2">'.$content.'</td>
		          </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td><div align="center"><img src="http://pazter.com/images/email_footer.jpg" border="0"></div></td>
			  </tr>
			</table>
			</body>
			</html>
			';
			
			//	SEND 20 MAIL EVERY 30 MINS
			$rs_list=$this->get_user_mail_list();
			
			while($mail_list=mysqli_fetch_array($rs_list)){
				
				$num_entries++;
				if($num_entries==1){	
					$receiver=$mail_list['user_email'];
				}else{
					$receiver.=",".$mail_list['user_email'];
				}
				if($num_entries==20){
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type:text/html; charset=utf-8\r\n";
					$headers .= "From:".mb_encode_mimeheader('PAZTER SERVICE', 'UTF-8')." <news@pazter.com >\r\n"; //optional headerfields
					$headers .= "BCC:".$receiver."\r\n" .'X-Mailer: PHP/' . phpversion();
					mail('news@pazter.com', $subject, $message, $headers);
					sleep(1);
					$num_entries=0;
					$receiver="";
				}
							 
			}
			
			if($receiver!=""){
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type:text/html; charset=utf-8\r\n";
				$headers .= "From:".mb_encode_mimeheader('PAZTER SERVICE', 'UTF-8')." <news@pazter.com >\r\n"; //optional headerfields
				$headers .= "BCC:".$receiver."\r\n" .'X-Mailer: PHP/' . phpversion();
				if(mail('news@pazter.com', $subject, $message, $headers)) return true;
			}else{
				return true;
			}
			
		}	
	}
	
}
//$mailer=new mailer();
//$mailer->send_news_mail();
?>