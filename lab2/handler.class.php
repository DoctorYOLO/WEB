<?php

class handler
{
     const SLEEP = 1;
     const COOKIE_FILE   =   "cookie.dat";
     const AUTH_URL      =   "http://super-warez.net/";
	 const COMMENTS_URL  =   "http://super-warez.net/engine/ajax/addcomments.php?";
	 const MAIN_URL		 =   "http://super-warez.net/favorites/";
	 const FAILED_DOWNLOAD_SLEEP = 2;
	 const ATTEMPTS = 5;

    public function __construct($login, $pass)
    {
        $this->login( self::AUTH_URL, $login, $pass );
    }
	
	private function initCurl($url, $ref, $postfields)
	{
		$ch = curl_init();

        if( strtolower((substr($url, 0, 5)) == 'https') )
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $ref);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_COOKIEFILE, self::COOKIE_FILE);
        curl_setopt($ch, CURLOPT_COOKIEJAR, self::COOKIE_FILE);

        $result=curl_exec($ch);
        curl_close($ch);
        
        return $result;
	}
	
	private function login($url, $login, $password)
	{
		return $this->initCurl($url, $url, "login=submit"."login_name=".$login."&login_password=".$password);
	}
	
	public function loadMainPage()
	{
		sleep(self::SLEEP);

        $data = file_get_contents(self::MAIN_URL);

        if (!$data)
        {
            echo "Error load page " . MAIN_URL . ". New attempt.\r\n";

            for ($i = 0; $i < self::ATTEMPTS; $i++)
            {
                sleep(self::FAILED_DOWNLOAD_SLEEP);
                $data = file_get_contents(self::MAIN_URL);
                if ($data)
                {
                    return $data;
                }
            }
        }
        
        return $data;
	}
	
	public function getFirstNewsPage()
	{
		$page = $this->loadMainPage();
		if ($page)
		{
			$html = new DOMDocument();
			$html->preserveWhiteSpace = false;
			if ($html->loadHTML($page))
			{
				$xPathExt = "//*[@id=content]/table/tbody/tr/td/div/div/div[1]/a/h2";
				$xPath = new DOMXPath($html);
				$nodelist = $xPath->query($xPathExt);
				
				foreach($nodelist as $n)
				{
					return self::MAIN_URL . $n->getAttribute('href');
				}
			}
		}
        
        echo "mainpage";
		
		return "";
	}
	
	public function sendMessage($url, $text)
	{

	}
}
