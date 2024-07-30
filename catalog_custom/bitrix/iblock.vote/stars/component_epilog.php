<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();                        
if(\Bitrix\Main\Loader::includeSharewareModule("krayt.mall") == \Bitrix\Main\Loader::MODULE_DEMO_EXPIRED || 
   \Bitrix\Main\Loader::includeSharewareModule("krayt.mall") ==  \Bitrix\Main\Loader::MODULE_NOT_FOUND
    )
{ return false;}
/** @var array $templateData */
if (isset($templateData['JS_OBJ']))
{
	if (!isset($_SESSION["IBLOCK_RATING"][$templateData['ELEMENT_ID']]) && 'Y' != $arParams['READ_ONLY'])
	{
?>
<script type="text/javascript">
BX.ready(
	BX.defer(function(){
		if (!!window.<? echo $templateData['JS_OBJ']; ?>)
		{
			window.<? echo $templateData['JS_OBJ']; ?>.bindEvents();
		}
	})
);
</script>
<?
	}
}
?>