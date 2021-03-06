!function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : "object" == typeof exports ? module.exports = a : a(jQuery)

}(function (a) {
    function b(b) {
        var g = b || window.event, h = i.call(arguments, 1), j = 0, l = 0, m = 0, n = 0, o = 0, p = 0;
        if (b = a.event.fix(g), b.type = "mousewheel", "detail" in g && (m = -1 * g.detail), "wheelDelta" in g && (m = g.wheelDelta), "wheelDeltaY" in g && (m = g.wheelDeltaY), "wheelDeltaX" in g && (l = -1 * g.wheelDeltaX), "axis" in g && g.axis === g.HORIZONTAL_AXIS && (l = -1 * m, m = 0), j = 0 === m ? l : m, "deltaY" in g && (m = -1 * g.deltaY, j = m), "deltaX" in g && (l = g.deltaX, 0 === m && (j = -1 * l)), 0 !== m || 0 !== l) {
            if (1 === g.deltaMode) {
                var q = a.data(this, "mousewheel-line-height");
                j *= q, m *= q, l *= q
            } else if (2 === g.deltaMode) {
                var r = a.data(this, "mousewheel-page-height");
                j *= r, m *= r, l *= r
            }
            if (n = Math.max(Math.abs(m), Math.abs(l)), (!f || f > n) && (f = n, d(g, n) && (f /= 40)), d(g, n) && (j /= 40, l /= 40, m /= 40), j = Math[j >= 1 ? "floor" : "ceil"](j / f), l = Math[l >= 1 ? "floor" : "ceil"](l / f), m = Math[m >= 1 ? "floor" : "ceil"](m / f), k.settings.normalizeOffset && this.getBoundingClientRect) {
                var s = this.getBoundingClientRect();
                o = b.clientX - s.left, p = b.clientY - s.top
            }
            return b.deltaX = l, b.deltaY = m, b.deltaFactor = f, b.offsetX = o, b.offsetY = p, b.deltaMode = 0, h.unshift(b, j, l, m), e && clearTimeout(e), e = setTimeout(c, 200), (a.event.dispatch || a.event.handle).apply(this, h)
        }
    }

    function c() {
        f = null
    }

    function d(a, b) {
        return k.settings.adjustOldDeltas && "mousewheel" === a.type && b % 120 === 0
    }

    var e, f, g = ["wheel", "mousewheel", "DOMMouseScroll", "MozMousePixelScroll"], h = "onwheel" in document || document.documentMode >= 9 ? ["wheel"] : ["mousewheel", "DomMouseScroll", "MozMousePixelScroll"], i = Array.prototype.slice;
    if (a.event.fixHooks)for (var j = g.length; j;)a.event.fixHooks[g[--j]] = a.event.mouseHooks;
    var k = a.event.special.mousewheel = {
        version: "3.1.11", setup: function () {
            if (this.addEventListener)for (var c = h.length; c;)this.addEventListener(h[--c], b, !1); else this.onmousewheel = b;
            a.data(this, "mousewheel-line-height", k.getLineHeight(this)), a.data(this, "mousewheel-page-height", k.getPageHeight(this))
        }, teardown: function () {
            if (this.removeEventListener)for (var c = h.length; c;)this.removeEventListener(h[--c], b, !1); else this.onmousewheel = null;
            a.removeData(this, "mousewheel-line-height"), a.removeData(this, "mousewheel-page-height")
        }, getLineHeight: function (b) {
            var c = a(b)["offsetParent" in a.fn ? "offsetParent" : "parent"]();
            return c.length || (c = a("body")), parseInt(c.css("fontSize"), 10)
        }, getPageHeight: function (b) {
            return a(b).height()
        }, settings: {adjustOldDeltas: !0, normalizeOffset: !0}
    };
    a.fn.extend({
        mousewheel: function (a) {
            return a ? this.bind("mousewheel", a) : this.trigger("mousewheel")
        }, unmousewheel: function (a) {
            return this.unbind("mousewheel", a)
        }
    })
}), function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery)
}(function (a, b) {
    function c(a) {
        function b() {
            d ? (c(), M(b), e = !0, d = !1) : e = !1
        }

        var c = a, d = !1, e = !1;
        this.kick = function () {
            d = !0, e || b()
        }, this.end = function (a) {
            var b = c;
            a && (e ? (c = d ? function () {
                        b(), a()
                    } : a, d = !0) : a())
        }
    }

    function d() {
        return !0
    }

    function e() {
        return !1
    }

    function f(a) {
        a.preventDefault()
    }

    function g(a) {
        N[a.target.tagName.toLowerCase()] || a.preventDefault()
    }

    function h(a) {
        return 1 === a.which && !a.ctrlKey && !a.altKey
    }

    function i(a, b) {
        var c, d;
        if (a.identifiedTouch)return a.identifiedTouch(b);
        for (c = -1, d = a.length; ++c < d;)if (a[c].identifier === b)return a[c]
    }

    function j(a, b) {
        var c = i(a.changedTouches, b.identifier);
        if (c && (c.pageX !== b.pageX || c.pageY !== b.pageY))return c
    }

    function k(a) {
        var b;
        h(a) && (b = {
            target: a.target,
            startX: a.pageX,
            startY: a.pageY,
            timeStamp: a.timeStamp
        }, J(document, O.move, l, b), J(document, O.cancel, m, b))
    }

    function l(a) {
        var b = a.data;
        s(a, b, a, n)
    }

    function m() {
        n()
    }

    function n() {
        K(document, O.move, l), K(document, O.cancel, m)
    }

    function o(a) {
        var b, c;
        N[a.target.tagName.toLowerCase()] || (b = a.changedTouches[0], c = {
            target: b.target,
            startX: b.pageX,
            startY: b.pageY,
            timeStamp: a.timeStamp,
            identifier: b.identifier
        }, J(document, P.move + "." + b.identifier, p, c), J(document, P.cancel + "." + b.identifier, q, c))
    }

    function p(a) {
        var b = a.data, c = j(a, b);
        c && s(a, b, c, r)
    }

    function q(a) {
        var b = a.data, c = i(a.changedTouches, b.identifier);
        c && r(b.identifier)
    }

    function r(a) {
        K(document, "." + a, p), K(document, "." + a, q)
    }

    function s(a, b, c, d) {
        var e = c.pageX - b.startX, f = c.pageY - b.startY;
        I * I > e * e + f * f || v(a, b, c, e, f, d)
    }

    function t() {
        return this._handled = d, !1
    }

    function u(a) {
        a._handled()
    }

    function v(a, b, c, d, e, f) {
        {
            var g, h;
            b.target
        }
        g = a.targetTouches, h = a.timeStamp - b.timeStamp, b.type = "movestart", b.distX = d, b.distY = e, b.deltaX = d, b.deltaY = e, b.pageX = c.pageX, b.pageY = c.pageY, b.velocityX = d / h, b.velocityY = e / h, b.targetTouches = g, b.finger = g ? g.length : 1, b._handled = t, b._preventTouchmoveDefault = function () {
            a.preventDefault()
        }, L(b.target, b), f(b.identifier)
    }

    function w(a) {
        var b = a.data.timer;
        a.data.touch = a, a.data.timeStamp = a.timeStamp, b.kick()
    }

    function x(a) {
        var b = a.data.event, c = a.data.timer;
        y(), D(b, c, function () {
            setTimeout(function () {
                K(b.target, "click", e)
            }, 0)
        })
    }

    function y() {
        K(document, O.move, w), K(document, O.end, x)
    }

    function z(a) {
        var b = a.data.event, c = a.data.timer, d = j(a, b);
        d && (a.preventDefault(), b.targetTouches = a.targetTouches, a.data.touch = d, a.data.timeStamp = a.timeStamp, c.kick())
    }

    function A(a) {
        var b = a.data.event, c = a.data.timer, d = i(a.changedTouches, b.identifier);
        d && (B(b), D(b, c))
    }

    function B(a) {
        K(document, "." + a.identifier, z), K(document, "." + a.identifier, A)
    }

    function C(a, b, c) {
        var d = c - a.timeStamp;
        a.type = "move", a.distX = b.pageX - a.startX, a.distY = b.pageY - a.startY, a.deltaX = b.pageX - a.pageX, a.deltaY = b.pageY - a.pageY, a.velocityX = .3 * a.velocityX + .7 * a.deltaX / d, a.velocityY = .3 * a.velocityY + .7 * a.deltaY / d, a.pageX = b.pageX, a.pageY = b.pageY
    }

    function D(a, b, c) {
        b.end(function () {
            return a.type = "moveend", L(a.target, a), c && c()
        })
    }

    function E() {
        return J(this, "movestart.move", u), !0
    }

    function F() {
        return K(this, "dragstart drag", f), K(this, "mousedown touchstart", g), K(this, "movestart", u), !0
    }

    function G(a) {
        "move" !== a.namespace && "moveend" !== a.namespace && (J(this, "dragstart." + a.guid + " drag." + a.guid, f, b, a.selector), J(this, "mousedown." + a.guid, g, b, a.selector))
    }

    function H(a) {
        "move" !== a.namespace && "moveend" !== a.namespace && (K(this, "dragstart." + a.guid + " drag." + a.guid), K(this, "mousedown." + a.guid))
    }

    var I = 6, J = a.event.add, K = a.event.remove, L = function (b, c, d) {
        a.event.trigger(c, d, b)
    }, M = function () {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (a) {
                return window.setTimeout(function () {
                    a()
                }, 25)
            }
    }(), N = {textarea: !0, input: !0, select: !0, button: !0}, O = {
        move: "mousemove",
        cancel: "mouseup dragstart",
        end: "mouseup"
    }, P = {move: "touchmove", cancel: "touchend", end: "touchend"};
    a.event.special.movestart = {
        setup: E, teardown: F, add: G, remove: H, _default: function (a) {
            function d() {
                C(f, g.touch, g.timeStamp), L(a.target, f)
            }

            var f, g;
            a._handled() && (f = {
                target: a.target,
                startX: a.startX,
                startY: a.startY,
                pageX: a.pageX,
                pageY: a.pageY,
                distX: a.distX,
                distY: a.distY,
                deltaX: a.deltaX,
                deltaY: a.deltaY,
                velocityX: a.velocityX,
                velocityY: a.velocityY,
                timeStamp: a.timeStamp,
                identifier: a.identifier,
                targetTouches: a.targetTouches,
                finger: a.finger
            }, g = {
                event: f,
                timer: new c(d),
                touch: b,
                timeStamp: b
            }, a.identifier === b ? (J(a.target, "click", e), J(document, O.move, w, g), J(document, O.end, x, g)) : (a._preventTouchmoveDefault(), J(document, P.move + "." + a.identifier, z, g), J(document, P.end + "." + a.identifier, A, g)))
        }
    }, a.event.special.move = {
        setup: function () {
            J(this, "movestart.move", a.noop)
        }, teardown: function () {
            K(this, "movestart.move", a.noop)
        }
    }, a.event.special.moveend = {
        setup: function () {
            J(this, "movestart.moveend", a.noop)
        }, teardown: function () {
            K(this, "movestart.moveend", a.noop)
        }
    }, J(document, "mousedown.move", k), J(document, "touchstart.move", o), "function" == typeof Array.prototype.indexOf && !function (a) {
        for (var b = ["changedTouches", "targetTouches"], c = b.length; c--;)-1 === a.event.props.indexOf(b[c]) && a.event.props.push(b[c])
    }(a)
}), function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery)
}(function (a) {
    function b(a) {
        var b, c, d;
        b = a.currentTarget.offsetWidth, c = a.currentTarget.offsetHeight, d = {
            distX: a.distX,
            distY: a.distY,
            velocityX: a.velocityX,
            velocityY: a.velocityY,
            finger: a.finger
        }, a.distX > a.distY ? a.distX > -a.distY ? (a.distX / b > g.threshold || a.velocityX * a.distX / b * g.sensitivity > 1) && (d.type = "swiperight", f(a.currentTarget, d)) : (-a.distY / c > g.threshold || a.velocityY * a.distY / b * g.sensitivity > 1) && (d.type = "swipeup", f(a.currentTarget, d)) : a.distX > -a.distY ? (a.distY / c > g.threshold || a.velocityY * a.distY / b * g.sensitivity > 1) && (d.type = "swipedown", f(a.currentTarget, d)) : (-a.distX / b > g.threshold || a.velocityX * a.distX / b * g.sensitivity > 1) && (d.type = "swipeleft", f(a.currentTarget, d))
    }

    function c(b) {
        var c = a.data(b, "event_swipe");
        return c || (c = {count: 0}, a.data(b, "event_swipe", c)), c
    }

    var d = a.event.add, e = a.event.remove, f = function (b, c, d) {
        a.event.trigger(c, d, b)
    }, g = {threshold: .4, sensitivity: 6};
    a.event.special.swipe = a.event.special.swipeleft = a.event.special.swiperight = a.event.special.swipeup = a.event.special.swipedown = {
        setup: function (a) {
            var a = c(this);
            if (!(a.count++ > 0))return d(this, "moveend", b), !0
        }, teardown: function () {
            var a = c(this);
            if (!(--a.count > 0))return e(this, "moveend", b), !0
        }, settings: g
    }
}), function (a) {
    a.fn.avController = function (b) {
        function c(a, b) {
            var e;
            d.trigger({type: a}), e = b ? 500 : 1e3 * f.stepTime, g = setTimeout(function () {
                c(a, !1)
            }, e)
        }

        var d = this, e = {playIsActive: !0, stepTime: .2}, f = a.extend({}, e, b);
        this.append('<div id="relativediv"><img id="prev" class="imag" src="' + b.path + 'img/controller/next.png" alt="prev"><img id="play" class="imag" src="' + b.path + 'img/controller/play.png" alt="play"><span id="pause-span" class="imag"><img id="pause" src="' + b.path + 'img/controller/pause.png" alt="pause"></span><img id="next" class="imag" src="' + b.path + 'img/controller/next.png" alt="next"></div>'), $imag = this.find(".imag"), $play = this.find(".play"), $pause = this.find(".pause"), $prev = this.find(".prev"), $next = this.find(".next"), f.playIsActive ? ($play.css("visibility", "visible"), $pause.css("visibility", "hidden")) : ($pause.css("visibility", "visible"), $play.css("visibility", "hidden")), $imag.on("mousedown", function () {
            a(this).css("top", 1).css("left", 1)
        }), $imag.on("mouseup mouseleave", function () {
            a(this).css("top", "auto").css("left", "auto")
        }), $play.on("click", function () {
            playIsActive = !1, a(this).css("visibility", "hidden"), d.find(".pause").css("visibility", "visible"), d.trigger({type: "play"})
        }), $pause.on("click", function () {
            playIsActive = !0, a(this).css("visibility", "hidden"), d.find(".play").css("visibility", "visible"), d.trigger({type: "pause"})
        });
        var g;
        return $prev.on("mousedown", function () {
            c("prev", !0)
        }), $next.on("mousedown", function () {
            c("next", !0)
        }), $prev.on("mouseup mouseleave", function () {
            clearTimeout(g)
        }), $next.on("mouseup mouseleave", function () {
            clearTimeout(g)
        }), this
    }
}(jQuery), function (a) {
    a.fn.avScrollBar = function (b) {
        function c(a, b, d) {
            var e;
            k.changeStep(a), e = b ? 500 : 1e3 * j.stepTime, void 0 === d || null === d ? q = setTimeout(function () {
                    c(a, !1)
                }, e) : (h > d || d > h + f) && (q = setTimeout(function () {
                    c(a, !1, d)
                }, e))
        }

        var d, e, f, g, h, i = {
            nrOfSteps: 10,
            startStep: 6,
            stepTime: .2,
            transitionFunction: "ease-out",
            transitionDuration: .1
        }, j = a.extend({}, i, b), k = this, l = j.startStep - 1;
        this.append('<div class="firstArrow"><div class="firstTriangle"></div></div><div class="dragArea"><div class="tracker"></div></div><div class="lastArrow"><div class="lastTriangle"></div></div>');
        var m = this.find(".dragArea"), n = this.find(".firstArrow"), o = this.find(".lastArrow"), p = this.find(".tracker");
        this.trackWidth = p.width() ? "non-auto" : "auto", this.setNrOfSteps = function (a) {
            j.nrOfSteps = a
        }, this.setCurrentStep = function (a) {
            l = a
        }, this.refreshPositions = function () {
            "none" === n.css("display") ? (e = this.width(), m.css("width", this.width()).css("height", this.height())) : (e = this.width() - 2 * this.height(), m.css("width", e).css("height", this.height())), f = "auto" === this.trackWidth ? Math.round(e / j.nrOfSteps * 2) : p.width(), p.css("width", f), d = e - f - 4, g = d / (j.nrOfSteps - 1), h = l * g, p.addClass("notransition"), p.css("left", h), p[0].offsetHeight, p.removeClass("notransition")
        }, this.refreshPositions(), p.draggable({
            axis: "x", containment: "parent", drag: function () {
                h = parseInt(p.css("left"));
                var a, b = Math.floor(g);
                a = Math.floor(h / b), h % b > b / 2 && a++, a !== l && (l = a, k.trigger({
                    type: "change",
                    current: l + 1
                }))
            }, start: function () {
                p.addClass("notransition")
            }, stop: function () {
                var a = Math.floor(g);
                h = parseInt(p.css("left")), l = Math.floor(h / a), h % a > a / 2 && l++, p[0].offsetHeight, p.removeClass("notransition"), h = l * g, p.css("left", h)
            }
        }), this.changeStep = function (a, b) {
            var c;
            c = "number" == typeof a ? a - 1 : "prev" === a ? l - 1 : l + 1, c >= 0 && c < j.nrOfSteps && (l = c, h = l * g, p.css("left", h), (void 0 === b || null === b) && this.trigger({
                type: "change",
                current: l + 1
            }))
        };
        var q, r = !1;
        return n.on("mousedown", function () {
            c("prev", !0)
        }), o.on("mousedown", function () {
            c("next", !0)
        }), n.on("mouseup mouseleave", function () {
            clearTimeout(q)
        }), o.on("mouseup mouseleave", function () {
            clearTimeout(q)
        }), p.on("mousedown", function () {
            r = !0
        }), p.on("mouseup mouseleave", function () {
            r = !1
        }), m.on("mousedown", function (a) {
            if (!r) {
                var b = a.clientX - m.offset().left;
                h > b ? c("prev", !0, b) : b > h + f && c("next", !0, b)
            }
        }), m.on("mouseup mouseleave", function () {
            clearTimeout(q)
        }), this
    }
}(jQuery);


!function (a, b) {
    a.fn.coverflow = function (c) {
        function d() {
            v = H.width(), w = C.width(), x = B.width()
        }

        function e() {
            N && (M > v ? (E.width(v), E.refreshPositions()) : E.width() < M && (E.width(M), E.refreshPositions()))
        }

        function f(b) {
            var c = i;
            if ("number" == typeof b ? b > 0 && g >= b && (i = b) : i = parseInt(a(this).parent().attr("id")), c !== i) {
                H.trigger({type: "change", current: i}), H.recenterCF();
                var d = B.eq(i - 1);
                d.find(".imgdiv").removeClass().addClass("imgdiv straight"), d.removeClass("leftLI rightLI").addClass("straightLI"), i > c ? C.slice(c - 1, i - 1).removeClass().addClass("imgdiv leftItems").parent().removeClass("rightLI straightLI").addClass("leftLI") : C.slice(i, c).removeClass().addClass("imgdiv rightItems").parent().removeClass("leftLI straightLI").addClass("rightLI"), j = g * k, clearTimeout(y), y = setTimeout(function () {
                    C.each(function (b) {
                        j += i > b ? k : -k, a(this).parent().css("z-index", j)
                    })
                }, A.css("transition-duration").slice(0, -1) / 4 * 1e3);
                var e = d.find(".text"), f = B.eq(c - 1).find(".text"), h = 1e3 * parseFloat(e.css("transition-duration"));
                h = parseInt(.6 * h), f.addClass("notransition").css("visibility", "hidden").css("opacity", 0), f.length && f[0].offsetHeight, f.removeClass("notransition"), e.css("visibility", "visible"), clearTimeout(u), u = setTimeout(function () {
                    e.css("opacity", 1)
                }, h);
                var l = d.find("a").first(), m = B.eq(c - 1).find("a").first();
                l.unbind("click"), m.on("click", function (a) {
                    a.preventDefault()
                }), K && ("gallery" !== l.attr("data-gallery") || l.hasClass("boxed") || l.boxer(), "gallery" === m.attr("data-gallery") && m.boxer("destroy"))
            }
        }

        var g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F = {path: "coverflow/"}, G = a.extend({}, F, c), H = this, I = 140, J = a('<div class="Lightbox"></div>').appendTo(H), K = "block" === H.find(".Lightbox").css("display");
        J.remove(), "function" == typeof aW && aW(H), H.buildCoverflow = function () {
            z = H.find(".covers"), A = H.find("ul"), B = H.find("li"), C = H.find(".imgdiv"), D = C.find("a"), g = B.length, i = h = Math.round(g / 2), k = 100, j = g * k, B.each(function (b) {
                var c = a(this), d = b + 1;
                c.attr("id", d), j += h >= d ? k : -k, c.css("z-index", j)
            }), D.on("click", function (a) {
                a.preventDefault()
            }), D.eq(h - 1).unbind("click"), B.eq(h - 1).find(".text").css("visibility", "visible").css("opacity", 1), C.on("click", f)
        }, H.buildCoverflow(), A.addClass("notransition"), B.addClass("notransition"), C.addClass("notransition");
        var L = a('<div class="refl"></div>').appendTo(H);
        "block" === H.find(".refl").css("display") && C.each(function () {
            var b = a(this);
            b.find("img").clone().appendTo(b).wrap('<div class="refl"></div>')
        }), L.remove(), H.refreshRotations = function () {
            i = h = Math.round(g / 2);
            var a = B.eq(i - 1);
            a.find(".imgdiv").removeClass().addClass("imgdiv straight"), a.removeClass("leftLI rightLI").addClass("straightLI"), C.slice(0, i - 1).removeClass().addClass("imgdiv leftItems").parent().removeClass("rightLI straightLI").addClass("leftLI"), C.slice(i, g).removeClass().addClass("imgdiv rightItems").parent().removeClass("leftLI straightLI").addClass("rightLI"), B.find(".text").css("visibility", "hidden").css("opacity", 0), B.eq(h - 1).find(".text").css("visibility", "visible").css("opacity", 1), S && (E.setNrOfSteps(g), E.setCurrentStep(h - 1), E.refreshPositions())
        }, H.refreshScrollbarPositions = function () {
            E.refreshPositions()
        }, H.setTrackWidth = function (a) {
            E.trackWidth = a
        }, d(), H.on("getcssvalues", d);
        var M, N = !1;
        H.recenterCF = function () {
            A.css("transform", "translate3d(" + (v / 2 - w / 2 - (i - 1) * x) + "px, 0, 0)")
        }, H.recenterCF(), a(b).on("resize", function () {
            d(), H.recenterCF(), e()
        }), H.on("scrollbarloaded", function () {
            N = !0, M = E.width(), e()
        }), H.on("bind-mousewheel", function () {
            H.on("mousewheel", function (a) {
                a.preventDefault(), f(i - a.deltaY)
            }), H.css("overflow", "hidden")
        }), H.on("unbind-mousewheel", function () {
            H.unbind("mousewheel"), H.css("overflow", "visible")
        }), "hidden" === H.css("overflow") && setTimeout(function () {
            H.trigger("bind-mousewheel")
        }, 100);
        var O = function (c) {
            var d = a(b).scrollTop();
            c.focus(), a(b).scrollTop(d)
        };
        H.on("bind-arrowkeys", function () {
            H.attr("tabindex", 1), H.on("keydown", function (a) {
                if (37 === a.keyCode) {
                    var b = i - 1;
                    a.preventDefault(), f(b)
                }
                if (39 === a.keyCode) {
                    var b = i + 1;
                    a.preventDefault(), f(b)
                }
            }), O(H), H.css("outline", "solid 0px")
        }), H.on("unbind-arrowkeys", function () {
            H.unbind("keydown"), H.css("outline", "none")
        }), "solid" === H.css("outline-style") && H.trigger("bind-arrowkeys"), z.on("movestart", function () {
            m = 0, o = I / 3, p = 0, q = 0, s = 0, t = .001
        }), z.on("move", function (a) {
            n = a.distX - m, r = Math.abs(n), 0 === p && (p = n > 0 ? 1 : -1), r > o && (m = a.distX, o = I, .8 > t && (f(i - p), t = Math.abs(a.velocityX)), p = 0, q = 0, s = 0), p > 0 ? n > q ? q = n : q - 20 > n && (p = q = s = 0, m = a.distX, f(i + 1)) : 0 > p && (s > n ? s = n : n > s + 20 && (p = q = s = 0, m = a.distX, f(i - 1)))
        });
        var P = a.Deferred(), Q = a.Deferred(), R = a.Deferred();
        H.refreshLightbox = function () {
            if (K) {
                D.each(function () {
                    a(this).boxer("destroy")
                });
                var b = D.eq(i - 1);
                "gallery" === b.attr("data-gallery") && b.boxer()
            }
        }, K ? a.get(G.path + "js/jquery.fs.boxer.min.css", function (b) {
                a("<style>" + b + "</style>").appendTo("head"), a.getScript(G.path + "js/jquery.fs.boxer.min.js", function () {
                    H.refreshLightbox(), R.resolve()
                })
            }) : R.resolve();
        var S = "block" === H.find(".ScrollBar").css("display");
        this.loadScrollbar = function () {
            a.getScript(G.path + "/js/jquery-ui-1.10.4.draggable.min.js", function () {
                E = a(".ScrollBar").avScrollBar({nrOfSteps: g, startStep: h}), E.on("change", function (a) {
                    f(a.current)
                }), H.trigger("scrollbarloaded"), Q.resolve()
            })
        }, S ? this.loadScrollbar() : Q.resolve(), H.on("change", function (a) {
            S && E.changeStep(a.current, "dontTrigger"), H.trigger({type: "restartPreloader"})
        });
        var T, U = "block" === H.find(".Controller").css("display"), V = a('<div class="Preloader"></div>').appendTo(H), W = "block" === H.find(".Preloader").css("display");
        return this.loadController = function () {
            function b() {
                e ? i === g ? (e = !1, f(i - 1)) : f(i + 1) : 1 === i ? (e = !0, f(i + 1)) : f(i - 1), d()
            }

            function c() {
                $preloader.addClass("notransition"), $preloader.removeClass("modified"), $preloader[0].offsetHeight
            }

            function d() {
                c(), $preloader.css("visibility", "visible"), $preloader.removeClass("notransition"), $preloader.addClass("modified")
            }

            T = a(".Controller").avController({path: G.path}), $preloader = a(".Preloader"), $preloader.css("display", "block"), H.pauseDelay = 1e3 * parseInt($preloader.css("transition-duration"));
            var e = !0, h = !1;
            T.on("prev", function () {
                f(i - 1)
            }), T.on("next", function () {
                f(i + 1)
            }), T.on("play", function () {
                h = !0, d(), l = setTimeout(b, H.pauseDelay)
            }), W && (T.trigger("play"), H.find(".play").trigger("click")), H.on("restartPreloader", function () {
                h && (d(), clearTimeout(l), l = setTimeout(b, H.pauseDelay))
            }), T.on("pause", function () {
                clearTimeout(l), c(), h = !1
            }), P.resolve()
        }, U ? this.loadController() : (V.remove(), P.resolve()), a.when(P, Q, R).done(function () {
            A.removeClass("notransition"), B.removeClass("notransition"), C.removeClass("notransition"), H.trigger("coverflowLoaded")
        }), this
    }
}(jQuery, window, document);




