<?php
	header("Content-Type:text/html;Charset=utf8");
	date_default_timezone_set('PRC');
	
	
	function geter($url){    
	 $ip_long = array(
			   array('607649792', '608174079'), //36.56.0.0-36.63.255.255
			   array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
			   array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
			   array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
			   array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
			   array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
			   array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
			   array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
			   array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
			   array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
		   );
		   $rand_key = mt_rand(0, 9);
		   $ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
		   $headers['CLIENT-IP'] = $ip; 
		   $headers['X-FORWARDED-FOR'] = $ip; 
		   $headerArr = array(); 
		   foreach( $headers as $n => $v ) { 
			   $headerArr[] = $n .':' . $v;  
		 }
		   
		$curl = curl_init(); // 启动一个CURL会话      
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址                  
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查      
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; OPPO R11 Plus Build/HEXCNFN5902606141S) AppleWebKit/537.36 (KHTML, like Gecko) Baiduspider/55.0.2883.87 Safari/537.36 en-zh'); // 模拟用户使用的浏览器      
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转      
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer           	
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环      
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容      
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回           
		 $tmpInfo = curl_exec($curl); // 执行操作      
		if (curl_errno($curl)) {      
		   echo 'Errno'.curl_error($curl);      
		}      
		curl_close($curl); // 关键CURL会话      
		return $tmpInfo; // 关键CURL会话          
	} 
	
	
	
	$file='dm.txt';
	if(file_exists($file)){
		file_put_contents('dm.txt','', LOCK_EX);
	}
	
	$dm=file_get_contents('dm.txt');
	
	
	for($i=1;$i<=3;$i++){
		$a=geter("https://img.xjh.me/random_img.php");
		
		$c=mb_strpos($a,'src="//img.xjh.me/desktop/img/');
		$k=mb_strpos($a,'" /><script');
		$e=mb_strlen($a);
		$b=mb_substr($a,$c+30,$k-$e);
		
		if(strstr($dm,$b) == false){
			file_put_contents('dm.txt',$b."\n", FILE_APPEND | LOCK_EX);	
			$url='http://img.xjh.me/desktop/img/'.$b;
			$dst = geter($url);
			$dst = imagecreatefromstring($dst);
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
			imagepng($dst,$b);
			imagedestroy($dst);
			echo '图'.$i.'采集成功！</br>';
		}
		else{
			echo '图'.$i.'已采集过！</br>';
		}
	}
?>
