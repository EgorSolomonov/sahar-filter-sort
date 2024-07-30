$(document).ready(function () {
  var windowW = document.documentElement.clientWidth;
  if (windowW > 767) {
    $(".blo").click(function () {
      $(".blo").toggleClass("blo_active");
      $(".krayt_filter_block").toggleClass("krayt_filter_block_active");
      $(".krayt_content_block").toggleClass("krayt_content_block_active");
    });
    $(".krayt_filter_block").css("background-color", "#f5f5f5");
    function autonheight() {
      var height = $(".krayt_content_block ").height();
      $(".krayt_filter_block").css("height", height + 6);
    }
    autonheight();
    $(window).resize(function () {
      autonheight();
    });
  } else {
    $(".blo").removeClass("blo_active");
    $(".krayt_filter_block").hide();
    if ($(".close_mobile_filter")) {
      $(".close_mobile_filter").click(function () {
        $(".blo").removeClass("blo_active");
        $(".krayt_filter_block").hide();
      });
    }
    $(".blo").click(function () {
      $(".blo").toggleClass("blo_active");
      if ($(".blo").hasClass("blo_active")) {
        $(".krayt_filter_block").slideDown(300);
      } else {
        $(".krayt_filter_block").slideUp(300);
      }
    });
  }
});
