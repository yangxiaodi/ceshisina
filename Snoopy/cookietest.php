<?php
//echo var_dump($_SERVER);
header('Content-Type:text/html; charset=UTF-8');
include("Snoopy.class.php");
include("phpQuery/phpQuery.php");
/**
 * 已生效
 */
function init(){
	$snoopy = new Snoopy();
	$snoopy->agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5 FirePHP/0.2.1";//这项是浏览器信息，前面你用什么浏览器查看cookie，就用那个浏览器的信息(ps:$_SERVER可以查看到浏览器的信息)
	$snoopy->referer = "http://weibo.com/1730813174/profile?rightmod=1&wvr=6&mod=personnumber&is_all=1kkk";
	$snoopy->expandlinks = true;
	$snoopy->rawheaders["COOKIE"]="_s_tentry=login.sina.com.cn;ALF=1503470865;Apache=8794896837789.565.1471934869325;Hm_lvt_cdc2220e7553b2a2cd949e1765e21edc=1466418850,1466472305;SCF=AjbVfK4Xdw2XgTYyQGOIRtCsFHf_smxmyXZval-aDwjopr5G9Kf1oBHIgHzo2CLitKOAyWk89sLVq2bhxG6qfuM.;SINAGLOBAL=8062468627467.752.1458205942141;SSOLoginState=1471934866;SUB=_2A256v4HCDeTxGedJ6FIZ8S3NzDiIHXVZzPQKrDV8PUNbmtBeLWP5kW8BO1NKNFj4yJ7rmJPyZG4XFjRvPQ..;SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WhOaEMKSk.Lxxebbn5-7OSC5JpX5KzhUgL.Fo2Ne05ReKepS0B2dJLoIpqLxKBLB.BLBoeLxKBLB.BLBoSNI0W9;SUHB=0jBZYyMZ2L3eDx;ULV=1471934869335:139:20:4:8794896837789.565.1471934869325:1471924462910;un=jiachunhui1988@sina.cn;UOR=news.ifeng.com,widget.weibo.com,login.sina.com.cn;wvr=6;YF-Ugrow-G0=57484c7c1ded49566c905773d5d00f82;YF-V5-G0=b188043973f8ae1849ba6cd9ae007290;";
	return $snoopy;
}


$page=3;
$href="http://weibo.com/1730813174/profile?profile_ftype=1&is_all=1";
$domain="100505";
$id="1005051730813174";
$href2="http://weibo.com/p/aj/v6/mblog/mbloglist?ajwvr=6&domain=".$domain."&is_all=1&profile_ftype=1&pagebar=0&id=".$id;
$total=[];
for($i=1;$i<=$page;$i++){
   if($i===1){
   	$url=$href."#_0";
   }else{
   	$url=$href."&page=".$i;
   }
   $html=getHtml($url);
   $arr=dealmain($html);
   $total=array_merge($total,$arr);
   for($j=0;$j<2;$j++){
   	$url=$href2."&page=".$i."&pre_page=".$i."&pagebar=".$j;
   	$html=getHtml($url);
   	$arr=dealjs($html);
   	$total=array_merge($total,$arr);
   }
}
var_dump($total);
/*
$snoopy->fetch("http://weibo.com/1730813174/profile?is_search=0&visible=0&is_all=1&is_tag=0&profile_ftype=1&page=3#feedtop");
              //http://weibo.com/1730813174/profile?is_search=0&visible=0&is_all=1&is_tag=0&profile_ftype=1&page=2#feedtop
              //http://weibo.com/1730813174/profile?profile_ftype=1&is_all=1#_0
              //http://weibo.com/p/aj/v6/mblog/mbloglist?ajwvr=6&domain=100505&is_all=1&profile_ftype=1&page=2&pagebar=0&pl_name=Pl_Official_MyProfileFeed__25&id=1005051730813174&script_uri=/1730813174/profile&feed_type=0&pre_page=2
              //http://weibo.com/p/aj/v6/mblog/mbloglist?ajwvr=6&domain=100505&is_all=1&profile_ftype=1&page=2&pagebar=0&id=1005051730813174&pre_page=2&domain_op=100505
$snoopy->fetch("http://weibo.com/p/aj/v6/mblog/mbloglist?ajwvr=6&domain=100505&profile_ftype=1&is_all=1&pagebar=1&pl_name=Pl_Official_MyProfileFeed__25&id=1005051730813174&script_uri=/1730813174/profile&feed_type=0&page=2&pre_page=2");
*/
// http://weibo.com/p/aj/v6/mblog/mbloglist?ajwvr=6&domain=100505&profile_ftype=1&is_all=1&pagebar=1&pl_name=Pl_Official_MyProfileFeed__25&id=1005051730813174&script_uri=/1730813174/profile&feed_type=0&page=1&pre_page=1
// $snoopy->fetch("http://weibo.com/aj/mblog/fsearch?ajwvr=6&pre_page=1&page=1&pagebar=3");
//              http://weibo.com/aj/mblog/fsearch?ajwvr=6&pre_page=1&page=1&end_id=4011665382921949&min_id=4011662849639487&pagebar=0&__rnd=1471939063454
//              http://weibo.com/aj/mblog/fsearch?ajwvr=6&pre_page=1&page=1&end_id=4011665382921949&min_id=4011660681545973&pagebar=1&__rnd=1471939212660
// $snoopy->fetch("http://weibo.com/1730813174/profile?profile_ftype=1&page=12&is_all=1#feedtop");
// $snoopy->fetch("http://weibo.com/p/aj/v6/mblog/mbloglist?ajwvr=6&domain=100505&profile_ftype=1&page=12&is_all=1&pagebar=1&pl_name=Pl_Official_MyProfileFeed__25&id=1005051730813174&script_uri=/1730813174/profile&feed_type=0&pre_page=12&domain_op=100505&__rnd=1469603524435");
// http://weibo.com/1730813174/profile?is_search=0&visible=0&is_all=1&is_tag=0&profile_ftype=1&page=2#feedtop
// http://weibo.com/p/aj/v6/mblog/mbloglist?ajwvr=6&domain=100505&profile_ftype=1&page=12&is_all=1&pagebar=1&pl_name=Pl_Official_MyProfileFeed__25&id=1005051730813174&script_uri=/1730813174/profile&feed_type=0&pre_page=12&domain_op=100505&__rnd=1469603524435
// http://weibo.com/1730813174/profile?profile_ftype=1&page=12&is_all=1#feedtop
// $n=ereg_replace("href=\"","href=\"http://bbs.phpchina.com/",$snoopy->results );
// $str=utf8_encode($snoopy->results);
// http://weibo.com/aj/mblog/fsearch?ajwvr=6&pre_page=1&page=1&end_id=4011647544070677&min_id=4011645515081642&pagebar=0&__rnd=1471934983417

/*$str=$snoopy->results;
$str=str_replace('{"code":"100000","msg":"","data":"','',$str);*/


// $str=str_replace('/div>"}','',$str);
// $str=str_replace('\/','/',$str);
// $str=str_replace('\"','"',$str);
// $str=str_replace('\n','',$str);
// $str=str_replace('\r','',$str);
// $str=str_replace('\t','',$str);


/*$html=unicode_decode($str);*/

function getHtml($url){
	$snoopy=init();
	$snoopy->fetch($url);
	$str=$snoopy->results;
	$str=str_replace('{"code":"100000","msg":"","data":"','',$str);
	// $str=str_replace('/div>"}','',$str);
	// $str=str_replace('\/','/',$str);
	// $str=str_replace('\"','"',$str);
	// $str=str_replace('\n','',$str);
	// $str=str_replace('\r','',$str);
	// $str=str_replace('\t','',$str);
	$html=unicode_decode($str);
	return $html;
}

function dealmain($html){

// echo $html;
// 处理直接拿到的数据
	$pat='/<script>(.*?)<\/script>/i';
	// $pat='/FM\.view\(\{(.*?)\)\}/i';
	preg_match_all($pat, $html, $match);

	// var_dump($match[1]);
	foreach($match[1] as $ke=>$va){
		// echo $va;
		$str=str_replace('FM.view','',$va);
		$str=ltrim($str,'(');
		$str=rtrim($str,';');
		$str=rtrim($str,')');
        $darr=json_decode($str,true);
        if(isset($darr['html'])){
        	// echo $ke."***<br/>";
        	 phpQuery::newDocument($darr['html']);
        	 $resu=pq(".WB_detail>.WB_text");

			$listarr=array();

			foreach($resu as $v){
				$larr=array();
				$name=pq($v)->text();
					$listarr[]=$name;//一维数组
			}
			// var_dump($listarr);
			if(empty($listarr)){
				continue;
			}else{
                break;
			}
        }
        // var_dump($darr->html);
	}
	return $listarr;
}


function dealjs($html){


// 处理js的数据
phpQuery::newDocument($html);
$resu=pq(".WB_detail>.WB_text");
$listarr=array();

		foreach($resu as $v){
			$larr=array();
			$name=pq($v)->text();
				$listarr[]=$name;//一维数组
		}
	return $listarr;

}

function unicode_decode($name)
{
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches))
    {
        for ($j = 0; $j < count($matches[0]); $j++)
        {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0)
            {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code).chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $name=str_replace($str,$c,$name);
            }
            // else
            // {
            //     $name .= $str;
            // }
        }
    }
    return $name;
}