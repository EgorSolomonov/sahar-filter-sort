<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (
	\Bitrix\Main\Loader::includeSharewareModule("krayt.mall") == \Bitrix\Main\Loader::MODULE_DEMO_EXPIRED ||
	\Bitrix\Main\Loader::includeSharewareModule("krayt.mall") ==  \Bitrix\Main\Loader::MODULE_NOT_FOUND
) {
	return false;
}
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

use Bitrix\Main\Loader;

if (\Bitrix\Main\Loader::includeModule('bxmaker.geoip')) {
	$oManager = \Bxmaker\GeoIP\Manager::getInstance();
}


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
if (isset($templateData['JS_OBJ'])) {
	?><script type="text/javascript">
		BX.ready(BX.defer(function() {
			if (!!window.<? echo $templateData['JS_OBJ']; ?>) {
				window.<? echo $templateData['JS_OBJ']; ?>.allowViewedCount(true);
			}
		}));
		//
		<? if (\Bitrix\Main\Loader::includeModule('bxmaker.geoip')) { ?>
			$(document).on("bxmaker.geoip.city.show", function(event, data) {
				$("#delivery_city_name").text(data.city);
			});

			$("#delivery_options_city").click(function() {

				var ID = getCurrentOfId();
				$.get('/ajax/get_delivery.php?ID=' + ID + '&LOCATION=' + <?= $oManager->getLocation(); ?>, function(data) {

					quickViewOpen(data);
				});
			});
		<? } ?>
	</script><?
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