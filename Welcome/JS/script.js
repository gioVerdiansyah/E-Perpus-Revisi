$(document).ready(function () {
let onclick = false;

    if (window.matchMedia("(max-width: 768px)").matches) {
        onclick = true;
        $("header").hide();
    } else {
        onclick = false;
    }

    $("#darkmode").click(function (e) { 
        e.preventDefault();
        $("#dm").attr("href") === "CSS/darkmode.css" ? $("#dm").removeAttr("href") : $("#dm").attr("href", "CSS/darkmode.css");
    
        $("#darkmode i").attr("class") === "fa-solid fa-moon" ? $("#darkmode i").attr("class", "fa-solid fa-sun") : $("#darkmode i").attr("class", "fa-solid fa-moon");
    });


    $("nav ul li button").click(function (e) {
        e.preventDefault();

        if (!onclick) {
            $("nav ul li button span:first-child").css({
                'transform-origin': '0 0',
                'transform': 'rotate(45deg) translate(1px, -1px)'
            });
            $("nav ul li button span:last-child").css({
                'transform-origin': '0 100%',
                'transform': 'rotate(-45deg) translate(1px, 1px)'
            });
            $("nav ul li button span:nth-child(2)").css({
                'opacity': 0,
                'transform': 'scale(0)'
            });
            $("header").slideUp(800);
            onclick = true;
        } else {
            $("nav ul li button span:first-child").css({
                'transform-origin': '0 0',
                'transform': 'rotate(0deg) translate(0px, 0px)'
            });
            $("nav ul li button span:last-child").css({
                'transform-origin': '0 0',
                'transform': 'rotate(0deg) translate(0px, 0px)'
            });
            $("nav ul li button span:nth-child(2)").css({
                'opacity': 1,
                'transform': 'scale(1)'
            });
            $("header").slideDown(1000);
            onclick = false;
        }
    });

    $("nav ul li:last-child").mouseenter(function() {
        $("nav ul li:last-child .dropdown-logout").stop().slideDown();
      });
      
      $("nav ul li:last-child").mouseleave(function() {
        $("nav ul li:last-child .dropdown-logout").stop().slideUp();
      });


    $("main").load("component/Home.php");
    
    function onClick(ini, path){
        $("main").load(path);
        $("*").removeClass("on");
        $(ini).addClass("on");
    }

    $("#home").click(function (e) { 
        e.preventDefault();
        onClick(this, "component/Home.php");
    });

    $("#cari-buku").click(function (e) { 
        e.preventDefault();
        onClick(this, "component/Buku.php");
    });

    $("#riwayat").click(function (e) { 
        e.preventDefault();
        onClick(this, "component/Peminjaman.php");
    });
    



//! Alert
var Alert = undefined;

(function(Alert) {
  var alert, success, warning, _container;
  warning = function(message, title, options) {
    return alert("warning", message, title, "fa-solid fa-triangle-exclamation", options);
  };

  success = function(message, title, options) {
    return alert("success", message, title, "fa-solid fa-circle-check", options);
  };
  alert = function(type, message, title, icon, options) {
    var alertElem, messageElem, titleElem, iconElem, innerElem, _container;
    if (typeof options === "undefined") {
      options = {};
    }
    options = $.extend({}, Alert.defaults, options);
    if (!_container) {
      _container = $("#alerts");
      if (_container.length === 0) {
        _container = $("<ul>").attr("id", "alerts").appendTo($("body"));
      }
    }
    if (options.width) {
      _container.css({
        width: options.width
      });
    }
    alertElem = $("<li>").addClass("alert").addClass("alert-" + type);
    setTimeout(function() {
      alertElem.addClass('open');
    }, 1);
    if (icon) {
      iconElem = $("<i>").addClass(icon);
      alertElem.append(iconElem);
    }
    innerElem = $("<div>").addClass("alert-block");
    //innerElem = $("<i>").addClass("fa fa-times");
    alertElem.append(innerElem);
    if (title) {
      titleElem = $("<div>").addClass("alert-title").append(title);
      innerElem.append(titleElem);
      
    }
    if (message) {
      messageElem = $("<div>").addClass("alert-message").append(message);
      //innerElem.append("<i class="fa fa-times"></i>");
      innerElem.append(messageElem);
      //innerElem.append("<em>Click to Dismiss</em>");
//      innerElemc = $("<i>").addClass("fa fa-times");

    }
    if (options.displayDuration > 0) {
      setTimeout((function() {
        leave();
      }), options.displayDuration);
    } else {
      innerElem.append("<em>Click to Dismiss</em>");
    }
    alertElem.on("click", function() {
      leave();
    });

    function leave() {
      alertElem.removeClass('open');
      alertElem.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function() {
        return alertElem.remove();
      });
    }
    return _container.prepend(alertElem);
  };
  Alert.defaults = {
    width: "",
    icon: "",
    displayDuration: 3000,
    pos: ""
  };
  Alert.warning = warning;
  Alert.success = success;
  return _container = void 0;

})(Alert || (Alert = {}));

this.Alert = Alert;

});