class l{static init(){var e=$(".sidebar"),i=e.find(".sidebar-background"),t=$(".full-page"),r=$("body > .navbar-collapse");sidebar_mini_active=!0,$(window).width(),$(".sidebar .sidebar-wrapper .nav li.active a p").html(),$(".fixed-plugin a").click(function(a){$(this).hasClass("switch-trigger")&&(a.stopPropagation?a.stopPropagation():window.event&&(window.event.cancelBubble=!0))}),$(".fixed-plugin .active-color span").click(function(){$(".full-page-background"),$(this).siblings().removeClass("active"),$(this).addClass("active");var a=$(this).data("color");e.length!=0&&e.attr("data-active-color",a),t.length!=0&&t.attr("data-active-color",a),r.length!=0&&r.attr("data-active-color",a)}),$(".fixed-plugin .background-color span").click(function(){$(this).siblings().removeClass("active"),$(this).addClass("active");var a=$(this).data("color");e.length!=0&&e.attr("data-color",a),t.length!=0&&t.attr("filter-color",a),r.length!=0&&r.attr("data-color",a)}),$(".fixed-plugin .img-holder").click(function(){$full_page_background=$(".full-page-background"),$(this).parent("li").siblings().removeClass("active"),$(this).parent("li").addClass("active");var a=$(this).find("img").attr("src");if(i.length!=0&&$(".switch-sidebar-image input:checked").length!=0&&i.fadeOut("fast",function(){i.css("background-image",'url("'+a+'")'),i.fadeIn("fast")}),$full_page_background.length!=0&&$(".switch-sidebar-image input:checked").length!=0){var n=$(".fixed-plugin li.active .img-holder").find("img").data("src");$full_page_background.fadeOut("fast",function(){$full_page_background.css("background-image",'url("'+n+'")'),$full_page_background.fadeIn("fast")})}if($(".switch-sidebar-image input:checked").length==0){var a=$(".fixed-plugin li.active .img-holder").find("img").attr("src"),n=$(".fixed-plugin li.active .img-holder").find("img").data("src");i.css("background-image",'url("'+a+'")'),$full_page_background.css("background-image",'url("'+n+'")')}r.length!=0&&r.css("background-image",'url("'+a+'")')}),$(".switch-sidebar-image input").on("switchChange.bootstrapSwitch",function(){$full_page_background=$(".full-page-background"),$input=$(this),$input.is(":checked")?(i.length!=0&&(i.fadeIn("fast"),e.attr("data-image","#")),$full_page_background.length!=0&&($full_page_background.fadeIn("fast"),t.attr("data-image","#")),background_image=!0):(i.length!=0&&(e.removeAttr("data-image"),i.fadeOut("fast")),$full_page_background.length!=0&&(t.removeAttr("data-image","#"),$full_page_background.fadeOut("fast")),background_image=!1)}),$(".switch-mini input").on("switchChange.bootstrapSwitch",function(){$body=$("body"),$input=$(this),paperDashboard.misc.sidebar_mini_active==!0?($("body").removeClass("sidebar-mini"),paperDashboard.misc.sidebar_mini_active=!1,$(".sidebar .sidebar-wrapper, .main-panel").perfectScrollbar()):($(".sidebar .sidebar-wrapper, .main-panel").perfectScrollbar("destroy"),setTimeout(function(){$("body").addClass("sidebar-mini"),paperDashboard.misc.sidebar_mini_active=!0},300));var a=setInterval(function(){window.dispatchEvent(new Event("resize"))},180);setTimeout(function(){clearInterval(a)},1e3)})}}$(document).ready(function(){l.init()});
