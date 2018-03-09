<?php

define("AGENT_COMMON",      "COM");
define("AGENT_UCWEB",       "UCW");
define("AGENT_MOZILLA",     "MOZ");
define("AGENT_MSIE",        "MIE");
define("AGENT_PPC",         "PPC");
define("AGENT_EMAIL",       "EMI");
define("AGENT_WAP_GPRS",        "WAP");
define("AGENT_WAP_DEBUG",       "DBG");
define("AGENT_SEARCH_GOOGLE",       "GOO");
define("AGENT_SEARCH_BAIDU",        "BAI");
define("AGENT_SEARCH_YAHOO",        "YAH");
define("AGENT_SEARCH_SOHU",         "SOH");
define("AGENT_SEARCH_SINA",         "SIN");
define("AGENT_SEARCH_OPERA",        "OPE");

//we only have agent_type ucweb,wap,commen web browser
define("AGENT_TYPE",        0);
define("AGENT_DETAIL",      1); 
    
class CGetAgent
{
   public static function isFromWap()
    {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER))
        {
            $agent_trans = array(
                'arima'         => '[mob]',
                'cect'          => '[mob]',
                'compal'        => '[mob]',
                'ctl'           => '[mob]',
                'lg'            => '[mob]',
                'nec'           => '[mob]',
                'tcl'           => '[mob]',
                'alcatel'       => '[mob]',
                'ericsson'      => '[mob]',
                'bird'          => '[mob]',
                'daxian'        => '[mob]',
                'dbtel'         => '[mob]',
                'eastcom'       => '[mob]',
                'pantech'       => '[mob]',
                'dopod'         => '[mob]',
                'philips'       => '[mob]',
                'kanka'         => '[mob]',
                'haier'         => '[mob]',
                'kejian'        => '[mob]',
                'lenovo'        => '[mob]',
                'benq'          => '[mob]',
                'mot'           => '[mob]',
                'soutec'        => '[mob]',
                'nokia'         => '[mob]',
                'sagem'         => '[mob]',
                'sgh'           => '[mob]',
                'sed'           => '[mob]',
                'capitel'       => '[mob]',
                'panasonic'     => '[mob]',
                'sonyericsson'  => '[mob]',
                'sie'           => '[mob]',
                'sharp'         => '[mob]',
                'amoi'          => '[mob]',
                'emol'          => '[mob]',
                'zte'           => '[mob]',
                'wap'           => '[mob]',
                'cldc'          => '[mob]',
                'midp'          => '[mob]',
                'gecko'         => '[com]',
                'msie'          => '[com]',
                'googlebot'     => '[com]',
                'baiduspider'   => '[com]',
                'slurp'         => '[com]',
                'sohu agent'    => '[com]',
                'iaskspider'    => '[com]',
                'opera'         => '[com]'
            );
            $sHttpUserAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
            if (preg_match('/(\w+).*/', $sHttpUserAgent, $matches))
            {
                $sHttpUserAgent = $matches[1];
            }
            $sAgentString = strtr($sHttpUserAgent, $agent_trans);
            if (stristr($sAgentString, '[mob]'))
            {
                return true;
            }
            if (strstr($_SERVER['HTTP_USER_AGENT'], 'MIDP-2.0'))
            { 
                return true;
            }
            return false;
        }
        if (array_key_exists('HTTP_ACCEPT', $_SERVER))
        {
            $accept_trans = array(
                'text/vnd.wap.wml'              => '[mob]',
                'application/vnd.wap.xhtml+xml' => '[mob]'
            );
            $sHttpAccept = strtolower($_SERVER['HTTP_ACCEPT']);
            $sAcceptString = strtr($sHttpAccept, $accept_trans);
            if (strstr($sAcceptString, '[mob]'))
            {
                return true;
            }
            return false;
        }
        return false;
    }

   public static function isFromUcweb()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $mask = ip2long('255.255.255.0');
        $checkip = ip2long('220.181.31.0');
        if (($ip & $mask) == $checkip)
        {
            return true;
        }
        return false;
    }

   public static function GetAgent($type = AGENT_TYPE)
    {
        $browser = AGENT_COMMON;
        $str_agent = $_SERVER['HTTP_USER_AGENT'];
        SF::log("HTTP_USER_AGENT {$str_agent}");
        
        if ($type == AGENT_TYPE)
        {
            if (strstr($str_agent, 'UCWEB') || strstr($str_agent, 'Smartphone') || 
                (strstr($str_agent, 'PPC') && strstr($str_agent, 'MSIE')))
            {
                $browser = AGENT_UCWEB;
            }
            else if (self::isFromUcweb() && $str_agent == '')
            {
                $browser = AGENT_UCWEB;
            }
            else if (self::IsFromWap())
            {
                $browser = AGENT_WAP_GPRS;
            }
            else
            {
                $browser = AGENT_COMMON;
            }
        }
        if ($type == AGENT_DETAIL)
        {
            if (strstr($str_agent, 'Gecko'))
            {
                $browser = AGENT_MOZILLA;
            }
            else if (strstr($str_agent, 'MSIE'))
            {
                $browser = AGENT_MSIE;
            }
            else if (strstr($str_agent, 'Googlebot'))
            {
                $browser = AGENT_SEARCH_GOOGLE;
            }
            else if (strstr($str_agent, 'Baiduspider'))
            {
                $browser = AGENT_SEARCH_BAIDU;
            }
            else if (strstr($str_agent, 'Slurp'))
            {
                $browser = AGENT_SEARCH_YAHOO;
            }
            else if (strstr($str_agent, 'sohu agent'))
            {
                $browser = AGENT_SEARCH_SOHU;
            }
            else if (strstr($str_agent, 'iaskspider'))
            {
                $browser = AGENT_SEARCH_SINA;
            }
            else if (strstr($str_agent, 'Opera'))
            {
                $browser = AGENT_SEARCH_OPERA;
            }
            else if (strstr($str_agent, 'PPC')|| strstr($str_agent, 'Smartphone'))
            {
                $browser = AGENT_PPC;
            }
            else if (strstr($str_agent,'UCWEB'))
            {
                $browser = AGENT_UCWEB;
            }
            else if (self::IsFromWap())
            {
                $browser = AGENT_WAP_GPRS;
            }
        }
        
        SF::log("{$browser}===========================");
        return $browser;
    }
}
?>