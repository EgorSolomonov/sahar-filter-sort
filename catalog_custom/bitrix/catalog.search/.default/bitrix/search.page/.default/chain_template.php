<?
//Navigation chain template
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();                        
if(\Bitrix\Main\Loader::includeSharewareModule("krayt.mall") == \Bitrix\Main\Loader::MODULE_DEMO_EXPIRED || 
   \Bitrix\Main\Loader::includeSharewareModule("krayt.mall") ==  \Bitrix\Main\Loader::MODULE_NOT_FOUND
    )
{ return false;}

$arChainBody = array();
foreach($arCHAIN as $item)
{
	if(strlen($item["LINK"])<strlen(SITE_DIR))
		continue;
	if($item["LINK"] <> "")
		$arChainBody[] = '<a href="'.$item["LINK"].'">'.htmlspecialcharsex($item["TITLE"]).'</a>';
	else
		$arChainBody[] = htmlspecialcharsex($item["TITLE"]);
}
return implode('&nbsp;/&nbsp;', $arChainBody);
?>