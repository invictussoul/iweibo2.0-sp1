<?php
/**
 * 格式化表情
 * @param 
 * @return
 * @author fucaixie
 * @package /application/common/
 */

class Core_Comm_Emotion
{	//获取表情数据
	
	private static function getEmotions()
	{
		return "f14|微笑,f1|撇嘴,f2|色,f3|发呆,f4|得意,f5|流泪,f6|害羞,f7|闭嘴,f8|睡,f9|大哭,f10|尴尬,f11|发怒,f12|调皮,f13|呲牙,f0|惊讶,f15|难过,f16|酷,f96|冷汗,f18|抓狂,f19|吐,f20|偷笑,f21|可爱,f22|白眼,f23|傲慢,f24|饥饿,f25|困,f26|惊恐,f27|流汗,f28|憨笑,f29|大兵,f30|奋斗,f31|咒骂,f32|疑问,f33|嘘,f34|晕,f35|折磨,f36|衰,f37|骷髅,f38|敲打,f39|再见,f97|擦汗,f98|抠鼻,f99|鼓掌,f100|糗大了,f101|坏笑,f102|左哼哼,f103|右哼哼,f104|哈欠,f105|鄙视,f106|委屈,f107|快哭了,f108|阴险,f109|亲亲,f110|吓,f111|可怜,f112|菜刀,f89|西瓜,f113|啤酒,f114|篮球,f115|乒乓,f60|咖啡,f61|饭,f46|猪头,f63|玫瑰,f64|凋谢,f116|示爱,f66|爱心,f67|心碎,f53|蛋糕,f54|闪电,f55|炸弹,f56|刀,f57|足球,f117|瓢虫,f59|便便,f75|月亮,f74|太阳,f69|礼物,f49|拥抱,f76|强,f77|弱,f78|握手,f79|胜利,f118|抱拳,f119|勾引,f120|拳头,f121|差劲,f122|爱你,f123|NO,f124|OK,f42|爱情,f85|飞吻,f43|跳跳,f41|发抖,f86|怄火,f125|转圈,f126|磕头,f127|回头,f128|跳绳,f129|挥手,f130|激动,f131|街舞,f132|献吻,f133|左太极,f134|右太极";
	}
	//格式化表情为数组格式
	private static function getEmotionsArr($s)
	{	$returnArray=array();
		foreach(explode(",",$s) as $e)
		{$faceArr=array();
		 $f=explode("|",$e);
		 $faceArr["id"]=str_replace("f","",$f[0]);
		 $faceArr["name"]=$f[1];
		 $returnArray[]=$faceArr;	
		}
		return $returnArray;
	}	
	//替换表情	
	private static function replaceEmotions($eArr,$str)
	{
        $preUrl = Core_Config::get('resource_path','basic','/');
		foreach($eArr as $e)
		{
		$str=str_replace('/'.$e['name'],'<img alt="'.$e['name'].'" src=\''.$preUrl.'resource/images/emotions/'.$e['id'].'.gif\' valign="top"/>',$str);
		}
		return $str;
	}
	
	public static function replace($str) 
	{	
		$emotionsstr=self::getEmotions();
		$emotionsArr=self::getEmotionsArr($emotionsstr);
		return self::replaceEmotions($emotionsArr,$str);
	}
}
?>