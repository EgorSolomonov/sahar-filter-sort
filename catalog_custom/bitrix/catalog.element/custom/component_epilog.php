<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
// LazyLoad
if (isset($_GET['AJAX_PAGE'])) {

	$content = ob_get_contents();
	ob_end_clean();

	$APPLICATION->RestartBuffer();

	list(, $content_html) = explode('<!--RestartBuffer-->', $content);

	echo $content_html;

	die();
}
// LazyLoad конец

if (
	\Bitrix\Main\Loader::includeSharewareModule("krayt.mall") == \Bitrix\Main\Loader::MODULE_DEMO_EXPIRED ||
	\Bitrix\Main\Loader::includeSharewareModule("krayt.mall") ==  \Bitrix\Main\Loader::MODULE_NOT_FOUND
) {
	return false;
}
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

use Bitrix\Main\Loader;

global $APPLICATION;
if (isset($templateData['TEMPLATE_THEME'])) {
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}
if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY'])) {
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency) {
?>
		<script type="text/javascript">
			BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
		</script>
<?
	}
}
?>

<? // Избранное 
?>
<?
if (!$USER->IsAuthorized()) {
	$arFavorites = unserialize($APPLICATION->get_cookie("favorites"));
	//print_r($arFavorites);
} else {
	$idUser = $USER->GetID();
	$rsUser = CUser::GetByID($idUser);
	$arUser = $rsUser->Fetch();
	$arFavorites = $arUser['UF_FAVORITES'];  // Достаём избранное пользователя
}
count($arFavorites);
/* Меняем отображение сердечка товара */
foreach ($arFavorites as $k => $favoriteItem) : ?>
	<script>
		if ($('.section_favorite[data-item="<?= $favoriteItem ?>"]'))
			$('.section_favorite[data-item="<?= $favoriteItem ?>"]').addClass('active');
	</script>
<? endforeach; ?>