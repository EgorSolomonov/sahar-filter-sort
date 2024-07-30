// Таблица размеров
$(document).ready(function () {
  // Определим ширину overflow, чтобы избежать дёрганий при клике (сделаем константой, чтобы использовать ещё и в модальке)
  const overflow_document =
    window.innerWidth - document.getElementsByTagName("html")[0].clientWidth;
  // Модальное окно подарка
  // Слушаем клик на кнопку вызова
  document
    .querySelector(".loyalnost__gift")
    .addEventListener("click", function () {
      document.querySelector(".gift__modal").classList.add("active");
      document.querySelector(".overlay").classList.add("showoverlay");
      document.querySelector(".bx_rt_wrapper").style.position = "static";
    });
  // Слушаем клик на закрыть
  document
    .querySelector(".gift__close svg")
    .addEventListener("click", function () {
      document.querySelector(".gift__modal").classList.remove("active");
      document.querySelector(".overlay").classList.remove("showoverlay");
      document.querySelector(".bx_rt_wrapper").style.position = "sticky";
    });
  // Добавим свайп на закрытие) (Ради одной модальки, не буду заморачиваться и вешать touchend)
  // Вешаем на прикосновение функцию handleTouchStart
  document
    .querySelector(".sidePanel__mera")
    .addEventListener("touchstart", handleTouchStart);
  // А на движение пальцем по экрану - handleTouchMove
  document
    .querySelector(".sidePanel__mera")
    .addEventListener("touchmove", handleTouchMove);
  document
    .querySelector(".sidePanel__note")
    .addEventListener("touchstart", handleTouchStart);
  document
    .querySelector(".sidePanel__note")
    .addEventListener("touchmove", handleTouchMove);
  document
    .querySelector(".sidePanel__header")
    .addEventListener("touchstart", handleTouchStart);
  document
    .querySelector(".sidePanel__header")
    .addEventListener("touchmove", handleTouchMove);
  document
    .querySelector(".sidepanel__footer")
    .addEventListener("touchstart", handleTouchStart);
  document
    .querySelector(".sidepanel__footer")
    .addEventListener("touchmove", handleTouchMove);
  var xDown = null;
  var yDown = null;
  function handleTouchStart(evt) {
    xDown = evt.touches[0].clientX;
    yDown = evt.touches[0].clientY;
  }
  function handleTouchMove(evt) {
    if (!xDown || !yDown) {
      return;
    }
    var xUp = evt.touches[0].clientX;
    var yUp = evt.touches[0].clientY;
    var xDiff = xDown - xUp;
    var yDiff = yDown - yUp;
    //Тут берутся модули движения по оси абсцисс и ординат (Потому что если движение сделано влево или вниз, то его показатель будет отрицательным) и сравнивается, чего было больше: движения по абсциссам или ординатам. Нужно это для того, чтобы, если пользователь провел вправо, но немного наискосок вниз, сработал именно коллбэк для движения вправо, а ни как-то иначе.
    if (Math.abs(xDiff) > Math.abs(yDiff)) {
      if (xDiff > 0) {
        // Влево
      } else {
        // Вправо
        document
          .querySelector(".sidepanel__block")
          .classList.remove("showside");
        document.querySelector(".overlay").classList.remove("showoverlay");
        document.querySelector(".bx_rt_wrapper").css("position", "sticky");
        document.querySelector("body").style.overflow = "visible";
        document.querySelector("body").style.paddingRight = "px";
      }
    } else {
      if (yDiff > 0) {
        /* Вверх */
      } else {
        /* Вниз */
      }
    }
    /* reset значений по x и по y */
    xDown = null;
    yDown = null;
  }

  // Показываем
  $('[data-id="tablsize"]').click(function () {
    $(".sidepanel__block").addClass("showside");
    $("body").css("padding-right", overflow_document);
    $("body").css("overflow", "hidden");
    $(".overlay").addClass("showoverlay");
    $(".bx_rt_wrapper").css("position", "static");
  });
  // Скрываем
  $(".sidePanel__CloseModule").click(function () {
    $(".sidepanel__block").removeClass("showside");
    $(".overlay").removeClass("showoverlay");
    $("body").css("padding-right", "0");
    $("body").css("overflow", "visible");
    $(".bx_rt_wrapper").css("position", "sticky");
    return false;
  });
  // Скрываем по клику вне блока
  $(document).mouseup(function (e) {
    let sidepanel = $("[data-id=sidepanel]");
    if (!sidepanel.is(e.target) && sidepanel.has(e.target).length === 0) {
      sidepanel.removeClass("showside");
      sidepanel.removeClass("active");
      $(".overlay").removeClass("showoverlay");
      $("body").css("padding-right", "0");
      $("body").css("overflow", "visible");
      $(".bx_rt_wrapper").css("position", "sticky");
    }
  });
  // Проверяем ширину и высоту внутреннего блока по отношению к родителю
  Element.prototype.isOverflowing = function () {
    return (
      this.scrollHeight > this.clientHeight ||
      this.scrollWidth > this.clientWidth
    );
  };
  let parentblock = document.querySelector(".sidePanel__sizestableflex");
  let arrowscroll = document.querySelector(".sidePanel__arrowscroll");
  let isOverflowing = function () {
    // условие, если дочерний элемент больше родителя
    if (parentblock.isOverflowing()) {
      arrowscroll.classList.add("showscroll");
    } else {
      arrowscroll.classList.remove("showscroll");
    }
  };
  // событие ресайза и загрузки страницы
  window.onload = isOverflowing;
  window.addEventListener("resize", isOverflowing);

  // Фиксация кнопок купить и размеров при скроле

  let sizes = document.querySelector(".scu_block");
  let buyButtons = document.querySelector(".bx_item_buttons");
  let buttons = document.querySelector(".fixed-wrapper");
  let width = document.documentElement.clientWidth;

  // console.log(width);

  document.addEventListener("scroll", () => {
    let posTop =
      window.pageYOffset !== undefined
        ? window.pageYOffset
        : (
            document.documentElement ||
            document.body.parentNode ||
            document.body
          ).scrollTop;

    if (posTop >= 250 && width >= 1202) {
      sizes.classList.add("fixed");
      sizes.style.zIndex = "999999";

      buyButtons.classList.add("fixed");
      buyButtons.classList.add("plus_top");
      buyButtons.style.zIndex = "999999";
      buyButtons.style.right = "auto";

      buttons.classList.add("fixed");
      buttons.style.display = "flex";
    } else {
      sizes.classList.remove("fixed");
      sizes.style.zIndex = "inherit";

      buyButtons.classList.remove("fixed");
      buyButtons.classList.remove("plus_top");
      buyButtons.style.zIndex = "inherit";
      buyButtons.style.right = "40px";

      buttons.classList.remove("fixed");
      buttons.style.display = "none";
    }
  });
});
