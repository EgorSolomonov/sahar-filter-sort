<?
global $APPLICATION;

$page = $APPLICATION->GetCurPage();

//$now = strtotime(date("d.m.Y"));
$now = date("d.m.Y");
// Весна и лето с начала февраля - 01.02 по 14.08

//Осень с середины августа - 15.08 по 01.11
//$summer = strtotime(date("15.08.".date('Y')));
// Новый год с начала ноября - 01.11 по 31.01

//Новый год
// $ny = strtotime(date("01.01.".date('Y')+1));
// $vesna = strtotime(date("02.01.".date('Y')));
// $autumn = strtotime(date("15.08.".date('Y')+1));

$date1 = date("01.01." . date('Y'));
$date2 = date("02.01." . date('Y'));
$date3 = date("15.08." . date('Y'));

$ny = $DB->CompareDates($now, $date1);
$vesna = $DB->CompareDates($now, $date2);
$autumn = $DB->CompareDates($now, $date3);

// dump($ny);
// dump($vesna);
// dump($autumn);

//set filter
if (!empty($_REQUEST['sort'])) {
	$section_sort = $_REQUEST['sort'];
	switch ($section_sort) {
		case 'price':
			$arParams["ELEMENT_SORT_FIELD"] = 'SCALED_PRICE_1';
			$arParams["ELEMENT_SORT_ORDER"] = 'desc';
			break;
		case 'price_asc':
			$arParams["ELEMENT_SORT_FIELD"] = 'SCALED_PRICE_1';
			$arParams["ELEMENT_SORT_ORDER"] = 'asc';
			break;
		case 'new':
			$arParams["ELEMENT_SORT_FIELD"] = 'PROPERTY_NOVINKA';
			$arParams["ELEMENT_SORT_ORDER"] = 'desc';
			break;
		case 'popular':
			$arParams["ELEMENT_SORT_FIELD"] = 'show_counter';
			$arParams["ELEMENT_SORT_ORDER"] = 'desc';
			break;
	}
} else {
	//значение сортировки сезона по умолчанию
	$SEZON_SORT = [
		"PROPERTY_SEZON_ZIMA" => "desc", //зима
		"PROPERTY_NOVYY_GOD" => "desc", //новый год
		"PROPERTY_SEZON_LETO" => "desc", //лето
		"PROPERTY_VESNA_LETO" => "desc", // весна - лето
		"PROPERTY_OSEN_ZIMA" => "desc", //осень - зима
		"PROPERTY_NOVINKA" => "desc",
		"PROPERTY_LIDER_PRODAZH" => "desc",
		"PROPERTY_KHODOVOY_TOVAR" => "desc",
		"PROPERTY_ROZNICHNYY_INTERNET_MAGAZIN" => "desc",
		"PROPERTY_RASPRODAZHA" => "desc",
		"PROPERTY_VESNA_LETO" => "desc",
		"ADD_EMPTY_PROPERTYES_AND_FIX_SORT" => "desc",
	];

	if ($ny == 1) {
		//dump('сейчас Новый год: 01.11');
		$SEZON_SORT = [
			"PROPERTY_NOVYY_GOD" => "desc", //новый год
			//"PROPERTY_SEZON_ZIMA" => "desc", //зима
			"PROPERTY_OSEN_ZIMA" => "desc", //осень - зима
			"PROPERTY_VESNA_LETO" => "desc", // весна - лето
			//"PROPERTY_SEZON_LETO" => "desc", //лето
			"PROPERTY_NOVINKA" => "desc",
			"PROPERTY_LIDER_PRODAZH" => "desc",
			"PROPERTY_KHODOVOY_TOVAR" => "desc",
			"PROPERTY_ROZNICHNYY_INTERNET_MAGAZIN" => "desc",
			"PROPERTY_RASPRODAZHA" => "desc",
			"ADD_EMPTY_PROPERTYES_AND_FIX_SORT" => "desc",
		];
	}

	if ($vesna == 1) {
		//	dump('сейчас весна: 01.02');
		$SEZON_SORT = [
			//"SCALED_PRICE_1" => "desc",
			"PROPERTY_VESNA_LETO" => "desc", // весна - лето
			//"PROPERTY_SEZON_LETO" => "desc", //лето
			"PROPERTY_OSEN_ZIMA" => "desc", //осень - зима
			//"PROPERTY_SEZON_ZIMA" => "desc", //зима
			"PROPERTY_NOVYY_GOD" => "desc", //новый год
			"PROPERTY_NOVINKA" => "desc",
			"PROPERTY_LIDER_PRODAZH" => "desc",
			"PROPERTY_KHODOVOY_TOVAR" => "desc",
			"PROPERTY_ROZNICHNYY_INTERNET_MAGAZIN" => "desc",
			"PROPERTY_RASPRODAZHA" => "desc",

			"ADD_EMPTY_PROPERTYES_AND_FIX_SORT" => "desc",
		];
	}

	if ($autumn == 1) {
		//	dump('сейчас осень: 15.08');

		$SEZON_SORT = [
			"PROPERTY_OSEN_ZIMA" => "desc", //осень - зима
			//"PROPERTY_SEZON_ZIMA" => "descs", //зима
			"PROPERTY_VESNA_LETO" => "desc", // весна - лето
			"PROPERTY_NOVYY_GOD" => "desc", //новый год
			//"PROPERTY_SEZON_LETO" => "desc", //лето
			"PROPERTY_NOVINKA" => "desc",
			"PROPERTY_LIDER_PRODAZH" => "desc",
			"PROPERTY_KHODOVOY_TOVAR" => "desc",
			"PROPERTY_ROZNICHNYY_INTERNET_MAGAZIN" => "desc",
			"PROPERTY_RASPRODAZHA" => "desc",
			"ADD_EMPTY_PROPERTYES_AND_FIX_SORT" => "desc",
		];
	}
}


?>
<div class="catalog_filter_box" <? if ($page == "/catalog/khity-prodazh/" || $page == "/catalog/posledniy_razmer/" || $page == "/catalog/rasprodazja/" || $page == "/catalog/novinki/" || $page == '/catalog/vse-tovary/') : ?>style="display: flex !important;justify-content: flex-end !important;align-items: center;gap: 15px;margin-bottom: 20px;" <? endif; ?>>
	<? if ($section_sort == "price") : ?>
		<div class="blo_sort"><span class="chosen_sort">по убыванию цены</span></div>
	<? elseif ($section_sort == 'price_asc') : ?>
		<div class="blo_sort"><span class="chosen_sort">по возрастанию цены</span></div>
	<? elseif ($section_sort == 'new') : ?>
		<div class="blo_sort"><span class="chosen_sort">по новинкам</span></div>
	<? elseif ($section_sort == 'popular') : ?>
		<div class="blo_sort"><span class="chosen_sort">по популярности</span></div>
	<? else : ?>
		<div class="blo_sort"><span class="chosen_sort">Сортировать</span></div>
	<? endif; ?>
	<div class="modal_sort" <? if ($page == "/catalog/khity-prodazh/" || $page == "/catalog/posledniy_razmer/" || $page == "/catalog/rasprodazja/" || $page == "/catalog/novinki/" || $page == '/catalog/vse-tovary/') : ?> style="margin-top: 170px !important;" <? endif; ?>>
		<a href="<?= ($section_sort != "price") ? $APPLICATION->GetCurPageParam("sort=price", array("sort")) : $APPLICATION->GetCurPageParam("", array("sort")); ?>" <? if ($section_sort == "price") : ?>class="active" <? endif; ?>>по убыванию цены</a>
		<a href="<?= ($section_sort != "price_asc") ? $APPLICATION->GetCurPageParam("sort=price_asc", array("sort")) : $APPLICATION->GetCurPageParam("", array("sort")); ?>" <? if ($section_sort == "price_asc") : ?>class="active" <? endif; ?>>по возрастанию цены</a>
		<a href="<?= ($section_sort != "new") ? $APPLICATION->GetCurPageParam("sort=new", array("sort")) : $APPLICATION->GetCurPageParam("", array("sort")); ?>" <? if ($section_sort == "new") : ?>class="active" <? endif; ?>>по новинкам</a>
		<a href="<?= ($section_sort != "popular") ? $APPLICATION->GetCurPageParam("sort=popular", array("sort")) : $APPLICATION->GetCurPageParam("", array("sort")); ?>" <? if ($section_sort == "popular") : ?>class="active" <? endif; ?>>по популярности</a>
	</div>
</div>

<div class="modal_sort__mobile">
	<span class="close_mobile_sort"></span>
	<p>СОРТИРОВАТЬ</p>
	<a href="<?= ($section_sort != "price") ? $APPLICATION->GetCurPageParam("sort=price", array("sort")) : $APPLICATION->GetCurPageParam("", array("sort")); ?>" <? if ($section_sort == "price") : ?>class="active" <? endif; ?>>по убыванию цены</a>
	<a href="<?= ($section_sort != "price_asc") ? $APPLICATION->GetCurPageParam("sort=price_asc", array("sort")) : $APPLICATION->GetCurPageParam("", array("sort")); ?>" <? if ($section_sort == "price_asc") : ?>class="active" <? endif; ?>>по возрастанию цены</a>
	<a href="<?= ($section_sort != "new") ? $APPLICATION->GetCurPageParam("sort=new", array("sort")) : $APPLICATION->GetCurPageParam("", array("sort")); ?>" <? if ($section_sort == "new") : ?>class="active" <? endif; ?>>по новинкам</a>
	<a href="<?= ($section_sort != "popular") ? $APPLICATION->GetCurPageParam("sort=popular", array("sort")) : $APPLICATION->GetCurPageParam("", array("sort")); ?>" <? if ($section_sort == "popular") : ?>class="active" <? endif; ?>>по популярности</a>
</div>



<script>
	// Сортировка и фильтрация

	window.onload = function() {
		let modal_sort = document.querySelector(".modal_sort");
		let modal_sort__mobile = document.querySelector(".modal_sort__mobile");
		let sort = document.querySelector(".blo_sort");
		let sort_elements = document.querySelectorAll(".modal_sort");
		let width = document.documentElement.clientWidth;
		let close_mob_sort = document.querySelector(".close_mobile_sort");
		let filter_butt = document.querySelector(".blo_custom");
		let items = document.querySelectorAll(".bx_catalog_item");

		// увеличиваем элементы в ряду при отключении фильтра
		if (width > 767) {
			if (filter_butt)
				filter_butt.addEventListener("click", () => {
					items.forEach((element) => {
						element.classList.toggle("bx_catalog_item_short");
					});
				});
		}

		// обработка сортировки
		if (sort)
			sort.addEventListener("click", () => {
				if (width >= 767) {
					modal_sort.classList.toggle("visible");
					if (modal_sort.classList.contains("visible")) {
						sort_elements.forEach((element) => {
							element.addEventListener("click", () => {
								//   sort.innerHTML = element.innerHTML;
								element.classList.add("sort_active");
							});
						});
					}
				} else {
					modal_sort__mobile.classList.toggle("visible");
					if (modal_sort__mobile.classList.contains("visible")) {
						sort_elements.forEach((element) => {
							element.addEventListener("click", () => {
								//   sort.innerHTML = element.innerHTML;
								element.classList.add("sort_active");
							});
						});
					}

					if (close_mob_sort) {
						close_mob_sort.addEventListener("click", () => {
							modal_sort__mobile.classList.remove("visible");
						});
					}
				}
			});
	};

	// Сортировка и фильтрация
</script>