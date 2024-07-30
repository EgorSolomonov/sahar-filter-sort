<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (
	\Bitrix\Main\Loader::includeSharewareModule("krayt.mall") == \Bitrix\Main\Loader::MODULE_DEMO_EXPIRED ||
	\Bitrix\Main\Loader::includeSharewareModule("krayt.mall") ==  \Bitrix\Main\Loader::MODULE_NOT_FOUND
) {
	return false;
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES'])) {
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
	'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID . '_pict',
	'DISCOUNT_PICT_ID' => $strMainID . '_dsc_pict',
	'STICKER_ID' => $strMainID . '_sticker',
	'BIG_SLIDER_ID' => $strMainID . '_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID . '_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID . '_slider_cont',
	'SLIDER_LIST' => $strMainID . '_slider_list',
	'SLIDER_LEFT' => $strMainID . '_slider_left',
	'SLIDER_RIGHT' => $strMainID . '_slider_right',
	'OLD_PRICE' => $strMainID . '_old_price',
	'PRICE' => $strMainID . '_price',
	'DISCOUNT_PRICE' => $strMainID . '_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID . '_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID . '_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID . '_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID . '_slider_right_',
	'QUANTITY' => $strMainID . '_quantity',
	'QUANTITY_DOWN' => $strMainID . '_quant_down',
	'QUANTITY_UP' => $strMainID . '_quant_up',
	'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
	'QUANTITY_LIMIT' => $strMainID . '_quant_limit',
	'BASIS_PRICE' => $strMainID . '_basis_price',
	'BUY_LINK' => $strMainID . '_buy_link',
	'ADD_BASKET_LINK' => $strMainID . '_add_basket_link',
	'BASKET_ACTIONS' => $strMainID . '_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID . '_not_avail',
	'COMPARE_LINK' => $strMainID . '_compare_link',
	'PROP' => $strMainID . '_prop_',
	'PROP_DIV' => $strMainID . '_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
	'DISPLAY_SCU_AVAILABLE' => $strMainID . '_sku_available',
	'OFFER_GROUP' => $strMainID . '_set_group_',
	'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
	'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
);
$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
?><div class="bx_item_detail" id="<? echo $arItemIDs['ID']; ?>">
	<?
	reset($arResult['MORE_PHOTO']);
	$arFirstPhoto = current($arResult['MORE_PHOTO']);
	?>
	<div class="bx_item_container">
		<div class="bx_lt">
			<?
			$minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
			$minPrice['DISCOUNT_DIFF_PERCENT'];
			?>

			<div class="bx_item_slider" id="<? echo $arItemIDs['BIG_SLIDER_ID']; ?>">
				<div class="stickers">
					<? if ($arResult['PROPERTIES']["NOVINKA"]["VALUE"] == 'Да') : ?>
						<div class="sticker_novinka"></div>
					<? endif; ?>
					<? if ($arResult['PROPERTIES']["LIDER_PRODAZH"]["VALUE"] == 'Да') : ?>
						<div class="sticker_hit"></div>
					<? endif; ?>
					<? if ($arResult['PROPERTIES']["POSLEDNIY_RAZMER"]["VALUE"] == 'Да') : ?>
						<div class="sticker_posledniy_razmer"></div>
					<? endif; ?>
				</div>
				<div class="labels_wrp">
					<? if ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y' && $minPrice['DISCOUNT_DIFF_PERCENT'] > 0) : ?>
						<div class="discont_procent">-<?= $minPrice['DISCOUNT_DIFF_PERCENT'] ?>%</div>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"] == "Y") : ?>
						<div class="label label_hit"><?= GetMessage("HIT"); ?></div>
					<? elseif ($arResult["PROPERTIES"]["NEW"]["VALUE_XML_ID"] == "Y") : ?>
						<div class="label label_new"><?= GetMessage("NEW"); ?></div>
					<? endif; ?>
				</div>
				<?
				if ($arResult['SHOW_SLIDER']) {
					if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS'])) {
						if (1 < $arResult['MORE_PHOTO_COUNT']) {
							$strClass = 'bx_slider_conteiner bx_photo_slider';
							$strSlideStyle = '';
						} else {
							$strClass = 'bx_slider_conteiner';
							$strSlideStyle = 'display: none;';
						}
				?>
						<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_ID']; ?>">
							<ul id="<? echo $arItemIDs['SLIDER_LIST']; ?>">
								<?
								foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto) {
								?>
									<li data-value="<? echo $arOnePhoto['ID']; ?>"><img src="<? echo $arOnePhoto['SRC']; ?>" alt="" /></li>
								<?
								}
								unset($arOnePhoto);
								?>
							</ul>
							<div class="bx_photo_nav"></div>
						</div>
						<?
					} else {
						foreach ($arResult['OFFERS'] as $key => $arOneOffer) {
							if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
								continue;
							//$strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');
							if (1 < $arOneOffer['MORE_PHOTO_COUNT']) {
								$strClass = 'bx_slider_conteiner bx_photo_slider';
								$strSlideStyle = '';
							} else {
								$strClass = 'bx_slider_conteiner';
								$strSlideStyle = 'display: none;';
							}
						?>
							<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'] . $arOneOffer['ID']; ?>">
								<ul id="<? echo $arItemIDs['SLIDER_LIST_OF_ID'] . $arOneOffer['ID']; ?>">
									<?
									foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto) {
									?>
										<li class="zoom" data-value="<? echo $arOneOffer['ID'] . '_' . $arOnePhoto['ID']; ?>"><img src="<? echo $arOnePhoto['SRC']; ?>" alt="" /></li>
									<?
									}
									unset($arOnePhoto);
									?>
								</ul>
								<div class="nav_<? echo $arItemIDs['SLIDER_LIST_OF_ID'] . $arOneOffer['ID']; ?>"></div>
							</div>
							<script>
								$(".bx_photo_slider #<? echo $arItemIDs['SLIDER_LIST_OF_ID'] . $arOneOffer['ID']; ?>").owlCarousel({
									items: 1,
									dots: false,
									nav: true,
									navClass: ['btn-circle btn-circle_prev', 'btn-circle btn-circle_next'],
									navText: "",
									navRewind: false,
									navContainer: ".nav_<? echo $arItemIDs['SLIDER_LIST_OF_ID'] . $arOneOffer['ID']; ?>",
									animateOut: "fadeOut",
									responsiveRefreshRate: 0,
									video: true
								});
							</script>
							<script>
								$(document).ready(function() {
									if (window.innerWidth > 450) {
										$(document).on('mouseover', '.zoom', function() {
											$('.owl-item').zoom({
												url: $(this).find('img').attr("scr")
											});
										})
									}
								});
							</script>
				<?
						}
					}
				}
				?>
			</div>
		</div>
		<div class="bx_rt">
			<div class="property_sticker">
				<?
				if ($arResult['PROPERTIES']["AVTORSKIY_PRINT"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("author_print") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["USTOYCHIV_K_STIRKAM"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("USTOYCHIV_K_STIRKAM") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["SIYAYUSHCHIY_GLITER"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("SIYAYUSHCHIY_GLITER") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["SHELKOVAYA_VSTAVKA"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("SHELKOVAYA_VSTAVKA") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["NATURALNYY_KHLOPOK"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("NATURALNYY_KHLOPOK") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["TEKSTILNAYA_FOLGA"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("TEKSTILNAYA_FOLGA") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["TKAN_NE_SKATYVAETSYA"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("TKAN_NE_SKATYVAETSYA") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["OVERSIZE"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("OVERSIZE") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["SDELANO_V_ROSSII"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("SDELANO_V_ROSSII") ?>"></div>
				<? endif; ?>
				<? if ($arResult['PROPERTIES']["UTEPLENNYY_NACHES"]["VALUE"] == "Да") : ?>
					<div class="<? echo ("UTEPLENNYY_NACHES") ?>"></div>
				<? endif; ?>
			</div>


			<?
			if ('Y' == $arParams['DISPLAY_NAME']) {
			?>
				<h1><?
					echo (isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
						? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
						: $arResult["NAME"]
					); ?>
				</h1>
			<?
			}
			?>
			<div class="bx_item_info">
				<div class="available" id="<?= $arItemIDs['DISPLAY_SCU_AVAILABLE']; ?>">Товар в наличии</div>
				<?
				$useBrands = ('Y' == $arParams['BRAND_USE']);
				$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
				if ($useBrands || $useVoteRating) {
				?>
					<div class="bx_optionblock">
						<?
						if ($useVoteRating) {
						?><? $APPLICATION->IncludeComponent(
								"bitrix:iblock.vote",
								"stars",
								array(
									"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
									"IBLOCK_ID" => $arParams['IBLOCK_ID'],
									"ELEMENT_ID" => $arResult['ID'],
									"ELEMENT_CODE" => "",
									"MAX_VOTE" => "5",
									"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
									"SET_STATUS_404" => "N",
									"DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
									"CACHE_TYPE" => $arParams['CACHE_TYPE'],
									"CACHE_TIME" => $arParams['CACHE_TIME']
								),
								$component,
								array("HIDE_ICONS" => "Y")
							); ?><?
								}
								if ($useBrands) {
									?><? $APPLICATION->IncludeComponent(
											"bitrix:catalog.brandblock",
											".default",
											array(
												"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
												"IBLOCK_ID" => $arParams['IBLOCK_ID'],
												"ELEMENT_ID" => $arResult['ID'],
												"ELEMENT_CODE" => "",
												"PROP_CODE" => $arParams['BRAND_PROP_CODE'],
												"CACHE_TYPE" => $arParams['CACHE_TYPE'],
												"CACHE_TIME" => $arParams['CACHE_TIME'],
												"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
												"WIDTH" => "",
												"HEIGHT" => ""
											),
											$component,
											array("HIDE_ICONS" => "Y")
										); ?><?
											}
												?>
					</div>
				<?
				}
				unset($useVoteRating, $useBrands);
				?>
				<div class="item_price">
					<? if (($arResult['PROPERTIES']["OLD_PRICE"]["VALUE"] != $arResult['MIN_PRICE']["VALUE"])&&($arResult['PROPERTIES']["OLD_PRICE"]["VALUE"] > $arResult['MIN_PRICE']["VALUE"])) : ?>
						<div class="item_old_price"><?= $arResult['PROPERTIES']["OLD_PRICE"]["VALUE"] ?></div>
					<? endif; ?>

					<?
					$minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
					$boolDiscountShow = (0 < $minPrice['DISCOUNT_DIFF']);
					?>
					<div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo $minPrice['PRINT_DISCOUNT_VALUE']; ?></div>
					<?
					if ($arParams['SHOW_OLD_PRICE'] == 'Y') {
					?>
						<div class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? $minPrice['PRINT_VALUE'] : ''); ?></div>
					<?
					}
					?>
					<?
					if ($arParams['SHOW_OLD_PRICE'] == 'Y') {
					?>
						<div class="item_economy_price" id="<? echo $arItemIDs['DISCOUNT_PRICE']; ?>" style="display: none;"><? echo ($boolDiscountShow ? GetMessage('CT_BCE_CATALOG_ECONOMY_INFO', array('#ECONOMY#' => $minPrice['PRINT_DISCOUNT_DIFF'])) : ''); ?></div>
					<?
					}
					?>
				</div>
				<?
				unset($minPrice);
				?>
				<?
				if ('' != $arResult['PREVIEW_TEXT']) {
					if (
						'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
						|| ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
					) {
				?>
						<div class="item_info_section">
							<?
							echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>' . $arResult['PREVIEW_TEXT'] . '</p>');
							?>
						</div>
					<?
					}
				}


				if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP'])) {
					$arSkuProps = array();
					?>

					<div class="scu_block" id="<? echo $arItemIDs['PROP_DIV']; ?>">
						<?
						foreach ($arResult['SKU_PROPS'] as &$arProp) {
							if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
								continue;
							$arSkuProps[] = array(
								'ID' => $arProp['ID'],
								'SHOW_MODE' => $arProp['SHOW_MODE'],
								'VALUES_COUNT' => $arProp['VALUES_COUNT']
							);
							if ('TEXT' == $arProp['SHOW_MODE']) {
								$strClass = 'bx_item_detail_size';
								$strSlideStyle = 'display: none;';
						?>
								<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
									<div class="bx_size_scroller_container">
										<div class="bx_size">
											<ul id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list">
												<?
												foreach ($arProp['VALUES'] as $arOneValue) {
													$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
												?>
													<li data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID']; ?>" data-onevalue="<? echo $arOneValue['ID']; ?>" style="display: none;">
														<? echo $arOneValue['NAME']; ?></li>
												<? }
												?>
											</ul>
										</div>
										<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
										<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
									</div>
								</div>
							<?
							} elseif ('PICT' == $arProp['SHOW_MODE']) {
								if (5 < $arProp['VALUES_COUNT']) {
									$strClass = 'bx_item_detail_scu full';
									$strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
									$strWidth = (20 * $arProp['VALUES_COUNT']) . '%';
									$strSlideStyle = '';
								} else {
									$strClass = 'bx_item_detail_scu';
									$strOneWidth = '10px';
									$strWidth = '100%';
									$strSlideStyle = 'display: none;';
								}
							?>
								<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
									<span class="bx_item_section_name_gray"><? echo htmlspecialcharsEx($arProp['NAME']); ?></span>
									<div class="bx_scu_scroller_container">
										<div class="bx_scu">
											<ul id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
												<?
												foreach ($arProp['VALUES'] as $arOneValue) {
													$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
												?>
													<li data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>" data-onevalue="<? echo $arOneValue['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;">
														<i title="<? echo $arOneValue['NAME']; ?>"></i>
														<span class="cnt"><span class="cnt_item" style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');" title="<? echo $arOneValue['NAME']; ?>"></span></span>
													</li>
												<?
												}
												?>
											</ul>
										</div>
										<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
										<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
									</div>
								</div>
						<?
							}
						}
						?>
					</div>
				<?
				}
				?>
				<? if (\Bitrix\Main\Loader::includeModule('bxmaker.geoip')) : ?>
					<div id="delivery_options_city" class="delivery_options">
						<span><?= GetMessage('K_DEF_CITY') ?></span>
					</div>
				<? endif; ?>
				<? if (\Bitrix\Main\Loader::includeModule('api.yashare')) : ?>
					<div class="share">
						<?= GetMessage('K_SHARE_SOC') ?>
						<? $APPLICATION->IncludeComponent(
							"api:yashare",
							"",
							array(
								"DATA_DESCRIPTION" => "",
								"DATA_IMAGE" => "",
								"DATA_TITLE" => "",
								"DATA_URL" => "",
								"LANG" => "ru",
								"QUICKSERVICES" => array("vkontakte", "facebook", "odnoklassniki", "moimir", "gplus", "twitter"),
								"SHARE_SERVICES" => array(),
								"SIZE" => "s",
								"TYPE" => "icon",
								"UNUSED_CSS" => "N",
								"vkontakte_description" => "",
								"vkontakte_image" => "",
								"vkontakte_title" => "",
								"vkontakte_url" => ""
							)
						); ?>
					</div><? endif; ?>
			</div>


			<div class="bx_item_buttons">

				<?
				// Массив с категориями, которые не выводить в кнопке Таблица размеров
				$notable = array('Банты', 'Маски', 'Картины', 'Керамика', 'Свечи');
				//пересоздадим массив с данными для таблицы размеров
				// dump($arResult["OFFERS"]);
				$table_size = [];
				foreach ($arResult["OFFERS"] as $key => $arOffers) {
					$RAZMER[] = $arOffers["PROPERTIES"]["RAZMER"]["VALUE"];
					$OBKHVAT_GRUDI_PO_GOSTU[] = $arOffers["PROPERTIES"]["OBKHVAT_GRUDI_PO_GOSTU"]["VALUE"];
					$OBEM_PO_GRUDI[] = $arOffers["PROPERTIES"]["OBEM_PO_GRUDI"]["VALUE"];
					$PLECHO[] = $arOffers["PROPERTIES"]["PLECHO"]["VALUE"];
					$SHIRINA_PO_TALII[] = $arOffers["PROPERTIES"]["SHIRINA_PO_TALII"]["VALUE"];
					$OBEM_PO_BEDRAM[] = $arOffers["PROPERTIES"]["OBEM_PO_BEDRAM"]["VALUE"];
					$DLINA[] = $arOffers["PROPERTIES"]["DLINA"]["VALUE"];
					$SHIRINA_RUKAVA[] = $arOffers["PROPERTIES"]["SHIRINA_RUKAVA"]["VALUE"];
					$DLINA_RUKAVA[] = $arOffers["PROPERTIES"]["DLINA_RUKAVA"]["VALUE"];
					$DLINA_RUKAVA_OT_GORLOVINY[] = $arOffers["PROPERTIES"]["DLINA_RUKAVA_OT_GORLOVINY"]["VALUE"];
					$OBKHVAT_BEDER[] = $arOffers["PROPERTIES"]["OBKHVAT_BEDER"]["VALUE"];
					$OBKHVAT_TALII[] = $arOffers["PROPERTIES"]["OBKHVAT_TALII"]["VALUE"];
					$SHIRINA_PO_LINII_BEDER[] = $arOffers["PROPERTIES"]["SHIRINA_PO_LINII_BEDER"]["VALUE"];
					$DLINA_PO_VNUTRENNEMU_SHVU[] = $arOffers["PROPERTIES"]["DLINA_PO_VNUTRENNEMU_SHVU"]["VALUE"];
					$DLINA_PO_BOKOVOMU_SHVU[] = $arOffers["PROPERTIES"]["DLINA_PO_BOKOVOMU_SHVU"]["VALUE"];
				}

				$table_size = [
					'Размер' => $RAZMER,
					'Обхват груди по ГОСТу' => $OBKHVAT_GRUDI_PO_GOSTU,
					'Ширина по груди' => $OBEM_PO_GRUDI,
					'Ширина в плечах' => $PLECHO,
					'Ширина по талии' => $SHIRINA_PO_TALII,
					'Ширина по бедрам' => $OBEM_PO_BEDRAM,
					'Длина изделия' => $DLINA,
					'Ширина рукава' => $SHIRINA_RUKAVA,
					'Длина рукава от плеча' => $DLINA_RUKAVA,
					'Длина рукава от горловины' => $DLINA_RUKAVA_OT_GORLOVINY,
					'Обхват бедер по ГОСТу' => $OBKHVAT_BEDER,
					'Обхват талии' => $OBKHVAT_TALII,
					'Ширина по линии бедер' => $SHIRINA_PO_LINII_BEDER,
					'Длина по внутреннему шву' => $DLINA_PO_VNUTRENNEMU_SHVU,
					'Длина по боковому шву' => $DLINA_PO_BOKOVOMU_SHVU,
				];

				//  dump($table_size);
				?>



				<div class="tablesizebtn <? if (in_array($arResult['PROPERTIES']['KATEGORIYA']['VALUE'], $notable)) {
												echo ("none");
											} else {
												echo ("block");
											} ?>">
					<a href="#tablessize" data-id="tablsize">Таблица размеров</a>
				</div>

				<div class="sidepanel__block" data-id="sidepanel">
					<div class="sidePanel__header">
						<h4 class="sidePanel__title">
							ТАБЛИЦА РАЗМЕРОВ
						</h4>
						<button type="button" class="sidePanel__CloseModule">
							<span class="sidePanel__CloseModule__label">
								Закрыть
							</span>
							<span class="sidePanel__CloseModule__icon">
								<svg width="27" height="27" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
									<line y2="40.10298" x2="40.11363" y1="-0.12429" x1="-0.11364" stroke="currentColor" stroke-width="1"></line>
									<line y2="-2.39702" x2="42.38636" y1="46.23934" x1="-7.38636" stroke="currentColor" stroke-width="1"></line>
								</svg>
							</span>
						</button>
					</div>
					<div class="sidePanel">
						<div class="sidePanel__container">
							<div class="sidePanel__body">
								<div class="sidePanel__content">
									<div class="sidePanel__wrapper">
										<p class="sidePanel__note">Данные обмеры относятся к выбранной вами вещи, для других вещей
											могут
											быть другие обмеры</p>
										<div class="sidePanel__sizestable">
											<div class="sidePanel__arrowscroll">
												Прокрутите в сторону
												<svg xmlns="http://www.w3.org/2000/svg" width="21" height="11" viewBox="0 0 21 11" fill="none">
													<path d="M1.21053 5.5H20.0006M1.21053 5.5L6 1M1.21053 5.5L6 10M20.0006 5.5L15.2112 1M20.0006 5.5L15.2112 10" stroke="#E4E4E4" stroke-linecap="round" stroke-linejoin="round" />
												</svg>
											</div>
											<div class="sidePanel__sizestableflex">
												<div class="sidePanel__righttablediv">
													<table class="table_size"> <!-- class="sidePanel__righttabletable" -->
														<tbody class="tbody">
															<?
															$i = 0;
															foreach ($table_size as $name => $items) : ?>
																<? if ($items[0]) : ?>
																	<tr <? if ($i == 0) : ?>class="first_row" <? endif; ?>>
																		<th><?= $name ?></th>
																		<? foreach ($items as $item) : ?>
																			<td><?= $item ?></td>
																		<? endforeach; ?>
																	</tr>
																	<? $i++ ?>
																<? endif; ?>
															<? endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="sidePanel__mera">
											<div class="sidePanel__meratext">
												<h4>Как правильно себя обмерить</h4>
												<h5>1. ПЛЕЧИ</h5>
												<p>От точки соединения плеча и шеи до самой крайней точки плеча.</p>
												<h5>2. ОБХВАТ ГРУДИ</h5>
												<p>Измеряется по самым выступающим точкам.</p>
												<h5>3. ОБХВАТ ТАЛИИ</h5>
												<p>естественная линия талии измеряется в самом узком месте.</p>
												<h5>4. ОБХВАТ БЕДЕР</h5>
												<p>Измеряется горизонтально полу по наиболее выступающим точкам ягодиц.</p>
												<h5>5. ДЛИНА РУКИ</h5>
												<p>Измеряется от плечевого сустава до запястья.</p>
												<h5>6. ДЛИНА ПО ВНУТРЕННЕМУ ШВУ</h5>
												<p>Это мерка внутренней стороны брючины, от основания ноги до предполагаемого низа
													брюк. Данную мерку можно измерить так: возьмите брюки, которые на вас хорошо
													сидят, разложите их на ровной поверхности и замерьте внутренний шов.</p>
												<h5>7. ДЛИНА ПО ВНЕШНЕМУ ШВУ</h5>
												<p>Это мерка внешней стороны брючины, от верхнего края брюк до низа изделия. Данную
													мерку можно измерить так: возьмите брюки которые на вас хорошо сидят, с такой же
													посадкой на талии, как брюки, которые вы планируете купить. Расправьте, сложите
													вдвое и разложите на горизонтальной поверхности. Замер производится по внешнему
													шву изделия, включая ширину пояса.</p>
											</div>
											<div class="sidepanel__img">
												<img src="/bitrix/templates/mall/images/bodysize.svg" alt="">
											</div>
										</div>
									</div>
								</div>
								<div class="sidepanel__footer">
									<div class="sidepanel__svg">
										<svg width="2rem" height="2rem" version="1.1" viewBox="0 0 752 752" xmlns="http://www.w3.org/2000/svg" fill="#646464">
											<defs>
												<clipPath id="b">
													<path d="m189 139.21h121v112.79h-121z" />
												</clipPath>
												<clipPath id="a">
													<path d="m145 151h462v461.79h-462z" />
												</clipPath>
											</defs>
											<g clip-path="url(#b)">
												<path d="m231.63 144.94-41.625 92.867c-1.5664 3.1484-1.2578 6.9062 0.80078 9.7539 2.0547 2.8516 5.5195 4.3281 9 3.8398l101.2-10.418h0.003907c3.5078-0.21875 6.6094-2.3672 8.0508-5.5742 1.4414-3.2109 0.98438-6.9531-1.1875-9.7227l-59.574-82.449c-1.9453-2.9258-5.3555-4.5352-8.8516-4.1758-3.4961 0.35547-6.5039 2.6211-7.8203 5.8789zm16.527 37.887 22.305 30.832 0.003906-0.003906c1.0977 1.3828 1.3359 3.2578 0.61719 4.8711-0.71484 1.6094-2.2695 2.6914-4.0273 2.8008l-37.887 3.8828v0.003906c-1.707 0.16797-3.3711-0.59375-4.3516-2-0.98438-1.4062-1.1289-3.2305-0.38281-4.7734l15.535-34.715h-0.003906c0.63281-1.5938 2.0781-2.7188 3.7773-2.9453 1.6992-0.22656 3.3906 0.48438 4.418 1.8555z" />
											</g>
											<g clip-path="url(#a)">
												<path d="m538.98 219.29c-34.324-34.277-78.574-56.875-126.47-64.586-47.891-7.7109-96.996-0.14062-140.35 21.633l13.828 18.941c40.508-19.535 86.266-25.375 130.38-16.633 44.113 8.7422 84.188 31.594 114.18 65.102 29.992 33.508 48.277 75.859 52.098 120.67 3.8203 44.812-7.0352 89.648-30.922 127.75-23.891 38.102-59.52 67.402-101.52 83.484-41.996 16.082-88.086 18.07-131.31 5.6719-43.23-12.402-81.254-38.52-108.34-74.418-27.086-35.902-41.766-79.633-41.824-124.61v-1.9414c0.078125-2.5625-0.88672-5.043-2.6719-6.8828-1.7852-1.8398-4.2383-2.875-6.8008-2.875h-4.3086c-5.2305 0-9.4727 4.2422-9.4727 9.4727v2.082c-0.027344 45.605 13.473 90.191 38.793 128.12 25.316 37.926 61.316 67.492 103.45 84.953 42.125 17.461 88.484 22.035 133.21 13.141 44.727-8.8945 85.809-30.859 118.05-63.109 28.617-28.609 49.199-64.25 59.676-103.34 10.477-39.086 10.477-80.242 0-119.33-10.477-39.09-31.059-74.73-59.676-103.34z" />
											</g>
											<path d="m440.67 321.96c-4.707 0.058594-8.7422-3.3516-9.4688-8.0039-2.5625-15.93-12.582-29.676-26.961-36.988-14.379-7.3125-31.391-7.3125-45.77 0-14.383 7.3125-24.402 21.059-26.961 36.988-0.73047 4.6523-4.7617 8.0625-9.4727 8.0039h-61.566 0.003906c-2.7344-0.019532-5.3398 1.1367-7.1562 3.1797-1.8125 2.043-2.6562 4.7656-2.3164 7.4766l17.051 114.84h-0.003906c1.7539 13.711 8.4414 26.316 18.812 35.449 10.375 9.1367 23.723 14.18 37.547 14.184h113.66-0.003906c13.824-0.003906 27.172-5.0469 37.547-14.184 10.371-9.1328 17.059-21.738 18.812-35.449l17.051-114.84h-0.003906c0.34375-2.7109-0.50391-5.4336-2.3164-7.4766-1.8164-2.043-4.4219-3.1992-7.1562-3.1797zm-59.199-27.656h0.003906c6.3594 0.007812 12.523 2.1914 17.469 6.1914 4.9453 3.9961 8.3711 9.5664 9.7148 15.781 0.28516 1.4102-0.082031 2.875-1.0039 3.9805s-2.293 1.7344-3.7344 1.7031h-44.988c-1.4414 0.03125-2.8125-0.59766-3.7344-1.7031-0.92187-1.1055-1.2891-2.5703-1.0039-3.9805 1.3359-6.2383 4.7734-11.832 9.7422-15.84 4.9648-4.0078 11.156-6.1914 17.539-6.1797zm53.516 179.96h-107.03c-9.2227-0.007812-18.125-3.3789-25.039-9.4844-6.9141-6.1016-11.363-14.516-12.516-23.668l-14.207-91.258c-0.17969-1.3594 0.23828-2.7266 1.1484-3.7539 0.90625-1.0273 2.2148-1.6094 3.5859-1.5977h200.8c1.3711-0.011718 2.6797 0.57031 3.5898 1.5977 0.90625 1.0273 1.3242 2.3945 1.1484 3.7539l-14.207 91.258c-1.125 9.1211-5.5273 17.52-12.383 23.637-6.8555 6.1133-15.703 9.5312-24.891 9.6094z" />
											<path d="m378.77 375.39h3.7891c5.2305 0 9.4727 5.2305 9.4727 9.4727v48.258c0 5.2305-4.2422 9.4727-9.4727 9.4727h-3.7891c-5.2305 0-9.4727-5.2305-9.4727-9.4727v-48.258c0-5.2305 4.2422-9.4727 9.4727-9.4727z" />
											<path d="m420.4 375.39h3.7891c5.2305 0 9.4727 5.2305 9.4727 9.4727v48.258c0 5.2305-4.2422 9.4727-9.4727 9.4727h-3.7891c-5.2305 0-9.4727-5.2305-9.4727-9.4727v-48.258c0-5.2305 4.2422-9.4727 9.4727-9.4727z" />
											<path d="m337.1 375.39h3.7891c5.2305 0 9.4727 5.2305 9.4727 9.4727v48.258c0 5.2305-4.2422 9.4727-9.4727 9.4727h-3.7891c-5.2305 0-9.4727-5.2305-9.4727-9.4727v-48.258c0-5.2305 4.2422-9.4727 9.4727-9.4727z" />
										</svg>
									</div>
									<span>
										Не забывайте, вы всегда можете вернуть товар обратно
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- <pre>
						<? var_dump($arResult["PROPERTIES"]) ?>
					</pre> -->
				<?
				if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
					$canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
				} else {
					$canBuy = $arResult['CAN_BUY'];
				}
				$buyBtnMessage = ($arParams['MESS_BTN_BUY'] != '' ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
				$addToBasketBtnMessage = ($arParams['MESS_BTN_ADD_TO_BASKET'] != '' ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCE_CATALOG_ADD'));
				$notAvailableMessage = ($arParams['MESS_NOT_AVAILABLE'] != '' ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
				$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
				$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);

				if ($arResult['CATALOG_SUBSCRIBE'] == 'Y')
					$showSubscribeBtn = true;
				else
					$showSubscribeBtn = false;
				$compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCE_CATALOG_COMPARE'));

				if ($arParams['USE_PRODUCT_QUANTITY'] == 'Y') {
					if ($arParams['SHOW_BASIS_PRICE'] == 'Y') {
						$basisPriceInfo = array(
							'#PRICE#' => $arResult['MIN_BASIS_PRICE']['PRINT_DISCOUNT_VALUE'],
							'#MEASURE#' => (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : '')
						);
				?>
						<p id="<? echo $arItemIDs['BASIS_PRICE']; ?>" class="item_section_name_gray"><? echo GetMessage('CT_BCE_CATALOG_MESS_BASIS_PRICE', $basisPriceInfo); ?></p>
					<?
					}
					?>
					<span class="item_section_name_gray"><? echo GetMessage('CATALOG_QUANTITY'); ?></span>
					<div class="item_buttons vam">
						<span class="item_buttons_counter_block">
							<?
							/*<script>
								var itemParams = {
									product: {
										id: <?= $arResult["ID"] ?>,
										name: <?= $arResult["NAME"] ?>,
										price: <?= $arResult["MIN_PRICE"]["VALUE"] ?>,
									}
								};
							</script>*/
							?>
							<? //  onclick="ym(89685747,'params', {params: itemParams},'addItemToBasket'); return true;"
							?>
							<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
							<input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
																																? 1
																																: $arResult['CATALOG_MEASURE_RATIO']
																															); ?>">
							<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
							<span class="bx_cnt_desc" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><? echo (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
						</span>
						<span class="item_buttons_counter_block" id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" style="display: <? echo ($canBuy ? '' : 'none'); ?>;">
							<?
							if ($showBuyBtn) {
							?>
								<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
							<?
							}
							if ($showAddBtn) {
							?>
								<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<? echo $arItemIDs['ADD_BASKET_LINK']; ?>"><span></span><? echo $addToBasketBtnMessage; ?></a>
							<?
							}
							?>
						</span>

						<? if ($showSubscribeBtn) {
							$APPLICATION->includeComponent(
								'bitrix:catalog.product.subscribe',
								'',
								array(
									'PRODUCT_ID' => $arResult['ID'],
									'BUTTON_ID' => $arItemIDs['SUBSCRIBE_LINK'],
									'BUTTON_CLASS' => 'bx_big bx_bt_button',
									'DEFAULT_DISPLAY' => !$canBuy,
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
						} ?>

						<br>
						<span id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_notavailable<?= ($showSubscribeBtn ? ' bx_notavailable_subscribe' : ''); ?>" style="display: <? echo (!$canBuy ? '' : 'none'); ?>;"><? echo $notAvailableMessage; ?></span>
						<? if ($arParams['DISPLAY_COMPARE']) {
						?>
							<span class="item_buttons_counter_block">
								<a href="javascript:void(0);" class="bx_big bx_bt_button_type_2 bx_cart" id="<? echo $arItemIDs['COMPARE_LINK']; ?>"><? echo $compareBtnMessage; ?></a>
							</span>
						<? } ?>

					</div>

					<? if ('Y' == $arParams['SHOW_MAX_QUANTITY']) {
						if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
					?>
							<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>" style="display: none;"><? echo GetMessage('OSTATOK'); ?>: <span></span></p>
							<?
						} else {
							if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']) {
							?>
								<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>"><? echo GetMessage('OSTATOK'); ?>: <span><? echo $arResult['CATALOG_QUANTITY']; ?></span></p>
					<?
							}
						}
					}
				} else {
					?>
					<div class="item_buttons vam">
						<span class="item_buttons_counter_block" id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" style="display: <? echo ($canBuy ? '' : 'none'); ?>;">
							<?
							if ($showBuyBtn) {
								// onclick="ym(89685747,'reachGoal','addItemToBasket'); return true;"
							?>
								<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
							<?
							}
							if ($showAddBtn) {
								// onclick="ym(89685747,'reachGoal','addItemToBasket'); return true;"
							?>
								<a href="javascript:void(0);" class="btn btn_add" id="<? echo $arItemIDs['ADD_BASKET_LINK']; ?>"><? echo $addToBasketBtnMessage; ?></a>
							<?
							}
							?>
						</span>
						<div class="one-click-order">
							<a href="#" class="one-click-btn" data-name="<?= $arResult['NAME'] ?>" data-url="<?= $arResult['DETAIL_PAGE_URL'] ?>" data-offer-id="">Заказать в 1 клик</a>
						</div>
						<? if ($showSubscribeBtn) {
							$APPLICATION->IncludeComponent(
								'bitrix:catalog.product.subscribe',
								'',
								array(
									'PRODUCT_ID' => $arResult['ID'],
									'BUTTON_ID' => $arItemIDs['SUBSCRIBE_LINK'],
									'BUTTON_CLASS' => 'bx_big bx_bt_button',
									'DEFAULT_DISPLAY' => !$canBuy,
								),
								$component,
								array('HIDE_ICONS' => 'Y')
							);
						} ?>
						<br>
						<span id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_notavailable<?= ($showSubscribeBtn ? ' bx_notavailable_subscribe' : ''); ?>" style="display: <? echo (!$canBuy ? '' : 'none'); ?>;"><? echo $notAvailableMessage; ?></span>
						<? if ($arParams['DISPLAY_COMPARE']) {
						?>
							<span class="item_buttons_counter_block">
								<? if ($arParams['DISPLAY_COMPARE']) {
								?><a href="javascript:void(0);" class="bx_big bx_bt_button_type_2 bx_cart" id="<? echo $arItemIDs['COMPARE_LINK']; ?>"><? echo $compareBtnMessage; ?></a><?
																																														} ?>
							</span>
						<? } ?>
					</div>
				<?
				}
				unset($showAddBtn, $showBuyBtn);
				?>
			</div>
			<?
			if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']) {
			?>
				<div class="loyalnost">
					<div class="loyalnost__gift">
					</div>
					<div class="loyalnost__discont">
					</div>
					<div class="gift__modal" data-id="sidepanel">
						<div class="gift__close">
							<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M13.8811 12.5005L24.714 1.66764C25.0954 1.28624 25.0954 0.667882 24.714 0.286535C24.3326 -0.0948118 23.7142 -0.0948607 23.3329 0.286535L12.5 11.1194L1.66715 0.286535C1.28575 -0.0948607 0.667394 -0.0948607 0.286047 0.286535C-0.0953001 0.667931 -0.0953489 1.28629 0.286047 1.66764L11.1189 12.5005L0.286047 23.3334C-0.0953489 23.7148 -0.0953489 24.3331 0.286047 24.7145C0.47672 24.9051 0.726671 25.0005 0.976622 25.0005C1.22657 25.0005 1.47647 24.9051 1.6672 24.7145L12.5 13.8816L23.3328 24.7145C23.5235 24.9051 23.7735 25.0005 24.0234 25.0005C24.2734 25.0005 24.5233 24.9051 24.714 24.7145C25.0954 24.3331 25.0954 23.7147 24.714 23.3334L13.8811 12.5005Z" fill="white" />
							</svg>
						</div>
						<div class="gift__image"></div>
						<div class="gift">
							<div class="gift__title">
								<p>Получи подарок</p>
							</div>
							<div class="gift__subtitle">
								<p>За заказ от 7000₽ ты можешь выбрать в подарок
									любую бант-ленту из нашей авторской коллекции!</p>
							</div>
							<div class="gift__text">
								<p>Это универсальный аксессуар твилли, который
									можно носить разными способами: как резинку
									для волос, повязку на запястье, платок на сумочку,
									джинсы или шею</p>
							</div>
						</div>
					</div>
				</div>
				<div class="property_list">
					<?
					if (!empty($arResult['DISPLAY_PROPERTIES'])) {
					?>
						<h3><?= GetMessage("PROPERTIES"); ?></h3>
						<?
						foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp) {
						?>
							<div class="property">
								<span class="prop_name"><? echo $arOneProp['NAME']; ?></span><span class="prop_value"><?
																														echo (is_array($arOneProp['DISPLAY_VALUE'])
																															? implode(' / ', $arOneProp['DISPLAY_VALUE'])
																															: $arOneProp['DISPLAY_VALUE']
																														); ?></span>
							</div><?
								}
								unset($arOneProp);
							}
							if ($arResult['SHOW_OFFERS_PROPS']) {
									?>
						<div id="<? echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></div>
					<?
							}
					?>
				</div>
			<?
			}
			?>
			<div class="delivery">
				<p>Доставка:</p>
				<div class="delivery__punkt">
					<div class="delivery__punktlogo"></div>
					<div class="delivery__text">
						<span class="delivery__textup">В пунктах выдачи</span>
					</div>
				</div>
				<div class="delivery__courier">
					<div class="delivery__courierlogo"></div>
					<div class="delivery__text">
						<span class="delivery__textup">Курьером</span>
						<span class="delivery__textdown">1-3 рабочих дня</span>
					</div>
				</div>
				<div class="delivery__return">
					<div class="delivery__returnlogo"></div>
					<div class="delivery__text">
						<span class="delivery__textup">Бесплатный обмен и удобный возврат</span>
						<span class="delivery__textdown">Без вопросов возьмём товар обратно</span>
					</div>
				</div>
			</div>
		</div>

		<div class="bx_md">
			<div class="wrapper">
				<?
				if ('' != $arResult['DETAIL_TEXT']) {
				?>
					<div class="bx_item_description">
						<h2><? echo GetMessage('FULL_DESCRIPTION'); ?></h2>
						<?
						if ('html' == $arResult['DETAIL_TEXT_TYPE']) {
							echo $arResult['DETAIL_TEXT'];
						} else {
						?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
																	}
																		?>
					</div>
				<?
				}
				?>
				<div class="item_info_section">
					<?
					if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
						if ($arResult['OFFER_GROUP']) {
							foreach ($arResult['OFFER_GROUP_VALUES'] as $offerID) {
					?>
								<span id="<? echo $arItemIDs['OFFER_GROUP'] . $offerID; ?>" style="display: none;">
									<? $APPLICATION->IncludeComponent(
										"bitrix:catalog.set.constructor",
										".default",
										array(
											"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
											"ELEMENT_ID" => $offerID,
											"PRICE_CODE" => $arParams["PRICE_CODE"],
											"BASKET_URL" => $arParams["BASKET_URL"],
											"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
											"CACHE_TYPE" => $arParams["CACHE_TYPE"],
											"CACHE_TIME" => $arParams["CACHE_TIME"],
											"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
											"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
											"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
											"CURRENCY_ID" => $arParams["CURRENCY_ID"]
										),
										$component,
										array("HIDE_ICONS" => "Y")
									); ?><?
											?>
								</span>
								<?
							}
						}
					} else {
						if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP']) {
								?><? $APPLICATION->IncludeComponent(
										"bitrix:catalog.set.constructor",
										".default",
										array(
											"IBLOCK_ID" => $arParams["IBLOCK_ID"],
											"ELEMENT_ID" => $arResult["ID"],
											"PRICE_CODE" => $arParams["PRICE_CODE"],
											"BASKET_URL" => $arParams["BASKET_URL"],
											"CACHE_TYPE" => $arParams["CACHE_TYPE"],
											"CACHE_TIME" => $arParams["CACHE_TIME"],
											"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
											"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
											"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
											"CURRENCY_ID" => $arParams["CURRENCY_ID"]
										),
										$component,
										array("HIDE_ICONS" => "Y")
									); ?><?
										}
									}

									if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled("sale")) {
										$APPLICATION->IncludeComponent("bitrix:sale.gift.product", ".default", array(
											'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
											'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
											'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
											'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
											'SUBSCRIBE_URL_TEMPLATE' => $arResult['~SUBSCRIBE_URL_TEMPLATE'],
											'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],

											"SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
											"SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
											"PAGE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
											"LINE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
											"HIDE_BLOCK_TITLE" => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
											"BLOCK_TITLE" => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
											"TEXT_LABEL_GIFT" => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
											"SHOW_NAME" => $arParams['GIFTS_SHOW_NAME'],
											"SHOW_IMAGE" => $arParams['GIFTS_SHOW_IMAGE'],
											"MESS_BTN_BUY" => $arParams['GIFTS_MESS_BTN_BUY'],

											"SHOW_PRODUCTS_{$arParams['IBLOCK_ID']}" => "Y",
											"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
											"PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
											"MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
											"MESS_BTN_SUBSCRIBE" => $arParams["MESS_BTN_SUBSCRIBE"],
											"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
											"PRICE_CODE" => $arParams["PRICE_CODE"],
											"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
											"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
											"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
											"BASKET_URL" => $arParams["BASKET_URL"],
											"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
											"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
											"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
											"USE_PRODUCT_QUANTITY" => 'N',
											"OFFER_TREE_PROPS_{$arResult['OFFERS_IBLOCK']}" => $arParams['OFFER_TREE_PROPS'],
											"CART_PROPERTIES_{$arResult['OFFERS_IBLOCK']}" => $arParams['OFFERS_CART_PROPERTIES'],
											"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
											"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
											"POTENTIAL_PRODUCT_TO_BUY" => array(
												'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
												'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
												'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
												'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
												'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

												'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
												'SECTION' => array(
													'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
													'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
													'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
													'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
												),
											)
										), $component, array("HIDE_ICONS" => "Y"));
									}
									if ($arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled("sale")) {
										$APPLICATION->IncludeComponent(
											"bitrix:sale.gift.main.products",
											".default",
											array(
												"PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
												"BLOCK_TITLE" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

												"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
												"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],

												"AJAX_MODE" => $arParams["AJAX_MODE"],
												"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
												"IBLOCK_ID" => $arParams["IBLOCK_ID"],

												"ELEMENT_SORT_FIELD" => 'ID',
												"ELEMENT_SORT_ORDER" => 'DESC',
												//"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
												//"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
												"FILTER_NAME" => 'searchFilter',
												"SECTION_URL" => $arParams["SECTION_URL"],
												"DETAIL_URL" => $arParams["DETAIL_URL"],
												"BASKET_URL" => $arParams["BASKET_URL"],
												"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
												"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
												"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],

												"CACHE_TYPE" => $arParams["CACHE_TYPE"],
												"CACHE_TIME" => $arParams["CACHE_TIME"],

												"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
												"SET_TITLE" => $arParams["SET_TITLE"],
												"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
												"PRICE_CODE" => $arParams["PRICE_CODE"],
												"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
												"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

												"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
												"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
												"CURRENCY_ID" => $arParams["CURRENCY_ID"],
												"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
												"TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"]) ? $arParams["TEMPLATE_THEME"] : ""),

												"ADD_PICT_PROP" => (isset($arParams["ADD_PICT_PROP"]) ? $arParams["ADD_PICT_PROP"] : ""),

												"LABEL_PROP" => (isset($arParams["LABEL_PROP"]) ? $arParams["LABEL_PROP"] : ""),
												"OFFER_ADD_PICT_PROP" => (isset($arParams["OFFER_ADD_PICT_PROP"]) ? $arParams["OFFER_ADD_PICT_PROP"] : ""),
												"OFFER_TREE_PROPS" => (isset($arParams["OFFER_TREE_PROPS"]) ? $arParams["OFFER_TREE_PROPS"] : ""),
												"SHOW_DISCOUNT_PERCENT" => (isset($arParams["SHOW_DISCOUNT_PERCENT"]) ? $arParams["SHOW_DISCOUNT_PERCENT"] : ""),
												"SHOW_OLD_PRICE" => (isset($arParams["SHOW_OLD_PRICE"]) ? $arParams["SHOW_OLD_PRICE"] : ""),
												"MESS_BTN_BUY" => (isset($arParams["MESS_BTN_BUY"]) ? $arParams["MESS_BTN_BUY"] : ""),
												"MESS_BTN_ADD_TO_BASKET" => (isset($arParams["MESS_BTN_ADD_TO_BASKET"]) ? $arParams["MESS_BTN_ADD_TO_BASKET"] : ""),
												"MESS_BTN_DETAIL" => (isset($arParams["MESS_BTN_DETAIL"]) ? $arParams["MESS_BTN_DETAIL"] : ""),
												"MESS_NOT_AVAILABLE" => (isset($arParams["MESS_NOT_AVAILABLE"]) ? $arParams["MESS_NOT_AVAILABLE"] : ""),
												'ADD_TO_BASKET_ACTION' => (isset($arParams["ADD_TO_BASKET_ACTION"]) ? $arParams["ADD_TO_BASKET_ACTION"] : ""),
												'SHOW_CLOSE_POPUP' => (isset($arParams["SHOW_CLOSE_POPUP"]) ? $arParams["SHOW_CLOSE_POPUP"] : ""),
												'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
												'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
											)
												+ array(
													'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID']) ? $arResult['ID'] : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
													'SECTION_ID' => $arResult['SECTION']['ID'],
													'ELEMENT_ID' => $arResult['ID'],
												),
											$component,
											array("HIDE_ICONS" => "Y")
										);
									}
											?>
				</div>
			</div>
		</div>
		<? if (!empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])) : ?>
			<div class="accessories prod-carousel">
				<div class="prod-carousel-control">
					<h2><?= GetMessage("ACCESSORIES"); ?></h2>
					<div class="accessories-navigation prod-carousel-nav"></div>
				</div>
				<div class="prod-carousel-wrp">
					<?
					global $arrFilter;
					$arrFilter = array("ID" => $arResult['PROPERTIES']['ACCESSORIES']['VALUE']);

					$APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						"products_carousel",
						array(
							"ACTION_VARIABLE" => "action",
							"ADD_PICT_PROP" => "-",
							"ADD_PROPERTIES_TO_BASKET" => $arParams['CACHE_TIME'],
							"ADD_SECTIONS_CHAIN" => "N",
							"ADD_TO_BASKET_ACTION" => "ADD",
							"AJAX_MODE" => "N",
							"AJAX_OPTION_ADDITIONAL" => "",
							"AJAX_OPTION_HISTORY" => "N",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"BACKGROUND_IMAGE" => "-",
							"BASKET_URL" => $arParams['BASKET_URL'],
							"BROWSER_TITLE" => "-",
							"CACHE_FILTER" => "N",
							"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
							"CACHE_TIME" => $arParams['CACHE_TIME'],
							"CACHE_TYPE" => $arParams['CACHE_TYPE'],
							"CONVERT_CURRENCY" => "N",
							"DETAIL_URL" => "",
							"DISABLE_INIT_JS_IN_COMPONENT" => "N",
							"DISPLAY_BOTTOM_PAGER" => "N",
							"DISPLAY_TOP_PAGER" => "N",
							"ELEMENT_SORT_FIELD" => "sort",
							"ELEMENT_SORT_FIELD2" => "id",
							"ELEMENT_SORT_ORDER" => "asc",
							"ELEMENT_SORT_ORDER2" => "desc",
							"FILTER_NAME" => "arrFilter",
							"HIDE_NOT_AVAILABLE" => "N",
							"IBLOCK_ID" => $arParams['IBLOCK_ID'],
							"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
							"INCLUDE_SUBSECTIONS" => "Y",
							"LABEL_PROP" => "-",
							"LINE_ELEMENT_COUNT" => "3",
							"MESSAGE_404" => "",
							"MESS_BTN_ADD_TO_BASKET" => $arParams['MESS_BTN_ADD_TO_BASKET'],
							"MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
							"MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
							"MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
							"MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
							"META_DESCRIPTION" => "-",
							"META_KEYWORDS" => "-",
							"OFFERS_CART_PROPERTIES" => array(),
							"OFFERS_FIELD_CODE" => array("", ""),
							"OFFERS_LIMIT" => "5",
							"OFFERS_PROPERTY_CODE" => array("SIZE", ""),
							"OFFERS_SORT_FIELD" => "sort",
							"OFFERS_SORT_FIELD2" => "id",
							"OFFERS_SORT_ORDER" => "asc",
							"OFFERS_SORT_ORDER2" => "desc",
							"OFFER_ADD_PICT_PROP" => "-",
							"OFFER_TREE_PROPS" => $arParams['OFFER_TREE_PROPS'],
							"PAGER_BASE_LINK_ENABLE" => "N",
							"PAGER_DESC_NUMBERING" => "N",
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
							"PAGER_SHOW_ALL" => "N",
							"PAGER_SHOW_ALWAYS" => "N",
							"PAGER_TEMPLATE" => ".default",
							"PAGER_TITLE" => "",
							"PAGE_ELEMENT_COUNT" => "100",
							"PARTIAL_PRODUCT_PROPERTIES" => "N",
							"PRICE_CODE" => $arParams['PRICE_CODE'],
							"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
							"PRODUCT_DISPLAY_MODE" => "N",
							"PRODUCT_ID_VARIABLE" => "id",
							"PRODUCT_PROPERTIES" => array(),
							"PRODUCT_PROPS_VARIABLE" => "prop",
							"PRODUCT_QUANTITY_VARIABLE" => "",
							"PRODUCT_SUBSCRIPTION" => "N",
							"PROPERTY_CODE" => array("", ""),
							"SECTION_CODE" => "",
							"SECTION_CODE_PATH" => "",
							"SECTION_ID" => "",
							"SECTION_ID_VARIABLE" => "SECTION_ID",
							"SECTION_URL" => "",
							"SECTION_USER_FIELDS" => array("", ""),
							"SEF_MODE" => "N",
							"SEF_RULE" => "",
							"SET_BROWSER_TITLE" => "N",
							"SET_LAST_MODIFIED" => "N",
							"SET_META_DESCRIPTION" => "N",
							"SET_META_KEYWORDS" => "N",
							"SET_STATUS_404" => "N",
							"SET_TITLE" => "N",
							"SHOW_404" => "N",
							"SHOW_ALL_WO_SECTION" => "Y",
							"SHOW_CLOSE_POPUP" => "Y",
							"SHOW_DISCOUNT_PERCENT" => "N",
							"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
							"SHOW_PRICE_COUNT" => $arParams['SHOW_PRICE_COUNT'],
							"TEMPLATE_THEME" => "blue",
							"USE_MAIN_ELEMENT_SECTION" => "N",
							"USE_PRICE_COUNT" => "N",
							"USE_PRODUCT_QUANTITY" => "N"
						),
						$component,
						array("HIDE_ICONS" => "Y")
					); ?>
				</div>
			</div>
		<? endif; ?>
		<? if (!empty($arResult["PROPERTIES"]["SIMILAR"]["VALUE"])) : ?>
			<div class="similar-products prod-carousel">
				<div class="prod-carousel-control">
					<h2><?= GetMessage("SIMILAR_PRODUCTS"); ?></h2>
					<div class="similar-products-navigation prod-carousel-nav"></div>
				</div>
				<div class="prod-carousel-wrp">
					<?
					global $arrFilter;
					$arrFilter = array("ID" => $arResult['PROPERTIES']['SIMILAR']['VALUE']);

					$APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						"products_carousel",
						array(
							"ACTION_VARIABLE" => "action",
							"ADD_PICT_PROP" => "-",
							"ADD_PROPERTIES_TO_BASKET" => "Y",
							"ADD_SECTIONS_CHAIN" => "N",
							"ADD_TO_BASKET_ACTION" => "ADD",
							"AJAX_MODE" => "N",
							"AJAX_OPTION_ADDITIONAL" => "",
							"AJAX_OPTION_HISTORY" => "N",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"BACKGROUND_IMAGE" => "-",
							"BASKET_URL" => $arParams['BASKET_URL'],
							"BROWSER_TITLE" => "-",
							"CACHE_FILTER" => $arParams['CACHE_FILTER'],
							"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
							"CACHE_TIME" => $arParams['CACHE_TIME'],
							"CACHE_TYPE" => $arParams['CACHE_TYPE'],
							"CONVERT_CURRENCY" => "N",
							"DETAIL_URL" => "",
							"DISABLE_INIT_JS_IN_COMPONENT" => "N",
							"DISPLAY_BOTTOM_PAGER" => "N",
							"DISPLAY_TOP_PAGER" => "N",
							"ELEMENT_SORT_FIELD" => "sort",
							"ELEMENT_SORT_FIELD2" => "id",
							"ELEMENT_SORT_ORDER" => "asc",
							"ELEMENT_SORT_ORDER2" => "desc",
							"FILTER_NAME" => "arrFilter",
							"HIDE_NOT_AVAILABLE" => "N",
							"IBLOCK_ID" => $arParams['IBLOCK_ID'],
							"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
							"INCLUDE_SUBSECTIONS" => "Y",
							"LABEL_PROP" => "-",
							"LINE_ELEMENT_COUNT" => "3",
							"MESSAGE_404" => "",
							"MESS_BTN_ADD_TO_BASKET" => $arParams['MESS_BTN_ADD_TO_BASKET'],
							"MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
							"MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
							"MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
							"MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
							"META_DESCRIPTION" => "-",
							"META_KEYWORDS" => "-",
							"OFFERS_CART_PROPERTIES" => $arParams['OFFERS_CART_PROPERTIES'],
							"OFFERS_FIELD_CODE" => $arParams['OFFERS_FIELD_CODE'],
							"OFFERS_LIMIT" => "5",
							"OFFERS_PROPERTY_CODE" => $arParams['OFFERS_PROPERTY_CODE'],
							"OFFERS_SORT_FIELD" => "sort",
							"OFFERS_SORT_FIELD2" => "id",
							"OFFERS_SORT_ORDER" => "asc",
							"OFFERS_SORT_ORDER2" => "desc",
							"OFFER_ADD_PICT_PROP" => "-",
							"OFFER_TREE_PROPS" => $arParams['OFFER_TREE_PROPS'],
							"PAGER_BASE_LINK_ENABLE" => "N",
							"PAGER_DESC_NUMBERING" => "N",
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
							"PAGER_SHOW_ALL" => "N",
							"PAGER_SHOW_ALWAYS" => "N",
							"PAGER_TEMPLATE" => ".default",
							"PAGER_TITLE" => "",
							"PAGE_ELEMENT_COUNT" => "100",
							"PARTIAL_PRODUCT_PROPERTIES" => "N",
							"PRICE_CODE" => $arParams['PRICE_CODE'],
							"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
							"PRODUCT_DISPLAY_MODE" => "N",
							"PRODUCT_ID_VARIABLE" => "id",
							"PRODUCT_PROPERTIES" => array(),
							"PRODUCT_PROPS_VARIABLE" => "prop",
							"PRODUCT_QUANTITY_VARIABLE" => "",
							"PRODUCT_SUBSCRIPTION" => "N",
							"PROPERTY_CODE" => array("", ""),
							"SECTION_CODE" => "",
							"SECTION_CODE_PATH" => "",
							"SECTION_ID" => "",
							"SECTION_ID_VARIABLE" => "SECTION_ID",
							"SECTION_URL" => "",
							"SECTION_USER_FIELDS" => array("", ""),
							"SEF_MODE" => "N",
							"SEF_RULE" => "",
							"SET_BROWSER_TITLE" => "N",
							"SET_LAST_MODIFIED" => "N",
							"SET_META_DESCRIPTION" => "N",
							"SET_META_KEYWORDS" => "N",
							"SET_STATUS_404" => "N",
							"SET_TITLE" => "N",
							"SHOW_404" => "N",
							"SHOW_ALL_WO_SECTION" => "Y",
							"SHOW_CLOSE_POPUP" => "Y",
							"SHOW_DISCOUNT_PERCENT" => "N",
							"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
							"SHOW_PRICE_COUNT" => $arParams['SHOW_PRICE_COUNT'],
							"TEMPLATE_THEME" => "blue",
							"USE_MAIN_ELEMENT_SECTION" => "N",
							"USE_PRICE_COUNT" => "N",
							"USE_PRODUCT_QUANTITY" => "N"
						),
						$component,
						array("HIDE_ICONS" => "Y")
					); ?>
				</div>
			</div>

		<? endif; ?>
		<div class="bx_lb">
			<div class="tac ovh">
			</div>
			<div class="tab-section-container">
				<?
				if ('Y' == $arParams['USE_COMMENTS']) {
				?>
					<? $APPLICATION->IncludeComponent(
						"bitrix:catalog.comments",
						"",
						array(
							"ELEMENT_ID" => $arResult['ID'],
							"ELEMENT_CODE" => "",
							"IBLOCK_ID" => $arParams['IBLOCK_ID'],
							"SHOW_DEACTIVATED" => $arParams['SHOW_DEACTIVATED'],
							"URL_TO_COMMENT" => "",
							"WIDTH" => "",
							"COMMENTS_COUNT" => "5",
							"BLOG_USE" => $arParams['BLOG_USE'],
							"FB_USE" => $arParams['FB_USE'],
							"FB_APP_ID" => $arParams['FB_APP_ID'],
							"VK_USE" => $arParams['VK_USE'],
							"VK_API_ID" => $arParams['VK_API_ID'],
							"CACHE_TYPE" => $arParams['CACHE_TYPE'],
							"CACHE_TIME" => $arParams['CACHE_TIME'],
							'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
							"BLOG_TITLE" => "",
							"BLOG_URL" => $arParams['BLOG_URL'],
							"PATH_TO_SMILE" => "",
							"EMAIL_NOTIFY" => $arParams['BLOG_EMAIL_NOTIFY'],
							"AJAX_POST" => "Y",
							"SHOW_SPAM" => "Y",
							"SHOW_RATING" => "N",
							"FB_TITLE" => "",
							"FB_USER_ADMIN_ID" => "",
							"FB_COLORSCHEME" => "light",
							"FB_ORDER_BY" => "reverse_time",
							"VK_TITLE" => "",
							"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME']
						),
						$component,
						array("HIDE_ICONS" => "Y")
					); ?>
				<?
				}
				?>
			</div>
		</div>
	</div>
	<div class="clb"></div>
</div><?
		if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
			foreach ($arResult['JS_OFFERS'] as &$arOneJS) {

				if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE']) {
					$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
					$arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
				}
				$strProps = '';
				if ($arResult['SHOW_OFFERS_PROPS']) {
					if (!empty($arOneJS['DISPLAY_PROPERTIES'])) {
						foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp) {
							$strProps .= '<div class="property"><span class="prop_name">' . $arOneProp['NAME'] . '</span><span class="prop_value">' . (is_array($arOneProp['VALUE'])
								? implode(' / ', $arOneProp['VALUE'])
								: $arOneProp['VALUE']
							) . '</span></div>';
						}
					}
				}
				$arOneJS['DISPLAY_PROPERTIES'] = $strProps;
			}
			if (isset($arOneJS))
				unset($arOneJS);
			$arJSParams = array(
				'CONFIG' => array(
					'USE_CATALOG' => $arResult['CATALOG'],
					'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
					'SHOW_PRICE' => true,
					'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
					'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
					'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
					'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
					'OFFER_GROUP' => $arResult['OFFER_GROUP'],
					'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
					'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
					'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
					'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
					'USE_STICKERS' => true,
					'USE_SUBSCRIBE' => $showSubscribeBtn,
				),
				'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
				'VISUAL' => array(
					'ID' => $arItemIDs['ID'],
				),
				'DEFAULT_PICTURE' => array(
					'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
					'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
				),
				'PRODUCT' => array(
					'ID' => $arResult['ID'],
					'NAME' => $arResult['~NAME']
				),
				'BASKET' => array(
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'BASKET_URL' => $arParams['BASKET_URL'],
					'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
					'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
				),
				'OFFERS' => $arResult['JS_OFFERS'],
				'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
				'TREE_PROPS' => $arSkuProps
			);
			if ($arParams['DISPLAY_COMPARE']) {
				$arJSParams['COMPARE'] = array(
					'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
					'COMPARE_PATH' => $arParams['COMPARE_PATH']
				);
			}
		} else {
			$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
			if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties) {
		?>
		<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
			<?
				if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
					foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
			?>
					<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
				<?
						if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
							unset($arResult['PRODUCT_PROPERTIES'][$propID]);
					}
				}
				$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
				if (!$emptyProductProperties) {
				?>
				<table>
					<?
					foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
					?>
						<tr>
							<td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
							<td>
								<?
								if (
									'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
									&& 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
								) {
									foreach ($propInfo['VALUES'] as $valueID => $value) {
								?><label><input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?></label><br><?
																																																																	}
																																																																} else {
																																																																		?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
																																																																																								foreach ($propInfo['VALUES'] as $valueID => $value) {
																																																																																								?><option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option><?
																																																																																																																									}
																																																																																																																										?></select><?
																																																																																																																												}
																																																																																																																													?>
							</td>
						</tr>
					<?
					}
					?>
				</table>
			<?
				}
			?>
		</div>
<?
			}
			if ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] != $arResult['MIN_PRICE']['VALUE']) {
				$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
				$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
			}
			$arJSParams = array(
				'CONFIG' => array(
					'USE_CATALOG' => $arResult['CATALOG'],
					'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
					'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
					'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
					'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
					'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
					'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
					'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
					'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
					'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
					'USE_STICKERS' => true,
					'USE_SUBSCRIBE' => $showSubscribeBtn,
				),
				'VISUAL' => array(
					'ID' => $arItemIDs['ID'],
				),
				'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
				'PRODUCT' => array(
					'ID' => $arResult['ID'],
					'PICT' => $arFirstPhoto,
					'NAME' => $arResult['~NAME'],
					'SUBSCRIPTION' => true,
					'PRICE' => $arResult['MIN_PRICE'],
					'BASIS_PRICE' => $arResult['MIN_BASIS_PRICE'],
					'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
					'SLIDER' => $arResult['MORE_PHOTO'],
					'CAN_BUY' => $arResult['CAN_BUY'],
					'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
					'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
					'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
					'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
				),
				'BASKET' => array(
					'ADD_PROPS' => ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'),
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'EMPTY_PROPS' => $emptyProductProperties,
					'BASKET_URL' => $arParams['BASKET_URL'],
					'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
				)
			);
			if ($arParams['DISPLAY_COMPARE']) {
				$arJSParams['COMPARE'] = array(
					'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
					'COMPARE_PATH' => $arParams['COMPARE_PATH']
				);
			}
			unset($emptyProductProperties);
		}
		$arResult['strObName'] = $strObName;
?>
<script type="text/javascript">
	var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);

	function getCurrentOfId() {
		var item = <?= $strObName; ?>;

		return item.offers[item.offerNum].ID;
	}

	BX.message({
		ECONOMY_INFO_MESSAGE: '<? echo GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO'); ?>',
		BASIS_PRICE_MESSAGE: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_BASIS_PRICE') ?>',
		TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
		TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
		BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
		BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT') ?>',
		BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE'); ?>',
		BTN_MESSAGE_CLOSE_POPUP: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
		TITLE_SUCCESSFUL: '<? echo GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK'); ?>',
		COMPARE_MESSAGE_OK: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK') ?>',
		COMPARE_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
		COMPARE_TITLE: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE') ?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
		PRODUCT_GIFT_LABEL: '<? echo GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL') ?>',
		SITE_ID: '<? echo SITE_ID; ?>'
	});
</script>