/**
 * Theme: Appzia - Bootstrap 4 Admin Template
 * Author: Themesdesign
 * Module/App: Main Js
 */

!(function (e) {
    "use strict";
    var t = function () {
        (this.$body = e("body")), (this.$openLeftBtn = e(".open-left")), (this.$menuItem = e("#sidebar-menu a"));
    };
    (t.prototype.openLeftBar = function () {
        e("#wrapper").toggleClass("enlarged"),
            e("#wrapper").addClass("forced"),
            e("#wrapper").hasClass("enlarged") && e("body").hasClass("fixed-left")
                ? e("body").removeClass("fixed-left").addClass("fixed-left-void")
                : !e("#wrapper").hasClass("enlarged") && e("body").hasClass("fixed-left-void") && e("body").removeClass("fixed-left-void").addClass("fixed-left"),
            e("#wrapper").hasClass("enlarged") ? e(".left ul").removeAttr("style") : e(".subdrop").siblings("ul:first").show(),
            toggle_slimscroll(".slimscrollleft"),
            e("body").trigger("resize");
    }),
        (t.prototype.menuItemClick = function (t) {
            e("#wrapper").hasClass("enlarged") ||
            (e(this).parent().hasClass("has_sub") && t.preventDefault(),
                e(this).hasClass("subdrop")
                    ? e(this).hasClass("subdrop") && (e(this).removeClass("subdrop"), e(this).next("ul").slideUp(350), e(".float-right i", e(this).parent()).removeClass("mdi-minus").addClass("mdi-plus"))
                    : (e("ul", e(this).parents("ul:first")).slideUp(350),
                        e("a", e(this).parents("ul:first")).removeClass("subdrop"),
                        e("#sidebar-menu .float-right i").removeClass("mdi-minus").addClass("mdi-plus"),
                        e(this).next("ul").slideDown(350),
                        e(this).addClass("subdrop"),
                        e(".float-right i", e(this).parents(".has_sub:last")).removeClass("mdi-plus").addClass("mdi-minus"),
                        e(".float-right i", e(this).siblings("ul")).removeClass("mdi-minus").addClass("mdi-plus")));
        }),
        (t.prototype.init = function () {
            var t = this;
            e(".open-left").click(function (e) {
                e.stopPropagation(), t.openLeftBar();
            }),
                t.$menuItem.on("click", t.menuItemClick),
                e("#sidebar-menu ul li.has_sub a.active").parents("li:last").children("a:first").addClass("active").trigger("click");
        }),
        (e.Sidemenu = new t()),
        (e.Sidemenu.Constructor = t);
})(window.jQuery),
    (function (e) {
        "use strict";
        var t = function () {
            (this.$body = e("body")), (this.$fullscreenBtn = e("#btn-fullscreen"));
        };
        (t.prototype.launchFullscreen = function (e) {
            e.requestFullscreen ? e.requestFullscreen() : e.mozRequestFullScreen ? e.mozRequestFullScreen() : e.webkitRequestFullscreen ? e.webkitRequestFullscreen() : e.msRequestFullscreen && e.msRequestFullscreen();
        }),
            (t.prototype.exitFullscreen = function () {
                document.exitFullscreen ? document.exitFullscreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitExitFullscreen && document.webkitExitFullscreen();
            }),
            (t.prototype.toggle_fullscreen = function () {
                (document.fullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled) &&
                (document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement ? this.exitFullscreen() : this.launchFullscreen(document.documentElement));
            }),
            (t.prototype.init = function () {
                var e = this;
                e.$fullscreenBtn.on("click", function () {
                    e.toggle_fullscreen();
                });
            }),
            (e.FullScreen = new t()),
            (e.FullScreen.Constructor = t);
    })(window.jQuery),
    (function (e) {
        "use strict";
        var t = function () {
            (this.$body = e("body")), (this.$portletIdentifier = ".portlet"), (this.$portletCloser = '.portlet a[data-toggle="remove"]'), (this.$portletRefresher = '.portlet a[data-toggle="reload"]');
        };
        (t.prototype.init = function () {
            var t = this;
            e(document).on("click", this.$portletCloser, function (i) {
                i.preventDefault();
                var s = e(this).closest(t.$portletIdentifier),
                    n = s.parent();
                s.remove(), 0 == n.children().length && n.remove();
            }),
                e(document).on("click", this.$portletRefresher, function (i) {
                    i.preventDefault();
                    var s = e(this).closest(t.$portletIdentifier);
                    s.append('<div class="panel-disabled"><div class="loader-1"></div></div>');
                    var n = s.find(".panel-disabled");
                    setTimeout(function () {
                        n.fadeOut("fast", function () {
                            n.remove();
                        });
                    }, 500 + 5 * Math.random() * 300);
                });
        }),
            (e.Portlet = new t()),
            (e.Portlet.Constructor = t);
    })(window.jQuery),
    (function (e) {
        "use strict";
        var t = function () {
            (this.VERSION = "1.1.0"), (this.AUTHOR = "ThemesDesign"), (this.SUPPORT = "#"), (this.pageScrollElement = "html, body"), (this.$body = e("body"));
        };
        (t.prototype.initTooltipPlugin = function () {
            e.fn.tooltip && e('[data-toggle="tooltip"]').tooltip();
        }),
            (t.prototype.initPopoverPlugin = function () {
                e.fn.popover && e('[data-toggle="popover"]').popover();
            }),
            (t.prototype.initNiceScrollPlugin = function () {
                e.fn.niceScroll && e(".nicescroll").niceScroll({ cursorcolor: "#9d9ea5", cursorborderradius: "0px" });
            }),
            (t.prototype.onDocReady = function (t) {
                FastClick.attach(document.body),
                    Menufunction.push("initscrolls"),
                    Menufunction.push("changeptype"),
                    e(".animate-number").each(function () {
                        e(this).animateNumbers(e(this).attr("data-value"), !0, parseInt(e(this).attr("data-duration")));
                    }),
                    e(window).resize(debounce(resizeitems, 100)),
                    e("body").trigger("resize");
            }),
            (t.prototype.init = function () {
                this.initTooltipPlugin(), this.initPopoverPlugin(), this.initNiceScrollPlugin(), e(document).ready(this.onDocReady), e.Portlet.init(), e.Sidemenu.init(), e.FullScreen.init();
            }),
            (e.AppziaApp = new t()),
            (e.AppziaApp.Constructor = t);
    })(window.jQuery),
    (function (e) {
        "use strict";
        window.jQuery.AppziaApp.init();
    })();
var w,
    h,
    dw,
    dh,
    toggle_fullscreen = function () {};
function executeFunctionByName(e, t) {
    for (var i = [].slice.call(arguments).splice(2), s = e.split("."), n = s.pop(), l = 0; l < s.length; l++) t = t[s[l]];
    return t[n].apply(this, i);
}
var changeptype = function () {
        (w = $(window).width()),
            (h = $(window).height()),
            (dw = $(document).width()),
            (dh = $(document).height()),
        !0 === jQuery.browser.mobile && $("body").addClass("mobile").removeClass("fixed-left"),
        $("#wrapper").hasClass("forced") ||
        (w > 1024
            ? ($("body").removeClass("smallscreen").addClass("widescreen"), $("#wrapper").removeClass("enlarged"))
            : ($("body").removeClass("widescreen").addClass("smallscreen"), $("#wrapper").addClass("enlarged"), $(".left ul").removeAttr("style")),
            $("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left")
                ? $("body").removeClass("fixed-left").addClass("fixed-left-void")
                : !$("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left-void") && $("body").removeClass("fixed-left-void").addClass("fixed-left")),
            toggle_slimscroll(".slimscrollleft");
    },
    debounce = function (e, t, i) {
        var s, n;
        return function () {
            var l = this,
                o = arguments,
                r = i && !s;
            return (
                clearTimeout(s),
                    (s = setTimeout(function () {
                        (s = null), i || (n = e.apply(l, o));
                    }, t)),
                r && (n = e.apply(l, o)),
                    n
            );
        };
    };
function resizeitems() {
    if ($.isArray(Menufunction)) for (i = 0; i < Menufunction.length; i++) window[Menufunction[i]]();
}
function initscrolls() {
    !0 !== jQuery.browser.mobile && ($(".slimscroller").slimscroll({ height: "auto", size: "5px" }), $(".slimscrollleft").slimScroll({ height: "auto", position: "right", size: "5px", color: "#7A868F", wheelStep: 5 }));
}
function toggle_slimscroll(e) {
    $("#wrapper").hasClass("enlarged")
        ? ($(e).css("overflow", "inherit").parent().css("overflow", "inherit"), $(e).siblings(".slimScrollBar").css("visibility", "hidden"))
        : ($(e).css("overflow", "hidden").parent().css("overflow", "hidden"), $(e).siblings(".slimScrollBar").css("visibility", "visible"));
}
var wow = new WOW({ boxClass: "wow", animateClass: "animated", offset: 50, mobile: !1 });
wow.init(),
    $(document).ready(function () {
        $("#sidebar-menu a").each(function () {
            this.href == window.location.href && ($(this).addClass("active"), $(this).parent().addClass("active"), $(this).parent().parent().prev().addClass("active"), $(this).parent().parent().prev().trigger("click"));
        });
    });
var Menufunction = [];
