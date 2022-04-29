<?php
	//Visist http://http://esms.vn/SMSApi/ApiSendSMSNormal for more information about API
	//� 2013 esms.vn
	//Website: http://esms.vn/
	//Hotline: 0901.888.484      
   
	//Huong dan chi tiet cach su dung API: http://esms.vn/blog/3-buoc-de-co-the-gui-tin-nhan-tu-website-ung-dung-cua-ban-bang-sms-api-cua-esmsvn
	//De lay Key cac ban dang nhap eSMS.vn v� vao quan Quan li API 
  $APIKey="43009D2FC640A4444734FEE529AC01";
	$SecretKey="E97CE9133C9DB6F9D0E4C4E0DB60AB";
	$YourPhone="0707327857";
	$Content="Mã xác thực sdt của bạn là: ";
	$SendContent=urlencode($Content);
	$data="http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=$YourPhone&ApiKey=$APIKey&SecretKey=$SecretKey&Content=$SendContent&SmsType=8";
	$curl = curl_init($data); 
	curl_setopt($curl, CURLOPT_FAILONERROR, true); 
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	$result = curl_exec($curl); 
	$obj = json_decode($result,true);
  if($obj['CodeResult']==100)
  {
      print "<br>";
      print "CodeResult:".$obj['CodeResult'];
      print "<br>";
      print "CountRegenerate:".$obj['CountRegenerate'];
      print "<br>";     
      print "SMSID:".$obj['SMSID'];
      print "<br>";
  }
  else
  {
      print "ErrorMessage:".$obj['ErrorMessage'];
  }
?>