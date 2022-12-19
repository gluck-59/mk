/*
 * Generated: 02.04.2014 20:13
 * Copyright (c) 2014.
 * Tinkoff Credit Systems Bank.
 * All rights reserved.
 */
function initGetAppLink(a) {
    "use strict";

    function b(a) {
        return a.length < 13 ? !1 : a.match(/\d\(\d{3}\)\d{3}-\d{2}-\d{2}/)
    }
    var c, d = ".tcs-mob-bank__phone-for-receive-reference-sms";
    c = a ? $(a).find(d) : $(d);
    c.mask("8(999)999-99-99", {
        placeholder: "_"
    });
    $(".tcs-mob-bank__receive-reference-sms").click(function () {
        var a, e = $(d).val(),
            f = "<p class='tcs-mob-bank__receive-reference-sms-title'>Ссылка на указанный номер отправлена.</p>";
        if (b(e)) {
            c.removeClass("phone-error");
            a = {
                phone: e
            };
            $.get(TCS.getServiceURL("get_mobile_app"), a).done(function () {
                $(".tcs-mob-bank_sms-link").empty().append(f)
            })
        } else c.addClass("phone-error");
        c.keyup(function () {
            var a = c.val();
            b(a) ? c.removeClass("phone-error") : c.addClass("phone-error")
        });
        return !1
    })
}! function (a) {
    function b(b, f, g) {
        var h = !1;
        if (d) {
            var i = [];
            a.each(e, function () {
                g.hasOwnProperty(this) && (h = !0) && i.push({
                    name: this,
                    val: g[this]
                })
            });
            if (h) {
                a.each(g, function (a) {
                    i.push({
                        name: a,
                        val: this
                    })
                });
                g = i
            }
        }
        a.each(g, function (d, e) {
            if (h) {
                d = e.name;
                e = e.val
            }
            if (a.isFunction(e) && (!c || e.toString().indexOf(".__base") > -1)) {
                var g = b[d] || function () {};
                f[d] = function () {
                    var a = this.__base;
                    this.__base = g;
                    var b = e.apply(this, arguments);
                    this.__base = a;
                    return b
                }
            } else f[d] = e
        })
    }
    var c = function () {}.toString().indexOf("_") > -1,
        d = a.browser.msie,
        e = d ? ["toString", "valueOf"] : null,
        f = function () {};
    a.inherit = function () {
        var c = arguments,
            d = a.isFunction(c[0]),
            e = d ? c[0] : f,
            g = c[d ? 1 : 0] || {}, h = c[d ? 2 : 1],
            i = g.__constructor || d && e.prototype.__constructor ? function () {
                return this.__constructor.apply(this, arguments)
            } : function () {};
        if (!d) {
            i.prototype = g;
            i.prototype.__self = i.prototype.constructor = i;
            return a.extend(i, h)
        }
        a.extend(i, e);
        var j = function () {}, k = j.prototype = e.prototype,
            l = i.prototype = new j;
        l.__self = l.constructor = i;
        b(k, l, g);
        h && b(e, i, h);
        return i
    };
    a.inheritSelf = function (a, c, d) {
        var e = a.prototype;
        b(e, e, c);
        d && b(a, a, d);
        return a
    }
}(jQuery);
! function (a, b, c) {
    function d(a) {
        return a
    }

    function e(a) {
        return decodeURIComponent(a.replace(g, " "))
    }

    function f(a) {
        return a.constructor.toString().match(/function ([^(]*)\(/)[1]
    }
    var g = /\+/g,
        h = a.cookie = function (g, i, j) {
            if (i !== c) {
                j = a.extend({}, h.defaults, j);
                null === i && (j.expires = -1);
                if ("number" == typeof j.expires) {
                    var k = j.expires,
                        l = j.expires = new Date;
                    l.setDate(l.getDate() + k)
                }
                i = h.json ? JSON.stringify(i) : String(i);
                return b.cookie = [encodeURIComponent(g), "=", h.raw ? i : encodeURIComponent(i), j.expires ? "; expires=" + j.expires.toUTCString() : "", j.path ? "; path=" + j.path : "", j.domain ? "; domain=" + j.domain : "", j.secure ? "; secure" : ""].join("")
            }
            for (var m, n, o, p = h.raw ? d : e, q = b.cookie.split("; "), r = null, s = 0; o = q[s] && q[s].split("="); s++) {
                m = p(o.shift());
                if ("RegExp" === f(g) && g.test(m)) {
                    n = p(o.join("="));
                    r = r || {};
                    r[m] = h.json ? JSON.parse(n) : n
                } else if ("String" === f(g) && m === g) {
                    n = p(o.join("="));
                    try {
                        return h.json ? JSON.parse(n) : n
                    } catch (t) {}
                }
            }
            return r || null
        };
    h.defaults = {};
    a.removeCookie = function (b, c) {
        if (null !== a.cookie(b, c)) {
            a.cookie(b, null, c);
            return !0
        }
        return !1
    }
}(jQuery, document);
var TCS = TCS || {};
! function (a) {
    "use strict";
    a.permaCookie = function (b, c) {
        var d = {
            methods: ["cookie", "localstorage"],
            prefix: "__P__",
            initialize: {},
            set: function (a, b) {
                var c, d;
                for (c = 0; c < this.methods.length; c++) {
                    d = "set_" + this.methods[c];
                    this.initialize[this.methods[c]] = this[d](this.prefix + a, b)
                }
                return this.isInitializeSuccess()
            },
            isInitializeSuccess: function () {
                var a, b = !1;
                for (a in this.initialize) this.initialize.hasOwnProperty(a) && (b = b || this.initialize[a]);
                return b
            },
            set_cookie: function (b, c) {
                var d = 3650;
                a.cookie(b, c, {
                    path: "/",
                    expires: d
                });
                return a.cookie(b) == c
            },
            set_localstorage: function (a, b) {
                try {
                    if (window.localStorage) {
                        b ? localStorage.setItem(a, b) : localStorage.removeItem(a);
                        return localStorage.getItem(a) == b
                    }
                    return !1
                } catch (c) {
                    return !1
                }
            },
            get: function (a) {
                var b, c, d, e = this.methods;
                for (b = 0; b < e.length; b++) {
                    c = "get_" + e[b];
                    d = this[c](this.prefix + a);
                    if (d) {
                        this.set(a, d);
                        break
                    }
                }
                return d
            },
            get_cookie: function (b) {
                return a.cookie(b) || null
            },
            get_localstorage: function (a) {
                try {
                    return window.localStorage ? localStorage.getItem(a) || null : null
                } catch (b) {
                    return null
                }
            }
        };
        return b && "undefined" != typeof c ? d.set(b, c) : b ? d.get(b) : void 0
    }
}(jQuery);
! function (a) {
    function b(a, b) {
        var c = Math.pow(10, b);
        return Math.round(a * c) / c
    }

    function c(a, b) {
        var c = parseInt(a.css(b), 10);
        if (c) return c;
        var d = a[0].currentStyle;
        return d && d.width && parseInt(d.width, 10)
    }

    function d(a) {
        var b = a.data("events");
        return b && b.onSlide
    }

    function e(e, f) {
        function g(a, c, d, g) {
            void 0 === d ? d = c / m * t : g && (d -= f.min), u && (d = Math.round(d / u) * u);
            (void 0 === c || u) && (c = d * m / t);
            if (isNaN(d)) return o;
            c = Math.max(0, Math.min(c, m)), d = c / m * t;
            (g || !j) && (d += f.min);
            j && (g ? c = m - c : d = f.max - d), d = b(d, v);
            var h = "click" == a.type;
            if (A && void 0 !== k && !h) {
                a.type = "onSlide", z.trigger(a, [d, c]);
                if (a.isDefaultPrevented()) return o
            }
            var i = h ? f.speed : 0,
                l = h ? function () {
                    a.type = "change", z.trigger(a, [d])
                } : null;
            j ? (r.animate({
                top: c
            }, i, l), f.progress && s.animate({
                height: m - c + r.height() / 2
            }, i)) : (r.animate({
                left: c
            }, i, l), f.progress && s.animate({
                width: c + r.width() / 2
            }, i)), k = d, n = c, e.val(d);
            return o
        }

        function h() {
            j = f.vertical || c(q, "height") > c(q, "width"), j ? (m = c(q, "height") - c(r, "height"), l = q.offset().top + m) : (m = c(q, "width") - c(r, "width"), l = q.offset().left)
        }

        function i() {
            h(), o.setValue(void 0 !== f.value ? f.value : f.min)
        }
        var j, k, l, m, n, o = this,
            p = f.css,
            q = a("<div><div/><a href='#'/></div>").data("rangeinput", o);
        e.before(q);
        var r = q.addClass(p.slider).find("a").addClass(p.handle),
            s = q.find("div").addClass(p.progress);
        a.each("min,max,step,value".split(","), function (a, b) {
            var c = e.attr(b);
            parseFloat(c) && (f[b] = parseFloat(c, 10))
        });
        var t = f.max - f.min,
            u = "any" == f.step ? 0 : f.step,
            v = f.precision;
        if (void 0 === v) try {
            v = u.toString().split(".")[1].length
        } catch (w) {
            v = 0
        }
        if ("range" == e.attr("type")) {
            var x = e.clone().wrap("<div/>").parent().html(),
                y = a(x.replace(/type/i, "type=text data-orig-type"));
            y.val(f.value), e.replaceWith(y), e = y
        }
        e.addClass(p.input);
        var z = a(o).add(e),
            A = !0;
        a.extend(o, {
            getValue: function () {
                return k
            },
            setValue: function (b, c) {
                h();
                return g(c || a.Event("api"), void 0, b, !0)
            },
            getConf: function () {
                return f
            },
            getProgress: function () {
                return s
            },
            getHandle: function () {
                return r
            },
            getInput: function () {
                return e
            },
            step: function (b, c) {
                c = c || a.Event();
                var d = "any" == f.step ? 1 : f.step;
                o.setValue(k + d * (b || 1), c)
            },
            stepUp: function (a) {
                return o.step(a || 1)
            },
            stepDown: function (a) {
                return o.step(-a || -1)
            }
        }), a.each("onSlide,change".split(","), function (b, c) {
            a.isFunction(f[c]) && a(o).bind(c, f[c]), o[c] = function (b) {
                b && a(o).bind(c, b);
                return o
            }
        }), r.drag({
            drag: !1
        }).bind("dragStart", function () {
            h(), A = d(a(o)) || d(e)
        }).bind("drag", function (a, b, c) {
            if (e.is(":disabled")) return !1;
            g(a, j ? b : c);
            return void 0
        }).bind("dragEnd", function (a) {
            a.isDefaultPrevented() || (a.type = "change", z.trigger(a, [k]))
        }).click(function (a) {
            return a.preventDefault()
        }), q.click(function (a) {
            if (e.is(":disabled") || a.target == r[0]) return a.preventDefault();
            h();
            var b = j ? r.height() / 2 : r.width() / 2;
            g(a, j ? m - l - b + a.pageY : a.pageX - l - b)
        }), f.keyboard && e.keydown(function (b) {
            if (!e.attr("readonly")) {
                var c = b.keyCode,
                    d = -1 != a([75, 76, 38, 33, 39]).index(c),
                    f = -1 != a([74, 72, 40, 34, 37]).index(c);
                if ((d || f) && !(b.shiftKey || b.altKey || b.ctrlKey)) {
                    d ? o.step(33 == c ? 10 : 1, b) : f && o.step(34 == c ? -10 : -1, b);
                    return b.preventDefault()
                }
            }
        }), e.blur(function (b) {
            var c = a(this).val();
            c !== k && o.setValue(c, b)
        }), a.extend(e[0], {
            stepUp: o.stepUp,
            stepDown: o.stepDown
        });
        i(), m || a(window).load(i)
    }
    a.tools = a.tools || {
        version: "v1.2.6"
    };
    var f;
    f = a.tools.rangeinput = {
        conf: {
            min: 0,
            max: 100,
            step: "any",
            steps: 0,
            value: 0,
            precision: void 0,
            vertical: 0,
            keyboard: !0,
            progress: !1,
            speed: 100,
            css: {
                input: "range",
                slider: "slider",
                progress: "progress",
                handle: "handle"
            }
        }
    };
    var g, h;
    a.fn.drag = function (b) {
        document.ondragstart = function () {
            return !1
        }, b = a.extend({
            x: !0,
            y: !0,
            drag: !0
        }, b), g = g || a(document).bind("mousedown mouseup", function (c) {
            var d = a(c.target);
            if ("mousedown" == c.type && d.data("drag")) {
                var e = d.position(),
                    f = c.pageX - e.left,
                    i = c.pageY - e.top,
                    j = !0;
                g.bind("mousemove.drag", function (a) {
                    var c = a.pageX - f,
                        e = a.pageY - i,
                        g = {};
                    b.x && (g.left = c), b.y && (g.top = e), j && (d.trigger("dragStart"), j = !1), b.drag && d.css(g), d.trigger("drag", [e, c]), h = d
                }), c.preventDefault()
            } else try {
                h && h.trigger("dragEnd")
            } finally {
                g.unbind("mousemove.drag"), h = null
            }
        });
        return this.data("drag", !0)
    };
    a.expr[":"].range = function (b) {
        var c = b.getAttribute("type");
        return c && "range" == c || a(b).filter("input").data("rangeinput")
    }, a.fn.rangeinput = function (b) {
        if (this.data("rangeinput")) return this;
        b = a.extend(!0, {}, f.conf, b);
        var c;
        this.each(function () {
            var d = new e(a(this), a.extend(!0, {}, b)),
                f = d.getInput().data("rangeinput", d);
            c = c ? c.add(f) : f
        });
        return c ? c : this
    }
}(jQuery);
var JSON;
JSON || (JSON = {});
! function () {
    function k(a) {
        return 10 > a ? "0" + a : a
    }

    function o(a) {
        p.lastIndex = 0;
        return p.test(a) ? '"' + a.replace(p, function (a) {
            var b = r[a];
            return "string" == typeof b ? b : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + a + '"'
    }

    function l(a, b) {
        var c, d, f, g, h, j = e,
            k = b[a];
        k && "object" == typeof k && "function" == typeof k.toJSON && (k = k.toJSON(a));
        "function" == typeof i && (k = i.call(b, a, k));
        switch (typeof k) {
        case "string":
            return o(k);
        case "number":
            return isFinite(k) ? String(k) : "null";
        case "boolean":
        case "null":
            return String(k);
        case "object":
            if (!k) return "null";
            e += n;
            h = [];
            if ("[object Array]" === Object.prototype.toString.apply(k)) {
                g = k.length;
                for (c = 0; g > c; c += 1) h[c] = l(c, k) || "null";
                f = 0 === h.length ? "[]" : e ? "[\n" + e + h.join(",\n" + e) + "\n" + j + "]" : "[" + h.join(",") + "]";
                e = j;
                return f
            }
            if (i && "object" == typeof i) {
                g = i.length;
                for (c = 0; g > c; c += 1) "string" == typeof i[c] && (d = i[c], (f = l(d, k)) && h.push(o(d) + (e ? ": " : ":") + f))
            } else
                for (d in k) Object.prototype.hasOwnProperty.call(k, d) && (f = l(d, k)) && h.push(o(d) + (e ? ": " : ":") + f);
            f = 0 === h.length ? "{}" : e ? "{\n" + e + h.join(",\n" + e) + "\n" + j + "}" : "{" + h.join(",") + "}";
            e = j;
            return f
        }
    }
    "function" != typeof Date.prototype.toJSON && (Date.prototype.toJSON = function () {
        return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + k(this.getUTCMonth() + 1) + "-" + k(this.getUTCDate()) + "T" + k(this.getUTCHours()) + ":" + k(this.getUTCMinutes()) + ":" + k(this.getUTCSeconds()) + "Z" : null
    }, String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function () {
        return this.valueOf()
    });
    var q = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        p = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        e, n, r = {
            "\b": "\\b",
            "	": "\\t",
            "\n": "\\n",
            "\f": "\\f",
            "\r": "\\r",
            '"': '\\"',
            "\\": "\\\\"
        }, i;
    "function" != typeof JSON.stringify && (JSON.stringify = function (a, b, c) {
        var d;
        n = e = "";
        if ("number" == typeof c)
            for (d = 0; c > d; d += 1) n += " ";
        else "string" == typeof c && (n = c); if ((i = b) && "function" != typeof b && ("object" != typeof b || "number" != typeof b.length)) throw Error("JSON.stringify");
        return l("", {
            "": a
        })
    });
    "function" != typeof JSON.parse && (JSON.parse = function (a, e) {
        function c(a, b) {
            var d, f, g = a[b];
            if (g && "object" == typeof g)
                for (d in g) Object.prototype.hasOwnProperty.call(g, d) && (f = c(g, d), void 0 !== f ? g[d] = f : delete g[d]);
            return e.call(a, b, g)
        }
        var d, a = String(a);
        q.lastIndex = 0;
        q.test(a) && (a = a.replace(q, function (a) {
            return "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
        }));
        if (/^[\],:{}\s]*$/.test(a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) return d = eval("(" + a + ")"), "function" == typeof e ? c({
            "": d
        }, "") : d;
        throw new SyntaxError("JSON.parse")
    })
}();
var Handlebars = {};
Handlebars.VERSION = "1.0.beta.6";
Handlebars.helpers = {};
Handlebars.partials = {};
Handlebars.registerHelper = function (a, b, c) {
    c && (b.not = c);
    this.helpers[a] = b
};
Handlebars.registerPartial = function (a, b) {
    this.partials[a] = b
};
Handlebars.registerHelper("helperMissing", function (a) {
    if (2 === arguments.length) return void 0;
    throw new Error("Could not find property '" + a + "'")
});
var toString = Object.prototype.toString,
    functionType = "[object Function]";
Handlebars.registerHelper("blockHelperMissing", function (a, b) {
    var c = b.inverse || function () {}, d = b.fn,
        e = "",
        f = toString.call(a);
    f === functionType && (a = a.call(this));
    if (a === !0) return d(this);
    if (a === !1 || null == a) return c(this);
    if ("[object Array]" === f) {
        if (a.length > 0)
            for (var g = 0, h = a.length; h > g; g++) e += d(a[g]);
        else e = c(this);
        return e
    }
    return d(a)
});
Handlebars.registerHelper("each", function (a, b) {
    var c = b.fn,
        d = b.inverse,
        e = "";
    if (a && a.length > 0)
        for (var f = 0, g = a.length; g > f; f++) e += c(a[f]);
    else e = d(this);
    return e
});
Handlebars.registerHelper("if", function (a, b) {
    var c = toString.call(a);
    c === functionType && (a = a.call(this));
    return !a || Handlebars.Utils.isEmpty(a) ? b.inverse(this) : b.fn(this)
});
Handlebars.registerHelper("unless", function (a, b) {
    var c = b.fn,
        d = b.inverse;
    b.fn = d;
    b.inverse = c;
    return Handlebars.helpers["if"].call(this, a, b)
});
Handlebars.registerHelper("with", function (a, b) {
    return b.fn(a)
});
Handlebars.registerHelper("log", function (a) {
    Handlebars.log(a)
});
var handlebars = function () {
    var a = {
        trace: function () {},
        yy: {},
        symbols_: {
            error: 2,
            root: 3,
            program: 4,
            EOF: 5,
            statements: 6,
            simpleInverse: 7,
            statement: 8,
            openInverse: 9,
            closeBlock: 10,
            openBlock: 11,
            mustache: 12,
            partial: 13,
            CONTENT: 14,
            COMMENT: 15,
            OPEN_BLOCK: 16,
            inMustache: 17,
            CLOSE: 18,
            OPEN_INVERSE: 19,
            OPEN_ENDBLOCK: 20,
            path: 21,
            OPEN: 22,
            OPEN_UNESCAPED: 23,
            OPEN_PARTIAL: 24,
            params: 25,
            hash: 26,
            param: 27,
            STRING: 28,
            INTEGER: 29,
            BOOLEAN: 30,
            hashSegments: 31,
            hashSegment: 32,
            ID: 33,
            EQUALS: 34,
            pathSegments: 35,
            SEP: 36,
            $accept: 0,
            $end: 1
        },
        terminals_: {
            2: "error",
            5: "EOF",
            14: "CONTENT",
            15: "COMMENT",
            16: "OPEN_BLOCK",
            18: "CLOSE",
            19: "OPEN_INVERSE",
            20: "OPEN_ENDBLOCK",
            22: "OPEN",
            23: "OPEN_UNESCAPED",
            24: "OPEN_PARTIAL",
            28: "STRING",
            29: "INTEGER",
            30: "BOOLEAN",
            33: "ID",
            34: "EQUALS",
            36: "SEP"
        },
        productions_: [0, [3, 2],
            [4, 3],
            [4, 1],
            [4, 0],
            [6, 1],
            [6, 2],
            [8, 3],
            [8, 3],
            [8, 1],
            [8, 1],
            [8, 1],
            [8, 1],
            [11, 3],
            [9, 3],
            [10, 3],
            [12, 3],
            [12, 3],
            [13, 3],
            [13, 4],
            [7, 2],
            [17, 3],
            [17, 2],
            [17, 2],
            [17, 1],
            [25, 2],
            [25, 1],
            [27, 1],
            [27, 1],
            [27, 1],
            [27, 1],
            [26, 1],
            [31, 2],
            [31, 1],
            [32, 3],
            [32, 3],
            [32, 3],
            [32, 3],
            [21, 1],
            [35, 3],
            [35, 1]
        ],
        performAction: function (a, b, c, d, e, f) {
            var g = f.length - 1;
            switch (e) {
            case 1:
                return f[g - 1];
            case 2:
                this.$ = new d.ProgramNode(f[g - 2], f[g]);
                break;
            case 3:
                this.$ = new d.ProgramNode(f[g]);
                break;
            case 4:
                this.$ = new d.ProgramNode([]);
                break;
            case 5:
                this.$ = [f[g]];
                break;
            case 6:
                f[g - 1].push(f[g]);
                this.$ = f[g - 1];
                break;
            case 7:
                this.$ = new d.InverseNode(f[g - 2], f[g - 1], f[g]);
                break;
            case 8:
                this.$ = new d.BlockNode(f[g - 2], f[g - 1], f[g]);
                break;
            case 9:
                this.$ = f[g];
                break;
            case 10:
                this.$ = f[g];
                break;
            case 11:
                this.$ = new d.ContentNode(f[g]);
                break;
            case 12:
                this.$ = new d.CommentNode(f[g]);
                break;
            case 13:
                this.$ = new d.MustacheNode(f[g - 1][0], f[g - 1][1]);
                break;
            case 14:
                this.$ = new d.MustacheNode(f[g - 1][0], f[g - 1][1]);
                break;
            case 15:
                this.$ = f[g - 1];
                break;
            case 16:
                this.$ = new d.MustacheNode(f[g - 1][0], f[g - 1][1]);
                break;
            case 17:
                this.$ = new d.MustacheNode(f[g - 1][0], f[g - 1][1], !0);
                break;
            case 18:
                this.$ = new d.PartialNode(f[g - 1]);
                break;
            case 19:
                this.$ = new d.PartialNode(f[g - 2], f[g - 1]);
                break;
            case 20:
                break;
            case 21:
                this.$ = [
                    [f[g - 2]].concat(f[g - 1]), f[g]
                ];
                break;
            case 22:
                this.$ = [
                    [f[g - 1]].concat(f[g]), null
                ];
                break;
            case 23:
                this.$ = [
                    [f[g - 1]], f[g]
                ];
                break;
            case 24:
                this.$ = [
                    [f[g]], null
                ];
                break;
            case 25:
                f[g - 1].push(f[g]);
                this.$ = f[g - 1];
                break;
            case 26:
                this.$ = [f[g]];
                break;
            case 27:
                this.$ = f[g];
                break;
            case 28:
                this.$ = new d.StringNode(f[g]);
                break;
            case 29:
                this.$ = new d.IntegerNode(f[g]);
                break;
            case 30:
                this.$ = new d.BooleanNode(f[g]);
                break;
            case 31:
                this.$ = new d.HashNode(f[g]);
                break;
            case 32:
                f[g - 1].push(f[g]);
                this.$ = f[g - 1];
                break;
            case 33:
                this.$ = [f[g]];
                break;
            case 34:
                this.$ = [f[g - 2], f[g]];
                break;
            case 35:
                this.$ = [f[g - 2], new d.StringNode(f[g])];
                break;
            case 36:
                this.$ = [f[g - 2], new d.IntegerNode(f[g])];
                break;
            case 37:
                this.$ = [f[g - 2], new d.BooleanNode(f[g])];
                break;
            case 38:
                this.$ = new d.IdNode(f[g]);
                break;
            case 39:
                f[g - 2].push(f[g]);
                this.$ = f[g - 2];
                break;
            case 40:
                this.$ = [f[g]]
            }
        },
        table: [{
            3: 1,
            4: 2,
            5: [2, 4],
            6: 3,
            8: 4,
            9: 5,
            11: 6,
            12: 7,
            13: 8,
            14: [1, 9],
            15: [1, 10],
            16: [1, 12],
            19: [1, 11],
            22: [1, 13],
            23: [1, 14],
            24: [1, 15]
        }, {
            1: [3]
        }, {
            5: [1, 16]
        }, {
            5: [2, 3],
            7: 17,
            8: 18,
            9: 5,
            11: 6,
            12: 7,
            13: 8,
            14: [1, 9],
            15: [1, 10],
            16: [1, 12],
            19: [1, 19],
            20: [2, 3],
            22: [1, 13],
            23: [1, 14],
            24: [1, 15]
        }, {
            5: [2, 5],
            14: [2, 5],
            15: [2, 5],
            16: [2, 5],
            19: [2, 5],
            20: [2, 5],
            22: [2, 5],
            23: [2, 5],
            24: [2, 5]
        }, {
            4: 20,
            6: 3,
            8: 4,
            9: 5,
            11: 6,
            12: 7,
            13: 8,
            14: [1, 9],
            15: [1, 10],
            16: [1, 12],
            19: [1, 11],
            20: [2, 4],
            22: [1, 13],
            23: [1, 14],
            24: [1, 15]
        }, {
            4: 21,
            6: 3,
            8: 4,
            9: 5,
            11: 6,
            12: 7,
            13: 8,
            14: [1, 9],
            15: [1, 10],
            16: [1, 12],
            19: [1, 11],
            20: [2, 4],
            22: [1, 13],
            23: [1, 14],
            24: [1, 15]
        }, {
            5: [2, 9],
            14: [2, 9],
            15: [2, 9],
            16: [2, 9],
            19: [2, 9],
            20: [2, 9],
            22: [2, 9],
            23: [2, 9],
            24: [2, 9]
        }, {
            5: [2, 10],
            14: [2, 10],
            15: [2, 10],
            16: [2, 10],
            19: [2, 10],
            20: [2, 10],
            22: [2, 10],
            23: [2, 10],
            24: [2, 10]
        }, {
            5: [2, 11],
            14: [2, 11],
            15: [2, 11],
            16: [2, 11],
            19: [2, 11],
            20: [2, 11],
            22: [2, 11],
            23: [2, 11],
            24: [2, 11]
        }, {
            5: [2, 12],
            14: [2, 12],
            15: [2, 12],
            16: [2, 12],
            19: [2, 12],
            20: [2, 12],
            22: [2, 12],
            23: [2, 12],
            24: [2, 12]
        }, {
            17: 22,
            21: 23,
            33: [1, 25],
            35: 24
        }, {
            17: 26,
            21: 23,
            33: [1, 25],
            35: 24
        }, {
            17: 27,
            21: 23,
            33: [1, 25],
            35: 24
        }, {
            17: 28,
            21: 23,
            33: [1, 25],
            35: 24
        }, {
            21: 29,
            33: [1, 25],
            35: 24
        }, {
            1: [2, 1]
        }, {
            6: 30,
            8: 4,
            9: 5,
            11: 6,
            12: 7,
            13: 8,
            14: [1, 9],
            15: [1, 10],
            16: [1, 12],
            19: [1, 11],
            22: [1, 13],
            23: [1, 14],
            24: [1, 15]
        }, {
            5: [2, 6],
            14: [2, 6],
            15: [2, 6],
            16: [2, 6],
            19: [2, 6],
            20: [2, 6],
            22: [2, 6],
            23: [2, 6],
            24: [2, 6]
        }, {
            17: 22,
            18: [1, 31],
            21: 23,
            33: [1, 25],
            35: 24
        }, {
            10: 32,
            20: [1, 33]
        }, {
            10: 34,
            20: [1, 33]
        }, {
            18: [1, 35]
        }, {
            18: [2, 24],
            21: 40,
            25: 36,
            26: 37,
            27: 38,
            28: [1, 41],
            29: [1, 42],
            30: [1, 43],
            31: 39,
            32: 44,
            33: [1, 45],
            35: 24
        }, {
            18: [2, 38],
            28: [2, 38],
            29: [2, 38],
            30: [2, 38],
            33: [2, 38],
            36: [1, 46]
        }, {
            18: [2, 40],
            28: [2, 40],
            29: [2, 40],
            30: [2, 40],
            33: [2, 40],
            36: [2, 40]
        }, {
            18: [1, 47]
        }, {
            18: [1, 48]
        }, {
            18: [1, 49]
        }, {
            18: [1, 50],
            21: 51,
            33: [1, 25],
            35: 24
        }, {
            5: [2, 2],
            8: 18,
            9: 5,
            11: 6,
            12: 7,
            13: 8,
            14: [1, 9],
            15: [1, 10],
            16: [1, 12],
            19: [1, 11],
            20: [2, 2],
            22: [1, 13],
            23: [1, 14],
            24: [1, 15]
        }, {
            14: [2, 20],
            15: [2, 20],
            16: [2, 20],
            19: [2, 20],
            22: [2, 20],
            23: [2, 20],
            24: [2, 20]
        }, {
            5: [2, 7],
            14: [2, 7],
            15: [2, 7],
            16: [2, 7],
            19: [2, 7],
            20: [2, 7],
            22: [2, 7],
            23: [2, 7],
            24: [2, 7]
        }, {
            21: 52,
            33: [1, 25],
            35: 24
        }, {
            5: [2, 8],
            14: [2, 8],
            15: [2, 8],
            16: [2, 8],
            19: [2, 8],
            20: [2, 8],
            22: [2, 8],
            23: [2, 8],
            24: [2, 8]
        }, {
            14: [2, 14],
            15: [2, 14],
            16: [2, 14],
            19: [2, 14],
            20: [2, 14],
            22: [2, 14],
            23: [2, 14],
            24: [2, 14]
        }, {
            18: [2, 22],
            21: 40,
            26: 53,
            27: 54,
            28: [1, 41],
            29: [1, 42],
            30: [1, 43],
            31: 39,
            32: 44,
            33: [1, 45],
            35: 24
        }, {
            18: [2, 23]
        }, {
            18: [2, 26],
            28: [2, 26],
            29: [2, 26],
            30: [2, 26],
            33: [2, 26]
        }, {
            18: [2, 31],
            32: 55,
            33: [1, 56]
        }, {
            18: [2, 27],
            28: [2, 27],
            29: [2, 27],
            30: [2, 27],
            33: [2, 27]
        }, {
            18: [2, 28],
            28: [2, 28],
            29: [2, 28],
            30: [2, 28],
            33: [2, 28]
        }, {
            18: [2, 29],
            28: [2, 29],
            29: [2, 29],
            30: [2, 29],
            33: [2, 29]
        }, {
            18: [2, 30],
            28: [2, 30],
            29: [2, 30],
            30: [2, 30],
            33: [2, 30]
        }, {
            18: [2, 33],
            33: [2, 33]
        }, {
            18: [2, 40],
            28: [2, 40],
            29: [2, 40],
            30: [2, 40],
            33: [2, 40],
            34: [1, 57],
            36: [2, 40]
        }, {
            33: [1, 58]
        }, {
            14: [2, 13],
            15: [2, 13],
            16: [2, 13],
            19: [2, 13],
            20: [2, 13],
            22: [2, 13],
            23: [2, 13],
            24: [2, 13]
        }, {
            5: [2, 16],
            14: [2, 16],
            15: [2, 16],
            16: [2, 16],
            19: [2, 16],
            20: [2, 16],
            22: [2, 16],
            23: [2, 16],
            24: [2, 16]
        }, {
            5: [2, 17],
            14: [2, 17],
            15: [2, 17],
            16: [2, 17],
            19: [2, 17],
            20: [2, 17],
            22: [2, 17],
            23: [2, 17],
            24: [2, 17]
        }, {
            5: [2, 18],
            14: [2, 18],
            15: [2, 18],
            16: [2, 18],
            19: [2, 18],
            20: [2, 18],
            22: [2, 18],
            23: [2, 18],
            24: [2, 18]
        }, {
            18: [1, 59]
        }, {
            18: [1, 60]
        }, {
            18: [2, 21]
        }, {
            18: [2, 25],
            28: [2, 25],
            29: [2, 25],
            30: [2, 25],
            33: [2, 25]
        }, {
            18: [2, 32],
            33: [2, 32]
        }, {
            34: [1, 57]
        }, {
            21: 61,
            28: [1, 62],
            29: [1, 63],
            30: [1, 64],
            33: [1, 25],
            35: 24
        }, {
            18: [2, 39],
            28: [2, 39],
            29: [2, 39],
            30: [2, 39],
            33: [2, 39],
            36: [2, 39]
        }, {
            5: [2, 19],
            14: [2, 19],
            15: [2, 19],
            16: [2, 19],
            19: [2, 19],
            20: [2, 19],
            22: [2, 19],
            23: [2, 19],
            24: [2, 19]
        }, {
            5: [2, 15],
            14: [2, 15],
            15: [2, 15],
            16: [2, 15],
            19: [2, 15],
            20: [2, 15],
            22: [2, 15],
            23: [2, 15],
            24: [2, 15]
        }, {
            18: [2, 34],
            33: [2, 34]
        }, {
            18: [2, 35],
            33: [2, 35]
        }, {
            18: [2, 36],
            33: [2, 36]
        }, {
            18: [2, 37],
            33: [2, 37]
        }],
        defaultActions: {
            16: [2, 1],
            37: [2, 23],
            53: [2, 21]
        },
        parseError: function (a) {
            throw new Error(a)
        },
        parse: function (a) {
            function b() {
                var a;
                a = c.lexer.lex() || 1;
                "number" != typeof a && (a = c.symbols_[a] || a);
                return a
            }
            var c = this,
                d = [0],
                e = [null],
                f = [],
                g = this.table,
                h = "",
                i = 0,
                j = 0,
                k = 0;
            this.lexer.setInput(a);
            this.lexer.yy = this.yy;
            this.yy.lexer = this.lexer;
            "undefined" == typeof this.lexer.yylloc && (this.lexer.yylloc = {});
            var l = this.lexer.yylloc;
            f.push(l);
            "function" == typeof this.yy.parseError && (this.parseError = this.yy.parseError);
            for (var m, n, o, p, q, r, s, t, u, v = {};;) {
                o = d[d.length - 1];
                if (this.defaultActions[o]) p = this.defaultActions[o];
                else {
                    null == m && (m = b());
                    p = g[o] && g[o][m]
                } if (!("undefined" != typeof p && p.length && p[0] || k)) {
                    u = [];
                    for (r in g[o]) this.terminals_[r] && r > 2 && u.push("'" + this.terminals_[r] + "'");
                    var w = "";
                    w = this.lexer.showPosition ? "Parse error on line " + (i + 1) + ":\n" + this.lexer.showPosition() + "\nExpecting " + u.join(", ") + ", got '" + this.terminals_[m] + "'" : "Parse error on line " + (i + 1) + ": Unexpected " + (1 == m ? "end of input" : "'" + (this.terminals_[m] || m) + "'");
                    this.parseError(w, {
                        text: this.lexer.match,
                        token: this.terminals_[m] || m,
                        line: this.lexer.yylineno,
                        loc: l,
                        expected: u
                    })
                }
                if (p[0] instanceof Array && p.length > 1) throw new Error("Parse Error: multiple actions possible at state: " + o + ", token: " + m);
                switch (p[0]) {
                case 1:
                    d.push(m);
                    e.push(this.lexer.yytext);
                    f.push(this.lexer.yylloc);
                    d.push(p[1]);
                    m = null;
                    if (n) {
                        m = n;
                        n = null
                    } else {
                        j = this.lexer.yyleng;
                        h = this.lexer.yytext;
                        i = this.lexer.yylineno;
                        l = this.lexer.yylloc;
                        k > 0 && k--
                    }
                    break;
                case 2:
                    s = this.productions_[p[1]][1];
                    v.$ = e[e.length - s];
                    v._$ = {
                        first_line: f[f.length - (s || 1)].first_line,
                        last_line: f[f.length - 1].last_line,
                        first_column: f[f.length - (s || 1)].first_column,
                        last_column: f[f.length - 1].last_column
                    };
                    q = this.performAction.call(v, h, j, i, this.yy, p[1], e, f);
                    if ("undefined" != typeof q) return q;
                    if (s) {
                        d = d.slice(0, -1 * s * 2);
                        e = e.slice(0, -1 * s);
                        f = f.slice(0, -1 * s)
                    }
                    d.push(this.productions_[p[1]][0]);
                    e.push(v.$);
                    f.push(v._$);
                    t = g[d[d.length - 2]][d[d.length - 1]];
                    d.push(t);
                    break;
                case 3:
                    return !0
                }
            }
            return !0
        }
    }, b = function () {
            var a = {
                EOF: 1,
                parseError: function (a, b) {
                    if (!this.yy.parseError) throw new Error(a);
                    this.yy.parseError(a, b)
                },
                setInput: function (a) {
                    this._input = a;
                    this._more = this._less = this.done = !1;
                    this.yylineno = this.yyleng = 0;
                    this.yytext = this.matched = this.match = "";
                    this.conditionStack = ["INITIAL"];
                    this.yylloc = {
                        first_line: 1,
                        first_column: 0,
                        last_line: 1,
                        last_column: 0
                    };
                    return this
                },
                input: function () {
                    var a = this._input[0];
                    this.yytext += a;
                    this.yyleng++;
                    this.match += a;
                    this.matched += a;
                    var b = a.match(/\n/);
                    b && this.yylineno++;
                    this._input = this._input.slice(1);
                    return a
                },
                unput: function (a) {
                    this._input = a + this._input;
                    return this
                },
                more: function () {
                    this._more = !0;
                    return this
                },
                pastInput: function () {
                    var a = this.matched.substr(0, this.matched.length - this.match.length);
                    return (a.length > 20 ? "..." : "") + a.substr(-20).replace(/\n/g, "")
                },
                upcomingInput: function () {
                    var a = this.match;
                    a.length < 20 && (a += this._input.substr(0, 20 - a.length));
                    return (a.substr(0, 20) + (a.length > 20 ? "..." : "")).replace(/\n/g, "")
                },
                showPosition: function () {
                    var a = this.pastInput(),
                        b = new Array(a.length + 1).join("-");
                    return a + this.upcomingInput() + "\n" + b + "^"
                },
                next: function () {
                    if (this.done) return this.EOF;
                    this._input || (this.done = !0);
                    var a, b, c;
                    if (!this._more) {
                        this.yytext = "";
                        this.match = ""
                    }
                    for (var d = this._currentRules(), e = 0; e < d.length; e++) {
                        b = this._input.match(this.rules[d[e]]);
                        if (b) {
                            c = b[0].match(/\n.*/g);
                            c && (this.yylineno += c.length);
                            this.yylloc = {
                                first_line: this.yylloc.last_line,
                                last_line: this.yylineno + 1,
                                first_column: this.yylloc.last_column,
                                last_column: c ? c[c.length - 1].length - 1 : this.yylloc.last_column + b[0].length
                            };
                            this.yytext += b[0];
                            this.match += b[0];
                            this.matches = b;
                            this.yyleng = this.yytext.length;
                            this._more = !1;
                            this._input = this._input.slice(b[0].length);
                            this.matched += b[0];
                            a = this.performAction.call(this, this.yy, this, d[e], this.conditionStack[this.conditionStack.length - 1]);
                            return a ? a : void 0
                        }
                    }
                    if ("" === this._input) return this.EOF;
                    this.parseError("Lexical error on line " + (this.yylineno + 1) + ". Unrecognized text.\n" + this.showPosition(), {
                        text: "",
                        token: null,
                        line: this.yylineno
                    });
                    return void 0
                },
                lex: function () {
                    var a = this.next();
                    return "undefined" != typeof a ? a : this.lex()
                },
                begin: function (a) {
                    this.conditionStack.push(a)
                },
                popState: function () {
                    return this.conditionStack.pop()
                },
                _currentRules: function () {
                    return this.conditions[this.conditionStack[this.conditionStack.length - 1]].rules
                },
                topState: function () {
                    return this.conditionStack[this.conditionStack.length - 2]
                },
                pushState: function (a) {
                    this.begin(a)
                }
            };
            a.performAction = function (a, b, c, d) {
                switch (c) {
                case 0:
                    "\\" !== b.yytext.slice(-1) && this.begin("mu");
                    "\\" === b.yytext.slice(-1) && (b.yytext = b.yytext.substr(0, b.yyleng - 1), this.begin("emu"));
                    if (b.yytext) return 14;
                    break;
                case 1:
                    return 14;
                case 2:
                    this.popState();
                    return 14;
                case 3:
                    return 24;
                case 4:
                    return 16;
                case 5:
                    return 20;
                case 6:
                    return 19;
                case 7:
                    return 19;
                case 8:
                    return 23;
                case 9:
                    return 23;
                case 10:
                    b.yytext = b.yytext.substr(3, b.yyleng - 5);
                    this.popState();
                    return 15;
                case 11:
                    return 22;
                case 12:
                    return 34;
                case 13:
                    return 33;
                case 14:
                    return 33;
                case 15:
                    return 36;
                case 16:
                    break;
                case 17:
                    this.popState();
                    return 18;
                case 18:
                    this.popState();
                    return 18;
                case 19:
                    b.yytext = b.yytext.substr(1, b.yyleng - 2).replace(/\\"/g, '"');
                    return 28;
                case 20:
                    return 30;
                case 21:
                    return 30;
                case 22:
                    return 29;
                case 23:
                    return 33;
                case 24:
                    b.yytext = b.yytext.substr(1, b.yyleng - 2);
                    return 33;
                case 25:
                    return "INVALID";
                case 26:
                    return 5
                }
            };
            a.rules = [/^[^\x00]*?(?=(\{\{))/, /^[^\x00]+/, /^[^\x00]{2,}?(?=(\{\{))/, /^\{\{>/, /^\{\{#/, /^\{\{\//, /^\{\{\^/, /^\{\{\s*else\b/, /^\{\{\{/, /^\{\{&/, /^\{\{![\s\S]*?\}\}/, /^\{\{/, /^=/, /^\.(?=[} ])/, /^\.\./, /^[\/.]/, /^\s+/, /^\}\}\}/, /^\}\}/, /^"(\\["]|[^"])*"/, /^true(?=[}\s])/, /^false(?=[}\s])/, /^[0-9]+(?=[}\s])/, /^[a-zA-Z0-9_$-]+(?=[=}\s\/.])/, /^\[[^\]]*\]/, /^./, /^$/];
            a.conditions = {
                mu: {
                    rules: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26],
                    inclusive: !1
                },
                emu: {
                    rules: [2],
                    inclusive: !1
                },
                INITIAL: {
                    rules: [0, 1, 26],
                    inclusive: !0
                }
            };
            return a
        }();
    a.lexer = b;
    return a
}();
if ("undefined" != typeof require && "undefined" != typeof exports) {
    exports.parser = handlebars;
    exports.parse = function () {
        return handlebars.parse.apply(handlebars, arguments)
    };
    exports.main = function (a) {
        if (!a[1]) throw new Error("Usage: " + a[0] + " FILE");
        if ("undefined" != typeof process) var b = require("fs").readFileSync(require("path").join(process.cwd(), a[1]), "utf8");
        else var c = require("file").path(require("file").cwd()),
        b = c.join(a[1]).read({
            charset: "utf-8"
        });
        return exports.parser.parse(b)
    };
    "undefined" != typeof module && require.main === module && exports.main("undefined" != typeof process ? process.argv.slice(1) : require("system").args)
}
Handlebars.Parser = handlebars;
Handlebars.parse = function (a) {
    Handlebars.Parser.yy = Handlebars.AST;
    return Handlebars.Parser.parse(a)
};
Handlebars.print = function (a) {
    return (new Handlebars.PrintVisitor).accept(a)
};
Handlebars.logger = {
    DEBUG: 0,
    INFO: 1,
    WARN: 2,
    ERROR: 3,
    level: 3,
    log: function () {}
};
Handlebars.log = function (a, b) {
    Handlebars.logger.log(a, b)
};
! function () {
    Handlebars.AST = {};
    Handlebars.AST.ProgramNode = function (a, b) {
        this.type = "program";
        this.statements = a;
        b && (this.inverse = new Handlebars.AST.ProgramNode(b))
    };
    Handlebars.AST.MustacheNode = function (a, b, c) {
        this.type = "mustache";
        this.id = a[0];
        this.params = a.slice(1);
        this.hash = b;
        this.escaped = !c
    };
    Handlebars.AST.PartialNode = function (a, b) {
        this.type = "partial";
        this.id = a;
        this.context = b
    };
    var a = function (a, b) {
        if (a.original !== b.original) throw new Handlebars.Exception(a.original + " doesn't match " + b.original)
    };
    Handlebars.AST.BlockNode = function (b, c, d) {
        a(b.id, d);
        this.type = "block";
        this.mustache = b;
        this.program = c
    };
    Handlebars.AST.InverseNode = function (b, c, d) {
        a(b.id, d);
        this.type = "inverse";
        this.mustache = b;
        this.program = c
    };
    Handlebars.AST.ContentNode = function (a) {
        this.type = "content";
        this.string = a
    };
    Handlebars.AST.HashNode = function (a) {
        this.type = "hash";
        this.pairs = a
    };
    Handlebars.AST.IdNode = function (a) {
        this.type = "ID";
        this.original = a.join(".");
        for (var b = [], c = 0, d = 0, e = a.length; e > d; d++) {
            var f = a[d];
            ".." === f ? c++ : "." === f || "this" === f ? this.isScoped = !0 : b.push(f)
        }
        this.parts = b;
        this.string = b.join(".");
        this.depth = c;
        this.isSimple = 1 === b.length && 0 === c
    };
    Handlebars.AST.StringNode = function (a) {
        this.type = "STRING";
        this.string = a
    };
    Handlebars.AST.IntegerNode = function (a) {
        this.type = "INTEGER";
        this.integer = a
    };
    Handlebars.AST.BooleanNode = function (a) {
        this.type = "BOOLEAN";
        this.bool = a
    };
    Handlebars.AST.CommentNode = function (a) {
        this.type = "comment";
        this.comment = a
    }
}();
Handlebars.Exception = function () {
    var a = Error.prototype.constructor.apply(this, arguments);
    for (var b in a) a.hasOwnProperty(b) && (this[b] = a[b]);
    this.message = a.message
};
Handlebars.Exception.prototype = new Error;
Handlebars.SafeString = function (a) {
    this.string = a
};
Handlebars.SafeString.prototype.toString = function () {
    return this.string.toString()
};
! function () {
    var a = {
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#x27;",
        "`": "&#x60;"
    }, b = /&(?!\w+;)|[<>"'`]/g,
        c = /[&<>"'`]/,
        d = function (b) {
            return a[b] || "&amp;"
        };
    Handlebars.Utils = {
        escapeExpression: function (a) {
            return a instanceof Handlebars.SafeString ? a.toString() : null == a || a === !1 ? "" : c.test(a) ? a.replace(b, d) : a
        },
        isEmpty: function (a) {
            return "undefined" == typeof a ? !0 : null === a ? !0 : a === !1 ? !0 : "[object Array]" === Object.prototype.toString.call(a) && 0 === a.length ? !0 : !1
        }
    }
}();
Handlebars.Compiler = function () {};
Handlebars.JavaScriptCompiler = function () {};
! function (a, b) {
    a.OPCODE_MAP = {
        appendContent: 1,
        getContext: 2,
        lookupWithHelpers: 3,
        lookup: 4,
        append: 5,
        invokeMustache: 6,
        appendEscaped: 7,
        pushString: 8,
        truthyOrFallback: 9,
        functionOrFallback: 10,
        invokeProgram: 11,
        invokePartial: 12,
        push: 13,
        assignToHash: 15,
        pushStringParam: 16
    };
    a.MULTI_PARAM_OPCODES = {
        appendContent: 1,
        getContext: 1,
        lookupWithHelpers: 2,
        lookup: 1,
        invokeMustache: 3,
        pushString: 1,
        truthyOrFallback: 1,
        functionOrFallback: 1,
        invokeProgram: 3,
        invokePartial: 1,
        push: 1,
        assignToHash: 1,
        pushStringParam: 1
    };
    a.DISASSEMBLE_MAP = {};
    for (var c in a.OPCODE_MAP) {
        var d = a.OPCODE_MAP[c];
        a.DISASSEMBLE_MAP[d] = c
    }
    a.multiParamSize = function (b) {
        return a.MULTI_PARAM_OPCODES[a.DISASSEMBLE_MAP[b]]
    };
    a.prototype = {
        compiler: a,
        disassemble: function () {
            for (var b, c, d, e, f, g = this.opcodes, h = [], i = 0, j = g.length; j > i; i++) {
                b = g[i];
                if ("DECLARE" === b) {
                    e = g[++i];
                    f = g[++i];
                    h.push("DECLARE " + e + " = " + f)
                } else {
                    d = a.DISASSEMBLE_MAP[b];
                    for (var k = a.multiParamSize(b), l = [], m = 0; k > m; m++) {
                        c = g[++i];
                        "string" == typeof c && (c = '"' + c.replace("\n", "\\n") + '"');
                        l.push(c)
                    }
                    d = d + " " + l.join(" ");
                    h.push(d)
                }
            }
            return h.join("\n")
        },
        guid: 0,
        compile: function (a, b) {
            this.children = [];
            this.depths = {
                list: []
            };
            this.options = b;
            var c = this.options.knownHelpers;
            this.options.knownHelpers = {
                helperMissing: !0,
                blockHelperMissing: !0,
                each: !0,
                "if": !0,
                unless: !0,
                "with": !0,
                log: !0
            };
            if (c)
                for (var d in c) this.options.knownHelpers[d] = c[d];
            return this.program(a)
        },
        accept: function (a) {
            return this[a.type](a)
        },
        program: function (a) {
            var b, c = a.statements;
            this.opcodes = [];
            for (var d = 0, e = c.length; e > d; d++) {
                b = c[d];
                this[b.type](b)
            }
            this.isSimple = 1 === e;
            this.depths.list = this.depths.list.sort(function (a, b) {
                return a - b
            });
            return this
        },
        compileProgram: function (a) {
            var b = (new this.compiler).compile(a, this.options),
                c = this.guid++;
            this.usePartial = this.usePartial || b.usePartial;
            this.children[c] = b;
            for (var d = 0, e = b.depths.list.length; e > d; d++) {
                depth = b.depths.list[d];
                2 > depth || this.addDepth(depth - 1)
            }
            return c
        },
        block: function (a) {
            var b, c = a.mustache,
                d = this.setupStackForMustache(c),
                e = this.compileProgram(a.program);
            if (a.program.inverse) {
                b = this.compileProgram(a.program.inverse);
                this.declare("inverse", b)
            }
            this.opcode("invokeProgram", e, d.length, !! c.hash);
            this.declare("inverse", null);
            this.opcode("append")
        },
        inverse: function (a) {
            var b = this.setupStackForMustache(a.mustache),
                c = this.compileProgram(a.program);
            this.declare("inverse", c);
            this.opcode("invokeProgram", null, b.length, !! a.mustache.hash);
            this.declare("inverse", null);
            this.opcode("append")
        },
        hash: function (a) {
            var b, c, d = a.pairs;
            this.opcode("push", "{}");
            for (var e = 0, f = d.length; f > e; e++) {
                b = d[e];
                c = b[1];
                this.accept(c);
                this.opcode("assignToHash", b[0])
            }
        },
        partial: function (a) {
            var b = a.id;
            this.usePartial = !0;
            a.context ? this.ID(a.context) : this.opcode("push", "depth0");
            this.opcode("invokePartial", b.original);
            this.opcode("append")
        },
        content: function (a) {
            this.opcode("appendContent", a.string)
        },
        mustache: function (a) {
            var b = this.setupStackForMustache(a);
            this.opcode("invokeMustache", b.length, a.id.original, !! a.hash);
            a.escaped && !this.options.noEscape ? this.opcode("appendEscaped") : this.opcode("append")
        },
        ID: function (a) {
            this.addDepth(a.depth);
            this.opcode("getContext", a.depth);
            this.opcode("lookupWithHelpers", a.parts[0] || null, a.isScoped || !1);
            for (var b = 1, c = a.parts.length; c > b; b++) this.opcode("lookup", a.parts[b])
        },
        STRING: function (a) {
            this.opcode("pushString", a.string)
        },
        INTEGER: function (a) {
            this.opcode("push", a.integer)
        },
        BOOLEAN: function (a) {
            this.opcode("push", a.bool)
        },
        comment: function () {},
        pushParams: function (a) {
            for (var b, c = a.length; c--;) {
                b = a[c];
                if (this.options.stringParams) {
                    b.depth && this.addDepth(b.depth);
                    this.opcode("getContext", b.depth || 0);
                    this.opcode("pushStringParam", b.string)
                } else this[b.type](b)
            }
        },
        opcode: function (b, c, d, e) {
            this.opcodes.push(a.OPCODE_MAP[b]);
            void 0 !== c && this.opcodes.push(c);
            void 0 !== d && this.opcodes.push(d);
            void 0 !== e && this.opcodes.push(e)
        },
        declare: function (a, b) {
            this.opcodes.push("DECLARE");
            this.opcodes.push(a);
            this.opcodes.push(b)
        },
        addDepth: function (a) {
            if (0 !== a && !this.depths[a]) {
                this.depths[a] = !0;
                this.depths.list.push(a)
            }
        },
        setupStackForMustache: function (a) {
            var b = a.params;
            this.pushParams(b);
            a.hash && this.hash(a.hash);
            this.ID(a.id);
            return b
        }
    };
    b.prototype = {
        nameLookup: function (a, c) {
            return /^[0-9]+$/.test(c) ? a + "[" + c + "]" : b.isValidJavaScriptVariableName(c) ? a + "." + c : a + "['" + c + "']"
        },
        appendToBuffer: function (a) {
            return this.environment.isSimple ? "return " + a + ";" : "buffer += " + a + ";"
        },
        initializeBuffer: function () {
            return this.quotedString("")
        },
        namespace: "Handlebars",
        compile: function (a, b, c, d) {
            this.environment = a;
            this.options = b || {};
            this.name = this.environment.name;
            this.isChild = !! c;
            this.context = c || {
                programs: [],
                aliases: {
                    self: "this"
                },
                registers: {
                    list: []
                }
            };
            this.preamble();
            this.stackSlot = 0;
            this.stackVars = [];
            this.compileChildren(a, b);
            var e, f = a.opcodes;
            this.i = 0;
            for (h = f.length; this.i < h; this.i++) {
                e = this.nextOpcode(0);
                if ("DECLARE" === e[0]) {
                    this.i = this.i + 2;
                    this[e[1]] = e[2]
                } else {
                    this.i = this.i + e[1].length;
                    this[e[0]].apply(this, e[1])
                }
            }
            return this.createFunctionContext(d)
        },
        nextOpcode: function (b) {
            var c, d, e, f, g = this.environment.opcodes,
                h = g[this.i + b];
            if ("DECLARE" === h) {
                c = g[this.i + 1];
                d = g[this.i + 2];
                return ["DECLARE", c, d]
            }
            c = a.DISASSEMBLE_MAP[h];
            e = a.multiParamSize(h);
            f = [];
            for (var i = 0; e > i; i++) f.push(g[this.i + i + 1 + b]);
            return [c, f]
        },
        eat: function (a) {
            this.i = this.i + a.length
        },
        preamble: function () {
            var a = [];
            this.useRegister("foundHelper");
            if (this.isChild) a.push("");
            else {
                var b = this.namespace,
                    c = "helpers = helpers || " + b + ".helpers;";
                this.environment.usePartial && (c = c + " partials = partials || " + b + ".partials;");
                a.push(c)
            }
            this.environment.isSimple ? a.push("") : a.push(", buffer = " + this.initializeBuffer());
            this.lastContext = 0;
            this.source = a
        },
        createFunctionContext: function (a) {
            var b = this.stackVars;
            this.isChild || (b = b.concat(this.context.registers.list));
            b.length > 0 && (this.source[1] = this.source[1] + ", " + b.join(", "));
            if (!this.isChild) {
                for (var c in this.context.aliases) this.source[1] = this.source[1] + ", " + c + "=" + this.context.aliases[c]
            }
            this.source[1] && (this.source[1] = "var " + this.source[1].substring(2) + ";");
            this.isChild || (this.source[1] += "\n" + this.context.programs.join("\n") + "\n");
            this.environment.isSimple || this.source.push("return buffer;");
            for (var d = this.isChild ? ["depth0", "data"] : ["Handlebars", "depth0", "helpers", "partials", "data"], e = 0, f = this.environment.depths.list.length; f > e; e++) d.push("depth" + this.environment.depths.list[e]);
            if (a) {
                d.push(this.source.join("\n  "));
                return Function.apply(this, d)
            }
            var g = "function " + (this.name || "") + "(" + d.join(",") + ") {\n  " + this.source.join("\n  ") + "}";
            Handlebars.log(Handlebars.logger.DEBUG, g + "\n\n");
            return g
        },
        appendContent: function (a) {
            this.source.push(this.appendToBuffer(this.quotedString(a)))
        },
        append: function () {
            var a = this.popStack();
            this.source.push("if(" + a + " || " + a + " === 0) { " + this.appendToBuffer(a) + " }");
            this.environment.isSimple && this.source.push("else { " + this.appendToBuffer("''") + " }")
        },
        appendEscaped: function () {
            var a = this.nextOpcode(1),
                b = "";
            this.context.aliases.escapeExpression = "this.escapeExpression";
            if ("appendContent" === a[0]) {
                b = " + " + this.quotedString(a[1][0]);
                this.eat(a)
            }
            this.source.push(this.appendToBuffer("escapeExpression(" + this.popStack() + ")" + b))
        },
        getContext: function (a) {
            this.lastContext !== a && (this.lastContext = a)
        },
        lookupWithHelpers: function (a, b) {
            if (a) {
                var c = this.nextStack();
                this.usingKnownHelper = !1;
                var d;
                if (!b && this.options.knownHelpers[a]) {
                    d = c + " = " + this.nameLookup("helpers", a, "helper");
                    this.usingKnownHelper = !0
                } else if (b || this.options.knownHelpersOnly) d = c + " = " + this.nameLookup("depth" + this.lastContext, a, "context");
                else {
                    this.register("foundHelper", this.nameLookup("helpers", a, "helper"));
                    d = c + " = foundHelper || " + this.nameLookup("depth" + this.lastContext, a, "context")
                }
                d += ";";
                this.source.push(d)
            } else this.pushStack("depth" + this.lastContext)
        },
        lookup: function (a) {
            var b = this.topStack();
            this.source.push(b + " = (" + b + " === null || " + b + " === undefined || " + b + " === false ? " + b + " : " + this.nameLookup(b, a, "context") + ");")
        },
        pushStringParam: function (a) {
            this.pushStack("depth" + this.lastContext);
            this.pushString(a)
        },
        pushString: function (a) {
            this.pushStack(this.quotedString(a))
        },
        push: function (a) {
            this.pushStack(a)
        },
        invokeMustache: function (a, b, c) {
            this.populateParams(a, this.quotedString(b), "{}", null, c, function (a, b, c) {
                if (!this.usingKnownHelper) {
                    this.context.aliases.helperMissing = "helpers.helperMissing";
                    this.context.aliases.undef = "void 0";
                    this.source.push("else if(" + c + "=== undef) { " + a + " = helperMissing.call(" + b + "); }");
                    a !== c && this.source.push("else { " + a + " = " + c + "; }")
                }
            })
        },
        invokeProgram: function (a, b, c) {
            var d = this.programExpression(this.inverse),
                e = this.programExpression(a);
            this.populateParams(b, null, e, d, c, function (a, b) {
                if (!this.usingKnownHelper) {
                    this.context.aliases.blockHelperMissing = "helpers.blockHelperMissing";
                    this.source.push("else { " + a + " = blockHelperMissing.call(" + b + "); }")
                }
            })
        },
        populateParams: function (a, b, c, d, e, f) {
            var g, h, i = e || this.options.stringParams || d || this.options.data,
                j = this.popStack(),
                k = [];
            if (i) {
                this.register("tmp1", c);
                h = "tmp1"
            } else h = "{ hash: {} }"; if (i) {
                var l = e ? this.popStack() : "{}";
                this.source.push("tmp1.hash = " + l + ";")
            }
            this.options.stringParams && this.source.push("tmp1.contexts = [];");
            for (var m = 0; a > m; m++) {
                g = this.popStack();
                k.push(g);
                this.options.stringParams && this.source.push("tmp1.contexts.push(" + this.popStack() + ");")
            }
            if (d) {
                this.source.push("tmp1.fn = tmp1;");
                this.source.push("tmp1.inverse = " + d + ";")
            }
            this.options.data && this.source.push("tmp1.data = data;");
            k.push(h);
            this.populateCall(k, j, b || j, f, "{}" !== c)
        },
        populateCall: function (a, b, c, d, e) {
            var f = ["depth0"].concat(a).join(", "),
                g = ["depth0"].concat(c).concat(a).join(", "),
                h = this.nextStack();
            if (this.usingKnownHelper) this.source.push(h + " = " + b + ".call(" + f + ");");
            else {
                this.context.aliases.functionType = '"function"';
                var i = e ? "foundHelper && " : "";
                this.source.push("if(" + i + "typeof " + b + " === functionType) { " + h + " = " + b + ".call(" + f + "); }")
            }
            d.call(this, h, g, b);
            this.usingKnownHelper = !1
        },
        invokePartial: function (a) {
            params = [this.nameLookup("partials", a, "partial"), "'" + a + "'", this.popStack(), "helpers", "partials"];
            this.options.data && params.push("data");
            this.pushStack("self.invokePartial(" + params.join(", ") + ");")
        },
        assignToHash: function (a) {
            var b = this.popStack(),
                c = this.topStack();
            this.source.push(c + "['" + a + "'] = " + b + ";")
        },
        compiler: b,
        compileChildren: function (a, b) {
            for (var c, d, e = a.children, f = 0, g = e.length; g > f; f++) {
                c = e[f];
                d = new this.compiler;
                this.context.programs.push("");
                var h = this.context.programs.length;
                c.index = h;
                c.name = "program" + h;
                this.context.programs[h] = d.compile(c, b, this.context)
            }
        },
        programExpression: function (a) {
            if (null == a) return "self.noop";
            for (var b = this.environment.children[a], c = b.depths.list, d = [b.index, b.name, "data"], e = 0, f = c.length; f > e; e++) {
                depth = c[e];
                1 === depth ? d.push("depth0") : d.push("depth" + (depth - 1))
            }
            if (0 === c.length) return "self.program(" + d.join(", ") + ")";
            d.shift();
            return "self.programWithDepth(" + d.join(", ") + ")"
        },
        register: function (a, b) {
            this.useRegister(a);
            this.source.push(a + " = " + b + ";")
        },
        useRegister: function (a) {
            if (!this.context.registers[a]) {
                this.context.registers[a] = !0;
                this.context.registers.list.push(a)
            }
        },
        pushStack: function (a) {
            this.source.push(this.nextStack() + " = " + a + ";");
            return "stack" + this.stackSlot
        },
        nextStack: function () {
            this.stackSlot++;
            this.stackSlot > this.stackVars.length && this.stackVars.push("stack" + this.stackSlot);
            return "stack" + this.stackSlot
        },
        popStack: function () {
            return "stack" + this.stackSlot--
        },
        topStack: function () {
            return "stack" + this.stackSlot
        },
        quotedString: function (a) {
            return '"' + a.replace(/\\/g, "\\\\").replace(/"/g, '\\"').replace(/\n/g, "\\n").replace(/\r/g, "\\r") + '"'
        }
    };
    for (var e = "break else new var case finally return void catch for switch while continue function this with default if throw delete in try do instanceof typeof abstract enum int short boolean export interface static byte extends long super char final native synchronized class float package throws const goto private transient debugger implements protected volatile double import public let yield".split(" "), f = b.RESERVED_WORDS = {}, g = 0, h = e.length; h > g; g++) f[e[g]] = !0;
    b.isValidJavaScriptVariableName = function (a) {
        return !b.RESERVED_WORDS[a] && /^[a-zA-Z_$][0-9a-zA-Z_$]+$/.test(a) ? !0 : !1
    }
}(Handlebars.Compiler, Handlebars.JavaScriptCompiler);
Handlebars.precompile = function (a, b) {
    b = b || {};
    var c = Handlebars.parse(a),
        d = (new Handlebars.Compiler).compile(c, b);
    return (new Handlebars.JavaScriptCompiler).compile(d, b)
};
Handlebars.compile = function (a, b) {
    function c() {
        var c = Handlebars.parse(a),
            d = (new Handlebars.Compiler).compile(c, b),
            e = (new Handlebars.JavaScriptCompiler).compile(d, b, void 0, !0);
        return Handlebars.template(e)
    }
    b = b || {};
    var d;
    return function (a, b) {
        d || (d = c());
        return d.call(this, a, b)
    }
};
Handlebars.VM = {
    template: function (a) {
        var b = {
            escapeExpression: Handlebars.Utils.escapeExpression,
            invokePartial: Handlebars.VM.invokePartial,
            programs: [],
            program: function (a, b, c) {
                var d = this.programs[a];
                if (c) return Handlebars.VM.program(b, c);
                if (d) return d;
                d = this.programs[a] = Handlebars.VM.program(b);
                return d
            },
            programWithDepth: Handlebars.VM.programWithDepth,
            noop: Handlebars.VM.noop
        };
        return function (c, d) {
            d = d || {};
            return a.call(b, Handlebars, c, d.helpers, d.partials, d.data)
        }
    },
    programWithDepth: function (a, b) {
        var c = Array.prototype.slice.call(arguments, 2);
        return function (d, e) {
            e = e || {};
            return a.apply(this, [d, e.data || b].concat(c))
        }
    },
    program: function (a, b) {
        return function (c, d) {
            d = d || {};
            return a(c, d.data || b)
        }
    },
    noop: function () {
        return ""
    },
    invokePartial: function (a, b, c, d, e, f) {
        options = {
            helpers: d,
            partials: e,
            data: f
        };
        if (void 0 === a) throw new Handlebars.Exception("The partial " + b + " could not be found");
        if (a instanceof Function) return a(c, options);
        if (Handlebars.compile) {
            e[b] = Handlebars.compile(a);
            return e[b](c, options)
        }
        throw new Handlebars.Exception("The partial " + b + " could not be compiled when running in runtime-only mode")
    }
};
Handlebars.template = Handlebars.VM.template;
! function (a) {
    "use strict";
    Handlebars.registerHelper("eq", function (a, b, c) {
        return a === b ? c.fn(this) : c.inverse(this)
    });
    Handlebars.registerHelper("lowercase", function (a) {
        return a && "string" == typeof a ? a.toLowerCase() : ""
    });
    Handlebars.registerHelper("drawIcon", function (a) {
        return a && "string" == typeof a ? a.toLowerCase() : "platinum"
    });
    Handlebars.registerHelper("isNewDeposit", function () {
        return this && "string" == typeof this.status && "new" === this.status.toLowerCase()
    });
    Handlebars.registerHelper("isSaving", function () {
        return this && "saving" === this.accountType.toLowerCase()
    });
    Handlebars.registerHelper("isCashLoan", function () {
        var a = 864e5;
        return this && "cashloan" === this.accountType.toLowerCase() && (!this.closeDate || (Date.now().getTime() - new Date(this.closeDate).getTime()) / a <= 10)
    });
    Handlebars.registerHelper("isUserPhoto", function () {
        return this && this.isUserPhoto
    });
    Handlebars.registerHelper("canBeDisplayed", function (a, b) {
        return TCS.canDisplayDeposit(a) ? b.fn(a) : ""
    });
    Handlebars.registerHelper("cardnumber", function (a) {
        return a && "string" == typeof a && a.length > 11 ? a.substr(11) : "*0000"
    });
    Handlebars.registerHelper("phone", function (a) {
        return a.replace(/(\+7)(\d{3})(\d{3})(\d{2})/, "$1 ($2) $3-$4-")
    });
    Handlebars.registerHelper("firstcardnumber", function (a) {
        return a && "string" == typeof a ? a.substr(0, 4) : "5213"
    });
    Handlebars.registerHelper("lastcardnumber", function (a) {
        return a && "string" == typeof a ? a.substr(12) : "0000"
    });
    Handlebars.registerHelper("closedate", function (a) {
        return a ? TCS.utils.convertTimeZone(new Date(a)).toString("dd.MM.yyyy") : ""
    });
    Handlebars.registerHelper("textDate", function (a) {
        return Globalize.format(a, "d MMMM yyyy")
    });
    Handlebars.registerHelper("formatmoney", function (b, c) {
        var d = {
            symbol: b.currency.name,
            format: "%v %s"
        }, e = accounting.settings.formatMapping[b.currency.name] || d;
        if (!b || a.isEmptyObject(b)) return "";
        _.isNumber(c) && (e = _.extend({}, e, {
            precision: c
        }));
        return accounting.formatMoney(b.value, e)
    });
    Handlebars.registerHelper("foreach", function (a, b) {
        return b.inverse && !a.length ? b.inverse(this) : _.map(a, function (c, d) {
            return b.fn(_.extend(c, {
                $index: d,
                $first: 0 === d,
                $last: d === a.length - 1
            }))
        }).join("")
    });
    Handlebars.registerHelper("Each", function (a, b) {
        var c, d, e = b.fn,
            f = (b.inverse, 0),
            g = "";
        _.isFunction(a) && (a = a.call(this));
        if (a && "object" == typeof a)
            if (_.isArray(a))
                for (c = a.length; c > f; f++) {
                    a[f]._index = f + 1;
                    a[f]._first = 0 === f;
                    a[f]._last = f === a.length - 1;
                    g += e(a[f])
                } else
                    for (d in a)
                        if (a.hasOwnProperty(d)) {
                            a[f]._key = d;
                            a[f]._index = f + 1;
                            a[f]._first = 0 === f;
                            g += e(a[d]);
                            f++
                        }
    });
    Handlebars.registerHelper("filesizeKb", function (a) {
        return accounting.formatNumber(Math.floor(a / 1024))
    });
    Handlebars.registerHelper("date", function (a, b, c) {
        c = _.isBoolean(c) ? c : !1;
        var d = c ? TCS.utils.withoutTimezoneOffset((a || {}).milliseconds) : (a || {}).milliseconds;
        return new Date(d).toString(b)
    });
    Handlebars.registerHelper("dateToAccusativeMonth", function (a, b, c) {
        var d = TCS.utils.dateToAccusativeMonth(new Date((a || {}).milliseconds), b);
        return c ? d.toLowerCase() : d
    });
    Handlebars.registerHelper("declOfNum", function (a, b) {
        return TCS.utils.declOfNum(a, b.split(","))
    });
    Handlebars.registerHelper("predefineDeclinate", function (a, b) {
        var c = {
            AllAirlines: ["миля", "мили", "миль"],
            Bravo: ["балл", "балла", "баллов"],
            OK: ["OK", "OKа", "OKов"]
        };
        return c[b] ? TCS.utils.declOfNum(a.toFixed(0), c[b]) : ""
    });
    Handlebars.registerHelper("toLowerCase", function (a) {
        return a.toLowerCase()
    });
    Handlebars.registerHelper("translit", function (a) {
        return window.Translit(a)
    });
    Handlebars.registerHelper("translitName", function (a) {
        if (a) {
            a = a.replace(/^(\S)|\s+(.)/g, function (a) {
                return a.toLocaleUpperCase()
            });
            return window.Translit(a)
        }
    });
    Handlebars.registerHelper("shortenEmail", function (a, b) {
        return a.length <= b ? a : a.substr(0, b) + "..."
    });
    Handlebars.registerHelper("docNumber", function (a) {
        if (a && !(a.length < 5)) {
            a = a.toUpperCase();
            return a[0] + a[1] + " " + a[2] + a[3] + " " + a.substr(4)
        }
    });
    Handlebars.registerHelper("printGradientStyles", function (a) {
        var b, c, d, e, f = "background: -moz-linear-gradient(left, {{colors}}); /* FF3.6+ */",
            g = "background: -webkit-gradient(linear, left top, right top,{{colors}}); /* Chrome,Safari4+ */",
            h = "background: -webkit-linear-gradient(left, {{colors}}); /* Chrome10+,Safari5.1+ */",
            i = "background: -o-linear-gradient(left, {{colors}}); /* Opera 11.10+ */",
            j = "background: -ms-linear-gradient(left, {{colors}}); /* IE10+ */",
            k = "background: linear-gradient(to right, {{colors}}); /* W3C */",
            l = "filter: progid:DXImageTransform.Microsoft.gradient( {{colors}},GradientType=1 );",
            m = [],
            n = [],
            o = "";
        if (a.length <= 1) return "";
        for (var p = 0; p < a.length; p++) {
            b = a[p].color;
            c = a[p].percent;
            0 === m.length && (o += "startColorstr='" + b + "'");
            m.push(b + " " + c + "%");
            n.push("color-stop(" + c + "%," + b + ")")
        }
        o += ", endColorstr='" + b + "'";
        d = m.join(", ");
        e = n.join(", ");
        return f.replace("{{colors}}", d) + g.replace("{{colors}}", e) + h.replace("{{colors}}", d) + i.replace("{{colors}}", d) + j.replace("{{colors}}", d) + k.replace("{{colors}}", d) + l.replace("{{colors}}", o)
    })
}(jQuery);
(function () {
    var a, b = this,
        c = b.Backbone,
        d = [],
        e = d.push,
        f = d.slice,
        g = d.splice;
    a = "undefined" != typeof exports ? exports : b.Backbone = {};
    a.VERSION = "0.9.9";
    var h = b._;
    !h && "undefined" != typeof require && (h = require("underscore"));
    a.$ = b.jQuery || b.Zepto || b.ender;
    a.noConflict = function () {
        b.Backbone = c;
        return this
    };
    a.emulateHTTP = !1;
    a.emulateJSON = !1;
    var i = /\s+/,
        j = function (a, b, c, d) {
            if (!c) return !0;
            if ("object" == typeof c)
                for (var e in c) a[b].apply(a, [e, c[e]].concat(d));
            else {
                if (!i.test(c)) return !0;
                c = c.split(i);
                e = 0;
                for (var f = c.length; f > e; e++) a[b].apply(a, [c[e]].concat(d))
            }
        }, k = function (a, b, c) {
            var d, a = -1,
                e = b.length;
            switch (c.length) {
            case 0:
                for (; ++a < e;)(d = b[a]).callback.call(d.ctx);
                break;
            case 1:
                for (; ++a < e;)(d = b[a]).callback.call(d.ctx, c[0]);
                break;
            case 2:
                for (; ++a < e;)(d = b[a]).callback.call(d.ctx, c[0], c[1]);
                break;
            case 3:
                for (; ++a < e;)(d = b[a]).callback.call(d.ctx, c[0], c[1], c[2]);
                break;
            default:
                for (; ++a < e;)(d = b[a]).callback.apply(d.ctx, c)
            }
        }, d = a.Events = {
            on: function (a, b, c) {
                if (!j(this, "on", a, [b, c]) || !b) return this;
                this._events || (this._events = {});
                (this._events[a] || (this._events[a] = [])).push({
                    callback: b,
                    context: c,
                    ctx: c || this
                });
                return this
            },
            once: function (a, b, c) {
                if (!j(this, "once", a, [b, c]) || !b) return this;
                var d = this,
                    e = h.once(function () {
                        d.off(a, e);
                        b.apply(this, arguments)
                    });
                e._callback = b;
                this.on(a, e, c);
                return this
            },
            off: function (a, b, c) {
                var d, e, f, g, i, k, l, m;
                if (!this._events || !j(this, "off", a, [b, c])) return this;
                if (!a && !b && !c) return this._events = {}, this;
                g = a ? [a] : h.keys(this._events);
                i = 0;
                for (k = g.length; k > i; i++)
                    if (a = g[i], d = this._events[a]) {
                        f = [];
                        if (b || c) {
                            l = 0;
                            for (m = d.length; m > l; l++) e = d[l], (b && b !== (e.callback._callback || e.callback) || c && c !== e.context) && f.push(e)
                        }
                        this._events[a] = f
                    }
                return this
            },
            trigger: function (a) {
                if (!this._events) return this;
                var b = f.call(arguments, 1);
                if (!j(this, "trigger", a, b)) return this;
                var c = this._events[a],
                    d = this._events.all;
                c && k(this, c, b);
                d && k(this, d, arguments);
                return this
            },
            listenTo: function (a, b, c) {
                var d = this._listeners || (this._listeners = {}),
                    e = a._listenerId || (a._listenerId = h.uniqueId("l"));
                d[e] = a;
                a.on(b, c || this, this);
                return this
            },
            stopListening: function (a, b, c) {
                var d = this._listeners;
                if (d) {
                    if (a) a.off(b, c, this), !b && !c && delete d[a._listenerId];
                    else {
                        for (var e in d) d[e].off(null, null, this);
                        this._listeners = {}
                    }
                    return this
                }
            }
        };
    d.bind = d.on;
    d.unbind = d.off;
    h.extend(a, d);
    var l = a.Model = function (a, b) {
        var c, d = a || {};
        this.cid = h.uniqueId("c");
        this.changed = {};
        this.attributes = {};
        this._changes = [];
        b && b.collection && (this.collection = b.collection);
        b && b.parse && (d = this.parse(d));
        (c = h.result(this, "defaults")) && h.defaults(d, c);
        this.set(d, {
            silent: !0
        });
        this._currentAttributes = h.clone(this.attributes);
        this._previousAttributes = h.clone(this.attributes);
        this.initialize.apply(this, arguments)
    };
    h.extend(l.prototype, d, {
        changed: null,
        idAttribute: "id",
        initialize: function () {},
        toJSON: function () {
            return h.clone(this.attributes)
        },
        sync: function () {
            return a.sync.apply(this, arguments)
        },
        get: function (a) {
            return this.attributes[a]
        },
        escape: function (a) {
            return h.escape(this.get(a))
        },
        has: function (a) {
            return null != this.get(a)
        },
        set: function (a, b, c) {
            var d, e;
            if (null == a) return this;
            h.isObject(a) ? (e = a, c = b) : (e = {})[a] = b;
            var a = c && c.silent,
                f = c && c.unset;
            if (!this._validate(e, c)) return !1;
            this.idAttribute in e && (this.id = e[this.idAttribute]);
            var g = this.attributes;
            for (d in e) b = e[d], f ? delete g[d] : g[d] = b, this._changes.push(d, b);
            this._hasComputed = !1;
            a || this.change(c);
            return this
        },
        unset: function (a, b) {
            return this.set(a, void 0, h.extend({}, b, {
                unset: !0
            }))
        },
        clear: function (a) {
            var b, c = {};
            for (b in this.attributes) c[b] = void 0;
            return this.set(c, h.extend({}, a, {
                unset: !0
            }))
        },
        fetch: function (a) {
            a = a ? h.clone(a) : {};
            void 0 === a.parse && (a.parse = !0);
            var b = this,
                c = a.success;
            a.success = function (d) {
                if (!b.set(b.parse(d), a)) return !1;
                c && c(b, d, a);
                return void 0
            };
            return this.sync("read", this, a)
        },
        save: function (a, b, c) {
            var d, e, f;
            null == a || h.isObject(a) ? (d = a, c = b) : null != a && ((d = {})[a] = b);
            c = c ? h.clone(c) : {};
            if (c.wait) {
                if (d && !this._validate(d, c)) return !1;
                e = h.clone(this.attributes)
            }
            a = h.extend({}, c, {
                silent: !0
            });
            if (d && !this.set(d, c.wait ? a : c) || !d && !this._validate(null, c)) return !1;
            var g = this,
                i = c.success;
            c.success = function (a) {
                f = !0;
                var b = g.parse(a);
                c.wait && (b = h.extend(d || {}, b));
                if (!g.set(b, c)) return !1;
                i && i(g, a, c);
                return void 0
            };
            b = this.isNew() ? "create" : c.patch ? "patch" : "update";
            "patch" == b && (c.attrs = d);
            b = this.sync(b, this, c);
            !f && c.wait && (this.clear(a), this.set(e, a));
            return b
        },
        destroy: function (a) {
            var a = a ? h.clone(a) : {}, b = this,
                c = a.success,
                d = function () {
                    b.trigger("destroy", b, b.collection, a)
                };
            a.success = function (e) {
                (a.wait || b.isNew()) && d();
                c && c(b, e, a)
            };
            if (this.isNew()) return a.success(), !1;
            var e = this.sync("delete", this, a);
            a.wait || d();
            return e
        },
        url: function () {
            var a = h.result(this, "urlRoot") || h.result(this.collection, "url") || B();
            return this.isNew() ? a : a + ("/" === a.charAt(a.length - 1) ? "" : "/") + encodeURIComponent(this.id)
        },
        parse: function (a) {
            return a
        },
        clone: function () {
            return new this.constructor(this.attributes)
        },
        isNew: function () {
            return null == this.id
        },
        change: function (a) {
            var b = this._changing;
            this._changing = !0;
            var c = this._computeChanges(!0);
            this._pending = !! c.length;
            for (var d = c.length - 2; d >= 0; d -= 2) this.trigger("change:" + c[d], this, c[d + 1], a);
            if (b) return this;
            for (; this._pending;) this._pending = !1, this.trigger("change", this, a), this._previousAttributes = h.clone(this.attributes);
            this._changing = !1;
            return this
        },
        hasChanged: function (a) {
            this._hasComputed || this._computeChanges();
            return null == a ? !h.isEmpty(this.changed) : h.has(this.changed, a)
        },
        changedAttributes: function (a) {
            if (!a) return this.hasChanged() ? h.clone(this.changed) : !1;
            var b, c, d = !1,
                e = this._previousAttributes;
            for (c in a) h.isEqual(e[c], b = a[c]) || ((d || (d = {}))[c] = b);
            return d
        },
        _computeChanges: function (a) {
            this.changed = {};
            for (var b = {}, c = [], d = this._currentAttributes, e = this._changes, f = e.length - 2; f >= 0; f -= 2) {
                var g = e[f],
                    h = e[f + 1];
                b[g] || (b[g] = !0, d[g] !== h && (this.changed[g] = h, a && (c.push(g, h), d[g] = h)))
            }
            a && (this._changes = []);
            this._hasComputed = !0;
            return c
        },
        previous: function (a) {
            return null != a && this._previousAttributes ? this._previousAttributes[a] : null
        },
        previousAttributes: function () {
            return h.clone(this._previousAttributes)
        },
        _validate: function (a, b) {
            if (!this.validate) return !0;
            var a = h.extend({}, this.attributes, a),
                c = this.validate(a, b);
            if (!c) return !0;
            b && b.error && b.error(this, c, b);
            this.trigger("error", this, c, b);
            return !1
        }
    });
    var m = a.Collection = function (a, b) {
        b || (b = {});
        b.model && (this.model = b.model);
        void 0 !== b.comparator && (this.comparator = b.comparator);
        this._reset();
        this.initialize.apply(this, arguments);
        a && this.reset(a, h.extend({
            silent: !0
        }, b))
    };
    h.extend(m.prototype, d, {
        model: l,
        initialize: function () {},
        toJSON: function (a) {
            return this.map(function (b) {
                return b.toJSON(a)
            })
        },
        sync: function () {
            return a.sync.apply(this, arguments)
        },
        add: function (a, b) {
            var c, d, f, i, j = b && b.at,
                k = null == (b && b.sort) ? !0 : b.sort,
                a = h.isArray(a) ? a.slice() : [a];
            for (c = a.length - 1; c >= 0; c--)(d = this._prepareModel(a[c], b)) ? (a[c] = d, (f = null != d.id && this._byId[d.id]) || this._byCid[d.cid] ? (b && b.merge && f && (f.set(d.attributes, b), i = k), a.splice(c, 1)) : (d.on("all", this._onModelEvent, this), this._byCid[d.cid] = d, null != d.id && (this._byId[d.id] = d))) : (this.trigger("error", this, a[c], b), a.splice(c, 1));
            a.length && (i = k);
            this.length += a.length;
            c = [null != j ? j : this.models.length, 0];
            e.apply(c, a);
            g.apply(this.models, c);
            i && this.comparator && null == j && this.sort({
                silent: !0
            });
            if (b && b.silent) return this;
            for (; d = a.shift();) d.trigger("add", d, this, b);
            return this
        },
        remove: function (a, b) {
            var c, d, e, f;
            b || (b = {});
            a = h.isArray(a) ? a.slice() : [a];
            c = 0;
            for (d = a.length; d > c; c++)(f = this.get(a[c])) && (delete this._byId[f.id], delete this._byCid[f.cid], e = this.indexOf(f), this.models.splice(e, 1), this.length--, b.silent || (b.index = e, f.trigger("remove", f, this, b)), this._removeReference(f));
            return this
        },
        push: function (a, b) {
            a = this._prepareModel(a, b);
            this.add(a, h.extend({
                at: this.length
            }, b));
            return a
        },
        pop: function (a) {
            var b = this.at(this.length - 1);
            this.remove(b, a);
            return b
        },
        unshift: function (a, b) {
            a = this._prepareModel(a, b);
            this.add(a, h.extend({
                at: 0
            }, b));
            return a
        },
        shift: function (a) {
            var b = this.at(0);
            this.remove(b, a);
            return b
        },
        slice: function (a, b) {
            return this.models.slice(a, b)
        },
        get: function (a) {
            return null == a ? void 0 : this._byId[null != a.id ? a.id : a] || this._byCid[a.cid || a]
        },
        at: function (a) {
            return this.models[a]
        },
        where: function (a) {
            return h.isEmpty(a) ? [] : this.filter(function (b) {
                for (var c in a)
                    if (a[c] !== b.get(c)) return !1;
                return !0
            })
        },
        sort: function (a) {
            if (!this.comparator) throw Error("Cannot sort a set without a comparator");
            h.isString(this.comparator) || 1 === this.comparator.length ? this.models = this.sortBy(this.comparator, this) : this.models.sort(h.bind(this.comparator, this));
            (!a || !a.silent) && this.trigger("sort", this, a);
            return this
        },
        pluck: function (a) {
            return h.invoke(this.models, "get", a)
        },
        update: function (a, b) {
            var c, d, e, f, g = [],
                i = [],
                j = {}, k = this.model.prototype.idAttribute,
                b = h.extend({
                    add: !0,
                    merge: !0,
                    remove: !0
                }, b);
            b.parse && (a = this.parse(a));
            h.isArray(a) || (a = a ? [a] : []);
            if (b.add && !b.remove) return this.add(a, b);
            d = 0;
            for (e = a.length; e > d; d++) c = a[d], f = this.get(c.id || c.cid || c[k]), b.remove && f && (j[f.cid] = !0), (b.add && !f || b.merge && f) && g.push(c);
            if (b.remove) {
                d = 0;
                for (e = this.models.length; e > d; d++) c = this.models[d], j[c.cid] || i.push(c)
            }
            i.length && this.remove(i, b);
            g.length && this.add(g, b);
            return this
        },
        reset: function (a, b) {
            b || (b = {});
            b.parse && (a = this.parse(a));
            for (var c = 0, d = this.models.length; d > c; c++) this._removeReference(this.models[c]);
            b.previousModels = this.models;
            this._reset();
            a && this.add(a, h.extend({
                silent: !0
            }, b));
            b.silent || this.trigger("reset", this, b);
            return this
        },
        fetch: function (a) {
            a = a ? h.clone(a) : {};
            void 0 === a.parse && (a.parse = !0);
            var b = this,
                c = a.success;
            a.success = function (d) {
                b[a.update ? "update" : "reset"](d, a);
                c && c(b, d, a)
            };
            return this.sync("read", this, a)
        },
        create: function (a, b) {
            var c = this,
                b = b ? h.clone(b) : {}, a = this._prepareModel(a, b);
            if (!a) return !1;
            b.wait || c.add(a, b);
            var d = b.success;
            b.success = function (a, b, e) {
                e.wait && c.add(a, e);
                d && d(a, b, e)
            };
            a.save(null, b);
            return a
        },
        parse: function (a) {
            return a
        },
        clone: function () {
            return new this.constructor(this.models)
        },
        chain: function () {
            return h(this.models).chain()
        },
        _reset: function () {
            this.length = 0;
            this.models = [];
            this._byId = {};
            this._byCid = {}
        },
        _prepareModel: function (a, b) {
            if (a instanceof l) return a.collection || (a.collection = this), a;
            b || (b = {});
            b.collection = this;
            var c = new this.model(a, b);
            return c._validate(a, b) ? c : !1
        },
        _removeReference: function (a) {
            this === a.collection && delete a.collection;
            a.off("all", this._onModelEvent, this)
        },
        _onModelEvent: function (a, b, c, d) {
            ("add" === a || "remove" === a) && c !== this || ("destroy" === a && this.remove(b, d), b && a === "change:" + b.idAttribute && (delete this._byId[b.previous(b.idAttribute)], null != b.id && (this._byId[b.id] = b)), this.trigger.apply(this, arguments))
        }
    });
    h.each("forEach each map collect reduce foldl inject reduceRight foldr find detect filter select reject every all some any include contains invoke max min sortedIndex toArray size first head take initial rest tail last without indexOf shuffle lastIndexOf isEmpty".split(" "), function (a) {
        m.prototype[a] = function () {
            var b = f.call(arguments);
            b.unshift(this.models);
            return h[a].apply(h, b)
        }
    });
    h.each(["groupBy", "countBy", "sortBy"], function (a) {
        m.prototype[a] = function (b, c) {
            var d = h.isFunction(b) ? b : function (a) {
                    return a.get(b)
                };
            return h[a](this.models, d, c)
        }
    });
    var n = a.Router = function (a) {
        a || (a = {});
        a.routes && (this.routes = a.routes);
        this._bindRoutes();
        this.initialize.apply(this, arguments)
    }, o = /\((.*?)\)/g,
        p = /:\w+/g,
        q = /\*\w+/g,
        r = /[\-{}\[\]+?.,\\\^$|#\s]/g;
    h.extend(n.prototype, d, {
        initialize: function () {},
        route: function (b, c, d) {
            h.isRegExp(b) || (b = this._routeToRegExp(b));
            d || (d = this[c]);
            a.history.route(b, h.bind(function (e) {
                e = this._extractParameters(b, e);
                d && d.apply(this, e);
                this.trigger.apply(this, ["route:" + c].concat(e));
                a.history.trigger("route", this, c, e)
            }, this));
            return this
        },
        navigate: function (b, c) {
            a.history.navigate(b, c);
            return this
        },
        _bindRoutes: function () {
            if (this.routes)
                for (var a, b = h.keys(this.routes); null != (a = b.pop());) this.route(a, this.routes[a])
        },
        _routeToRegExp: function (a) {
            a = a.replace(r, "\\$&").replace(o, "(?:$1)?").replace(p, "([^/]+)").replace(q, "(.*?)");
            return RegExp("^" + a + "$")
        },
        _extractParameters: function (a, b) {
            return a.exec(b).slice(1)
        }
    });
    var s = a.History = function () {
        this.handlers = [];
        h.bindAll(this, "checkUrl");
        "undefined" != typeof window && (this.location = window.location, this.history = window.history)
    }, t = /^[#\/]|\s+$/g,
        u = /^\/+|\/+$/g,
        v = /msie [\w.]+/,
        w = /\/$/;
    s.started = !1;
    h.extend(s.prototype, d, {
        interval: 50,
        getHash: function (a) {
            return (a = (a || this).location.href.match(/#(.*)$/)) ? a[1] : ""
        },
        getFragment: function (a, b) {
            if (null == a)
                if (this._hasPushState || !this._wantsHashChange || b) {
                    var a = this.location.pathname,
                        c = this.root.replace(w, "");
                    a.indexOf(c) || (a = a.substr(c.length))
                } else a = this.getHash();
            return a.replace(t, "")
        },
        start: function (b) {
            if (s.started) throw Error("Backbone.history has already been started");
            s.started = !0;
            this.options = h.extend({}, {
                root: "/"
            }, this.options, b);
            this.root = this.options.root;
            this._wantsHashChange = !1 !== this.options.hashChange;
            this._wantsPushState = !! this.options.pushState;
            this._hasPushState = !(!this.options.pushState || !this.history || !this.history.pushState);
            var b = this.getFragment(),
                c = document.documentMode,
                c = v.exec(navigator.userAgent.toLowerCase()) && (!c || 7 >= c);
            this.root = ("/" + this.root + "/").replace(u, "/");
            c && this._wantsHashChange && (this.iframe = a.$('<iframe src="javascript:0" tabindex="-1" />').hide().appendTo("body")[0].contentWindow, this.navigate(b));
            this._hasPushState ? a.$(window).bind("popstate", this.checkUrl) : this._wantsHashChange && "onhashchange" in window && !c ? a.$(window).bind("hashchange", this.checkUrl) : this._wantsHashChange && (this._checkUrlInterval = setInterval(this.checkUrl, this.interval));
            this.fragment = b;
            b = this.location;
            c = b.pathname.replace(/[^\/]$/, "$&/") === this.root;
            if (this._wantsHashChange && this._wantsPushState && !this._hasPushState && !c) return this.fragment = this.getFragment(null, !0), this.location.replace(this.root + this.location.search + "#" + this.fragment), !0;
            this._wantsPushState && this._hasPushState && c && b.hash && (this.fragment = this.getHash().replace(t, ""), this.history.replaceState({}, document.title, this.root + this.fragment + b.search));
            return this.options.silent ? void 0 : this.loadUrl()
        },
        stop: function () {
            a.$(window).unbind("popstate", this.checkUrl).unbind("hashchange", this.checkUrl);
            clearInterval(this._checkUrlInterval);
            s.started = !1
        },
        route: function (a, b) {
            this.handlers.unshift({
                route: a,
                callback: b
            })
        },
        checkUrl: function () {
            var a = this.getFragment();
            a === this.fragment && this.iframe && (a = this.getFragment(this.getHash(this.iframe)));
            if (a === this.fragment) return !1;
            this.iframe && this.navigate(a);
            this.loadUrl() || this.loadUrl(this.getHash())
        },
        loadUrl: function (a) {
            var b = this.fragment = this.getFragment(a);
            return h.any(this.handlers, function (a) {
                return a.route.test(b) ? (a.callback(b), !0) : void 0
            })
        },
        navigate: function (a, b) {
            if (!s.started) return !1;
            b && !0 !== b || (b = {
                trigger: b
            });
            a = this.getFragment(a || "");
            if (this.fragment !== a) {
                this.fragment = a;
                var c = this.root + a;
                if (this._hasPushState) this.history[b.replace ? "replaceState" : "pushState"]({}, document.title, c);
                else {
                    if (!this._wantsHashChange) return this.location.assign(c);
                    this._updateHash(this.location, a, b.replace), this.iframe && a !== this.getFragment(this.getHash(this.iframe)) && (b.replace || this.iframe.document.open().close(), this._updateHash(this.iframe.location, a, b.replace))
                }
                b.trigger && this.loadUrl(a)
            }
        },
        _updateHash: function (a, b, c) {
            c ? (c = a.href.replace(/(javascript:|#).*$/, ""), a.replace(c + "#" + b)) : a.hash = "#" + b
        }
    });
    a.history = new s;
    var x = a.View = function (a) {
        this.cid = h.uniqueId("view");
        this._configure(a || {});
        this._ensureElement();
        this.initialize.apply(this, arguments);
        this.delegateEvents()
    }, y = /^(\S+)\s*(.*)$/,
        z = "model collection el id attributes className tagName events".split(" ");
    h.extend(x.prototype, d, {
        tagName: "div",
        $: function (a) {
            return this.$el.find(a)
        },
        initialize: function () {},
        render: function () {
            return this
        },
        remove: function () {
            this.$el.remove();
            this.stopListening();
            return this
        },
        make: function (b, c, d) {
            b = document.createElement(b);
            c && a.$(b).attr(c);
            null != d && a.$(b).html(d);
            return b
        },
        setElement: function (b, c) {
            this.$el && this.undelegateEvents();
            this.$el = b instanceof a.$ ? b : a.$(b);
            this.el = this.$el[0];
            !1 !== c && this.delegateEvents();
            return this
        },
        delegateEvents: function (a) {
            if (a || (a = h.result(this, "events"))) {
                this.undelegateEvents();
                for (var b in a) {
                    var c = a[b];
                    h.isFunction(c) || (c = this[a[b]]);
                    if (!c) throw Error('Method "' + a[b] + '" does not exist');
                    var d = b.match(y),
                        e = d[1],
                        d = d[2],
                        c = h.bind(c, this),
                        e = e + (".delegateEvents" + this.cid);
                    "" === d ? this.$el.bind(e, c) : this.$el.delegate(d, e, c)
                }
            }
        },
        undelegateEvents: function () {
            this.$el.unbind(".delegateEvents" + this.cid)
        },
        _configure: function (a) {
            this.options && (a = h.extend({}, h.result(this, "options"), a));
            h.extend(this, h.pick(a, z));
            this.options = a
        },
        _ensureElement: function () {
            if (this.el) this.setElement(h.result(this, "el"), !1);
            else {
                var a = h.extend({}, h.result(this, "attributes"));
                this.id && (a.id = h.result(this, "id"));
                this.className && (a["class"] = h.result(this, "className"));
                this.setElement(this.make(h.result(this, "tagName"), a), !1)
            }
        }
    });
    var A = {
        create: "POST",
        update: "PUT",
        patch: "PATCH",
        "delete": "DELETE",
        read: "GET"
    };
    a.sync = function (b, c, d) {
        var e = A[b];
        h.defaults(d || (d = {}), {
            emulateHTTP: a.emulateHTTP,
            emulateJSON: a.emulateJSON
        });
        var f = {
            type: e,
            dataType: "json"
        };
        d.url || (f.url = h.result(c, "url") || B());
        null != d.data || !c || "create" !== b && "update" !== b && "patch" !== b || (f.contentType = "application/json", f.data = JSON.stringify(d.attrs || c.toJSON(d)));
        d.emulateJSON && (f.contentType = "application/x-www-form-urlencoded", f.data = f.data ? {
            model: f.data
        } : {});
        if (d.emulateHTTP && ("PUT" === e || "DELETE" === e || "PATCH" === e)) {
            f.type = "POST";
            d.emulateJSON && (f.data._method = e);
            var g = d.beforeSend;
            d.beforeSend = function (a) {
                a.setRequestHeader("X-HTTP-Method-Override", e);
                return g ? g.apply(this, arguments) : void 0
            }
        }
        "GET" !== f.type && !d.emulateJSON && (f.processData = !1);
        var i = d.success;
        d.success = function (a, b, e) {
            i && i(a, b, e);
            c.trigger("sync", c, a, d)
        };
        var j = d.error;
        d.error = function (a) {
            j && j(c, a, d);
            c.trigger("error", c, a, d)
        };
        b = a.ajax(h.extend(f, d));
        c.trigger("request", c, b, d);
        return b
    };
    a.ajax = function () {
        return a.$.ajax.apply(a.$, arguments)
    };
    l.extend = m.extend = n.extend = x.extend = s.extend = function (a, b) {
        var c, d = this;
        c = a && h.has(a, "constructor") ? a.constructor : function () {
            d.apply(this, arguments)
        };
        h.extend(c, d, b);
        var e = function () {
            this.constructor = c
        };
        e.prototype = d.prototype;
        c.prototype = new e;
        a && h.extend(c.prototype, a);
        c.__super__ = d.prototype;
        return c
    };
    var B = function () {
        throw Error('A "url" property or function must be specified')
    }
}).call(this);
! function () {
    "use strict";
    var a = [];
    Backbone.NestedModel = Backbone.Model.extend({
        get: function (a) {
            var b, c = Backbone.NestedModel.attrPath(a);
            Backbone.NestedModel.walkPath(this.attributes, c, function (a, d) {
                var e = _.last(d);
                d.length === c.length && (b = a[e])
            });
            return b
        },
        has: function (a) {
            var b = this.get(a);
            return !(null === b || _.isUndefined(b))
        },
        set: function (a, b, c) {
            var d, e = Backbone.NestedModel.deepClone(this.attributes);
            _.isString(a) ? d = Backbone.NestedModel.attrPath(a) : _.isArray(a) && (d = a);
            if (d) {
                c = c || {};
                this._setAttr(e, d, b, c)
            } else {
                c = b || {};
                var f = a;
                for (var g in f) f.hasOwnProperty(g) && this._setAttr(e, Backbone.NestedModel.attrPath(g), c.unset ? null : f[g], c)
            } if (!this._validate(e, c)) {
                this.changed = {};
                return !1
            }
            if (c.unset && d && 1 === d.length) {
                var h = {};
                h[a] = null;
                Backbone.NestedModel.__super__.set.call(this, h, c)
            } else {
                if (c.unset && d) {
                    c = _.extend({}, c);
                    delete c.unset
                }
                Backbone.NestedModel.__super__.set.call(this, e, c)
            }
            this._runDelayedTriggers();
            return this
        },
        clear: function (a) {
            a = a || {};
            if (!a.silent && this.validate && !this.validate({}, a)) return !1;
            var b = this.changed = {}, c = this,
                d = function (e, f) {
                    _.each(e, function (g, h) {
                        var i = f;
                        _.isArray(e) ? i += "[" + h + "]" : f ? i += "." + h : i = h;
                        g = e[h];
                        _.isObject(g) && d(g, i);
                        a.silent || c._delayedChange(i, null);
                        b[i] = null
                    })
                };
            d(this.attributes, "");
            this.attributes = {};
            this._escapedAttributes = {};
            a.silent || this._delayedTrigger("change");
            this._runDelayedTriggers();
            return this
        },
        add: function (a, b, c) {
            var d = this.get(a);
            if (!_.isArray(d)) throw new Error("current value is not an array");
            return this.set(a + "[" + d.length + "]", b, c)
        },
        remove: function (a, b) {
            b = b || {};
            var c = Backbone.NestedModel.attrPath(a),
                d = _.initial(c),
                e = this.get(d),
                f = _.last(c);
            if (!_.isArray(e)) throw new Error("remove() must be called on a nested array");
            var g = !b.silent && e.length >= f + 1,
                h = e[f];
            e.splice(f, 1);
            b.silent = !0;
            this.set(d, e, b);
            if (g) {
                a = Backbone.NestedModel.createAttrStr(d);
                this.trigger("remove:" + a, this, h);
                for (var i = d.length; i >= 1; i--) {
                    a = Backbone.NestedModel.createAttrStr(_.first(d, i));
                    this.trigger("change:" + a, this, h)
                }
                this.trigger("change", this, h)
            }
            return this
        },
        toJSON: function () {
            return Backbone.NestedModel.deepClone(this.attributes)
        },
        _delayedTrigger: function () {
            a.push(arguments)
        },
        _delayedChange: function (a, b) {
            this._delayedTrigger("change:" + a, this, b);
            this.changed[a] = b
        },
        _runDelayedTriggers: function () {
            for (; a.length > 0;) this.trigger.apply(this, a.shift())
        },
        _setAttr: function (a, b, c, d) {
            d = d || {};
            var e = b.length,
                f = this;
            Backbone.NestedModel.walkPath(a, b, function (a, g) {
                var h = _.last(g),
                    i = Backbone.NestedModel.createAttrStr(g),
                    j = !_.isEqual(a[h], c);
                if (g.length === e) {
                    if (d.unset) {
                        delete a[h];
                        delete f._escapedAttributes[i]
                    } else a[h] = c; if (!d.silent && _.isObject(c) && j) {
                        var k = function (a, b) {
                            var c, d;
                            for (var e in a)
                                if (a.hasOwnProperty(e)) {
                                    c = b + "." + e;
                                    d = a[e];
                                    _.isEqual(f.get(c), d) || f._delayedChange(c, d);
                                    _.isObject(d) && k(d, c)
                                }
                        };
                        k(c, i)
                    }
                    if (null === c) {
                        var l = Backbone.NestedModel.createAttrStr(_.initial(b));
                        f._delayedTrigger("remove:" + l, f, a[h])
                    }
                } else a[h] || (a[h] = _.isNumber(h) ? [] : {}); if (!d.silent) {
                    g.length > 1 && j && f._delayedChange(i, a[h]);
                    _.isArray(a[h]) && f._delayedTrigger("add:" + i, f, a[h])
                }
            })
        }
    }, {
        attrPath: function (a) {
            var b;
            if (_.isString(a)) {
                b = "" === a ? [""] : a.match(/[^\.\[\]]+/g);
                b = _.map(b, function (a) {
                    return a.match(/^\d+$/) ? parseInt(a, 10) : a
                })
            } else b = a;
            return b
        },
        createAttrStr: function (a) {
            var b = a[0];
            _.each(_.rest(a), function (a) {
                b += _.isNumber(a) ? "[" + a + "]" : "." + a
            });
            return b
        },
        deepClone: function (a) {
            return $.extend(!0, {}, a)
        },
        walkPath: function (a, b, c, d) {
            for (var e, f = a, g = 0; g < b.length; g++) {
                c.call(d || this, f, b.slice(0, g + 1));
                e = b[g];
                f = f[e];
                if (!f) break
            }
        }
    })
}();
! function (a) {
    {
        var b = [].slice,
            c = {};
        a.amplify = {
            publish: function (a) {
                if ("string" != typeof a) throw new Error("You must provide a valid topic to publish.");
                var d, e, f, g, h = b.call(arguments, 1),
                    i = 0;
                if (!c[a]) return !0;
                d = c[a].slice();
                for (f = d.length; f > i; i++) {
                    e = d[i], g = e.callback.apply(e.context, h);
                    if (g === !1) break
                }
                return g !== !1
            },
            subscribe: function (a, b, d, e) {
                if ("string" != typeof a) throw new Error("You must provide a valid topic to create a subscription.");
                3 === arguments.length && "number" == typeof d && (e = d, d = b, b = null), 2 === arguments.length && (d = b, b = null), e = e || 10;
                for (var f, g = 0, h = a.split(/\s/), i = h.length; i > g; g++) {
                    a = h[g], f = !1, c[a] || (c[a] = []);
                    for (var j = c[a].length - 1, k = {
                            callback: d,
                            context: b,
                            priority: e
                        }; j >= 0; j--)
                        if (c[a][j].priority <= e) {
                            c[a].splice(j + 1, 0, k), f = !0;
                            break
                        }
                    f || c[a].unshift(k)
                }
                return d
            },
            unsubscribe: function (a, b, d) {
                if ("string" != typeof a) throw new Error("You must provide a valid topic to remove a subscription.");
                2 === arguments.length && (d = b, b = null);
                if (c[a])
                    for (var e = c[a].length, f = 0; e > f; f++) c[a][f].callback === d && (!b || c[a][f].context === b) && (c[a].splice(f, 1), f--, e--)
            }
        }
    }
}(this),
function (a, b) {
    function c(a, c) {
        d.addType(a, function (f, g, h) {
            var i, j, k, l, m = g,
                n = (new Date).getTime();
            if (!f) {
                m = {}, l = [], k = 0;
                try {
                    f = c.length;
                    for (; f = c.key(k++);) e.test(f) && (j = JSON.parse(c.getItem(f)), j.expires && j.expires <= n ? l.push(f) : m[f.replace(e, "")] = j.data);
                    for (; f = l.pop();) c.removeItem(f)
                } catch (o) {}
                return m
            }
            f = "__amplify__" + f;
            if (g === b) {
                i = c.getItem(f), j = i ? JSON.parse(i) : {
                    expires: -1
                };
                if (!(j.expires && j.expires <= n)) return j.data;
                c.removeItem(f)
            } else if (null === g) c.removeItem(f);
            else {
                j = JSON.stringify({
                    data: g,
                    expires: h.expires ? n + h.expires : null
                });
                try {
                    c.setItem(f, j)
                } catch (o) {
                    d[a]();
                    try {
                        c.setItem(f, j)
                    } catch (o) {
                        throw d.error()
                    }
                }
            }
            return m
        })
    }
    var d = a.store = function (a, b, c) {
        var e = d.type;
        return c && c.type && c.type in d.types && (e = c.type), d.types[e](a, b, c || {})
    };
    d.types = {}, d.type = null, d.addType = function (a, b) {
        d.type || (d.type = a), d.types[a] = b, d[a] = function (b, c, e) {
            return e = e || {}, e.type = a, d(b, c, e)
        }
    }, d.error = function () {
        return "amplify.store quota exceeded"
    };
    var e = /^__amplify__/;
    for (var f in {
        localStorage: 1,
        sessionStorage: 1
    }) try {
        window[f].setItem("__amplify__", "x"), window[f].removeItem("__amplify__"), c(f, window[f])
    } catch (g) {}
    if (!d.types.localStorage && window.globalStorage) try {
        c("globalStorage", window.globalStorage[window.location.hostname]), "sessionStorage" === d.type && (d.type = "globalStorage")
    } catch (g) {}! function () {
        if (!d.types.localStorage) {
            var a = document.createElement("div"),
                c = "amplify";
            a.style.display = "none", document.getElementsByTagName("head")[0].appendChild(a);
            try {
                a.addBehavior("#default#userdata"), a.load(c)
            } catch (e) {
                a.parentNode.removeChild(a);
                return
            }
            d.addType("userData", function (e, f, g) {
                a.load(c);
                var h, i, j, k, l, m = f,
                    n = (new Date).getTime();
                if (!e) {
                    m = {}, l = [], k = 0;
                    for (; h = a.XMLDocument.documentElement.attributes[k++];) i = JSON.parse(h.value), i.expires && i.expires <= n ? l.push(h.name) : m[h.name] = i.data;
                    for (; e = l.pop();) a.removeAttribute(e);
                    return a.save(c), m
                }
                e = e.replace(/[^\-._0-9A-Za-z\xb7\xc0-\xd6\xd8-\xf6\xf8-\u037d\u037f-\u1fff\u200c-\u200d\u203f\u2040\u2070-\u218f]/g, "-"), e = e.replace(/^-/, "_-");
                if (f === b) {
                    h = a.getAttribute(e), i = h ? JSON.parse(h) : {
                        expires: -1
                    };
                    if (!(i.expires && i.expires <= n)) return i.data;
                    a.removeAttribute(e)
                } else null === f ? a.removeAttribute(e) : (j = a.getAttribute(e), i = JSON.stringify({
                    data: f,
                    expires: g.expires ? n + g.expires : null
                }), a.setAttribute(e, i));
                try {
                    a.save(c)
                } catch (o) {
                    null === j ? a.removeAttribute(e) : a.setAttribute(e, j), d.userData();
                    try {
                        a.setAttribute(e, i), a.save(c)
                    } catch (o) {
                        throw null === j ? a.removeAttribute(e) : a.setAttribute(e, j), d.error()
                    }
                }
                return m
            })
        }
    }(),
    function () {
        function a(a) {
            return a === b ? b : JSON.parse(JSON.stringify(a))
        }
        var c = {}, e = {};
        d.addType("memory", function (d, f, g) {
            return d ? f === b ? a(c[d]) : (e[d] && (clearTimeout(e[d]), delete e[d]), null === f ? (delete c[d], null) : (c[d] = f, g.expires && (e[d] = setTimeout(function () {
                delete c[d], delete e[d]
            }, g.expires)), f)) : a(c)
        })
    }()
}(this.amplify = this.amplify || {}),
function (a) {
    "use strict";

    function b() {}

    function c(a) {
        return "[object Function]" === {}.toString.call(a)
    }

    function d(a) {
        var b = !1;
        return setTimeout(function () {
            b = !0
        }, 1),
        function () {
            var c = this,
                d = arguments;
            b ? a.apply(c, d) : setTimeout(function () {
                a.apply(c, d)
            }, 1)
        }
    }
    a.request = function (e, f, g) {
        var h = e || {};
        "string" == typeof h && (c(f) && (g = f, f = {}), h = {
            resourceId: e,
            data: f || {},
            success: g
        });
        var i = {
            abort: b
        }, j = a.request.resources[h.resourceId],
            k = h.success || b,
            l = h.error || b;
        h.success = d(function (b, c) {
            c = c || "success", a.publish("request.success", h, b, c), a.publish("request.complete", h, b, c), k(b, c)
        }), h.error = d(function (b, c) {
            c = c || "error", a.publish("request.error", h, b, c), a.publish("request.complete", h, b, c), l(b, c)
        });
        if (!j) throw h.resourceId ? "amplify.request: unknown resourceId: " + h.resourceId : "amplify.request: no resourceId provided";
        if (a.publish("request.before", h)) return a.request.resources[h.resourceId](h, i), i;
        h.error(null, "abort");
        return void 0
    }, a.request.types = {}, a.request.resources = {}, a.request.define = function (b, c, d) {
        if ("string" == typeof c) {
            if (!(c in a.request.types)) throw "amplify.request.define: unknown type: " + c;
            d.resourceId = b, a.request.resources[b] = a.request.types[c](d)
        } else a.request.resources[b] = c
    }
}(amplify),
function (a, b, c) {
    "use strict";
    var d = ["status", "statusText", "responseText", "responseXML", "readyState"],
        e = /\{([^\}]+)\}/g;
    a.request.types.ajax = function (e) {
        return e = b.extend({
            type: "GET"
        }, e),
        function (f, g) {
            var h, i, j = (e.url, g.abort),
                k = b.extend(!0, {}, e, {
                    data: f.data
                }),
                l = !1,
                m = {
                    readyState: 0,
                    setRequestHeader: function (a, b) {
                        return h.setRequestHeader(a, b)
                    },
                    getAllResponseHeaders: function () {
                        return h.getAllResponseHeaders()
                    },
                    getResponseHeader: function (a) {
                        return h.getResponseHeader(a)
                    },
                    overrideMimeType: function (a) {
                        return h.overrideMimeType(a)
                    },
                    abort: function () {
                        l = !0;
                        try {
                            h.abort()
                        } catch (a) {}
                        i(null, "abort")
                    },
                    success: function (a, b) {
                        f.success(a, b)
                    },
                    error: function (a, b) {
                        f.error(a, b)
                    }
                };
            i = function (a, e) {
                b.each(d, function (a, b) {
                    try {
                        m[b] = h[b]
                    } catch (c) {}
                }), /OK$/.test(m.statusText) && (m.statusText = "success"), a === c && (a = null), l && (e = "abort"), /timeout|error|abort/.test(e) ? m.error(a, e) : m.success(a, e), i = b.noop
            }, a.publish("request.ajax.preprocess", e, f, k, m), b.extend(k, {
                isJSONP: function () {
                    return /jsonp/gi.test(this.dataType)
                },
                cacheURL: function () {
                    if (!this.isJSONP()) return this.url;
                    var a = "callback";
                    this.hasOwnProperty("jsonp") && (this.jsonp !== !1 ? a = this.jsonp : this.hasOwnProperty("jsonpCallback") && (a = this.jsonpCallback));
                    var b = new RegExp("&?" + a + "=[^&]*&?", "gi");
                    return this.url.replace(b, "")
                },
                success: function (a, b) {
                    i(a, b)
                },
                error: function (a, b) {
                    i(null, b)
                },
                beforeSend: function (b, c) {
                    h = b, k = c;
                    var d = e.beforeSend ? e.beforeSend.call(this, m, k) : !0;
                    return d && a.publish("request.before.ajax", e, f, k, m)
                }
            }), k.cache && k.isJSONP() && b.extend(k, {
                cache: !0
            }), b.ajax(k), g.abort = function () {
                m.abort(), j.call(this)
            }
        }
    }, a.subscribe("request.ajax.preprocess", function (a, c, d) {
        var f = [],
            g = d.data;
        "string" != typeof g && (g = b.extend(!0, {}, a.data, g), d.url = d.url.replace(e, function (a, b) {
            return b in g ? (f.push(b), g[b]) : void 0
        }), b.each(f, function (a, b) {
            delete g[b]
        }), d.data = g)
    }), a.subscribe("request.ajax.preprocess", function (a, c, d) {
        var e = d.data,
            f = a.dataMap;
        f && "string" != typeof e && (b.isFunction(f) ? d.data = f(e) : (b.each(a.dataMap, function (a, b) {
            a in e && (e[b] = e[a], delete e[a])
        }), d.data = e))
    });
    var f = a.request.cache = {
        _key: function (a, b, c) {
            function d() {
                return c.charCodeAt(f++) << 24 | c.charCodeAt(f++) << 16 | c.charCodeAt(f++) << 8 | c.charCodeAt(f++) << 0
            }
            c = b + c;
            for (var e = c.length, f = 0, g = d(); e > f;) g ^= d();
            return "request-" + a + "-" + g
        },
        _default: function () {
            var a = {};
            return function (b, c, d, e) {
                var g = f._key(c.resourceId, d.cacheURL(), d.data),
                    h = b.cache;
                if (g in a) return e.success(a[g]), !1;
                var i = e.success;
                e.success = function (b) {
                    a[g] = b, "number" == typeof h && setTimeout(function () {
                        delete a[g]
                    }, h), i.apply(this, arguments)
                }
            }
        }()
    };
    a.store && (b.each(a.store.types, function (b) {
        f[b] = function (c, d, e, g) {
            var h = f._key(d.resourceId, e.cacheURL(), e.data),
                i = a.store[b](h);
            if (i) return e.success(i), !1;
            var j = g.success;
            g.success = function (d) {
                a.store[b](h, d, {
                    expires: c.cache.expires
                }), j.apply(this, arguments)
            }
        }
    }), f.persist = f[a.store.type]), a.subscribe("request.before.ajax", function (a) {
        var b = a.cache;
        return b ? (b = b.type || b, f[b in f ? b : "_default"].apply(this, arguments)) : void 0
    }), a.request.decoders = {
        jsend: function (a, b, c, d, e) {
            "success" === a.status ? d(a.data) : "fail" === a.status ? e(a.data, "fail") : "error" === a.status ? (delete a.status, e(a, "error")) : e(null, "error")
        }
    }, a.subscribe("request.before.ajax", function (c, d, e, f) {
        function g(a, b) {
            i(a, b)
        }

        function h(a, b) {
            j(a, b)
        }
        var i = f.success,
            j = f.error,
            k = b.isFunction(c.decoder) ? c.decoder : c.decoder in a.request.decoders ? a.request.decoders[c.decoder] : a.request.decoders._default;
        k && (f.success = function (a, b) {
            k(a, b, f, g, h)
        }, f.error = function (a, b) {
            k(a, b, f, g, h)
        })
    })
}(amplify, jQuery);
! function (a, b) {
    function c(b, c) {
        var e, f, g, h = b.nodeName.toLowerCase();
        return "area" === h ? (e = b.parentNode, f = e.name, b.href && f && "map" === e.nodeName.toLowerCase() ? (g = a("img[usemap=#" + f + "]")[0], !! g && d(g)) : !1) : (/input|select|textarea|button|object/.test(h) ? !b.disabled : "a" === h ? b.href || c : c) && d(b)
    }

    function d(b) {
        return a.expr.filters.visible(b) && !a(b).parents().andSelf().filter(function () {
            return "hidden" === a.css(this, "visibility")
        }).length
    }
    var e = 0,
        f = /^ui-id-\d+$/;
    a.ui = a.ui || {}, a.ui.version || (a.extend(a.ui, {
        version: "1.9.2",
        keyCode: {
            BACKSPACE: 8,
            COMMA: 188,
            DELETE: 46,
            DOWN: 40,
            END: 35,
            ENTER: 13,
            ESCAPE: 27,
            HOME: 36,
            LEFT: 37,
            NUMPAD_ADD: 107,
            NUMPAD_DECIMAL: 110,
            NUMPAD_DIVIDE: 111,
            NUMPAD_ENTER: 108,
            NUMPAD_MULTIPLY: 106,
            NUMPAD_SUBTRACT: 109,
            PAGE_DOWN: 34,
            PAGE_UP: 33,
            PERIOD: 190,
            RIGHT: 39,
            SPACE: 32,
            TAB: 9,
            UP: 38
        }
    }), a.fn.extend({
        _focus: a.fn.focus,
        focus: function (b, c) {
            return "number" == typeof b ? this.each(function () {
                var d = this;
                setTimeout(function () {
                    a(d).focus(), c && c.call(d)
                }, b)
            }) : this._focus.apply(this, arguments)
        },
        scrollParent: function () {
            var b;
            return b = a.ui.ie && /(static|relative)/.test(this.css("position")) || /absolute/.test(this.css("position")) ? this.parents().filter(function () {
                return /(relative|absolute|fixed)/.test(a.css(this, "position")) && /(auto|scroll)/.test(a.css(this, "overflow") + a.css(this, "overflow-y") + a.css(this, "overflow-x"))
            }).eq(0) : this.parents().filter(function () {
                return /(auto|scroll)/.test(a.css(this, "overflow") + a.css(this, "overflow-y") + a.css(this, "overflow-x"))
            }).eq(0), /fixed/.test(this.css("position")) || !b.length ? a(document) : b
        },
        zIndex: function (c) {
            if (c !== b) return this.css("zIndex", c);
            if (this.length)
                for (var d, e, f = a(this[0]); f.length && f[0] !== document;) {
                    if (d = f.css("position"), ("absolute" === d || "relative" === d || "fixed" === d) && (e = parseInt(f.css("zIndex"), 10), !isNaN(e) && 0 !== e)) return e;
                    f = f.parent()
                }
            return 0
        },
        uniqueId: function () {
            return this.each(function () {
                this.id || (this.id = "ui-id-" + ++e)
            })
        },
        removeUniqueId: function () {
            return this.each(function () {
                f.test(this.id) && a(this).removeAttr("id")
            })
        }
    }), a.extend(a.expr[":"], {
        data: a.expr.createPseudo ? a.expr.createPseudo(function (b) {
            return function (c) {
                return !!a.data(c, b)
            }
        }) : function (b, c, d) {
            return !!a.data(b, d[3])
        },
        focusable: function (b) {
            return c(b, !isNaN(a.attr(b, "tabindex")))
        },
        tabbable: function (b) {
            var d = a.attr(b, "tabindex"),
                e = isNaN(d);
            return (e || d >= 0) && c(b, !e)
        }
    }), a(function () {
        var b = document.body,
            c = b.appendChild(c = document.createElement("div"));
        c.offsetHeight, a.extend(c.style, {
            minHeight: "100px",
            height: "auto",
            padding: 0,
            borderWidth: 0
        }), a.support.minHeight = 100 === c.offsetHeight, a.support.selectstart = "onselectstart" in c, b.removeChild(c).style.display = "none"
    }), a("<a>").outerWidth(1).jquery || a.each(["Width", "Height"], function (c, d) {
        function e(b, c, d, e) {
            return a.each(f, function () {
                c -= parseFloat(a.css(b, "padding" + this)) || 0, d && (c -= parseFloat(a.css(b, "border" + this + "Width")) || 0), e && (c -= parseFloat(a.css(b, "margin" + this)) || 0)
            }), c
        }
        var f = "Width" === d ? ["Left", "Right"] : ["Top", "Bottom"],
            g = d.toLowerCase(),
            h = {
                innerWidth: a.fn.innerWidth,
                innerHeight: a.fn.innerHeight,
                outerWidth: a.fn.outerWidth,
                outerHeight: a.fn.outerHeight
            };
        a.fn["inner" + d] = function (c) {
            return c === b ? h["inner" + d].call(this) : this.each(function () {
                a(this).css(g, e(this, c) + "px")
            })
        }, a.fn["outer" + d] = function (b, c) {
            return "number" != typeof b ? h["outer" + d].call(this, b) : this.each(function () {
                a(this).css(g, e(this, b, !0, c) + "px")
            })
        }
    }), a("<a>").data("a-b", "a").removeData("a-b").data("a-b") && (a.fn.removeData = function (b) {
        return function (c) {
            return arguments.length ? b.call(this, a.camelCase(c)) : b.call(this)
        }
    }(a.fn.removeData)), function () {
        var b = /msie ([\w.]+)/.exec(navigator.userAgent.toLowerCase()) || [];
        a.ui.ie = b.length ? !0 : !1, a.ui.ie6 = 6 === parseFloat(b[1], 10)
    }(), a.fn.extend({
        disableSelection: function () {
            return this.bind((a.support.selectstart ? "selectstart" : "mousedown") + ".ui-disableSelection", function (a) {
                a.preventDefault()
            })
        },
        enableSelection: function () {
            return this.unbind(".ui-disableSelection")
        }
    }), a.extend(a.ui, {
        plugin: {
            add: function (b, c, d) {
                var e, f = a.ui[b].prototype;
                for (e in d) f.plugins[e] = f.plugins[e] || [], f.plugins[e].push([c, d[e]])
            },
            call: function (a, b, c) {
                var d, e = a.plugins[b];
                if (e && a.element[0].parentNode && 11 !== a.element[0].parentNode.nodeType)
                    for (d = 0; e.length > d; d++) a.options[e[d][0]] && e[d][1].apply(a.element, c)
            }
        },
        contains: a.contains,
        hasScroll: function (b, c) {
            if ("hidden" === a(b).css("overflow")) return !1;
            var d = c && "left" === c ? "scrollLeft" : "scrollTop",
                e = !1;
            return b[d] > 0 ? !0 : (b[d] = 1, e = b[d] > 0, b[d] = 0, e)
        },
        isOverAxis: function (a, b, c) {
            return a > b && b + c > a
        },
        isOver: function (b, c, d, e, f, g) {
            return a.ui.isOverAxis(b, d, f) && a.ui.isOverAxis(c, e, g)
        }
    }))
}(jQuery);
! function (a, b) {
    var c = 0,
        d = Array.prototype.slice,
        e = a.cleanData;
    a.cleanData = function (b) {
        for (var c, d = 0; null != (c = b[d]); d++) try {
            a(c).triggerHandler("remove")
        } catch (f) {}
        e(b)
    }, a.widget = function (c, d, e) {
        var f, g, h, i, j = c.split(".")[0];
        c = c.split(".")[1], f = j + "-" + c, e || (e = d, d = a.Widget), a.expr[":"][f.toLowerCase()] = function (b) {
            return !!a.data(b, f)
        }, a[j] = a[j] || {}, g = a[j][c], h = a[j][c] = function (a, c) {
            return this._createWidget ? (arguments.length && this._createWidget(a, c), b) : new h(a, c)
        }, a.extend(h, g, {
            version: e.version,
            _proto: a.extend({}, e),
            _childConstructors: []
        }), i = new d, i.options = a.widget.extend({}, i.options), a.each(e, function (b, c) {
            a.isFunction(c) && (e[b] = function () {
                var a = function () {
                    return d.prototype[b].apply(this, arguments)
                }, e = function (a) {
                        return d.prototype[b].apply(this, a)
                    };
                return function () {
                    var b, d = this._super,
                        f = this._superApply;
                    return this._super = a, this._superApply = e, b = c.apply(this, arguments), this._super = d, this._superApply = f, b
                }
            }())
        }), h.prototype = a.widget.extend(i, {
            widgetEventPrefix: g ? i.widgetEventPrefix : c
        }, e, {
            constructor: h,
            namespace: j,
            widgetName: c,
            widgetBaseClass: f,
            widgetFullName: f
        }), g ? (a.each(g._childConstructors, function (b, c) {
            var d = c.prototype;
            a.widget(d.namespace + "." + d.widgetName, h, c._proto)
        }), delete g._childConstructors) : d._childConstructors.push(h), a.widget.bridge(c, h)
    }, a.widget.extend = function (c) {
        for (var e, f, g = d.call(arguments, 1), h = 0, i = g.length; i > h; h++)
            for (e in g[h]) f = g[h][e], g[h].hasOwnProperty(e) && f !== b && (c[e] = a.isPlainObject(f) ? a.isPlainObject(c[e]) ? a.widget.extend({}, c[e], f) : a.widget.extend({}, f) : f);
        return c
    }, a.widget.bridge = function (c, e) {
        var f = e.prototype.widgetFullName || c;
        a.fn[c] = function (g) {
            var h = "string" == typeof g,
                i = d.call(arguments, 1),
                j = this;
            return g = !h && i.length ? a.widget.extend.apply(null, [g].concat(i)) : g, h ? this.each(function () {
                var d, e = a.data(this, f);
                return e ? a.isFunction(e[g]) && "_" !== g.charAt(0) ? (d = e[g].apply(e, i), d !== e && d !== b ? (j = d && d.jquery ? j.pushStack(d.get()) : d, !1) : b) : a.error("no such method '" + g + "' for " + c + " widget instance") : a.error("cannot call methods on " + c + " prior to initialization; attempted to call method '" + g + "'")
            }) : this.each(function () {
                var b = a.data(this, f);
                b ? b.option(g || {})._init() : a.data(this, f, new e(g, this))
            }), j
        }
    }, a.Widget = function () {}, a.Widget._childConstructors = [], a.Widget.prototype = {
        widgetName: "widget",
        widgetEventPrefix: "",
        defaultElement: "<div>",
        options: {
            disabled: !1,
            create: null
        },
        _createWidget: function (b, d) {
            d = a(d || this.defaultElement || this)[0], this.element = a(d), this.uuid = c++, this.eventNamespace = "." + this.widgetName + this.uuid, this.options = a.widget.extend({}, this.options, this._getCreateOptions(), b), this.bindings = a(), this.hoverable = a(), this.focusable = a(), d !== this && (a.data(d, this.widgetName, this), a.data(d, this.widgetFullName, this), this._on(!0, this.element, {
                remove: function (a) {
                    a.target === d && this.destroy()
                }
            }), this.document = a(d.style ? d.ownerDocument : d.document || d), this.window = a(this.document[0].defaultView || this.document[0].parentWindow)), this._create(), this._trigger("create", null, this._getCreateEventData()), this._init()
        },
        _getCreateOptions: a.noop,
        _getCreateEventData: a.noop,
        _create: a.noop,
        _init: a.noop,
        destroy: function () {
            this._destroy(), this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(a.camelCase(this.widgetFullName)), this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled ui-state-disabled"), this.bindings.unbind(this.eventNamespace), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")
        },
        _destroy: a.noop,
        widget: function () {
            return this.element
        },
        option: function (c, d) {
            var e, f, g, h = c;
            if (0 === arguments.length) return a.widget.extend({}, this.options);
            if ("string" == typeof c)
                if (h = {}, e = c.split("."), c = e.shift(), e.length) {
                    for (f = h[c] = a.widget.extend({}, this.options[c]), g = 0; e.length - 1 > g; g++) f[e[g]] = f[e[g]] || {}, f = f[e[g]];
                    if (c = e.pop(), d === b) return f[c] === b ? null : f[c];
                    f[c] = d
                } else {
                    if (d === b) return this.options[c] === b ? null : this.options[c];
                    h[c] = d
                }
            return this._setOptions(h), this
        },
        _setOptions: function (a) {
            var b;
            for (b in a) this._setOption(b, a[b]);
            return this
        },
        _setOption: function (a, b) {
            return this.options[a] = b, "disabled" === a && (this.widget().toggleClass(this.widgetFullName + "-disabled ui-state-disabled", !! b).attr("aria-disabled", b), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")), this
        },
        enable: function () {
            return this._setOption("disabled", !1)
        },
        disable: function () {
            return this._setOption("disabled", !0)
        },
        _on: function (c, d, e) {
            var f, g = this;
            "boolean" != typeof c && (e = d, d = c, c = !1), e ? (d = f = a(d), this.bindings = this.bindings.add(d)) : (e = d, d = this.element, f = this.widget()), a.each(e, function (e, h) {
                function i() {
                    return c || g.options.disabled !== !0 && !a(this).hasClass("ui-state-disabled") ? ("string" == typeof h ? g[h] : h).apply(g, arguments) : b
                }
                "string" != typeof h && (i.guid = h.guid = h.guid || i.guid || a.guid++);
                var j = e.match(/^(\w+)\s*(.*)$/),
                    k = j[1] + g.eventNamespace,
                    l = j[2];
                l ? f.delegate(l, k, i) : d.bind(k, i)
            })
        },
        _off: function (a, b) {
            b = (b || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace, a.unbind(b).undelegate(b)
        },
        _delay: function (a, b) {
            function c() {
                return ("string" == typeof a ? d[a] : a).apply(d, arguments)
            }
            var d = this;
            return setTimeout(c, b || 0)
        },
        _hoverable: function (b) {
            this.hoverable = this.hoverable.add(b), this._on(b, {
                mouseenter: function (b) {
                    a(b.currentTarget).addClass("ui-state-hover")
                },
                mouseleave: function (b) {
                    a(b.currentTarget).removeClass("ui-state-hover")
                }
            })
        },
        _focusable: function (b) {
            this.focusable = this.focusable.add(b), this._on(b, {
                focusin: function (b) {
                    a(b.currentTarget).addClass("ui-state-focus")
                },
                focusout: function (b) {
                    a(b.currentTarget).removeClass("ui-state-focus")
                }
            })
        },
        _trigger: function (b, c, d) {
            var e, f, g = this.options[b];
            if (d = d || {}, c = a.Event(c), c.type = (b === this.widgetEventPrefix ? b : this.widgetEventPrefix + b).toLowerCase(), c.target = this.element[0], f = c.originalEvent)
                for (e in f) e in c || (c[e] = f[e]);
            return this.element.trigger(c, d), !(a.isFunction(g) && g.apply(this.element[0], [c].concat(d)) === !1 || c.isDefaultPrevented())
        }
    }, a.each({
        show: "fadeIn",
        hide: "fadeOut"
    }, function (b, c) {
        a.Widget.prototype["_" + b] = function (d, e, f) {
            "string" == typeof e && (e = {
                effect: e
            });
            var g, h = e ? e === !0 || "number" == typeof e ? c : e.effect || c : b;
            e = e || {}, "number" == typeof e && (e = {
                duration: e
            }), g = !a.isEmptyObject(e), e.complete = f, e.delay && d.delay(e.delay), g && a.effects && (a.effects.effect[h] || a.uiBackCompat !== !1 && a.effects[h]) ? d[b](e) : h !== b && d[h] ? d[h](e.duration, e.easing, f) : d.queue(function (c) {
                a(this)[b](), f && f.call(d[0]), c()
            })
        }
    }), a.uiBackCompat !== !1 && (a.Widget.prototype._getCreateOptions = function () {
        return a.metadata && a.metadata.get(this.element[0])[this.widgetName]
    })
}(jQuery);
! function (a) {
    var b = !1;
    a(document).mouseup(function () {
        b = !1
    }), a.widget("ui.mouse", {
        version: "1.9.2",
        options: {
            cancel: "input,textarea,button,select,option",
            distance: 1,
            delay: 0
        },
        _mouseInit: function () {
            var b = this;
            this.element.bind("mousedown." + this.widgetName, function (a) {
                return b._mouseDown(a)
            }).bind("click." + this.widgetName, function (c) {
                return !0 === a.data(c.target, b.widgetName + ".preventClickEvent") ? (a.removeData(c.target, b.widgetName + ".preventClickEvent"), c.stopImmediatePropagation(), !1) : void 0
            }), this.started = !1
        },
        _mouseDestroy: function () {
            this.element.unbind("." + this.widgetName), this._mouseMoveDelegate && a(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate)
        },
        _mouseDown: function (c) {
            if (!b) {
                this._mouseStarted && this._mouseUp(c), this._mouseDownEvent = c;
                var d = this,
                    e = 1 === c.which,
                    f = "string" == typeof this.options.cancel && c.target.nodeName ? a(c.target).closest(this.options.cancel).length : !1;
                return e && !f && this._mouseCapture(c) ? (this.mouseDelayMet = !this.options.delay, this.mouseDelayMet || (this._mouseDelayTimer = setTimeout(function () {
                    d.mouseDelayMet = !0
                }, this.options.delay)), this._mouseDistanceMet(c) && this._mouseDelayMet(c) && (this._mouseStarted = this._mouseStart(c) !== !1, !this._mouseStarted) ? (c.preventDefault(), !0) : (!0 === a.data(c.target, this.widgetName + ".preventClickEvent") && a.removeData(c.target, this.widgetName + ".preventClickEvent"), this._mouseMoveDelegate = function (a) {
                    return d._mouseMove(a)
                }, this._mouseUpDelegate = function (a) {
                    return d._mouseUp(a)
                }, a(document).bind("mousemove." + this.widgetName, this._mouseMoveDelegate).bind("mouseup." + this.widgetName, this._mouseUpDelegate), c.preventDefault(), b = !0, !0)) : !0
            }
        },
        _mouseMove: function (b) {
            return !a.ui.ie || document.documentMode >= 9 || b.button ? this._mouseStarted ? (this._mouseDrag(b), b.preventDefault()) : (this._mouseDistanceMet(b) && this._mouseDelayMet(b) && (this._mouseStarted = this._mouseStart(this._mouseDownEvent, b) !== !1, this._mouseStarted ? this._mouseDrag(b) : this._mouseUp(b)), !this._mouseStarted) : this._mouseUp(b)
        },
        _mouseUp: function (b) {
            return a(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate), this._mouseStarted && (this._mouseStarted = !1, b.target === this._mouseDownEvent.target && a.data(b.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(b)), !1
        },
        _mouseDistanceMet: function (a) {
            return Math.max(Math.abs(this._mouseDownEvent.pageX - a.pageX), Math.abs(this._mouseDownEvent.pageY - a.pageY)) >= this.options.distance
        },
        _mouseDelayMet: function () {
            return this.mouseDelayMet
        },
        _mouseStart: function () {},
        _mouseDrag: function () {},
        _mouseStop: function () {},
        _mouseCapture: function () {
            return !0
        }
    })
}(jQuery);
! function (a, b) {
    function c(a, b, c) {
        return [parseInt(a[0], 10) * (m.test(a[0]) ? b / 100 : 1), parseInt(a[1], 10) * (m.test(a[1]) ? c / 100 : 1)]
    }

    function d(b, c) {
        return parseInt(a.css(b, c), 10) || 0
    }
    a.ui = a.ui || {};
    var e, f = Math.max,
        g = Math.abs,
        h = Math.round,
        i = /left|center|right/,
        j = /top|center|bottom/,
        k = /[\+\-]\d+%?/,
        l = /^\w+/,
        m = /%$/,
        n = a.fn.position;
    a.position = {
        scrollbarWidth: function () {
            if (e !== b) return e;
            var c, d, f = a("<div style='display:block;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
                g = f.children()[0];
            return a("body").append(f), c = g.offsetWidth, f.css("overflow", "scroll"), d = g.offsetWidth, c === d && (d = f[0].clientWidth), f.remove(), e = c - d
        },
        getScrollInfo: function (b) {
            var c = b.isWindow ? "" : b.element.css("overflow-x"),
                d = b.isWindow ? "" : b.element.css("overflow-y"),
                e = "scroll" === c || "auto" === c && b.width < b.element[0].scrollWidth,
                f = "scroll" === d || "auto" === d && b.height < b.element[0].scrollHeight;
            return {
                width: e ? a.position.scrollbarWidth() : 0,
                height: f ? a.position.scrollbarWidth() : 0
            }
        },
        getWithinInfo: function (b) {
            var c = a(b || window),
                d = a.isWindow(c[0]);
            return {
                element: c,
                isWindow: d,
                offset: c.offset() || {
                    left: 0,
                    top: 0
                },
                scrollLeft: c.scrollLeft(),
                scrollTop: c.scrollTop(),
                width: d ? c.width() : c.outerWidth(),
                height: d ? c.height() : c.outerHeight()
            }
        }
    }, a.fn.position = function (b) {
        if (!b || !b.of) return n.apply(this, arguments);
        b = a.extend({}, b);
        var e, m, o, p, q, r = a(b.of),
            s = a.position.getWithinInfo(b.within),
            t = a.position.getScrollInfo(s),
            u = r[0],
            v = (b.collision || "flip").split(" "),
            w = {};
        return 9 === u.nodeType ? (m = r.width(), o = r.height(), p = {
            top: 0,
            left: 0
        }) : a.isWindow(u) ? (m = r.width(), o = r.height(), p = {
            top: r.scrollTop(),
            left: r.scrollLeft()
        }) : u.preventDefault ? (b.at = "left top", m = o = 0, p = {
            top: u.pageY,
            left: u.pageX
        }) : (m = r.outerWidth(), o = r.outerHeight(), p = r.offset()), q = a.extend({}, p), a.each(["my", "at"], function () {
            var a, c, d = (b[this] || "").split(" ");
            1 === d.length && (d = i.test(d[0]) ? d.concat(["center"]) : j.test(d[0]) ? ["center"].concat(d) : ["center", "center"]), d[0] = i.test(d[0]) ? d[0] : "center", d[1] = j.test(d[1]) ? d[1] : "center", a = k.exec(d[0]), c = k.exec(d[1]), w[this] = [a ? a[0] : 0, c ? c[0] : 0], b[this] = [l.exec(d[0])[0], l.exec(d[1])[0]]
        }), 1 === v.length && (v[1] = v[0]), "right" === b.at[0] ? q.left += m : "center" === b.at[0] && (q.left += m / 2), "bottom" === b.at[1] ? q.top += o : "center" === b.at[1] && (q.top += o / 2), e = c(w.at, m, o), q.left += e[0], q.top += e[1], this.each(function () {
            var i, j, k = a(this),
                l = k.outerWidth(),
                n = k.outerHeight(),
                u = d(this, "marginLeft"),
                x = d(this, "marginTop"),
                y = l + u + d(this, "marginRight") + t.width,
                z = n + x + d(this, "marginBottom") + t.height,
                A = a.extend({}, q),
                B = c(w.my, k.outerWidth(), k.outerHeight());
            "right" === b.my[0] ? A.left -= l : "center" === b.my[0] && (A.left -= l / 2), "bottom" === b.my[1] ? A.top -= n : "center" === b.my[1] && (A.top -= n / 2), A.left += B[0], A.top += B[1], a.support.offsetFractions || (A.left = h(A.left), A.top = h(A.top)), i = {
                marginLeft: u,
                marginTop: x
            }, a.each(["left", "top"], function (c, d) {
                a.ui.position[v[c]] && a.ui.position[v[c]][d](A, {
                    targetWidth: m,
                    targetHeight: o,
                    elemWidth: l,
                    elemHeight: n,
                    collisionPosition: i,
                    collisionWidth: y,
                    collisionHeight: z,
                    offset: [e[0] + B[0], e[1] + B[1]],
                    my: b.my,
                    at: b.at,
                    within: s,
                    elem: k
                })
            }), a.fn.bgiframe && k.bgiframe(), b.using && (j = function (a) {
                var c = p.left - A.left,
                    d = c + m - l,
                    e = p.top - A.top,
                    h = e + o - n,
                    i = {
                        target: {
                            element: r,
                            left: p.left,
                            top: p.top,
                            width: m,
                            height: o
                        },
                        element: {
                            element: k,
                            left: A.left,
                            top: A.top,
                            width: l,
                            height: n
                        },
                        horizontal: 0 > d ? "left" : c > 0 ? "right" : "center",
                        vertical: 0 > h ? "top" : e > 0 ? "bottom" : "middle"
                    };
                l > m && m > g(c + d) && (i.horizontal = "center"), n > o && o > g(e + h) && (i.vertical = "middle"), i.important = f(g(c), g(d)) > f(g(e), g(h)) ? "horizontal" : "vertical", b.using.call(this, a, i)
            }), k.offset(a.extend(A, {
                using: j
            }))
        })
    }, a.ui.position = {
        fit: {
            left: function (a, b) {
                var c, d = b.within,
                    e = d.isWindow ? d.scrollLeft : d.offset.left,
                    g = d.width,
                    h = a.left - b.collisionPosition.marginLeft,
                    i = e - h,
                    j = h + b.collisionWidth - g - e;
                b.collisionWidth > g ? i > 0 && 0 >= j ? (c = a.left + i + b.collisionWidth - g - e, a.left += i - c) : a.left = j > 0 && 0 >= i ? e : i > j ? e + g - b.collisionWidth : e : i > 0 ? a.left += i : j > 0 ? a.left -= j : a.left = f(a.left - h, a.left)
            },
            top: function (a, b) {
                var c, d = b.within,
                    e = d.isWindow ? d.scrollTop : d.offset.top,
                    g = b.within.height,
                    h = a.top - b.collisionPosition.marginTop,
                    i = e - h,
                    j = h + b.collisionHeight - g - e;
                b.collisionHeight > g ? i > 0 && 0 >= j ? (c = a.top + i + b.collisionHeight - g - e, a.top += i - c) : a.top = j > 0 && 0 >= i ? e : i > j ? e + g - b.collisionHeight : e : i > 0 ? a.top += i : j > 0 ? a.top -= j : a.top = f(a.top - h, a.top)
            }
        },
        flip: {
            left: function (a, b) {
                var c, d, e = b.within,
                    f = e.offset.left + e.scrollLeft,
                    h = e.width,
                    i = e.isWindow ? e.scrollLeft : e.offset.left,
                    j = a.left - b.collisionPosition.marginLeft,
                    k = j - i,
                    l = j + b.collisionWidth - h - i,
                    m = "left" === b.my[0] ? -b.elemWidth : "right" === b.my[0] ? b.elemWidth : 0,
                    n = "left" === b.at[0] ? b.targetWidth : "right" === b.at[0] ? -b.targetWidth : 0,
                    o = -2 * b.offset[0];
                0 > k ? (c = a.left + m + n + o + b.collisionWidth - h - f, (0 > c || g(k) > c) && (a.left += m + n + o)) : l > 0 && (d = a.left - b.collisionPosition.marginLeft + m + n + o - i, (d > 0 || l > g(d)) && (a.left += m + n + o))
            },
            top: function (a, b) {
                var c, d, e = b.within,
                    f = e.offset.top + e.scrollTop,
                    h = e.height,
                    i = e.isWindow ? e.scrollTop : e.offset.top,
                    j = a.top - b.collisionPosition.marginTop,
                    k = j - i,
                    l = j + b.collisionHeight - h - i,
                    m = "top" === b.my[1],
                    n = m ? -b.elemHeight : "bottom" === b.my[1] ? b.elemHeight : 0,
                    o = "top" === b.at[1] ? b.targetHeight : "bottom" === b.at[1] ? -b.targetHeight : 0,
                    p = -2 * b.offset[1];
                0 > k ? (d = a.top + n + o + p + b.collisionHeight - h - f, a.top + n + o + p > k && (0 > d || g(k) > d) && (a.top += n + o + p)) : l > 0 && (c = a.top - b.collisionPosition.marginTop + n + o + p - i, a.top + n + o + p > l && (c > 0 || l > g(c)) && (a.top += n + o + p))
            }
        },
        flipfit: {
            left: function () {
                a.ui.position.flip.left.apply(this, arguments), a.ui.position.fit.left.apply(this, arguments)
            },
            top: function () {
                a.ui.position.flip.top.apply(this, arguments), a.ui.position.fit.top.apply(this, arguments)
            }
        }
    },
    function () {
        var b, c, d, e, f, g = document.getElementsByTagName("body")[0],
            h = document.createElement("div");
        b = document.createElement(g ? "div" : "body"), d = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0,
            background: "none"
        }, g && a.extend(d, {
            position: "absolute",
            left: "-1000px",
            top: "-1000px"
        });
        for (f in d) b.style[f] = d[f];
        b.appendChild(h), c = g || document.documentElement, c.insertBefore(b, c.firstChild), h.style.cssText = "position: absolute; left: 10.7432222px;", e = a(h).offset().left, a.support.offsetFractions = e > 10 && 11 > e, b.innerHTML = "", c.removeChild(b)
    }(), a.uiBackCompat !== !1 && function (a) {
        var c = a.fn.position;
        a.fn.position = function (d) {
            if (!d || !d.offset) return c.call(this, d);
            var e = d.offset.split(" "),
                f = d.at.split(" ");
            return 1 === e.length && (e[1] = e[0]), /^\d/.test(e[0]) && (e[0] = "+" + e[0]), /^\d/.test(e[1]) && (e[1] = "+" + e[1]), 1 === f.length && (/left|center|right/.test(f[0]) ? f[1] = "center" : (f[1] = f[0], f[0] = "center")), c.call(this, a.extend(d, {
                at: f[0] + e[0] + " " + f[1] + e[1],
                offset: b
            }))
        }
    }(jQuery)
}(jQuery);
! function (a) {
    a.widget("ui.draggable", a.ui.mouse, {
        version: "1.9.2",
        widgetEventPrefix: "drag",
        options: {
            addClasses: !0,
            appendTo: "parent",
            axis: !1,
            connectToSortable: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            iframeFix: !1,
            opacity: !1,
            refreshPositions: !1,
            revert: !1,
            revertDuration: 500,
            scope: "default",
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            snap: !1,
            snapMode: "both",
            snapTolerance: 20,
            stack: !1,
            zIndex: !1
        },
        _create: function () {
            "original" != this.options.helper || /^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative"), this.options.addClasses && this.element.addClass("ui-draggable"), this.options.disabled && this.element.addClass("ui-draggable-disabled"), this._mouseInit()
        },
        _destroy: function () {
            this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"), this._mouseDestroy()
        },
        _mouseCapture: function (b) {
            var c = this.options;
            return this.helper || c.disabled || a(b.target).is(".ui-resizable-handle") ? !1 : (this.handle = this._getHandle(b), this.handle ? (a(c.iframeFix === !0 ? "iframe" : c.iframeFix).each(function () {
                a('<div class="ui-draggable-iframeFix" style="background: #fff;"></div>').css({
                    width: this.offsetWidth + "px",
                    height: this.offsetHeight + "px",
                    position: "absolute",
                    opacity: "0.001",
                    zIndex: 1e3
                }).css(a(this).offset()).appendTo("body")
            }), !0) : !1)
        },
        _mouseStart: function (b) {
            var c = this.options;
            return this.helper = this._createHelper(b), this.helper.addClass("ui-draggable-dragging"), this._cacheHelperProportions(), a.ui.ddmanager && (a.ui.ddmanager.current = this), this._cacheMargins(), this.cssPosition = this.helper.css("position"), this.scrollParent = this.helper.scrollParent(), this.offset = this.positionAbs = this.element.offset(), this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            }, a.extend(this.offset, {
                click: {
                    left: b.pageX - this.offset.left,
                    top: b.pageY - this.offset.top
                },
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset()
            }), this.originalPosition = this.position = this._generatePosition(b), this.originalPageX = b.pageX, this.originalPageY = b.pageY, c.cursorAt && this._adjustOffsetFromHelper(c.cursorAt), c.containment && this._setContainment(), this._trigger("start", b) === !1 ? (this._clear(), !1) : (this._cacheHelperProportions(), a.ui.ddmanager && !c.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b), this._mouseDrag(b, !0), a.ui.ddmanager && a.ui.ddmanager.dragStart(this, b), !0)
        },
        _mouseDrag: function (b, c) {
            if (this.position = this._generatePosition(b), this.positionAbs = this._convertPositionTo("absolute"), !c) {
                var d = this._uiHash();
                if (this._trigger("drag", b, d) === !1) return this._mouseUp({}), !1;
                this.position = d.position
            }
            return this.options.axis && "y" == this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" == this.options.axis || (this.helper[0].style.top = this.position.top + "px"), a.ui.ddmanager && a.ui.ddmanager.drag(this, b), !1
        },
        _mouseStop: function (b) {
            var c = !1;
            a.ui.ddmanager && !this.options.dropBehaviour && (c = a.ui.ddmanager.drop(this, b)), this.dropped && (c = this.dropped, this.dropped = !1);
            for (var d = this.element[0], e = !1; d && (d = d.parentNode);) d == document && (e = !0);
            if (!e && "original" === this.options.helper) return !1;
            if ("invalid" == this.options.revert && !c || "valid" == this.options.revert && c || this.options.revert === !0 || a.isFunction(this.options.revert) && this.options.revert.call(this.element, c)) {
                var f = this;
                a(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function () {
                    f._trigger("stop", b) !== !1 && f._clear()
                })
            } else this._trigger("stop", b) !== !1 && this._clear();
            return !1
        },
        _mouseUp: function (b) {
            return a("div.ui-draggable-iframeFix").each(function () {
                this.parentNode.removeChild(this)
            }), a.ui.ddmanager && a.ui.ddmanager.dragStop(this, b), a.ui.mouse.prototype._mouseUp.call(this, b)
        },
        cancel: function () {
            return this.helper.is(".ui-draggable-dragging") ? this._mouseUp({}) : this._clear(), this
        },
        _getHandle: function (b) {
            var c = this.options.handle && a(this.options.handle, this.element).length ? !1 : !0;
            return a(this.options.handle, this.element).find("*").andSelf().each(function () {
                this == b.target && (c = !0)
            }), c
        },
        _createHelper: function (b) {
            var c = this.options,
                d = a.isFunction(c.helper) ? a(c.helper.apply(this.element[0], [b])) : "clone" == c.helper ? this.element.clone().removeAttr("id") : this.element;
            return d.parents("body").length || d.appendTo("parent" == c.appendTo ? this.element[0].parentNode : c.appendTo), d[0] == this.element[0] || /(fixed|absolute)/.test(d.css("position")) || d.css("position", "absolute"), d
        },
        _adjustOffsetFromHelper: function (b) {
            "string" == typeof b && (b = b.split(" ")), a.isArray(b) && (b = {
                left: +b[0],
                top: +b[1] || 0
            }), "left" in b && (this.offset.click.left = b.left + this.margins.left), "right" in b && (this.offset.click.left = this.helperProportions.width - b.right + this.margins.left), "top" in b && (this.offset.click.top = b.top + this.margins.top), "bottom" in b && (this.offset.click.top = this.helperProportions.height - b.bottom + this.margins.top)
        },
        _getParentOffset: function () {
            this.offsetParent = this.helper.offsetParent();
            var b = this.offsetParent.offset();
            return "absolute" == this.cssPosition && this.scrollParent[0] != document && a.contains(this.scrollParent[0], this.offsetParent[0]) && (b.left += this.scrollParent.scrollLeft(), b.top += this.scrollParent.scrollTop()), (this.offsetParent[0] == document.body || this.offsetParent[0].tagName && "html" == this.offsetParent[0].tagName.toLowerCase() && a.ui.ie) && (b = {
                top: 0,
                left: 0
            }), {
                top: b.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: b.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function () {
            if ("relative" == this.cssPosition) {
                var a = this.element.position();
                return {
                    top: a.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: a.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {
                top: 0,
                left: 0
            }
        },
        _cacheMargins: function () {
            this.margins = {
                left: parseInt(this.element.css("marginLeft"), 10) || 0,
                top: parseInt(this.element.css("marginTop"), 10) || 0,
                right: parseInt(this.element.css("marginRight"), 10) || 0,
                bottom: parseInt(this.element.css("marginBottom"), 10) || 0
            }
        },
        _cacheHelperProportions: function () {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            }
        },
        _setContainment: function () {
            var b = this.options;
            if ("parent" == b.containment && (b.containment = this.helper[0].parentNode), ("document" == b.containment || "window" == b.containment) && (this.containment = ["document" == b.containment ? 0 : a(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left, "document" == b.containment ? 0 : a(window).scrollTop() - this.offset.relative.top - this.offset.parent.top, ("document" == b.containment ? 0 : a(window).scrollLeft()) + a("document" == b.containment ? document : window).width() - this.helperProportions.width - this.margins.left, ("document" == b.containment ? 0 : a(window).scrollTop()) + (a("document" == b.containment ? document : window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]), /^(document|window|parent)$/.test(b.containment) || b.containment.constructor == Array) b.containment.constructor == Array && (this.containment = b.containment);
            else {
                var c = a(b.containment),
                    d = c[0];
                if (!d) return;
                c.offset();
                var e = "hidden" != a(d).css("overflow");
                this.containment = [(parseInt(a(d).css("borderLeftWidth"), 10) || 0) + (parseInt(a(d).css("paddingLeft"), 10) || 0), (parseInt(a(d).css("borderTopWidth"), 10) || 0) + (parseInt(a(d).css("paddingTop"), 10) || 0), (e ? Math.max(d.scrollWidth, d.offsetWidth) : d.offsetWidth) - (parseInt(a(d).css("borderLeftWidth"), 10) || 0) - (parseInt(a(d).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left - this.margins.right, (e ? Math.max(d.scrollHeight, d.offsetHeight) : d.offsetHeight) - (parseInt(a(d).css("borderTopWidth"), 10) || 0) - (parseInt(a(d).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top - this.margins.bottom], this.relative_container = c
            }
        },
        _convertPositionTo: function (b, c) {
            c || (c = this.position);
            var d = "absolute" == b ? 1 : -1,
                e = (this.options, "absolute" != this.cssPosition || this.scrollParent[0] != document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent),
                f = /(html|body)/i.test(e[0].tagName);
            return {
                top: c.top + this.offset.relative.top * d + this.offset.parent.top * d - ("fixed" == this.cssPosition ? -this.scrollParent.scrollTop() : f ? 0 : e.scrollTop()) * d,
                left: c.left + this.offset.relative.left * d + this.offset.parent.left * d - ("fixed" == this.cssPosition ? -this.scrollParent.scrollLeft() : f ? 0 : e.scrollLeft()) * d
            }
        },
        _generatePosition: function (b) {
            var c = this.options,
                d = "absolute" != this.cssPosition || this.scrollParent[0] != document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
                e = /(html|body)/i.test(d[0].tagName),
                f = b.pageX,
                g = b.pageY;
            if (this.originalPosition) {
                var h;
                if (this.containment) {
                    if (this.relative_container) {
                        var i = this.relative_container.offset();
                        h = [this.containment[0] + i.left, this.containment[1] + i.top, this.containment[2] + i.left, this.containment[3] + i.top]
                    } else h = this.containment;
                    b.pageX - this.offset.click.left < h[0] && (f = h[0] + this.offset.click.left), b.pageY - this.offset.click.top < h[1] && (g = h[1] + this.offset.click.top), b.pageX - this.offset.click.left > h[2] && (f = h[2] + this.offset.click.left), b.pageY - this.offset.click.top > h[3] && (g = h[3] + this.offset.click.top)
                }
                if (c.grid) {
                    var j = c.grid[1] ? this.originalPageY + Math.round((g - this.originalPageY) / c.grid[1]) * c.grid[1] : this.originalPageY;
                    g = h ? j - this.offset.click.top < h[1] || j - this.offset.click.top > h[3] ? j - this.offset.click.top < h[1] ? j + c.grid[1] : j - c.grid[1] : j : j;
                    var k = c.grid[0] ? this.originalPageX + Math.round((f - this.originalPageX) / c.grid[0]) * c.grid[0] : this.originalPageX;
                    f = h ? k - this.offset.click.left < h[0] || k - this.offset.click.left > h[2] ? k - this.offset.click.left < h[0] ? k + c.grid[0] : k - c.grid[0] : k : k
                }
            }
            return {
                top: g - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" == this.cssPosition ? -this.scrollParent.scrollTop() : e ? 0 : d.scrollTop()),
                left: f - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" == this.cssPosition ? -this.scrollParent.scrollLeft() : e ? 0 : d.scrollLeft())
            }
        },
        _clear: function () {
            this.helper.removeClass("ui-draggable-dragging"), this.helper[0] == this.element[0] || this.cancelHelperRemoval || this.helper.remove(), this.helper = null, this.cancelHelperRemoval = !1
        },
        _trigger: function (b, c, d) {
            return d = d || this._uiHash(), a.ui.plugin.call(this, b, [c, d]), "drag" == b && (this.positionAbs = this._convertPositionTo("absolute")), a.Widget.prototype._trigger.call(this, b, c, d)
        },
        plugins: {},
        _uiHash: function () {
            return {
                helper: this.helper,
                position: this.position,
                originalPosition: this.originalPosition,
                offset: this.positionAbs
            }
        }
    }), a.ui.plugin.add("draggable", "connectToSortable", {
        start: function (b, c) {
            var d = a(this).data("draggable"),
                e = d.options,
                f = a.extend({}, c, {
                    item: d.element
                });
            d.sortables = [], a(e.connectToSortable).each(function () {
                var c = a.data(this, "sortable");
                c && !c.options.disabled && (d.sortables.push({
                    instance: c,
                    shouldRevert: c.options.revert
                }), c.refreshPositions(), c._trigger("activate", b, f))
            })
        },
        stop: function (b, c) {
            var d = a(this).data("draggable"),
                e = a.extend({}, c, {
                    item: d.element
                });
            a.each(d.sortables, function () {
                this.instance.isOver ? (this.instance.isOver = 0, d.cancelHelperRemoval = !0, this.instance.cancelHelperRemoval = !1, this.shouldRevert && (this.instance.options.revert = !0), this.instance._mouseStop(b), this.instance.options.helper = this.instance.options._helper, "original" == d.options.helper && this.instance.currentItem.css({
                    top: "auto",
                    left: "auto"
                })) : (this.instance.cancelHelperRemoval = !1, this.instance._trigger("deactivate", b, e))
            })
        },
        drag: function (b, c) {
            var d = a(this).data("draggable"),
                e = this;
            a.each(d.sortables, function () {
                var f = !1,
                    g = this;
                this.instance.positionAbs = d.positionAbs, this.instance.helperProportions = d.helperProportions, this.instance.offset.click = d.offset.click, this.instance._intersectsWith(this.instance.containerCache) && (f = !0, a.each(d.sortables, function () {
                    return this.instance.positionAbs = d.positionAbs, this.instance.helperProportions = d.helperProportions, this.instance.offset.click = d.offset.click, this != g && this.instance._intersectsWith(this.instance.containerCache) && a.ui.contains(g.instance.element[0], this.instance.element[0]) && (f = !1), f
                })), f ? (this.instance.isOver || (this.instance.isOver = 1, this.instance.currentItem = a(e).clone().removeAttr("id").appendTo(this.instance.element).data("sortable-item", !0), this.instance.options._helper = this.instance.options.helper, this.instance.options.helper = function () {
                    return c.helper[0]
                }, b.target = this.instance.currentItem[0], this.instance._mouseCapture(b, !0), this.instance._mouseStart(b, !0, !0), this.instance.offset.click.top = d.offset.click.top, this.instance.offset.click.left = d.offset.click.left, this.instance.offset.parent.left -= d.offset.parent.left - this.instance.offset.parent.left, this.instance.offset.parent.top -= d.offset.parent.top - this.instance.offset.parent.top, d._trigger("toSortable", b), d.dropped = this.instance.element, d.currentItem = d.element, this.instance.fromOutside = d), this.instance.currentItem && this.instance._mouseDrag(b)) : this.instance.isOver && (this.instance.isOver = 0, this.instance.cancelHelperRemoval = !0, this.instance.options.revert = !1, this.instance._trigger("out", b, this.instance._uiHash(this.instance)), this.instance._mouseStop(b, !0), this.instance.options.helper = this.instance.options._helper, this.instance.currentItem.remove(), this.instance.placeholder && this.instance.placeholder.remove(), d._trigger("fromSortable", b), d.dropped = !1)
            })
        }
    }), a.ui.plugin.add("draggable", "cursor", {
        start: function () {
            var b = a("body"),
                c = a(this).data("draggable").options;
            b.css("cursor") && (c._cursor = b.css("cursor")), b.css("cursor", c.cursor)
        },
        stop: function () {
            var b = a(this).data("draggable").options;
            b._cursor && a("body").css("cursor", b._cursor)
        }
    }), a.ui.plugin.add("draggable", "opacity", {
        start: function (b, c) {
            var d = a(c.helper),
                e = a(this).data("draggable").options;
            d.css("opacity") && (e._opacity = d.css("opacity")), d.css("opacity", e.opacity)
        },
        stop: function (b, c) {
            var d = a(this).data("draggable").options;
            d._opacity && a(c.helper).css("opacity", d._opacity)
        }
    }), a.ui.plugin.add("draggable", "scroll", {
        start: function () {
            var b = a(this).data("draggable");
            b.scrollParent[0] != document && "HTML" != b.scrollParent[0].tagName && (b.overflowOffset = b.scrollParent.offset())
        },
        drag: function (b) {
            var c = a(this).data("draggable"),
                d = c.options,
                e = !1;
            c.scrollParent[0] != document && "HTML" != c.scrollParent[0].tagName ? (d.axis && "x" == d.axis || (c.overflowOffset.top + c.scrollParent[0].offsetHeight - b.pageY < d.scrollSensitivity ? c.scrollParent[0].scrollTop = e = c.scrollParent[0].scrollTop + d.scrollSpeed : b.pageY - c.overflowOffset.top < d.scrollSensitivity && (c.scrollParent[0].scrollTop = e = c.scrollParent[0].scrollTop - d.scrollSpeed)), d.axis && "y" == d.axis || (c.overflowOffset.left + c.scrollParent[0].offsetWidth - b.pageX < d.scrollSensitivity ? c.scrollParent[0].scrollLeft = e = c.scrollParent[0].scrollLeft + d.scrollSpeed : b.pageX - c.overflowOffset.left < d.scrollSensitivity && (c.scrollParent[0].scrollLeft = e = c.scrollParent[0].scrollLeft - d.scrollSpeed))) : (d.axis && "x" == d.axis || (b.pageY - a(document).scrollTop() < d.scrollSensitivity ? e = a(document).scrollTop(a(document).scrollTop() - d.scrollSpeed) : a(window).height() - (b.pageY - a(document).scrollTop()) < d.scrollSensitivity && (e = a(document).scrollTop(a(document).scrollTop() + d.scrollSpeed))), d.axis && "y" == d.axis || (b.pageX - a(document).scrollLeft() < d.scrollSensitivity ? e = a(document).scrollLeft(a(document).scrollLeft() - d.scrollSpeed) : a(window).width() - (b.pageX - a(document).scrollLeft()) < d.scrollSensitivity && (e = a(document).scrollLeft(a(document).scrollLeft() + d.scrollSpeed)))), e !== !1 && a.ui.ddmanager && !d.dropBehaviour && a.ui.ddmanager.prepareOffsets(c, b)
        }
    }), a.ui.plugin.add("draggable", "snap", {
        start: function () {
            var b = a(this).data("draggable"),
                c = b.options;
            b.snapElements = [], a(c.snap.constructor != String ? c.snap.items || ":data(draggable)" : c.snap).each(function () {
                var c = a(this),
                    d = c.offset();
                this != b.element[0] && b.snapElements.push({
                    item: this,
                    width: c.outerWidth(),
                    height: c.outerHeight(),
                    top: d.top,
                    left: d.left
                })
            })
        },
        drag: function (b, c) {
            for (var d = a(this).data("draggable"), e = d.options, f = e.snapTolerance, g = c.offset.left, h = g + d.helperProportions.width, i = c.offset.top, j = i + d.helperProportions.height, k = d.snapElements.length - 1; k >= 0; k--) {
                var l = d.snapElements[k].left,
                    m = l + d.snapElements[k].width,
                    n = d.snapElements[k].top,
                    o = n + d.snapElements[k].height;
                if (g > l - f && m + f > g && i > n - f && o + f > i || g > l - f && m + f > g && j > n - f && o + f > j || h > l - f && m + f > h && i > n - f && o + f > i || h > l - f && m + f > h && j > n - f && o + f > j) {
                    if ("inner" != e.snapMode) {
                        var p = f >= Math.abs(n - j),
                            q = f >= Math.abs(o - i),
                            r = f >= Math.abs(l - h),
                            s = f >= Math.abs(m - g);
                        p && (c.position.top = d._convertPositionTo("relative", {
                            top: n - d.helperProportions.height,
                            left: 0
                        }).top - d.margins.top), q && (c.position.top = d._convertPositionTo("relative", {
                            top: o,
                            left: 0
                        }).top - d.margins.top), r && (c.position.left = d._convertPositionTo("relative", {
                            top: 0,
                            left: l - d.helperProportions.width
                        }).left - d.margins.left), s && (c.position.left = d._convertPositionTo("relative", {
                            top: 0,
                            left: m
                        }).left - d.margins.left)
                    }
                    var t = p || q || r || s;
                    if ("outer" != e.snapMode) {
                        var p = f >= Math.abs(n - i),
                            q = f >= Math.abs(o - j),
                            r = f >= Math.abs(l - g),
                            s = f >= Math.abs(m - h);
                        p && (c.position.top = d._convertPositionTo("relative", {
                            top: n,
                            left: 0
                        }).top - d.margins.top), q && (c.position.top = d._convertPositionTo("relative", {
                            top: o - d.helperProportions.height,
                            left: 0
                        }).top - d.margins.top), r && (c.position.left = d._convertPositionTo("relative", {
                            top: 0,
                            left: l
                        }).left - d.margins.left), s && (c.position.left = d._convertPositionTo("relative", {
                            top: 0,
                            left: m - d.helperProportions.width
                        }).left - d.margins.left)
                    }!d.snapElements[k].snapping && (p || q || r || s || t) && d.options.snap.snap && d.options.snap.snap.call(d.element, b, a.extend(d._uiHash(), {
                        snapItem: d.snapElements[k].item
                    })), d.snapElements[k].snapping = p || q || r || s || t
                } else d.snapElements[k].snapping && d.options.snap.release && d.options.snap.release.call(d.element, b, a.extend(d._uiHash(), {
                    snapItem: d.snapElements[k].item
                })), d.snapElements[k].snapping = !1
            }
        }
    }), a.ui.plugin.add("draggable", "stack", {
        start: function () {
            var b = a(this).data("draggable").options,
                c = a.makeArray(a(b.stack)).sort(function (b, c) {
                    return (parseInt(a(b).css("zIndex"), 10) || 0) - (parseInt(a(c).css("zIndex"), 10) || 0)
                });
            if (c.length) {
                var d = parseInt(c[0].style.zIndex) || 0;
                a(c).each(function (a) {
                    this.style.zIndex = d + a
                }), this[0].style.zIndex = d + c.length
            }
        }
    }), a.ui.plugin.add("draggable", "zIndex", {
        start: function (b, c) {
            var d = a(c.helper),
                e = a(this).data("draggable").options;
            d.css("zIndex") && (e._zIndex = d.css("zIndex")), d.css("zIndex", e.zIndex)
        },
        stop: function (b, c) {
            var d = a(this).data("draggable").options;
            d._zIndex && a(c.helper).css("zIndex", d._zIndex)
        }
    })
}(jQuery);
! function (a) {
    a.widget("ui.droppable", {
        version: "1.9.2",
        widgetEventPrefix: "drop",
        options: {
            accept: "*",
            activeClass: !1,
            addClasses: !0,
            greedy: !1,
            hoverClass: !1,
            scope: "default",
            tolerance: "intersect"
        },
        _create: function () {
            var b = this.options,
                c = b.accept;
            this.isover = 0, this.isout = 1, this.accept = a.isFunction(c) ? c : function (a) {
                return a.is(c)
            }, this.proportions = {
                width: this.element[0].offsetWidth,
                height: this.element[0].offsetHeight
            }, a.ui.ddmanager.droppables[b.scope] = a.ui.ddmanager.droppables[b.scope] || [], a.ui.ddmanager.droppables[b.scope].push(this), b.addClasses && this.element.addClass("ui-droppable")
        },
        _destroy: function () {
            for (var b = a.ui.ddmanager.droppables[this.options.scope], c = 0; b.length > c; c++) b[c] == this && b.splice(c, 1);
            this.element.removeClass("ui-droppable ui-droppable-disabled")
        },
        _setOption: function (b, c) {
            "accept" == b && (this.accept = a.isFunction(c) ? c : function (a) {
                return a.is(c)
            }), a.Widget.prototype._setOption.apply(this, arguments)
        },
        _activate: function (b) {
            var c = a.ui.ddmanager.current;
            this.options.activeClass && this.element.addClass(this.options.activeClass), c && this._trigger("activate", b, this.ui(c))
        },
        _deactivate: function (b) {
            var c = a.ui.ddmanager.current;
            this.options.activeClass && this.element.removeClass(this.options.activeClass), c && this._trigger("deactivate", b, this.ui(c))
        },
        _over: function (b) {
            var c = a.ui.ddmanager.current;
            c && (c.currentItem || c.element)[0] != this.element[0] && this.accept.call(this.element[0], c.currentItem || c.element) && (this.options.hoverClass && this.element.addClass(this.options.hoverClass), this._trigger("over", b, this.ui(c)))
        },
        _out: function (b) {
            var c = a.ui.ddmanager.current;
            c && (c.currentItem || c.element)[0] != this.element[0] && this.accept.call(this.element[0], c.currentItem || c.element) && (this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("out", b, this.ui(c)))
        },
        _drop: function (b, c) {
            var d = c || a.ui.ddmanager.current;
            if (!d || (d.currentItem || d.element)[0] == this.element[0]) return !1;
            var e = !1;
            return this.element.find(":data(droppable)").not(".ui-draggable-dragging").each(function () {
                var b = a.data(this, "droppable");
                return b.options.greedy && !b.options.disabled && b.options.scope == d.options.scope && b.accept.call(b.element[0], d.currentItem || d.element) && a.ui.intersect(d, a.extend(b, {
                    offset: b.element.offset()
                }), b.options.tolerance) ? (e = !0, !1) : void 0
            }), e ? !1 : this.accept.call(this.element[0], d.currentItem || d.element) ? (this.options.activeClass && this.element.removeClass(this.options.activeClass), this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("drop", b, this.ui(d)), this.element) : !1
        },
        ui: function (a) {
            return {
                draggable: a.currentItem || a.element,
                helper: a.helper,
                position: a.position,
                offset: a.positionAbs
            }
        }
    }), a.ui.intersect = function (b, c, d) {
        if (!c.offset) return !1;
        var e = (b.positionAbs || b.position.absolute).left,
            f = e + b.helperProportions.width,
            g = (b.positionAbs || b.position.absolute).top,
            h = g + b.helperProportions.height,
            i = c.offset.left,
            j = i + c.proportions.width,
            k = c.offset.top,
            l = k + c.proportions.height;
        switch (d) {
        case "fit":
            return e >= i && j >= f && g >= k && l >= h;
        case "intersect":
            return e + b.helperProportions.width / 2 > i && j > f - b.helperProportions.width / 2 && g + b.helperProportions.height / 2 > k && l > h - b.helperProportions.height / 2;
        case "pointer":
            var m = (b.positionAbs || b.position.absolute).left + (b.clickOffset || b.offset.click).left,
                n = (b.positionAbs || b.position.absolute).top + (b.clickOffset || b.offset.click).top,
                o = a.ui.isOver(n, m, k, i, c.proportions.height, c.proportions.width);
            return o;
        case "touch":
            return (g >= k && l >= g || h >= k && l >= h || k > g && h > l) && (e >= i && j >= e || f >= i && j >= f || i > e && f > j);
        default:
            return !1
        }
    }, a.ui.ddmanager = {
        current: null,
        droppables: {
            "default": []
        },
        prepareOffsets: function (b, c) {
            var d = a.ui.ddmanager.droppables[b.options.scope] || [],
                e = c ? c.type : null,
                f = (b.currentItem || b.element).find(":data(droppable)").andSelf();
            a: for (var g = 0; d.length > g; g++)
                if (!(d[g].options.disabled || b && !d[g].accept.call(d[g].element[0], b.currentItem || b.element))) {
                    for (var h = 0; f.length > h; h++)
                        if (f[h] == d[g].element[0]) {
                            d[g].proportions.height = 0;
                            continue a
                        }
                    d[g].visible = "none" != d[g].element.css("display"), d[g].visible && ("mousedown" == e && d[g]._activate.call(d[g], c), d[g].offset = d[g].element.offset(), d[g].proportions = {
                        width: d[g].element[0].offsetWidth,
                        height: d[g].element[0].offsetHeight
                    })
                }
        },
        drop: function (b, c) {
            var d = !1;
            return a.each(a.ui.ddmanager.droppables[b.options.scope] || [], function () {
                this.options && (!this.options.disabled && this.visible && a.ui.intersect(b, this, this.options.tolerance) && (d = this._drop.call(this, c) || d), !this.options.disabled && this.visible && this.accept.call(this.element[0], b.currentItem || b.element) && (this.isout = 1, this.isover = 0, this._deactivate.call(this, c)))
            }), d
        },
        dragStart: function (b, c) {
            b.element.parentsUntil("body").bind("scroll.droppable", function () {
                b.options.refreshPositions || a.ui.ddmanager.prepareOffsets(b, c)
            })
        },
        drag: function (b, c) {
            b.options.refreshPositions && a.ui.ddmanager.prepareOffsets(b, c), a.each(a.ui.ddmanager.droppables[b.options.scope] || [], function () {
                if (!this.options.disabled && !this.greedyChild && this.visible) {
                    var d = a.ui.intersect(b, this, this.options.tolerance),
                        e = d || 1 != this.isover ? d && 0 == this.isover ? "isover" : null : "isout";
                    if (e) {
                        var f;
                        if (this.options.greedy) {
                            var g = this.options.scope,
                                h = this.element.parents(":data(droppable)").filter(function () {
                                    return a.data(this, "droppable").options.scope === g
                                });
                            h.length && (f = a.data(h[0], "droppable"), f.greedyChild = "isover" == e ? 1 : 0)
                        }
                        f && "isover" == e && (f.isover = 0, f.isout = 1, f._out.call(f, c)), this[e] = 1, this["isout" == e ? "isover" : "isout"] = 0, this["isover" == e ? "_over" : "_out"].call(this, c), f && "isout" == e && (f.isout = 0, f.isover = 1, f._over.call(f, c))
                    }
                }
            })
        },
        dragStop: function (b, c) {
            b.element.parentsUntil("body").unbind("scroll.droppable"), b.options.refreshPositions || a.ui.ddmanager.prepareOffsets(b, c)
        }
    }
}(jQuery);
! function (a) {
    a.widget("ui.resizable", a.ui.mouse, {
        version: "1.9.2",
        widgetEventPrefix: "resize",
        options: {
            alsoResize: !1,
            animate: !1,
            animateDuration: "slow",
            animateEasing: "swing",
            aspectRatio: !1,
            autoHide: !1,
            containment: !1,
            ghost: !1,
            grid: !1,
            handles: "e,s,se",
            helper: !1,
            maxHeight: null,
            maxWidth: null,
            minHeight: 10,
            minWidth: 10,
            zIndex: 1e3
        },
        _create: function () {
            var b = this,
                c = this.options;
            if (this.element.addClass("ui-resizable"), a.extend(this, {
                _aspectRatio: !! c.aspectRatio,
                aspectRatio: c.aspectRatio,
                originalElement: this.element,
                _proportionallyResizeElements: [],
                _helper: c.helper || c.ghost || c.animate ? c.helper || "ui-resizable-helper" : null
            }), this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i) && (this.element.wrap(a('<div class="ui-wrapper" style="overflow: hidden;"></div>').css({
                position: this.element.css("position"),
                width: this.element.outerWidth(),
                height: this.element.outerHeight(),
                top: this.element.css("top"),
                left: this.element.css("left")
            })), this.element = this.element.parent().data("resizable", this.element.data("resizable")), this.elementIsWrapper = !0, this.element.css({
                marginLeft: this.originalElement.css("marginLeft"),
                marginTop: this.originalElement.css("marginTop"),
                marginRight: this.originalElement.css("marginRight"),
                marginBottom: this.originalElement.css("marginBottom")
            }), this.originalElement.css({
                marginLeft: 0,
                marginTop: 0,
                marginRight: 0,
                marginBottom: 0
            }), this.originalResizeStyle = this.originalElement.css("resize"), this.originalElement.css("resize", "none"), this._proportionallyResizeElements.push(this.originalElement.css({
                position: "static",
                zoom: 1,
                display: "block"
            })), this.originalElement.css({
                margin: this.originalElement.css("margin")
            }), this._proportionallyResize()), this.handles = c.handles || (a(".ui-resizable-handle", this.element).length ? {
                n: ".ui-resizable-n",
                e: ".ui-resizable-e",
                s: ".ui-resizable-s",
                w: ".ui-resizable-w",
                se: ".ui-resizable-se",
                sw: ".ui-resizable-sw",
                ne: ".ui-resizable-ne",
                nw: ".ui-resizable-nw"
            } : "e,s,se"), this.handles.constructor == String) {
                "all" == this.handles && (this.handles = "n,e,s,w,se,sw,ne,nw");
                var d = this.handles.split(",");
                this.handles = {};
                for (var e = 0; d.length > e; e++) {
                    var f = a.trim(d[e]),
                        g = "ui-resizable-" + f,
                        h = a('<div class="ui-resizable-handle ' + g + '"></div>');
                    h.css({
                        zIndex: c.zIndex
                    }), "se" == f && h.addClass("ui-icon ui-icon-gripsmall-diagonal-se"), this.handles[f] = ".ui-resizable-" + f, this.element.append(h)
                }
            }
            this._renderAxis = function (b) {
                b = b || this.element;
                for (var c in this.handles) {
                    if (this.handles[c].constructor == String && (this.handles[c] = a(this.handles[c], this.element).show()), this.elementIsWrapper && this.originalElement[0].nodeName.match(/textarea|input|select|button/i)) {
                        var d = a(this.handles[c], this.element),
                            e = 0;
                        e = /sw|ne|nw|se|n|s/.test(c) ? d.outerHeight() : d.outerWidth();
                        var f = ["padding", /ne|nw|n/.test(c) ? "Top" : /se|sw|s/.test(c) ? "Bottom" : /^e$/.test(c) ? "Right" : "Left"].join("");
                        b.css(f, e), this._proportionallyResize()
                    }
                    a(this.handles[c]).length
                }
            }, this._renderAxis(this.element), this._handles = a(".ui-resizable-handle", this.element).disableSelection(), this._handles.mouseover(function () {
                if (!b.resizing) {
                    if (this.className) var a = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i);
                    b.axis = a && a[1] ? a[1] : "se"
                }
            }), c.autoHide && (this._handles.hide(), a(this.element).addClass("ui-resizable-autohide").mouseenter(function () {
                c.disabled || (a(this).removeClass("ui-resizable-autohide"), b._handles.show())
            }).mouseleave(function () {
                c.disabled || b.resizing || (a(this).addClass("ui-resizable-autohide"), b._handles.hide())
            })), this._mouseInit()
        },
        _destroy: function () {
            this._mouseDestroy();
            var b = function (b) {
                a(b).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()
            };
            if (this.elementIsWrapper) {
                b(this.element);
                var c = this.element;
                this.originalElement.css({
                    position: c.css("position"),
                    width: c.outerWidth(),
                    height: c.outerHeight(),
                    top: c.css("top"),
                    left: c.css("left")
                }).insertAfter(c), c.remove()
            }
            return this.originalElement.css("resize", this.originalResizeStyle), b(this.originalElement), this
        },
        _mouseCapture: function (b) {
            var c = !1;
            for (var d in this.handles) a(this.handles[d])[0] == b.target && (c = !0);
            return !this.options.disabled && c
        },
        _mouseStart: function (c) {
            var d = this.options,
                e = this.element.position(),
                f = this.element;
            this.resizing = !0, this.documentScroll = {
                top: a(document).scrollTop(),
                left: a(document).scrollLeft()
            }, (f.is(".ui-draggable") || /absolute/.test(f.css("position"))) && f.css({
                position: "absolute",
                top: e.top,
                left: e.left
            }), this._renderProxy();
            var g = b(this.helper.css("left")),
                h = b(this.helper.css("top"));
            d.containment && (g += a(d.containment).scrollLeft() || 0, h += a(d.containment).scrollTop() || 0), this.offset = this.helper.offset(), this.position = {
                left: g,
                top: h
            }, this.size = this._helper ? {
                width: f.outerWidth(),
                height: f.outerHeight()
            } : {
                width: f.width(),
                height: f.height()
            }, this.originalSize = this._helper ? {
                width: f.outerWidth(),
                height: f.outerHeight()
            } : {
                width: f.width(),
                height: f.height()
            }, this.originalPosition = {
                left: g,
                top: h
            }, this.sizeDiff = {
                width: f.outerWidth() - f.width(),
                height: f.outerHeight() - f.height()
            }, this.originalMousePosition = {
                left: c.pageX,
                top: c.pageY
            }, this.aspectRatio = "number" == typeof d.aspectRatio ? d.aspectRatio : this.originalSize.width / this.originalSize.height || 1;
            var i = a(".ui-resizable-" + this.axis).css("cursor");
            return a("body").css("cursor", "auto" == i ? this.axis + "-resize" : i), f.addClass("ui-resizable-resizing"), this._propagate("start", c), !0
        },
        _mouseDrag: function (a) {
            var b = this.helper,
                c = (this.options, this.originalMousePosition),
                d = this.axis,
                e = a.pageX - c.left || 0,
                f = a.pageY - c.top || 0,
                g = this._change[d];
            if (!g) return !1;
            var h = g.apply(this, [a, e, f]);
            return this._updateVirtualBoundaries(a.shiftKey), (this._aspectRatio || a.shiftKey) && (h = this._updateRatio(h, a)), h = this._respectSize(h, a), this._propagate("resize", a), b.css({
                top: this.position.top + "px",
                left: this.position.left + "px",
                width: this.size.width + "px",
                height: this.size.height + "px"
            }), !this._helper && this._proportionallyResizeElements.length && this._proportionallyResize(), this._updateCache(h), this._trigger("resize", a, this.ui()), !1
        },
        _mouseStop: function (b) {
            this.resizing = !1;
            var c = this.options,
                d = this;
            if (this._helper) {
                var e = this._proportionallyResizeElements,
                    f = e.length && /textarea/i.test(e[0].nodeName),
                    g = f && a.ui.hasScroll(e[0], "left") ? 0 : d.sizeDiff.height,
                    h = f ? 0 : d.sizeDiff.width,
                    i = {
                        width: d.helper.width() - h,
                        height: d.helper.height() - g
                    }, j = parseInt(d.element.css("left"), 10) + (d.position.left - d.originalPosition.left) || null,
                    k = parseInt(d.element.css("top"), 10) + (d.position.top - d.originalPosition.top) || null;
                c.animate || this.element.css(a.extend(i, {
                    top: k,
                    left: j
                })), d.helper.height(d.size.height), d.helper.width(d.size.width), this._helper && !c.animate && this._proportionallyResize()
            }
            return a("body").css("cursor", "auto"), this.element.removeClass("ui-resizable-resizing"), this._propagate("stop", b), this._helper && this.helper.remove(), !1
        },
        _updateVirtualBoundaries: function (a) {
            var b, d, e, f, g, h = this.options;
            g = {
                minWidth: c(h.minWidth) ? h.minWidth : 0,
                maxWidth: c(h.maxWidth) ? h.maxWidth : 1 / 0,
                minHeight: c(h.minHeight) ? h.minHeight : 0,
                maxHeight: c(h.maxHeight) ? h.maxHeight : 1 / 0
            }, (this._aspectRatio || a) && (b = g.minHeight * this.aspectRatio, e = g.minWidth / this.aspectRatio, d = g.maxHeight * this.aspectRatio, f = g.maxWidth / this.aspectRatio, b > g.minWidth && (g.minWidth = b), e > g.minHeight && (g.minHeight = e), g.maxWidth > d && (g.maxWidth = d), g.maxHeight > f && (g.maxHeight = f)), this._vBoundaries = g
        },
        _updateCache: function (a) {
            this.options, this.offset = this.helper.offset(), c(a.left) && (this.position.left = a.left), c(a.top) && (this.position.top = a.top), c(a.height) && (this.size.height = a.height), c(a.width) && (this.size.width = a.width)
        },
        _updateRatio: function (a) {
            var b = (this.options, this.position),
                d = this.size,
                e = this.axis;
            return c(a.height) ? a.width = a.height * this.aspectRatio : c(a.width) && (a.height = a.width / this.aspectRatio), "sw" == e && (a.left = b.left + (d.width - a.width), a.top = null), "nw" == e && (a.top = b.top + (d.height - a.height), a.left = b.left + (d.width - a.width)), a
        },
        _respectSize: function (a, b) {
            var d = (this.helper, this._vBoundaries),
                e = (this._aspectRatio || b.shiftKey, this.axis),
                f = c(a.width) && d.maxWidth && d.maxWidth < a.width,
                g = c(a.height) && d.maxHeight && d.maxHeight < a.height,
                h = c(a.width) && d.minWidth && d.minWidth > a.width,
                i = c(a.height) && d.minHeight && d.minHeight > a.height;
            h && (a.width = d.minWidth), i && (a.height = d.minHeight), f && (a.width = d.maxWidth), g && (a.height = d.maxHeight);
            var j = this.originalPosition.left + this.originalSize.width,
                k = this.position.top + this.size.height,
                l = /sw|nw|w/.test(e),
                m = /nw|ne|n/.test(e);
            h && l && (a.left = j - d.minWidth), f && l && (a.left = j - d.maxWidth), i && m && (a.top = k - d.minHeight), g && m && (a.top = k - d.maxHeight);
            var n = !a.width && !a.height;
            return n && !a.left && a.top ? a.top = null : n && !a.top && a.left && (a.left = null), a
        },
        _proportionallyResize: function () {
            if (this.options, this._proportionallyResizeElements.length)
                for (var b = this.helper || this.element, c = 0; this._proportionallyResizeElements.length > c; c++) {
                    var d = this._proportionallyResizeElements[c];
                    if (!this.borderDif) {
                        var e = [d.css("borderTopWidth"), d.css("borderRightWidth"), d.css("borderBottomWidth"), d.css("borderLeftWidth")],
                            f = [d.css("paddingTop"), d.css("paddingRight"), d.css("paddingBottom"), d.css("paddingLeft")];
                        this.borderDif = a.map(e, function (a, b) {
                            var c = parseInt(a, 10) || 0,
                                d = parseInt(f[b], 10) || 0;
                            return c + d
                        })
                    }
                    d.css({
                        height: b.height() - this.borderDif[0] - this.borderDif[2] || 0,
                        width: b.width() - this.borderDif[1] - this.borderDif[3] || 0
                    })
                }
        },
        _renderProxy: function () {
            var b = this.element,
                c = this.options;
            if (this.elementOffset = b.offset(), this._helper) {
                this.helper = this.helper || a('<div style="overflow:hidden;"></div>');
                var d = a.ui.ie6 ? 1 : 0,
                    e = a.ui.ie6 ? 2 : -1;
                this.helper.addClass(this._helper).css({
                    width: this.element.outerWidth() + e,
                    height: this.element.outerHeight() + e,
                    position: "absolute",
                    left: this.elementOffset.left - d + "px",
                    top: this.elementOffset.top - d + "px",
                    zIndex: ++c.zIndex
                }), this.helper.appendTo("body").disableSelection()
            } else this.helper = this.element
        },
        _change: {
            e: function (a, b) {
                return {
                    width: this.originalSize.width + b
                }
            },
            w: function (a, b) {
                var c = (this.options, this.originalSize),
                    d = this.originalPosition;
                return {
                    left: d.left + b,
                    width: c.width - b
                }
            },
            n: function (a, b, c) {
                var d = (this.options, this.originalSize),
                    e = this.originalPosition;
                return {
                    top: e.top + c,
                    height: d.height - c
                }
            },
            s: function (a, b, c) {
                return {
                    height: this.originalSize.height + c
                }
            },
            se: function (b, c, d) {
                return a.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [b, c, d]))
            },
            sw: function (b, c, d) {
                return a.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [b, c, d]))
            },
            ne: function (b, c, d) {
                return a.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [b, c, d]))
            },
            nw: function (b, c, d) {
                return a.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [b, c, d]))
            }
        },
        _propagate: function (b, c) {
            a.ui.plugin.call(this, b, [c, this.ui()]), "resize" != b && this._trigger(b, c, this.ui())
        },
        plugins: {},
        ui: function () {
            return {
                originalElement: this.originalElement,
                element: this.element,
                helper: this.helper,
                position: this.position,
                size: this.size,
                originalSize: this.originalSize,
                originalPosition: this.originalPosition
            }
        }
    }), a.ui.plugin.add("resizable", "alsoResize", {
        start: function () {
            var b = a(this).data("resizable"),
                c = b.options,
                d = function (b) {
                    a(b).each(function () {
                        var b = a(this);
                        b.data("resizable-alsoresize", {
                            width: parseInt(b.width(), 10),
                            height: parseInt(b.height(), 10),
                            left: parseInt(b.css("left"), 10),
                            top: parseInt(b.css("top"), 10)
                        })
                    })
                };
            "object" != typeof c.alsoResize || c.alsoResize.parentNode ? d(c.alsoResize) : c.alsoResize.length ? (c.alsoResize = c.alsoResize[0], d(c.alsoResize)) : a.each(c.alsoResize, function (a) {
                d(a)
            })
        },
        resize: function (b, c) {
            var d = a(this).data("resizable"),
                e = d.options,
                f = d.originalSize,
                g = d.originalPosition,
                h = {
                    height: d.size.height - f.height || 0,
                    width: d.size.width - f.width || 0,
                    top: d.position.top - g.top || 0,
                    left: d.position.left - g.left || 0
                }, i = function (b, d) {
                    a(b).each(function () {
                        var b = a(this),
                            e = a(this).data("resizable-alsoresize"),
                            f = {}, g = d && d.length ? d : b.parents(c.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];
                        a.each(g, function (a, b) {
                            var c = (e[b] || 0) + (h[b] || 0);
                            c && c >= 0 && (f[b] = c || null)
                        }), b.css(f)
                    })
                };
            "object" != typeof e.alsoResize || e.alsoResize.nodeType ? i(e.alsoResize) : a.each(e.alsoResize, function (a, b) {
                i(a, b)
            })
        },
        stop: function () {
            a(this).removeData("resizable-alsoresize")
        }
    }), a.ui.plugin.add("resizable", "animate", {
        stop: function (b) {
            var c = a(this).data("resizable"),
                d = c.options,
                e = c._proportionallyResizeElements,
                f = e.length && /textarea/i.test(e[0].nodeName),
                g = f && a.ui.hasScroll(e[0], "left") ? 0 : c.sizeDiff.height,
                h = f ? 0 : c.sizeDiff.width,
                i = {
                    width: c.size.width - h,
                    height: c.size.height - g
                }, j = parseInt(c.element.css("left"), 10) + (c.position.left - c.originalPosition.left) || null,
                k = parseInt(c.element.css("top"), 10) + (c.position.top - c.originalPosition.top) || null;
            c.element.animate(a.extend(i, k && j ? {
                top: k,
                left: j
            } : {}), {
                duration: d.animateDuration,
                easing: d.animateEasing,
                step: function () {
                    var d = {
                        width: parseInt(c.element.css("width"), 10),
                        height: parseInt(c.element.css("height"), 10),
                        top: parseInt(c.element.css("top"), 10),
                        left: parseInt(c.element.css("left"), 10)
                    };
                    e && e.length && a(e[0]).css({
                        width: d.width,
                        height: d.height
                    }), c._updateCache(d), c._propagate("resize", b)
                }
            })
        }
    }), a.ui.plugin.add("resizable", "containment", {
        start: function () {
            var c = a(this).data("resizable"),
                d = c.options,
                e = c.element,
                f = d.containment,
                g = f instanceof a ? f.get(0) : /parent/.test(f) ? e.parent().get(0) : f;
            if (g)
                if (c.containerElement = a(g), /document/.test(f) || f == document) c.containerOffset = {
                    left: 0,
                    top: 0
                }, c.containerPosition = {
                    left: 0,
                    top: 0
                }, c.parentData = {
                    element: a(document),
                    left: 0,
                    top: 0,
                    width: a(document).width(),
                    height: a(document).height() || document.body.parentNode.scrollHeight
                };
                else {
                    var h = a(g),
                        i = [];
                    a(["Top", "Right", "Left", "Bottom"]).each(function (a, c) {
                        i[a] = b(h.css("padding" + c))
                    }), c.containerOffset = h.offset(), c.containerPosition = h.position(), c.containerSize = {
                        height: h.innerHeight() - i[3],
                        width: h.innerWidth() - i[1]
                    };
                    var j = c.containerOffset,
                        k = c.containerSize.height,
                        l = c.containerSize.width,
                        m = a.ui.hasScroll(g, "left") ? g.scrollWidth : l,
                        n = a.ui.hasScroll(g) ? g.scrollHeight : k;
                    c.parentData = {
                        element: g,
                        left: j.left,
                        top: j.top,
                        width: m,
                        height: n
                    }
                }
        },
        resize: function (b) {
            var c = a(this).data("resizable"),
                d = c.options,
                e = (c.containerSize, c.containerOffset),
                f = (c.size, c.position),
                g = c._aspectRatio || b.shiftKey,
                h = {
                    top: 0,
                    left: 0
                }, i = c.containerElement;
            i[0] != document && /static/.test(i.css("position")) && (h = e), f.left < (c._helper ? e.left : 0) && (c.size.width = c.size.width + (c._helper ? c.position.left - e.left : c.position.left - h.left), g && (c.size.height = c.size.width / c.aspectRatio), c.position.left = d.helper ? e.left : 0), f.top < (c._helper ? e.top : 0) && (c.size.height = c.size.height + (c._helper ? c.position.top - e.top : c.position.top), g && (c.size.width = c.size.height * c.aspectRatio), c.position.top = c._helper ? e.top : 0), c.offset.left = c.parentData.left + c.position.left, c.offset.top = c.parentData.top + c.position.top;
            var j = Math.abs((c._helper ? c.offset.left - h.left : c.offset.left - h.left) + c.sizeDiff.width),
                k = Math.abs((c._helper ? c.offset.top - h.top : c.offset.top - e.top) + c.sizeDiff.height),
                l = c.containerElement.get(0) == c.element.parent().get(0),
                m = /relative|absolute/.test(c.containerElement.css("position"));
            l && m && (j -= c.parentData.left), j + c.size.width >= c.parentData.width && (c.size.width = c.parentData.width - j, g && (c.size.height = c.size.width / c.aspectRatio)), k + c.size.height >= c.parentData.height && (c.size.height = c.parentData.height - k, g && (c.size.width = c.size.height * c.aspectRatio))
        },
        stop: function () {
            var b = a(this).data("resizable"),
                c = b.options,
                d = (b.position, b.containerOffset),
                e = b.containerPosition,
                f = b.containerElement,
                g = a(b.helper),
                h = g.offset(),
                i = g.outerWidth() - b.sizeDiff.width,
                j = g.outerHeight() - b.sizeDiff.height;
            b._helper && !c.animate && /relative/.test(f.css("position")) && a(this).css({
                left: h.left - e.left - d.left,
                width: i,
                height: j
            }), b._helper && !c.animate && /static/.test(f.css("position")) && a(this).css({
                left: h.left - e.left - d.left,
                width: i,
                height: j
            })
        }
    }), a.ui.plugin.add("resizable", "ghost", {
        start: function () {
            var b = a(this).data("resizable"),
                c = b.options,
                d = b.size;
            b.ghost = b.originalElement.clone(), b.ghost.css({
                opacity: .25,
                display: "block",
                position: "relative",
                height: d.height,
                width: d.width,
                margin: 0,
                left: 0,
                top: 0
            }).addClass("ui-resizable-ghost").addClass("string" == typeof c.ghost ? c.ghost : ""), b.ghost.appendTo(b.helper)
        },
        resize: function () {
            var b = a(this).data("resizable");
            b.options, b.ghost && b.ghost.css({
                position: "relative",
                height: b.size.height,
                width: b.size.width
            })
        },
        stop: function () {
            var b = a(this).data("resizable");
            b.options, b.ghost && b.helper && b.helper.get(0).removeChild(b.ghost.get(0))
        }
    }), a.ui.plugin.add("resizable", "grid", {
        resize: function (b) {
            var c = a(this).data("resizable"),
                d = c.options,
                e = c.size,
                f = c.originalSize,
                g = c.originalPosition,
                h = c.axis;
            d._aspectRatio || b.shiftKey, d.grid = "number" == typeof d.grid ? [d.grid, d.grid] : d.grid;
            var i = Math.round((e.width - f.width) / (d.grid[0] || 1)) * (d.grid[0] || 1),
                j = Math.round((e.height - f.height) / (d.grid[1] || 1)) * (d.grid[1] || 1);
            /^(se|s|e)$/.test(h) ? (c.size.width = f.width + i, c.size.height = f.height + j) : /^(ne)$/.test(h) ? (c.size.width = f.width + i, c.size.height = f.height + j, c.position.top = g.top - j) : /^(sw)$/.test(h) ? (c.size.width = f.width + i, c.size.height = f.height + j, c.position.left = g.left - i) : (c.size.width = f.width + i, c.size.height = f.height + j, c.position.top = g.top - j, c.position.left = g.left - i)
        }
    });
    var b = function (a) {
        return parseInt(a, 10) || 0
    }, c = function (a) {
            return !isNaN(parseInt(a, 10))
        }
}(jQuery);
! function (a) {
    a.widget("ui.selectable", a.ui.mouse, {
        version: "1.9.2",
        options: {
            appendTo: "body",
            autoRefresh: !0,
            distance: 0,
            filter: "*",
            tolerance: "touch"
        },
        _create: function () {
            var b = this;
            this.element.addClass("ui-selectable"), this.dragged = !1;
            var c;
            this.refresh = function () {
                c = a(b.options.filter, b.element[0]), c.addClass("ui-selectee"), c.each(function () {
                    var b = a(this),
                        c = b.offset();
                    a.data(this, "selectable-item", {
                        element: this,
                        $element: b,
                        left: c.left,
                        top: c.top,
                        right: c.left + b.outerWidth(),
                        bottom: c.top + b.outerHeight(),
                        startselected: !1,
                        selected: b.hasClass("ui-selected"),
                        selecting: b.hasClass("ui-selecting"),
                        unselecting: b.hasClass("ui-unselecting")
                    })
                })
            }, this.refresh(), this.selectees = c.addClass("ui-selectee"), this._mouseInit(), this.helper = a("<div class='ui-selectable-helper'></div>")
        },
        _destroy: function () {
            this.selectees.removeClass("ui-selectee").removeData("selectable-item"), this.element.removeClass("ui-selectable ui-selectable-disabled"), this._mouseDestroy()
        },
        _mouseStart: function (b) {
            var c = this;
            if (this.opos = [b.pageX, b.pageY], !this.options.disabled) {
                var d = this.options;
                this.selectees = a(d.filter, this.element[0]), this._trigger("start", b), a(d.appendTo).append(this.helper), this.helper.css({
                    left: b.clientX,
                    top: b.clientY,
                    width: 0,
                    height: 0
                }), d.autoRefresh && this.refresh(), this.selectees.filter(".ui-selected").each(function () {
                    var d = a.data(this, "selectable-item");
                    d.startselected = !0, b.metaKey || b.ctrlKey || (d.$element.removeClass("ui-selected"), d.selected = !1, d.$element.addClass("ui-unselecting"), d.unselecting = !0, c._trigger("unselecting", b, {
                        unselecting: d.element
                    }))
                }), a(b.target).parents().andSelf().each(function () {
                    var d = a.data(this, "selectable-item");
                    if (d) {
                        var e = !b.metaKey && !b.ctrlKey || !d.$element.hasClass("ui-selected");
                        return d.$element.removeClass(e ? "ui-unselecting" : "ui-selected").addClass(e ? "ui-selecting" : "ui-unselecting"), d.unselecting = !e, d.selecting = e, d.selected = e, e ? c._trigger("selecting", b, {
                            selecting: d.element
                        }) : c._trigger("unselecting", b, {
                            unselecting: d.element
                        }), !1
                    }
                })
            }
        },
        _mouseDrag: function (b) {
            var c = this;
            if (this.dragged = !0, !this.options.disabled) {
                var d = this.options,
                    e = this.opos[0],
                    f = this.opos[1],
                    g = b.pageX,
                    h = b.pageY;
                if (e > g) {
                    var i = g;
                    g = e, e = i
                }
                if (f > h) {
                    var i = h;
                    h = f, f = i
                }
                return this.helper.css({
                    left: e,
                    top: f,
                    width: g - e,
                    height: h - f
                }), this.selectees.each(function () {
                    var i = a.data(this, "selectable-item");
                    if (i && i.element != c.element[0]) {
                        var j = !1;
                        "touch" == d.tolerance ? j = !(i.left > g || e > i.right || i.top > h || f > i.bottom) : "fit" == d.tolerance && (j = i.left > e && g > i.right && i.top > f && h > i.bottom), j ? (i.selected && (i.$element.removeClass("ui-selected"), i.selected = !1), i.unselecting && (i.$element.removeClass("ui-unselecting"), i.unselecting = !1), i.selecting || (i.$element.addClass("ui-selecting"), i.selecting = !0, c._trigger("selecting", b, {
                            selecting: i.element
                        }))) : (i.selecting && ((b.metaKey || b.ctrlKey) && i.startselected ? (i.$element.removeClass("ui-selecting"), i.selecting = !1, i.$element.addClass("ui-selected"), i.selected = !0) : (i.$element.removeClass("ui-selecting"), i.selecting = !1, i.startselected && (i.$element.addClass("ui-unselecting"), i.unselecting = !0), c._trigger("unselecting", b, {
                            unselecting: i.element
                        }))), i.selected && (b.metaKey || b.ctrlKey || i.startselected || (i.$element.removeClass("ui-selected"), i.selected = !1, i.$element.addClass("ui-unselecting"), i.unselecting = !0, c._trigger("unselecting", b, {
                            unselecting: i.element
                        }))))
                    }
                }), !1
            }
        },
        _mouseStop: function (b) {
            var c = this;
            return this.dragged = !1, this.options, a(".ui-unselecting", this.element[0]).each(function () {
                var d = a.data(this, "selectable-item");
                d.$element.removeClass("ui-unselecting"), d.unselecting = !1, d.startselected = !1, c._trigger("unselected", b, {
                    unselected: d.element
                })
            }), a(".ui-selecting", this.element[0]).each(function () {
                var d = a.data(this, "selectable-item");
                d.$element.removeClass("ui-selecting").addClass("ui-selected"), d.selecting = !1, d.selected = !0, d.startselected = !0, c._trigger("selected", b, {
                    selected: d.element
                })
            }), this._trigger("stop", b), this.helper.remove(), !1
        }
    })
}(jQuery);
! function (a) {
    a.widget("ui.sortable", a.ui.mouse, {
        version: "1.9.2",
        widgetEventPrefix: "sort",
        ready: !1,
        options: {
            appendTo: "parent",
            axis: !1,
            connectWith: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            dropOnEmpty: !0,
            forcePlaceholderSize: !1,
            forceHelperSize: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            items: "> *",
            opacity: !1,
            placeholder: !1,
            revert: !1,
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            scope: "default",
            tolerance: "intersect",
            zIndex: 1e3
        },
        _create: function () {
            var a = this.options;
            this.containerCache = {}, this.element.addClass("ui-sortable"), this.refresh(), this.floating = this.items.length ? "x" === a.axis || /left|right/.test(this.items[0].item.css("float")) || /inline|table-cell/.test(this.items[0].item.css("display")) : !1, this.offset = this.element.offset(), this._mouseInit(), this.ready = !0
        },
        _destroy: function () {
            this.element.removeClass("ui-sortable ui-sortable-disabled"), this._mouseDestroy();
            for (var a = this.items.length - 1; a >= 0; a--) this.items[a].item.removeData(this.widgetName + "-item");
            return this
        },
        _setOption: function (b, c) {
            "disabled" === b ? (this.options[b] = c, this.widget().toggleClass("ui-sortable-disabled", !! c)) : a.Widget.prototype._setOption.apply(this, arguments)
        },
        _mouseCapture: function (b, c) {
            var d = this;
            if (this.reverting) return !1;
            if (this.options.disabled || "static" == this.options.type) return !1;
            this._refreshItems(b);
            var e = null;
            if (a(b.target).parents().each(function () {
                return a.data(this, d.widgetName + "-item") == d ? (e = a(this), !1) : void 0
            }), a.data(b.target, d.widgetName + "-item") == d && (e = a(b.target)), !e) return !1;
            if (this.options.handle && !c) {
                var f = !1;
                if (a(this.options.handle, e).find("*").andSelf().each(function () {
                    this == b.target && (f = !0)
                }), !f) return !1
            }
            return this.currentItem = e, this._removeCurrentsFromItems(), !0
        },
        _mouseStart: function (b, c, d) {
            var e = this.options;
            if (this.currentContainer = this, this.refreshPositions(), this.helper = this._createHelper(b), this._cacheHelperProportions(), this._cacheMargins(), this.scrollParent = this.helper.scrollParent(), this.offset = this.currentItem.offset(), this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            }, a.extend(this.offset, {
                click: {
                    left: b.pageX - this.offset.left,
                    top: b.pageY - this.offset.top
                },
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset()
            }), this.helper.css("position", "absolute"), this.cssPosition = this.helper.css("position"), this.originalPosition = this._generatePosition(b), this.originalPageX = b.pageX, this.originalPageY = b.pageY, e.cursorAt && this._adjustOffsetFromHelper(e.cursorAt), this.domPosition = {
                prev: this.currentItem.prev()[0],
                parent: this.currentItem.parent()[0]
            }, this.helper[0] != this.currentItem[0] && this.currentItem.hide(), this._createPlaceholder(), e.containment && this._setContainment(), e.cursor && (a("body").css("cursor") && (this._storedCursor = a("body").css("cursor")), a("body").css("cursor", e.cursor)), e.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", e.opacity)), e.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", e.zIndex)), this.scrollParent[0] != document && "HTML" != this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()), this._trigger("start", b, this._uiHash()), this._preserveHelperProportions || this._cacheHelperProportions(), !d)
                for (var f = this.containers.length - 1; f >= 0; f--) this.containers[f]._trigger("activate", b, this._uiHash(this));
            return a.ui.ddmanager && (a.ui.ddmanager.current = this), a.ui.ddmanager && !e.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b), this.dragging = !0, this.helper.addClass("ui-sortable-helper"), this._mouseDrag(b), !0
        },
        _mouseDrag: function (b) {
            if (this.position = this._generatePosition(b), this.positionAbs = this._convertPositionTo("absolute"), this.lastPositionAbs || (this.lastPositionAbs = this.positionAbs), this.options.scroll) {
                var c = this.options,
                    d = !1;
                this.scrollParent[0] != document && "HTML" != this.scrollParent[0].tagName ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - b.pageY < c.scrollSensitivity ? this.scrollParent[0].scrollTop = d = this.scrollParent[0].scrollTop + c.scrollSpeed : b.pageY - this.overflowOffset.top < c.scrollSensitivity && (this.scrollParent[0].scrollTop = d = this.scrollParent[0].scrollTop - c.scrollSpeed), this.overflowOffset.left + this.scrollParent[0].offsetWidth - b.pageX < c.scrollSensitivity ? this.scrollParent[0].scrollLeft = d = this.scrollParent[0].scrollLeft + c.scrollSpeed : b.pageX - this.overflowOffset.left < c.scrollSensitivity && (this.scrollParent[0].scrollLeft = d = this.scrollParent[0].scrollLeft - c.scrollSpeed)) : (b.pageY - a(document).scrollTop() < c.scrollSensitivity ? d = a(document).scrollTop(a(document).scrollTop() - c.scrollSpeed) : a(window).height() - (b.pageY - a(document).scrollTop()) < c.scrollSensitivity && (d = a(document).scrollTop(a(document).scrollTop() + c.scrollSpeed)), b.pageX - a(document).scrollLeft() < c.scrollSensitivity ? d = a(document).scrollLeft(a(document).scrollLeft() - c.scrollSpeed) : a(window).width() - (b.pageX - a(document).scrollLeft()) < c.scrollSensitivity && (d = a(document).scrollLeft(a(document).scrollLeft() + c.scrollSpeed))), d !== !1 && a.ui.ddmanager && !c.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b)
            }
            this.positionAbs = this._convertPositionTo("absolute"), this.options.axis && "y" == this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" == this.options.axis || (this.helper[0].style.top = this.position.top + "px");
            for (var e = this.items.length - 1; e >= 0; e--) {
                var f = this.items[e],
                    g = f.item[0],
                    h = this._intersectsWithPointer(f);
                if (h && f.instance === this.currentContainer && g != this.currentItem[0] && this.placeholder[1 == h ? "next" : "prev"]()[0] != g && !a.contains(this.placeholder[0], g) && ("semi-dynamic" == this.options.type ? !a.contains(this.element[0], g) : !0)) {
                    if (this.direction = 1 == h ? "down" : "up", "pointer" != this.options.tolerance && !this._intersectsWithSides(f)) break;
                    this._rearrange(b, f), this._trigger("change", b, this._uiHash());
                    break
                }
            }
            return this._contactContainers(b), a.ui.ddmanager && a.ui.ddmanager.drag(this, b), this._trigger("sort", b, this._uiHash()), this.lastPositionAbs = this.positionAbs, !1
        },
        _mouseStop: function (b, c) {
            if (b) {
                if (a.ui.ddmanager && !this.options.dropBehaviour && a.ui.ddmanager.drop(this, b), this.options.revert) {
                    var d = this,
                        e = this.placeholder.offset();
                    this.reverting = !0, a(this.helper).animate({
                        left: e.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollLeft),
                        top: e.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollTop)
                    }, parseInt(this.options.revert, 10) || 500, function () {
                        d._clear(b)
                    })
                } else this._clear(b, c);
                return !1
            }
        },
        cancel: function () {
            if (this.dragging) {
                this._mouseUp({
                    target: null
                }), "original" == this.options.helper ? this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper") : this.currentItem.show();
                for (var b = this.containers.length - 1; b >= 0; b--) this.containers[b]._trigger("deactivate", null, this._uiHash(this)), this.containers[b].containerCache.over && (this.containers[b]._trigger("out", null, this._uiHash(this)), this.containers[b].containerCache.over = 0)
            }
            return this.placeholder && (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]), "original" != this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(), a.extend(this, {
                helper: null,
                dragging: !1,
                reverting: !1,
                _noFinalSort: null
            }), this.domPosition.prev ? a(this.domPosition.prev).after(this.currentItem) : a(this.domPosition.parent).prepend(this.currentItem)), this
        },
        serialize: function (b) {
            var c = this._getItemsAsjQuery(b && b.connected),
                d = [];
            return b = b || {}, a(c).each(function () {
                var c = (a(b.item || this).attr(b.attribute || "id") || "").match(b.expression || /(.+)[-=_](.+)/);
                c && d.push((b.key || c[1] + "[]") + "=" + (b.key && b.expression ? c[1] : c[2]))
            }), !d.length && b.key && d.push(b.key + "="), d.join("&")
        },
        toArray: function (b) {
            var c = this._getItemsAsjQuery(b && b.connected),
                d = [];
            return b = b || {}, c.each(function () {
                d.push(a(b.item || this).attr(b.attribute || "id") || "")
            }), d
        },
        _intersectsWith: function (a) {
            var b = this.positionAbs.left,
                c = b + this.helperProportions.width,
                d = this.positionAbs.top,
                e = d + this.helperProportions.height,
                f = a.left,
                g = f + a.width,
                h = a.top,
                i = h + a.height,
                j = this.offset.click.top,
                k = this.offset.click.left,
                l = d + j > h && i > d + j && b + k > f && g > b + k;
            return "pointer" == this.options.tolerance || this.options.forcePointerForContainers || "pointer" != this.options.tolerance && this.helperProportions[this.floating ? "width" : "height"] > a[this.floating ? "width" : "height"] ? l : b + this.helperProportions.width / 2 > f && g > c - this.helperProportions.width / 2 && d + this.helperProportions.height / 2 > h && i > e - this.helperProportions.height / 2
        },
        _intersectsWithPointer: function (b) {
            var c = "x" === this.options.axis || a.ui.isOverAxis(this.positionAbs.top + this.offset.click.top, b.top, b.height),
                d = "y" === this.options.axis || a.ui.isOverAxis(this.positionAbs.left + this.offset.click.left, b.left, b.width),
                e = c && d,
                f = this._getDragVerticalDirection(),
                g = this._getDragHorizontalDirection();
            return e ? this.floating ? g && "right" == g || "down" == f ? 2 : 1 : f && ("down" == f ? 2 : 1) : !1
        },
        _intersectsWithSides: function (b) {
            var c = a.ui.isOverAxis(this.positionAbs.top + this.offset.click.top, b.top + b.height / 2, b.height),
                d = a.ui.isOverAxis(this.positionAbs.left + this.offset.click.left, b.left + b.width / 2, b.width),
                e = this._getDragVerticalDirection(),
                f = this._getDragHorizontalDirection();
            return this.floating && f ? "right" == f && d || "left" == f && !d : e && ("down" == e && c || "up" == e && !c)
        },
        _getDragVerticalDirection: function () {
            var a = this.positionAbs.top - this.lastPositionAbs.top;
            return 0 != a && (a > 0 ? "down" : "up")
        },
        _getDragHorizontalDirection: function () {
            var a = this.positionAbs.left - this.lastPositionAbs.left;
            return 0 != a && (a > 0 ? "right" : "left")
        },
        refresh: function (a) {
            return this._refreshItems(a), this.refreshPositions(), this
        },
        _connectWith: function () {
            var a = this.options;
            return a.connectWith.constructor == String ? [a.connectWith] : a.connectWith
        },
        _getItemsAsjQuery: function (b) {
            var c = [],
                d = [],
                e = this._connectWith();
            if (e && b)
                for (var f = e.length - 1; f >= 0; f--)
                    for (var g = a(e[f]), h = g.length - 1; h >= 0; h--) {
                        var i = a.data(g[h], this.widgetName);
                        i && i != this && !i.options.disabled && d.push([a.isFunction(i.options.items) ? i.options.items.call(i.element) : a(i.options.items, i.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), i])
                    }
            d.push([a.isFunction(this.options.items) ? this.options.items.call(this.element, null, {
                options: this.options,
                item: this.currentItem
            }) : a(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]);
            for (var f = d.length - 1; f >= 0; f--) d[f][0].each(function () {
                c.push(this)
            });
            return a(c)
        },
        _removeCurrentsFromItems: function () {
            var b = this.currentItem.find(":data(" + this.widgetName + "-item)");
            this.items = a.grep(this.items, function (a) {
                for (var c = 0; b.length > c; c++)
                    if (b[c] == a.item[0]) return !1;
                return !0
            })
        },
        _refreshItems: function (b) {
            this.items = [], this.containers = [this];
            var c = this.items,
                d = [
                    [a.isFunction(this.options.items) ? this.options.items.call(this.element[0], b, {
                        item: this.currentItem
                    }) : a(this.options.items, this.element), this]
                ],
                e = this._connectWith();
            if (e && this.ready)
                for (var f = e.length - 1; f >= 0; f--)
                    for (var g = a(e[f]), h = g.length - 1; h >= 0; h--) {
                        var i = a.data(g[h], this.widgetName);
                        i && i != this && !i.options.disabled && (d.push([a.isFunction(i.options.items) ? i.options.items.call(i.element[0], b, {
                            item: this.currentItem
                        }) : a(i.options.items, i.element), i]), this.containers.push(i))
                    }
            for (var f = d.length - 1; f >= 0; f--)
                for (var j = d[f][1], k = d[f][0], h = 0, l = k.length; l > h; h++) {
                    var m = a(k[h]);
                    m.data(this.widgetName + "-item", j), c.push({
                        item: m,
                        instance: j,
                        width: 0,
                        height: 0,
                        left: 0,
                        top: 0
                    })
                }
        },
        refreshPositions: function (b) {
            this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset());
            for (var c = this.items.length - 1; c >= 0; c--) {
                var d = this.items[c];
                if (d.instance == this.currentContainer || !this.currentContainer || d.item[0] == this.currentItem[0]) {
                    var e = this.options.toleranceElement ? a(this.options.toleranceElement, d.item) : d.item;
                    b || (d.width = e.outerWidth(), d.height = e.outerHeight());
                    var f = e.offset();
                    d.left = f.left, d.top = f.top
                }
            }
            if (this.options.custom && this.options.custom.refreshContainers) this.options.custom.refreshContainers.call(this);
            else
                for (var c = this.containers.length - 1; c >= 0; c--) {
                    var f = this.containers[c].element.offset();
                    this.containers[c].containerCache.left = f.left, this.containers[c].containerCache.top = f.top, this.containers[c].containerCache.width = this.containers[c].element.outerWidth(), this.containers[c].containerCache.height = this.containers[c].element.outerHeight()
                }
            return this
        },
        _createPlaceholder: function (b) {
            b = b || this;
            var c = b.options;
            if (!c.placeholder || c.placeholder.constructor == String) {
                var d = c.placeholder;
                c.placeholder = {
                    element: function () {
                        var c = a(document.createElement(b.currentItem[0].nodeName)).addClass(d || b.currentItem[0].className + " ui-sortable-placeholder").removeClass("ui-sortable-helper")[0];
                        return d || (c.style.visibility = "hidden"), c
                    },
                    update: function (a, e) {
                        (!d || c.forcePlaceholderSize) && (e.height() || e.height(b.currentItem.innerHeight() - parseInt(b.currentItem.css("paddingTop") || 0, 10) - parseInt(b.currentItem.css("paddingBottom") || 0, 10)), e.width() || e.width(b.currentItem.innerWidth() - parseInt(b.currentItem.css("paddingLeft") || 0, 10) - parseInt(b.currentItem.css("paddingRight") || 0, 10)))
                    }
                }
            }
            b.placeholder = a(c.placeholder.element.call(b.element, b.currentItem)), b.currentItem.after(b.placeholder), c.placeholder.update(b, b.placeholder)
        },
        _contactContainers: function (b) {
            for (var c = null, d = null, e = this.containers.length - 1; e >= 0; e--)
                if (!a.contains(this.currentItem[0], this.containers[e].element[0]))
                    if (this._intersectsWith(this.containers[e].containerCache)) {
                        if (c && a.contains(this.containers[e].element[0], c.element[0])) continue;
                        c = this.containers[e], d = e
                    } else this.containers[e].containerCache.over && (this.containers[e]._trigger("out", b, this._uiHash(this)), this.containers[e].containerCache.over = 0);
            if (c)
                if (1 === this.containers.length) this.containers[d]._trigger("over", b, this._uiHash(this)), this.containers[d].containerCache.over = 1;
                else {
                    for (var f = 1e4, g = null, h = this.containers[d].floating ? "left" : "top", i = this.containers[d].floating ? "width" : "height", j = this.positionAbs[h] + this.offset.click[h], k = this.items.length - 1; k >= 0; k--)
                        if (a.contains(this.containers[d].element[0], this.items[k].item[0]) && this.items[k].item[0] != this.currentItem[0]) {
                            var l = this.items[k].item.offset()[h],
                                m = !1;
                            Math.abs(l - j) > Math.abs(l + this.items[k][i] - j) && (m = !0, l += this.items[k][i]), f > Math.abs(l - j) && (f = Math.abs(l - j), g = this.items[k], this.direction = m ? "up" : "down")
                        }
                    if (!g && !this.options.dropOnEmpty) return;
                    this.currentContainer = this.containers[d], g ? this._rearrange(b, g, null, !0) : this._rearrange(b, null, this.containers[d].element, !0), this._trigger("change", b, this._uiHash()), this.containers[d]._trigger("change", b, this._uiHash(this)), this.options.placeholder.update(this.currentContainer, this.placeholder), this.containers[d]._trigger("over", b, this._uiHash(this)), this.containers[d].containerCache.over = 1
                }
        },
        _createHelper: function (b) {
            var c = this.options,
                d = a.isFunction(c.helper) ? a(c.helper.apply(this.element[0], [b, this.currentItem])) : "clone" == c.helper ? this.currentItem.clone() : this.currentItem;
            return d.parents("body").length || a("parent" != c.appendTo ? c.appendTo : this.currentItem[0].parentNode)[0].appendChild(d[0]), d[0] == this.currentItem[0] && (this._storedCSS = {
                width: this.currentItem[0].style.width,
                height: this.currentItem[0].style.height,
                position: this.currentItem.css("position"),
                top: this.currentItem.css("top"),
                left: this.currentItem.css("left")
            }), ("" == d[0].style.width || c.forceHelperSize) && d.width(this.currentItem.width()), ("" == d[0].style.height || c.forceHelperSize) && d.height(this.currentItem.height()), d
        },
        _adjustOffsetFromHelper: function (b) {
            "string" == typeof b && (b = b.split(" ")), a.isArray(b) && (b = {
                left: +b[0],
                top: +b[1] || 0
            }), "left" in b && (this.offset.click.left = b.left + this.margins.left), "right" in b && (this.offset.click.left = this.helperProportions.width - b.right + this.margins.left), "top" in b && (this.offset.click.top = b.top + this.margins.top), "bottom" in b && (this.offset.click.top = this.helperProportions.height - b.bottom + this.margins.top)
        },
        _getParentOffset: function () {
            this.offsetParent = this.helper.offsetParent();
            var b = this.offsetParent.offset();
            return "absolute" == this.cssPosition && this.scrollParent[0] != document && a.contains(this.scrollParent[0], this.offsetParent[0]) && (b.left += this.scrollParent.scrollLeft(), b.top += this.scrollParent.scrollTop()), (this.offsetParent[0] == document.body || this.offsetParent[0].tagName && "html" == this.offsetParent[0].tagName.toLowerCase() && a.ui.ie) && (b = {
                top: 0,
                left: 0
            }), {
                top: b.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: b.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function () {
            if ("relative" == this.cssPosition) {
                var a = this.currentItem.position();
                return {
                    top: a.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: a.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {
                top: 0,
                left: 0
            }
        },
        _cacheMargins: function () {
            this.margins = {
                left: parseInt(this.currentItem.css("marginLeft"), 10) || 0,
                top: parseInt(this.currentItem.css("marginTop"), 10) || 0
            }
        },
        _cacheHelperProportions: function () {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            }
        },
        _setContainment: function () {
            var b = this.options;
            if ("parent" == b.containment && (b.containment = this.helper[0].parentNode), ("document" == b.containment || "window" == b.containment) && (this.containment = [0 - this.offset.relative.left - this.offset.parent.left, 0 - this.offset.relative.top - this.offset.parent.top, a("document" == b.containment ? document : window).width() - this.helperProportions.width - this.margins.left, (a("document" == b.containment ? document : window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]), !/^(document|window|parent)$/.test(b.containment)) {
                var c = a(b.containment)[0],
                    d = a(b.containment).offset(),
                    e = "hidden" != a(c).css("overflow");
                this.containment = [d.left + (parseInt(a(c).css("borderLeftWidth"), 10) || 0) + (parseInt(a(c).css("paddingLeft"), 10) || 0) - this.margins.left, d.top + (parseInt(a(c).css("borderTopWidth"), 10) || 0) + (parseInt(a(c).css("paddingTop"), 10) || 0) - this.margins.top, d.left + (e ? Math.max(c.scrollWidth, c.offsetWidth) : c.offsetWidth) - (parseInt(a(c).css("borderLeftWidth"), 10) || 0) - (parseInt(a(c).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left, d.top + (e ? Math.max(c.scrollHeight, c.offsetHeight) : c.offsetHeight) - (parseInt(a(c).css("borderTopWidth"), 10) || 0) - (parseInt(a(c).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top]
            }
        },
        _convertPositionTo: function (b, c) {
            c || (c = this.position);
            var d = "absolute" == b ? 1 : -1,
                e = (this.options, "absolute" != this.cssPosition || this.scrollParent[0] != document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent),
                f = /(html|body)/i.test(e[0].tagName);
            return {
                top: c.top + this.offset.relative.top * d + this.offset.parent.top * d - ("fixed" == this.cssPosition ? -this.scrollParent.scrollTop() : f ? 0 : e.scrollTop()) * d,
                left: c.left + this.offset.relative.left * d + this.offset.parent.left * d - ("fixed" == this.cssPosition ? -this.scrollParent.scrollLeft() : f ? 0 : e.scrollLeft()) * d
            }
        },
        _generatePosition: function (b) {
            var c = this.options,
                d = "absolute" != this.cssPosition || this.scrollParent[0] != document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
                e = /(html|body)/i.test(d[0].tagName);
            "relative" != this.cssPosition || this.scrollParent[0] != document && this.scrollParent[0] != this.offsetParent[0] || (this.offset.relative = this._getRelativeOffset());
            var f = b.pageX,
                g = b.pageY;
            if (this.originalPosition && (this.containment && (b.pageX - this.offset.click.left < this.containment[0] && (f = this.containment[0] + this.offset.click.left), b.pageY - this.offset.click.top < this.containment[1] && (g = this.containment[1] + this.offset.click.top), b.pageX - this.offset.click.left > this.containment[2] && (f = this.containment[2] + this.offset.click.left), b.pageY - this.offset.click.top > this.containment[3] && (g = this.containment[3] + this.offset.click.top)), c.grid)) {
                var h = this.originalPageY + Math.round((g - this.originalPageY) / c.grid[1]) * c.grid[1];
                g = this.containment ? h - this.offset.click.top < this.containment[1] || h - this.offset.click.top > this.containment[3] ? h - this.offset.click.top < this.containment[1] ? h + c.grid[1] : h - c.grid[1] : h : h;
                var i = this.originalPageX + Math.round((f - this.originalPageX) / c.grid[0]) * c.grid[0];
                f = this.containment ? i - this.offset.click.left < this.containment[0] || i - this.offset.click.left > this.containment[2] ? i - this.offset.click.left < this.containment[0] ? i + c.grid[0] : i - c.grid[0] : i : i
            }
            return {
                top: g - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" == this.cssPosition ? -this.scrollParent.scrollTop() : e ? 0 : d.scrollTop()),
                left: f - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" == this.cssPosition ? -this.scrollParent.scrollLeft() : e ? 0 : d.scrollLeft())
            }
        },
        _rearrange: function (a, b, c, d) {
            c ? c[0].appendChild(this.placeholder[0]) : b.item[0].parentNode.insertBefore(this.placeholder[0], "down" == this.direction ? b.item[0] : b.item[0].nextSibling), this.counter = this.counter ? ++this.counter : 1;
            var e = this.counter;
            this._delay(function () {
                e == this.counter && this.refreshPositions(!d)
            })
        },
        _clear: function (b, c) {
            this.reverting = !1;
            var d = [];
            if (!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), this._noFinalSort = null, this.helper[0] == this.currentItem[0]) {
                for (var e in this._storedCSS)("auto" == this._storedCSS[e] || "static" == this._storedCSS[e]) && (this._storedCSS[e] = "");
                this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")
            } else this.currentItem.show();
            this.fromOutside && !c && d.push(function (a) {
                this._trigger("receive", a, this._uiHash(this.fromOutside))
            }), !this.fromOutside && this.domPosition.prev == this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent == this.currentItem.parent()[0] || c || d.push(function (a) {
                this._trigger("update", a, this._uiHash())
            }), this !== this.currentContainer && (c || (d.push(function (a) {
                this._trigger("remove", a, this._uiHash())
            }), d.push(function (a) {
                return function (b) {
                    a._trigger("receive", b, this._uiHash(this))
                }
            }.call(this, this.currentContainer)), d.push(function (a) {
                return function (b) {
                    a._trigger("update", b, this._uiHash(this))
                }
            }.call(this, this.currentContainer))));
            for (var e = this.containers.length - 1; e >= 0; e--) c || d.push(function (a) {
                return function (b) {
                    a._trigger("deactivate", b, this._uiHash(this))
                }
            }.call(this, this.containers[e])), this.containers[e].containerCache.over && (d.push(function (a) {
                return function (b) {
                    a._trigger("out", b, this._uiHash(this))
                }
            }.call(this, this.containers[e])), this.containers[e].containerCache.over = 0);
            if (this._storedCursor && a("body").css("cursor", this._storedCursor), this._storedOpacity && this.helper.css("opacity", this._storedOpacity), this._storedZIndex && this.helper.css("zIndex", "auto" == this._storedZIndex ? "" : this._storedZIndex), this.dragging = !1, this.cancelHelperRemoval) {
                if (!c) {
                    this._trigger("beforeStop", b, this._uiHash());
                    for (var e = 0; d.length > e; e++) d[e].call(this, b);
                    this._trigger("stop", b, this._uiHash())
                }
                return this.fromOutside = !1, !1
            }
            if (c || this._trigger("beforeStop", b, this._uiHash()), this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.helper[0] != this.currentItem[0] && this.helper.remove(), this.helper = null, !c) {
                for (var e = 0; d.length > e; e++) d[e].call(this, b);
                this._trigger("stop", b, this._uiHash())
            }
            return this.fromOutside = !1, !0
        },
        _trigger: function () {
            a.Widget.prototype._trigger.apply(this, arguments) === !1 && this.cancel()
        },
        _uiHash: function (b) {
            var c = b || this;
            return {
                helper: c.helper,
                placeholder: c.placeholder || a([]),
                position: c.position,
                originalPosition: c.originalPosition,
                offset: c.positionAbs,
                item: c.currentItem,
                sender: b ? b.element : null
            }
        }
    })
}(jQuery);
! function (a) {
    var b, c, d, e, f = "ui-button ui-widget ui-state-default ui-corner-all",
        g = "ui-state-hover ui-state-active ",
        h = "ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only",
        i = function () {
            var b = a(this).find(":ui-button");
            setTimeout(function () {
                b.button("refresh")
            }, 1)
        }, j = function (b) {
            var c = b.name,
                d = b.form,
                e = a([]);
            return c && (e = d ? a(d).find("[name='" + c + "']") : a("[name='" + c + "']", b.ownerDocument).filter(function () {
                return !this.form
            })), e
        };
    a.widget("ui.button", {
        version: "1.9.2",
        defaultElement: "<button>",
        options: {
            disabled: null,
            text: !0,
            label: null,
            icons: {
                primary: null,
                secondary: null
            }
        },
        _create: function () {
            this.element.closest("form").unbind("reset" + this.eventNamespace).bind("reset" + this.eventNamespace, i), "boolean" != typeof this.options.disabled ? this.options.disabled = !! this.element.prop("disabled") : this.element.prop("disabled", this.options.disabled), this._determineButtonType(), this.hasTitle = !! this.buttonElement.attr("title");
            var g = this,
                h = this.options,
                k = "checkbox" === this.type || "radio" === this.type,
                l = k ? "" : "ui-state-active",
                m = "ui-state-focus";
            null === h.label && (h.label = "input" === this.type ? this.buttonElement.val() : this.buttonElement.html()), this._hoverable(this.buttonElement), this.buttonElement.addClass(f).attr("role", "button").bind("mouseenter" + this.eventNamespace, function () {
                h.disabled || this === b && a(this).addClass("ui-state-active")
            }).bind("mouseleave" + this.eventNamespace, function () {
                h.disabled || a(this).removeClass(l)
            }).bind("click" + this.eventNamespace, function (a) {
                h.disabled && (a.preventDefault(), a.stopImmediatePropagation())
            }), this.element.bind("focus" + this.eventNamespace, function () {
                g.buttonElement.addClass(m)
            }).bind("blur" + this.eventNamespace, function () {
                g.buttonElement.removeClass(m)
            }), k && (this.element.bind("change" + this.eventNamespace, function () {
                e || g.refresh()
            }), this.buttonElement.bind("mousedown" + this.eventNamespace, function (a) {
                h.disabled || (e = !1, c = a.pageX, d = a.pageY)
            }).bind("mouseup" + this.eventNamespace, function (a) {
                h.disabled || (c !== a.pageX || d !== a.pageY) && (e = !0)
            })), "checkbox" === this.type ? this.buttonElement.bind("click" + this.eventNamespace, function () {
                return h.disabled || e ? !1 : (a(this).toggleClass("ui-state-active"), g.buttonElement.attr("aria-pressed", g.element[0].checked), void 0)
            }) : "radio" === this.type ? this.buttonElement.bind("click" + this.eventNamespace, function () {
                if (h.disabled || e) return !1;
                a(this).addClass("ui-state-active"), g.buttonElement.attr("aria-pressed", "true");
                var b = g.element[0];
                j(b).not(b).map(function () {
                    return a(this).button("widget")[0]
                }).removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : (this.buttonElement.bind("mousedown" + this.eventNamespace, function () {
                return h.disabled ? !1 : (a(this).addClass("ui-state-active"), b = this, g.document.one("mouseup", function () {
                    b = null
                }), void 0)
            }).bind("mouseup" + this.eventNamespace, function () {
                return h.disabled ? !1 : (a(this).removeClass("ui-state-active"), void 0)
            }).bind("keydown" + this.eventNamespace, function (b) {
                return h.disabled ? !1 : ((b.keyCode === a.ui.keyCode.SPACE || b.keyCode === a.ui.keyCode.ENTER) && a(this).addClass("ui-state-active"), void 0)
            }).bind("keyup" + this.eventNamespace, function () {
                a(this).removeClass("ui-state-active")
            }), this.buttonElement.is("a") && this.buttonElement.keyup(function (b) {
                b.keyCode === a.ui.keyCode.SPACE && a(this).click()
            })), this._setOption("disabled", h.disabled), this._resetButton()
        },
        _determineButtonType: function () {
            var a, b, c;
            this.type = this.element.is("[type=checkbox]") ? "checkbox" : this.element.is("[type=radio]") ? "radio" : this.element.is("input") ? "input" : "button", "checkbox" === this.type || "radio" === this.type ? (a = this.element.parents().last(), b = "label[for='" + this.element.attr("id") + "']", this.buttonElement = a.find(b), this.buttonElement.length || (a = a.length ? a.siblings() : this.element.siblings(), this.buttonElement = a.filter(b), this.buttonElement.length || (this.buttonElement = a.find(b))), this.element.addClass("ui-helper-hidden-accessible"), c = this.element.is(":checked"), c && this.buttonElement.addClass("ui-state-active"), this.buttonElement.prop("aria-pressed", c)) : this.buttonElement = this.element
        },
        widget: function () {
            return this.buttonElement
        },
        _destroy: function () {
            this.element.removeClass("ui-helper-hidden-accessible"), this.buttonElement.removeClass(f + " " + g + " " + h).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html()), this.hasTitle || this.buttonElement.removeAttr("title")
        },
        _setOption: function (a, b) {
            return this._super(a, b), "disabled" === a ? (b ? this.element.prop("disabled", !0) : this.element.prop("disabled", !1), void 0) : (this._resetButton(), void 0)
        },
        refresh: function () {
            var b = this.element.is("input, button") ? this.element.is(":disabled") : this.element.hasClass("ui-button-disabled");
            b !== this.options.disabled && this._setOption("disabled", b), "radio" === this.type ? j(this.element[0]).each(function () {
                a(this).is(":checked") ? a(this).button("widget").addClass("ui-state-active").attr("aria-pressed", "true") : a(this).button("widget").removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : "checkbox" === this.type && (this.element.is(":checked") ? this.buttonElement.addClass("ui-state-active").attr("aria-pressed", "true") : this.buttonElement.removeClass("ui-state-active").attr("aria-pressed", "false"))
        },
        _resetButton: function () {
            if ("input" === this.type) return this.options.label && this.element.val(this.options.label), void 0;
            var b = this.buttonElement.removeClass(h),
                c = a("<span></span>", this.document[0]).addClass("ui-button-text").html(this.options.label).appendTo(b.empty()).text(),
                d = this.options.icons,
                e = d.primary && d.secondary,
                f = [];
            d.primary || d.secondary ? (this.options.text && f.push("ui-button-text-icon" + (e ? "s" : d.primary ? "-primary" : "-secondary")), d.primary && b.prepend("<span class='ui-button-icon-primary ui-icon " + d.primary + "'></span>"), d.secondary && b.append("<span class='ui-button-icon-secondary ui-icon " + d.secondary + "'></span>"), this.options.text || (f.push(e ? "ui-button-icons-only" : "ui-button-icon-only"), this.hasTitle || b.attr("title", a.trim(c)))) : f.push("ui-button-text-only"), b.addClass(f.join(" "))
        }
    }), a.widget("ui.buttonset", {
        version: "1.9.2",
        options: {
            items: "button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(button)"
        },
        _create: function () {
            this.element.addClass("ui-buttonset")
        },
        _init: function () {
            this.refresh()
        },
        _setOption: function (a, b) {
            "disabled" === a && this.buttons.button("option", a, b), this._super(a, b)
        },
        refresh: function () {
            var b = "rtl" === this.element.css("direction");
            this.buttons = this.element.find(this.options.items).filter(":ui-button").button("refresh").end().not(":ui-button").button().end().map(function () {
                return a(this).button("widget")[0]
            }).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(b ? "ui-corner-right" : "ui-corner-left").end().filter(":last").addClass(b ? "ui-corner-left" : "ui-corner-right").end().end()
        },
        _destroy: function () {
            this.element.removeClass("ui-buttonset"), this.buttons.map(function () {
                return a(this).button("widget")[0]
            }).removeClass("ui-corner-left ui-corner-right").end().button("destroy")
        }
    })
}(jQuery);
! function (a) {
    var b = 5;
    a.widget("ui.slider", a.ui.mouse, {
        version: "1.9.2",
        widgetEventPrefix: "slide",
        options: {
            animate: !1,
            distance: 0,
            max: 100,
            min: 0,
            orientation: "horizontal",
            range: !1,
            step: 1,
            value: 0,
            values: null
        },
        _create: function () {
            var c, d, e = this.options,
                f = this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),
                g = "<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>",
                h = [];
            for (this._keySliding = !1, this._mouseSliding = !1, this._animateOff = !0, this._handleIndex = null, this._detectOrientation(), this._mouseInit(), this.element.addClass("ui-slider ui-slider-" + this.orientation + " ui-widget ui-widget-content ui-corner-all" + (e.disabled ? " ui-slider-disabled ui-disabled" : "")), this.range = a([]), e.range && (e.range === !0 && (e.values || (e.values = [this._valueMin(), this._valueMin()]), e.values.length && 2 !== e.values.length && (e.values = [e.values[0], e.values[0]])), this.range = a("<div></div>").appendTo(this.element).addClass("ui-slider-range ui-widget-header" + ("min" === e.range || "max" === e.range ? " ui-slider-range-" + e.range : ""))), d = e.values && e.values.length || 1, c = f.length; d > c; c++) h.push(g);
            this.handles = f.add(a(h.join("")).appendTo(this.element)), this.handle = this.handles.eq(0), this.handles.add(this.range).filter("a").click(function (a) {
                a.preventDefault()
            }).mouseenter(function () {
                e.disabled || a(this).addClass("ui-state-hover")
            }).mouseleave(function () {
                a(this).removeClass("ui-state-hover")
            }).focus(function () {
                e.disabled ? a(this).blur() : (a(".ui-slider .ui-state-focus").removeClass("ui-state-focus"), a(this).addClass("ui-state-focus"))
            }).blur(function () {
                a(this).removeClass("ui-state-focus")
            }), this.handles.each(function (b) {
                a(this).data("ui-slider-handle-index", b)
            }), this._on(this.handles, {
                keydown: function (c) {
                    var d, e, f, g, h = a(c.target).data("ui-slider-handle-index");
                    switch (c.keyCode) {
                    case a.ui.keyCode.HOME:
                    case a.ui.keyCode.END:
                    case a.ui.keyCode.PAGE_UP:
                    case a.ui.keyCode.PAGE_DOWN:
                    case a.ui.keyCode.UP:
                    case a.ui.keyCode.RIGHT:
                    case a.ui.keyCode.DOWN:
                    case a.ui.keyCode.LEFT:
                        if (c.preventDefault(), !this._keySliding && (this._keySliding = !0, a(c.target).addClass("ui-state-active"), d = this._start(c, h), d === !1)) return
                    }
                    switch (g = this.options.step, e = f = this.options.values && this.options.values.length ? this.values(h) : this.value(), c.keyCode) {
                    case a.ui.keyCode.HOME:
                        f = this._valueMin();
                        break;
                    case a.ui.keyCode.END:
                        f = this._valueMax();
                        break;
                    case a.ui.keyCode.PAGE_UP:
                        f = this._trimAlignValue(e + (this._valueMax() - this._valueMin()) / b);
                        break;
                    case a.ui.keyCode.PAGE_DOWN:
                        f = this._trimAlignValue(e - (this._valueMax() - this._valueMin()) / b);
                        break;
                    case a.ui.keyCode.UP:
                    case a.ui.keyCode.RIGHT:
                        if (e === this._valueMax()) return;
                        f = this._trimAlignValue(e + g);
                        break;
                    case a.ui.keyCode.DOWN:
                    case a.ui.keyCode.LEFT:
                        if (e === this._valueMin()) return;
                        f = this._trimAlignValue(e - g)
                    }
                    this._slide(c, h, f)
                },
                keyup: function (b) {
                    var c = a(b.target).data("ui-slider-handle-index");
                    this._keySliding && (this._keySliding = !1, this._stop(b, c), this._change(b, c), a(b.target).removeClass("ui-state-active"))
                }
            }), this._refreshValue(), this._animateOff = !1
        },
        _destroy: function () {
            this.handles.remove(), this.range.remove(), this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-slider-disabled ui-widget ui-widget-content ui-corner-all"), this._mouseDestroy()
        },
        _mouseCapture: function (b) {
            var c, d, e, f, g, h, i, j, k = this,
                l = this.options;
            return l.disabled ? !1 : (this.elementSize = {
                width: this.element.outerWidth(),
                height: this.element.outerHeight()
            }, this.elementOffset = this.element.offset(), c = {
                x: b.pageX,
                y: b.pageY
            }, d = this._normValueFromMouse(c), e = this._valueMax() - this._valueMin() + 1, this.handles.each(function (b) {
                var c = Math.abs(d - k.values(b));
                e > c && (e = c, f = a(this), g = b)
            }), l.range === !0 && this.values(1) === l.min && (g += 1, f = a(this.handles[g])), h = this._start(b, g), h === !1 ? !1 : (this._mouseSliding = !0, this._handleIndex = g, f.addClass("ui-state-active").focus(), i = f.offset(), j = !a(b.target).parents().andSelf().is(".ui-slider-handle"), this._clickOffset = j ? {
                left: 0,
                top: 0
            } : {
                left: b.pageX - i.left - f.width() / 2,
                top: b.pageY - i.top - f.height() / 2 - (parseInt(f.css("borderTopWidth"), 10) || 0) - (parseInt(f.css("borderBottomWidth"), 10) || 0) + (parseInt(f.css("marginTop"), 10) || 0)
            }, this.handles.hasClass("ui-state-hover") || this._slide(b, g, d), this._animateOff = !0, !0))
        },
        _mouseStart: function () {
            return !0
        },
        _mouseDrag: function (a) {
            var b = {
                x: a.pageX,
                y: a.pageY
            }, c = this._normValueFromMouse(b);
            return this._slide(a, this._handleIndex, c), !1
        },
        _mouseStop: function (a) {
            return this.handles.removeClass("ui-state-active"), this._mouseSliding = !1, this._stop(a, this._handleIndex), this._change(a, this._handleIndex), this._handleIndex = null, this._clickOffset = null, this._animateOff = !1, !1
        },
        _detectOrientation: function () {
            this.orientation = "vertical" === this.options.orientation ? "vertical" : "horizontal"
        },
        _normValueFromMouse: function (a) {
            var b, c, d, e, f;
            return "horizontal" === this.orientation ? (b = this.elementSize.width, c = a.x - this.elementOffset.left - (this._clickOffset ? this._clickOffset.left : 0)) : (b = this.elementSize.height, c = a.y - this.elementOffset.top - (this._clickOffset ? this._clickOffset.top : 0)), d = c / b, d > 1 && (d = 1), 0 > d && (d = 0), "vertical" === this.orientation && (d = 1 - d), e = this._valueMax() - this._valueMin(), f = this._valueMin() + d * e, this._trimAlignValue(f)
        },
        _start: function (a, b) {
            var c = {
                handle: this.handles[b],
                value: this.value()
            };
            return this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()), this._trigger("start", a, c)
        },
        _slide: function (a, b, c) {
            var d, e, f;
            this.options.values && this.options.values.length ? (d = this.values(b ? 0 : 1), 2 === this.options.values.length && this.options.range === !0 && (0 === b && c > d || 1 === b && d > c) && (c = d), c !== this.values(b) && (e = this.values(), e[b] = c, f = this._trigger("slide", a, {
                handle: this.handles[b],
                value: c,
                values: e
            }), d = this.values(b ? 0 : 1), f !== !1 && this.values(b, c, !0))) : c !== this.value() && (f = this._trigger("slide", a, {
                handle: this.handles[b],
                value: c
            }), f !== !1 && this.value(c))
        },
        _stop: function (a, b) {
            var c = {
                handle: this.handles[b],
                value: this.value()
            };
            this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()), this._trigger("stop", a, c)
        },
        _change: function (a, b) {
            if (!this._keySliding && !this._mouseSliding) {
                var c = {
                    handle: this.handles[b],
                    value: this.value()
                };
                this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()), this._trigger("change", a, c)
            }
        },
        value: function (a) {
            return arguments.length ? (this.options.value = this._trimAlignValue(a), this._refreshValue(), this._change(null, 0), void 0) : this._value()
        },
        values: function (b, c) {
            var d, e, f;
            if (arguments.length > 1) return this.options.values[b] = this._trimAlignValue(c), this._refreshValue(), this._change(null, b), void 0;
            if (!arguments.length) return this._values();
            if (!a.isArray(arguments[0])) return this.options.values && this.options.values.length ? this._values(b) : this.value();
            for (d = this.options.values, e = arguments[0], f = 0; d.length > f; f += 1) d[f] = this._trimAlignValue(e[f]), this._change(null, f);
            this._refreshValue()
        },
        _setOption: function (b, c) {
            var d, e = 0;
            switch (a.isArray(this.options.values) && (e = this.options.values.length), a.Widget.prototype._setOption.apply(this, arguments), b) {
            case "disabled":
                c ? (this.handles.filter(".ui-state-focus").blur(), this.handles.removeClass("ui-state-hover"), this.handles.prop("disabled", !0), this.element.addClass("ui-disabled")) : (this.handles.prop("disabled", !1), this.element.removeClass("ui-disabled"));
                break;
            case "orientation":
                this._detectOrientation(), this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-" + this.orientation), this._refreshValue();
                break;
            case "value":
                this._animateOff = !0, this._refreshValue(), this._change(null, 0), this._animateOff = !1;
                break;
            case "values":
                for (this._animateOff = !0, this._refreshValue(), d = 0; e > d; d += 1) this._change(null, d);
                this._animateOff = !1;
                break;
            case "min":
            case "max":
                this._animateOff = !0, this._refreshValue(), this._animateOff = !1
            }
        },
        _value: function () {
            var a = this.options.value;
            return a = this._trimAlignValue(a)
        },
        _values: function (a) {
            var b, c, d;
            if (arguments.length) return b = this.options.values[a], b = this._trimAlignValue(b);
            for (c = this.options.values.slice(), d = 0; c.length > d; d += 1) c[d] = this._trimAlignValue(c[d]);
            return c
        },
        _trimAlignValue: function (a) {
            if (this._valueMin() >= a) return this._valueMin();
            if (a >= this._valueMax()) return this._valueMax();
            var b = this.options.step > 0 ? this.options.step : 1,
                c = (a - this._valueMin()) % b,
                d = a - c;
            return 2 * Math.abs(c) >= b && (d += c > 0 ? b : -b), parseFloat(d.toFixed(5))
        },
        _valueMin: function () {
            return this.options.min
        },
        _valueMax: function () {
            return this.options.max
        },
        _refreshValue: function () {
            var b, c, d, e, f, g = this.options.range,
                h = this.options,
                i = this,
                j = this._animateOff ? !1 : h.animate,
                k = {};
            this.options.values && this.options.values.length ? this.handles.each(function (d) {
                c = 100 * ((i.values(d) - i._valueMin()) / (i._valueMax() - i._valueMin())), k["horizontal" === i.orientation ? "left" : "bottom"] = c + "%", a(this).stop(1, 1)[j ? "animate" : "css"](k, h.animate), i.options.range === !0 && ("horizontal" === i.orientation ? (0 === d && i.range.stop(1, 1)[j ? "animate" : "css"]({
                    left: c + "%"
                }, h.animate), 1 === d && i.range[j ? "animate" : "css"]({
                    width: c - b + "%"
                }, {
                    queue: !1,
                    duration: h.animate
                })) : (0 === d && i.range.stop(1, 1)[j ? "animate" : "css"]({
                    bottom: c + "%"
                }, h.animate), 1 === d && i.range[j ? "animate" : "css"]({
                    height: c - b + "%"
                }, {
                    queue: !1,
                    duration: h.animate
                }))), b = c
            }) : (d = this.value(), e = this._valueMin(), f = this._valueMax(), c = f !== e ? 100 * ((d - e) / (f - e)) : 0, k["horizontal" === this.orientation ? "left" : "bottom"] = c + "%", this.handle.stop(1, 1)[j ? "animate" : "css"](k, h.animate), "min" === g && "horizontal" === this.orientation && this.range.stop(1, 1)[j ? "animate" : "css"]({
                width: c + "%"
            }, h.animate), "max" === g && "horizontal" === this.orientation && this.range[j ? "animate" : "css"]({
                width: 100 - c + "%"
            }, {
                queue: !1,
                duration: h.animate
            }), "min" === g && "vertical" === this.orientation && this.range.stop(1, 1)[j ? "animate" : "css"]({
                height: c + "%"
            }, h.animate), "max" === g && "vertical" === this.orientation && this.range[j ? "animate" : "css"]({
                height: 100 - c + "%"
            }, {
                queue: !1,
                duration: h.animate
            }))
        }
    })
}(jQuery);
jQuery.effects || function (a, b) {
    var c = a.uiBackCompat !== !1,
        d = "ui-effects-";
    a.effects = {
        effect: {}
    },
    function (b, c) {
        function d(a, b, c) {
            var d = m[b.type] || {};
            return null == a ? c || !b.def ? null : b.def : (a = d.floor ? ~~a : parseFloat(a), isNaN(a) ? b.def : d.mod ? (a + d.mod) % d.mod : 0 > a ? 0 : a > d.max ? d.max : a)
        }

        function e(a) {
            var d = k(),
                e = d._rgba = [];
            return a = a.toLowerCase(), p(j, function (b, f) {
                var g, h = f.re.exec(a),
                    i = h && f.parse(h),
                    j = f.space || "rgba";
                return i ? (g = d[j](i), d[l[j].cache] = g[l[j].cache], e = d._rgba = g._rgba, !1) : c
            }), e.length ? ("0,0,0,0" === e.join() && b.extend(e, g.transparent), d) : g[a]
        }

        function f(a, b, c) {
            return c = (c + 1) % 1, 1 > 6 * c ? a + 6 * (b - a) * c : 1 > 2 * c ? b : 2 > 3 * c ? a + 6 * (b - a) * (2 / 3 - c) : a
        }
        var g, h = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor".split(" "),
            i = /^([\-+])=\s*(\d+\.?\d*)/,
            j = [{
                re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
                parse: function (a) {
                    return [a[1], a[2], a[3], a[4]]
                }
            }, {
                re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
                parse: function (a) {
                    return [2.55 * a[1], 2.55 * a[2], 2.55 * a[3], a[4]]
                }
            }, {
                re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,
                parse: function (a) {
                    return [parseInt(a[1], 16), parseInt(a[2], 16), parseInt(a[3], 16)]
                }
            }, {
                re: /#([a-f0-9])([a-f0-9])([a-f0-9])/,
                parse: function (a) {
                    return [parseInt(a[1] + a[1], 16), parseInt(a[2] + a[2], 16), parseInt(a[3] + a[3], 16)]
                }
            }, {
                re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
                space: "hsla",
                parse: function (a) {
                    return [a[1], a[2] / 100, a[3] / 100, a[4]]
                }
            }],
            k = b.Color = function (a, c, d, e) {
                return new b.Color.fn.parse(a, c, d, e)
            }, l = {
                rgba: {
                    props: {
                        red: {
                            idx: 0,
                            type: "byte"
                        },
                        green: {
                            idx: 1,
                            type: "byte"
                        },
                        blue: {
                            idx: 2,
                            type: "byte"
                        }
                    }
                },
                hsla: {
                    props: {
                        hue: {
                            idx: 0,
                            type: "degrees"
                        },
                        saturation: {
                            idx: 1,
                            type: "percent"
                        },
                        lightness: {
                            idx: 2,
                            type: "percent"
                        }
                    }
                }
            }, m = {
                "byte": {
                    floor: !0,
                    max: 255
                },
                percent: {
                    max: 1
                },
                degrees: {
                    mod: 360,
                    floor: !0
                }
            }, n = k.support = {}, o = b("<p>")[0],
            p = b.each;
        o.style.cssText = "background-color:rgba(1,1,1,.5)", n.rgba = o.style.backgroundColor.indexOf("rgba") > -1, p(l, function (a, b) {
            b.cache = "_" + a, b.props.alpha = {
                idx: 3,
                type: "percent",
                def: 1
            }
        }), k.fn = b.extend(k.prototype, {
            parse: function (f, h, i, j) {
                if (f === c) return this._rgba = [null, null, null, null], this;
                (f.jquery || f.nodeType) && (f = b(f).css(h), h = c);
                var m = this,
                    n = b.type(f),
                    o = this._rgba = [];
                return h !== c && (f = [f, h, i, j], n = "array"), "string" === n ? this.parse(e(f) || g._default) : "array" === n ? (p(l.rgba.props, function (a, b) {
                    o[b.idx] = d(f[b.idx], b)
                }), this) : "object" === n ? (f instanceof k ? p(l, function (a, b) {
                    f[b.cache] && (m[b.cache] = f[b.cache].slice())
                }) : p(l, function (b, c) {
                    var e = c.cache;
                    p(c.props, function (a, b) {
                        if (!m[e] && c.to) {
                            if ("alpha" === a || null == f[a]) return;
                            m[e] = c.to(m._rgba)
                        }
                        m[e][b.idx] = d(f[a], b, !0)
                    }), m[e] && 0 > a.inArray(null, m[e].slice(0, 3)) && (m[e][3] = 1, c.from && (m._rgba = c.from(m[e])))
                }), this) : c
            },
            is: function (a) {
                var b = k(a),
                    d = !0,
                    e = this;
                return p(l, function (a, f) {
                    var g, h = b[f.cache];
                    return h && (g = e[f.cache] || f.to && f.to(e._rgba) || [], p(f.props, function (a, b) {
                        return null != h[b.idx] ? d = h[b.idx] === g[b.idx] : c
                    })), d
                }), d
            },
            _space: function () {
                var a = [],
                    b = this;
                return p(l, function (c, d) {
                    b[d.cache] && a.push(c)
                }), a.pop()
            },
            transition: function (a, b) {
                var c = k(a),
                    e = c._space(),
                    f = l[e],
                    g = 0 === this.alpha() ? k("transparent") : this,
                    h = g[f.cache] || f.to(g._rgba),
                    i = h.slice();
                return c = c[f.cache], p(f.props, function (a, e) {
                    var f = e.idx,
                        g = h[f],
                        j = c[f],
                        k = m[e.type] || {};
                    null !== j && (null === g ? i[f] = j : (k.mod && (j - g > k.mod / 2 ? g += k.mod : g - j > k.mod / 2 && (g -= k.mod)), i[f] = d((j - g) * b + g, e)))
                }), this[e](i)
            },
            blend: function (a) {
                if (1 === this._rgba[3]) return this;
                var c = this._rgba.slice(),
                    d = c.pop(),
                    e = k(a)._rgba;
                return k(b.map(c, function (a, b) {
                    return (1 - d) * e[b] + d * a
                }))
            },
            toRgbaString: function () {
                var a = "rgba(",
                    c = b.map(this._rgba, function (a, b) {
                        return null == a ? b > 2 ? 1 : 0 : a
                    });
                return 1 === c[3] && (c.pop(), a = "rgb("), a + c.join() + ")"
            },
            toHslaString: function () {
                var a = "hsla(",
                    c = b.map(this.hsla(), function (a, b) {
                        return null == a && (a = b > 2 ? 1 : 0), b && 3 > b && (a = Math.round(100 * a) + "%"), a
                    });
                return 1 === c[3] && (c.pop(), a = "hsl("), a + c.join() + ")"
            },
            toHexString: function (a) {
                var c = this._rgba.slice(),
                    d = c.pop();
                return a && c.push(~~(255 * d)), "#" + b.map(c, function (a) {
                    return a = (a || 0).toString(16), 1 === a.length ? "0" + a : a
                }).join("")
            },
            toString: function () {
                return 0 === this._rgba[3] ? "transparent" : this.toRgbaString()
            }
        }), k.fn.parse.prototype = k.fn, l.hsla.to = function (a) {
            if (null == a[0] || null == a[1] || null == a[2]) return [null, null, null, a[3]];
            var b, c, d = a[0] / 255,
                e = a[1] / 255,
                f = a[2] / 255,
                g = a[3],
                h = Math.max(d, e, f),
                i = Math.min(d, e, f),
                j = h - i,
                k = h + i,
                l = .5 * k;
            return b = i === h ? 0 : d === h ? 60 * (e - f) / j + 360 : e === h ? 60 * (f - d) / j + 120 : 60 * (d - e) / j + 240, c = 0 === l || 1 === l ? l : .5 >= l ? j / k : j / (2 - k), [Math.round(b) % 360, c, l, null == g ? 1 : g]
        }, l.hsla.from = function (a) {
            if (null == a[0] || null == a[1] || null == a[2]) return [null, null, null, a[3]];
            var b = a[0] / 360,
                c = a[1],
                d = a[2],
                e = a[3],
                g = .5 >= d ? d * (1 + c) : d + c - d * c,
                h = 2 * d - g;
            return [Math.round(255 * f(h, g, b + 1 / 3)), Math.round(255 * f(h, g, b)), Math.round(255 * f(h, g, b - 1 / 3)), e]
        }, p(l, function (a, e) {
            var f = e.props,
                g = e.cache,
                h = e.to,
                j = e.from;
            k.fn[a] = function (a) {
                if (h && !this[g] && (this[g] = h(this._rgba)), a === c) return this[g].slice();
                var e, i = b.type(a),
                    l = "array" === i || "object" === i ? a : arguments,
                    m = this[g].slice();
                return p(f, function (a, b) {
                    var c = l["object" === i ? a : b.idx];
                    null == c && (c = m[b.idx]), m[b.idx] = d(c, b)
                }), j ? (e = k(j(m)), e[g] = m, e) : k(m)
            }, p(f, function (c, d) {
                k.fn[c] || (k.fn[c] = function (e) {
                    var f, g = b.type(e),
                        h = "alpha" === c ? this._hsla ? "hsla" : "rgba" : a,
                        j = this[h](),
                        k = j[d.idx];
                    return "undefined" === g ? k : ("function" === g && (e = e.call(this, k), g = b.type(e)), null == e && d.empty ? this : ("string" === g && (f = i.exec(e), f && (e = k + parseFloat(f[2]) * ("+" === f[1] ? 1 : -1))), j[d.idx] = e, this[h](j)))
                })
            })
        }), p(h, function (a, c) {
            b.cssHooks[c] = {
                set: function (a, d) {
                    var f, g, h = "";
                    if ("string" !== b.type(d) || (f = e(d))) {
                        if (d = k(f || d), !n.rgba && 1 !== d._rgba[3]) {
                            for (g = "backgroundColor" === c ? a.parentNode : a;
                                ("" === h || "transparent" === h) && g && g.style;) try {
                                h = b.css(g, "backgroundColor"), g = g.parentNode
                            } catch (i) {}
                            d = d.blend(h && "transparent" !== h ? h : "_default")
                        }
                        d = d.toRgbaString()
                    }
                    try {
                        a.style[c] = d
                    } catch (j) {}
                }
            }, b.fx.step[c] = function (a) {
                a.colorInit || (a.start = k(a.elem, c), a.end = k(a.end), a.colorInit = !0), b.cssHooks[c].set(a.elem, a.start.transition(a.end, a.pos))
            }
        }), b.cssHooks.borderColor = {
            expand: function (a) {
                var b = {};
                return p(["Top", "Right", "Bottom", "Left"], function (c, d) {
                    b["border" + d + "Color"] = a
                }), b
            }
        }, g = b.Color.names = {
            aqua: "#00ffff",
            black: "#000000",
            blue: "#0000ff",
            fuchsia: "#ff00ff",
            gray: "#808080",
            green: "#008000",
            lime: "#00ff00",
            maroon: "#800000",
            navy: "#000080",
            olive: "#808000",
            purple: "#800080",
            red: "#ff0000",
            silver: "#c0c0c0",
            teal: "#008080",
            white: "#ffffff",
            yellow: "#ffff00",
            transparent: [null, null, null, 0],
            _default: "#ffffff"
        }
    }(jQuery),
    function () {
        function c() {
            var b, c, d = this.ownerDocument.defaultView ? this.ownerDocument.defaultView.getComputedStyle(this, null) : this.currentStyle,
                e = {};
            if (d && d.length && d[0] && d[d[0]])
                for (c = d.length; c--;) b = d[c], "string" == typeof d[b] && (e[a.camelCase(b)] = d[b]);
            else
                for (b in d) "string" == typeof d[b] && (e[b] = d[b]);
            return e
        }

        function d(b, c) {
            var d, e, g = {};
            for (d in c) e = c[d], b[d] !== e && (f[d] || (a.fx.step[d] || !isNaN(parseFloat(e))) && (g[d] = e));
            return g
        }
        var e = ["add", "remove", "toggle"],
            f = {
                border: 1,
                borderBottom: 1,
                borderColor: 1,
                borderLeft: 1,
                borderRight: 1,
                borderTop: 1,
                borderWidth: 1,
                margin: 1,
                padding: 1
            };
        a.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"], function (b, c) {
            a.fx.step[c] = function (a) {
                ("none" !== a.end && !a.setAttr || 1 === a.pos && !a.setAttr) && (jQuery.style(a.elem, c, a.end), a.setAttr = !0)
            }
        }), a.effects.animateClass = function (b, f, g, h) {
            var i = a.speed(f, g, h);
            return this.queue(function () {
                var f, g = a(this),
                    h = g.attr("class") || "",
                    j = i.children ? g.find("*").andSelf() : g;
                j = j.map(function () {
                    var b = a(this);
                    return {
                        el: b,
                        start: c.call(this)
                    }
                }), f = function () {
                    a.each(e, function (a, c) {
                        b[c] && g[c + "Class"](b[c])
                    })
                }, f(), j = j.map(function () {
                    return this.end = c.call(this.el[0]), this.diff = d(this.start, this.end), this
                }), g.attr("class", h), j = j.map(function () {
                    var b = this,
                        c = a.Deferred(),
                        d = jQuery.extend({}, i, {
                            queue: !1,
                            complete: function () {
                                c.resolve(b)
                            }
                        });
                    return this.el.animate(this.diff, d), c.promise()
                }), a.when.apply(a, j.get()).done(function () {
                    f(), a.each(arguments, function () {
                        var b = this.el;
                        a.each(this.diff, function (a) {
                            b.css(a, "")
                        })
                    }), i.complete.call(g[0])
                })
            })
        }, a.fn.extend({
            _addClass: a.fn.addClass,
            addClass: function (b, c, d, e) {
                return c ? a.effects.animateClass.call(this, {
                    add: b
                }, c, d, e) : this._addClass(b)
            },
            _removeClass: a.fn.removeClass,
            removeClass: function (b, c, d, e) {
                return c ? a.effects.animateClass.call(this, {
                    remove: b
                }, c, d, e) : this._removeClass(b)
            },
            _toggleClass: a.fn.toggleClass,
            toggleClass: function (c, d, e, f, g) {
                return "boolean" == typeof d || d === b ? e ? a.effects.animateClass.call(this, d ? {
                    add: c
                } : {
                    remove: c
                }, e, f, g) : this._toggleClass(c, d) : a.effects.animateClass.call(this, {
                    toggle: c
                }, d, e, f)
            },
            switchClass: function (b, c, d, e, f) {
                return a.effects.animateClass.call(this, {
                    add: c,
                    remove: b
                }, d, e, f)
            }
        })
    }(),
    function () {
        function e(b, c, d, e) {
            return a.isPlainObject(b) && (c = b, b = b.effect), b = {
                effect: b
            }, null == c && (c = {}), a.isFunction(c) && (e = c, d = null, c = {}), ("number" == typeof c || a.fx.speeds[c]) && (e = d, d = c, c = {}), a.isFunction(d) && (e = d, d = null), c && a.extend(b, c), d = d || c.duration, b.duration = a.fx.off ? 0 : "number" == typeof d ? d : d in a.fx.speeds ? a.fx.speeds[d] : a.fx.speeds._default, b.complete = e || c.complete, b
        }

        function f(b) {
            return !b || "number" == typeof b || a.fx.speeds[b] ? !0 : "string" != typeof b || a.effects.effect[b] ? !1 : c && a.effects[b] ? !1 : !0
        }
        a.extend(a.effects, {
            version: "1.9.2",
            save: function (a, b) {
                for (var c = 0; b.length > c; c++) null !== b[c] && a.data(d + b[c], a[0].style[b[c]])
            },
            restore: function (a, c) {
                var e, f;
                for (f = 0; c.length > f; f++) null !== c[f] && (e = a.data(d + c[f]), e === b && (e = ""), a.css(c[f], e))
            },
            setMode: function (a, b) {
                return "toggle" === b && (b = a.is(":hidden") ? "show" : "hide"), b
            },
            getBaseline: function (a, b) {
                var c, d;
                switch (a[0]) {
                case "top":
                    c = 0;
                    break;
                case "middle":
                    c = .5;
                    break;
                case "bottom":
                    c = 1;
                    break;
                default:
                    c = a[0] / b.height
                }
                switch (a[1]) {
                case "left":
                    d = 0;
                    break;
                case "center":
                    d = .5;
                    break;
                case "right":
                    d = 1;
                    break;
                default:
                    d = a[1] / b.width
                }
                return {
                    x: d,
                    y: c
                }
            },
            createWrapper: function (b) {
                if (b.parent().is(".ui-effects-wrapper")) return b.parent();
                var c = {
                    width: b.outerWidth(!0),
                    height: b.outerHeight(!0),
                    "float": b.css("float")
                }, d = a("<div></div>").addClass("ui-effects-wrapper").css({
                        fontSize: "100%",
                        background: "transparent",
                        border: "none",
                        margin: 0,
                        padding: 0
                    }),
                    e = {
                        width: b.width(),
                        height: b.height()
                    }, f = document.activeElement;
                try {
                    f.id
                } catch (g) {
                    f = document.body
                }
                return b.wrap(d), (b[0] === f || a.contains(b[0], f)) && a(f).focus(), d = b.parent(), "static" === b.css("position") ? (d.css({
                    position: "relative"
                }), b.css({
                    position: "relative"
                })) : (a.extend(c, {
                    position: b.css("position"),
                    zIndex: b.css("z-index")
                }), a.each(["top", "left", "bottom", "right"], function (a, d) {
                    c[d] = b.css(d), isNaN(parseInt(c[d], 10)) && (c[d] = "auto")
                }), b.css({
                    position: "relative",
                    top: 0,
                    left: 0,
                    right: "auto",
                    bottom: "auto"
                })), b.css(e), d.css(c).show()
            },
            removeWrapper: function (b) {
                var c = document.activeElement;
                return b.parent().is(".ui-effects-wrapper") && (b.parent().replaceWith(b), (b[0] === c || a.contains(b[0], c)) && a(c).focus()), b
            },
            setTransition: function (b, c, d, e) {
                return e = e || {}, a.each(c, function (a, c) {
                    var f = b.cssUnit(c);
                    f[0] > 0 && (e[c] = f[0] * d + f[1])
                }), e
            }
        }), a.fn.extend({
            effect: function () {
                function b(b) {
                    function c() {
                        a.isFunction(f) && f.call(e[0]), a.isFunction(b) && b()
                    }
                    var e = a(this),
                        f = d.complete,
                        g = d.mode;
                    (e.is(":hidden") ? "hide" === g : "show" === g) ? c() : h.call(e[0], d, c)
                }
                var d = e.apply(this, arguments),
                    f = d.mode,
                    g = d.queue,
                    h = a.effects.effect[d.effect],
                    i = !h && c && a.effects[d.effect];
                return a.fx.off || !h && !i ? f ? this[f](d.duration, d.complete) : this.each(function () {
                    d.complete && d.complete.call(this)
                }) : h ? g === !1 ? this.each(b) : this.queue(g || "fx", b) : i.call(this, {
                    options: d,
                    duration: d.duration,
                    callback: d.complete,
                    mode: d.mode
                })
            },
            _show: a.fn.show,
            show: function (a) {
                if (f(a)) return this._show.apply(this, arguments);
                var b = e.apply(this, arguments);
                return b.mode = "show", this.effect.call(this, b)
            },
            _hide: a.fn.hide,
            hide: function (a) {
                if (f(a)) return this._hide.apply(this, arguments);
                var b = e.apply(this, arguments);
                return b.mode = "hide", this.effect.call(this, b)
            },
            __toggle: a.fn.toggle,
            toggle: function (b) {
                if (f(b) || "boolean" == typeof b || a.isFunction(b)) return this.__toggle.apply(this, arguments);
                var c = e.apply(this, arguments);
                return c.mode = "toggle", this.effect.call(this, c)
            },
            cssUnit: function (b) {
                var c = this.css(b),
                    d = [];
                return a.each(["em", "px", "%", "pt"], function (a, b) {
                    c.indexOf(b) > 0 && (d = [parseFloat(c), b])
                }), d
            }
        })
    }(),
    function () {
        var b = {};
        a.each(["Quad", "Cubic", "Quart", "Quint", "Expo"], function (a, c) {
            b[c] = function (b) {
                return Math.pow(b, a + 2)
            }
        }), a.extend(b, {
            Sine: function (a) {
                return 1 - Math.cos(a * Math.PI / 2)
            },
            Circ: function (a) {
                return 1 - Math.sqrt(1 - a * a)
            },
            Elastic: function (a) {
                return 0 === a || 1 === a ? a : -Math.pow(2, 8 * (a - 1)) * Math.sin((80 * (a - 1) - 7.5) * Math.PI / 15)
            },
            Back: function (a) {
                return a * a * (3 * a - 2)
            },
            Bounce: function (a) {
                for (var b, c = 4;
                    ((b = Math.pow(2, --c)) - 1) / 11 > a;);
                return 1 / Math.pow(4, 3 - c) - 7.5625 * Math.pow((3 * b - 2) / 22 - a, 2)
            }
        }), a.each(b, function (b, c) {
            a.easing["easeIn" + b] = c, a.easing["easeOut" + b] = function (a) {
                return 1 - c(1 - a)
            }, a.easing["easeInOut" + b] = function (a) {
                return .5 > a ? c(2 * a) / 2 : 1 - c(-2 * a + 2) / 2
            }
        })
    }()
}(jQuery);
! function (a) {
    var b = /up|down|vertical/,
        c = /up|left|vertical|horizontal/;
    a.effects.effect.blind = function (d, e) {
        var f, g, h, i = a(this),
            j = ["position", "top", "bottom", "left", "right", "height", "width"],
            k = a.effects.setMode(i, d.mode || "hide"),
            l = d.direction || "up",
            m = b.test(l),
            n = m ? "height" : "width",
            o = m ? "top" : "left",
            p = c.test(l),
            q = {}, r = "show" === k;
        i.parent().is(".ui-effects-wrapper") ? a.effects.save(i.parent(), j) : a.effects.save(i, j), i.show(), f = a.effects.createWrapper(i).css({
            overflow: "hidden"
        }), g = f[n](), h = parseFloat(f.css(o)) || 0, q[n] = r ? g : 0, p || (i.css(m ? "bottom" : "right", 0).css(m ? "top" : "left", "auto").css({
            position: "absolute"
        }), q[o] = r ? h : g + h), r && (f.css(n, 0), p || f.css(o, h + g)), f.animate(q, {
            duration: d.duration,
            easing: d.easing,
            queue: !1,
            complete: function () {
                "hide" === k && i.hide(), a.effects.restore(i, j), a.effects.removeWrapper(i), e()
            }
        })
    }
}(jQuery);
! function (a) {
    a.effects.effect.bounce = function (b, c) {
        var d, e, f, g = a(this),
            h = ["position", "top", "bottom", "left", "right", "height", "width"],
            i = a.effects.setMode(g, b.mode || "effect"),
            j = "hide" === i,
            k = "show" === i,
            l = b.direction || "up",
            m = b.distance,
            n = b.times || 5,
            o = 2 * n + (k || j ? 1 : 0),
            p = b.duration / o,
            q = b.easing,
            r = "up" === l || "down" === l ? "top" : "left",
            s = "up" === l || "left" === l,
            t = g.queue(),
            u = t.length;
        for ((k || j) && h.push("opacity"), a.effects.save(g, h), g.show(), a.effects.createWrapper(g), m || (m = g["top" === r ? "outerHeight" : "outerWidth"]() / 3), k && (f = {
            opacity: 1
        }, f[r] = 0, g.css("opacity", 0).css(r, s ? 2 * -m : 2 * m).animate(f, p, q)), j && (m /= Math.pow(2, n - 1)), f = {}, f[r] = 0, d = 0; n > d; d++) e = {}, e[r] = (s ? "-=" : "+=") + m, g.animate(e, p, q).animate(f, p, q), m = j ? 2 * m : m / 2;
        j && (e = {
            opacity: 0
        }, e[r] = (s ? "-=" : "+=") + m, g.animate(e, p, q)), g.queue(function () {
            j && g.hide(), a.effects.restore(g, h), a.effects.removeWrapper(g), c()
        }), u > 1 && t.splice.apply(t, [1, 0].concat(t.splice(u, o + 1))), g.dequeue()
    }
}(jQuery);
! function (a) {
    a.effects.effect.clip = function (b, c) {
        var d, e, f, g = a(this),
            h = ["position", "top", "bottom", "left", "right", "height", "width"],
            i = a.effects.setMode(g, b.mode || "hide"),
            j = "show" === i,
            k = b.direction || "vertical",
            l = "vertical" === k,
            m = l ? "height" : "width",
            n = l ? "top" : "left",
            o = {};
        a.effects.save(g, h), g.show(), d = a.effects.createWrapper(g).css({
            overflow: "hidden"
        }), e = "IMG" === g[0].tagName ? d : g, f = e[m](), j && (e.css(m, 0), e.css(n, f / 2)), o[m] = j ? f : 0, o[n] = j ? 0 : f / 2, e.animate(o, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: function () {
                j || g.hide(), a.effects.restore(g, h), a.effects.removeWrapper(g), c()
            }
        })
    }
}(jQuery);
! function (a) {
    a.effects.effect.drop = function (b, c) {
        var d, e = a(this),
            f = ["position", "top", "bottom", "left", "right", "opacity", "height", "width"],
            g = a.effects.setMode(e, b.mode || "hide"),
            h = "show" === g,
            i = b.direction || "left",
            j = "up" === i || "down" === i ? "top" : "left",
            k = "up" === i || "left" === i ? "pos" : "neg",
            l = {
                opacity: h ? 1 : 0
            };
        a.effects.save(e, f), e.show(), a.effects.createWrapper(e), d = b.distance || e["top" === j ? "outerHeight" : "outerWidth"](!0) / 2, h && e.css("opacity", 0).css(j, "pos" === k ? -d : d), l[j] = (h ? "pos" === k ? "+=" : "-=" : "pos" === k ? "-=" : "+=") + d, e.animate(l, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: function () {
                "hide" === g && e.hide(), a.effects.restore(e, f), a.effects.removeWrapper(e), c()
            }
        })
    }
}(jQuery);
! function (a) {
    a.effects.effect.explode = function (b, c) {
        function d() {
            t.push(this), t.length === l * m && e()
        }

        function e() {
            n.css({
                visibility: "visible"
            }), a(t).remove(), p || n.hide(), c()
        }
        var f, g, h, i, j, k, l = b.pieces ? Math.round(Math.sqrt(b.pieces)) : 3,
            m = l,
            n = a(this),
            o = a.effects.setMode(n, b.mode || "hide"),
            p = "show" === o,
            q = n.show().css("visibility", "hidden").offset(),
            r = Math.ceil(n.outerWidth() / m),
            s = Math.ceil(n.outerHeight() / l),
            t = [];
        for (f = 0; l > f; f++)
            for (i = q.top + f * s, k = f - (l - 1) / 2, g = 0; m > g; g++) h = q.left + g * r, j = g - (m - 1) / 2, n.clone().appendTo("body").wrap("<div></div>").css({
                position: "absolute",
                visibility: "visible",
                left: -g * r,
                top: -f * s
            }).parent().addClass("ui-effects-explode").css({
                position: "absolute",
                overflow: "hidden",
                width: r,
                height: s,
                left: h + (p ? j * r : 0),
                top: i + (p ? k * s : 0),
                opacity: p ? 0 : 1
            }).animate({
                left: h + (p ? 0 : j * r),
                top: i + (p ? 0 : k * s),
                opacity: p ? 1 : 0
            }, b.duration || 500, b.easing, d)
    }
}(jQuery);
! function (a) {
    a.effects.effect.fade = function (b, c) {
        var d = a(this),
            e = a.effects.setMode(d, b.mode || "toggle");
        d.animate({
            opacity: e
        }, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: c
        })
    }
}(jQuery);
! function (a) {
    a.effects.effect.fold = function (b, c) {
        var d, e, f = a(this),
            g = ["position", "top", "bottom", "left", "right", "height", "width"],
            h = a.effects.setMode(f, b.mode || "hide"),
            i = "show" === h,
            j = "hide" === h,
            k = b.size || 15,
            l = /([0-9]+)%/.exec(k),
            m = !! b.horizFirst,
            n = i !== m,
            o = n ? ["width", "height"] : ["height", "width"],
            p = b.duration / 2,
            q = {}, r = {};
        a.effects.save(f, g), f.show(), d = a.effects.createWrapper(f).css({
            overflow: "hidden"
        }), e = n ? [d.width(), d.height()] : [d.height(), d.width()], l && (k = parseInt(l[1], 10) / 100 * e[j ? 0 : 1]), i && d.css(m ? {
            height: 0,
            width: k
        } : {
            height: k,
            width: 0
        }), q[o[0]] = i ? e[0] : k, r[o[1]] = i ? e[1] : 0, d.animate(q, p, b.easing).animate(r, p, b.easing, function () {
            j && f.hide(), a.effects.restore(f, g), a.effects.removeWrapper(f), c()
        })
    }
}(jQuery);
! function (a) {
    a.effects.effect.highlight = function (b, c) {
        var d = a(this),
            e = ["backgroundImage", "backgroundColor", "opacity"],
            f = a.effects.setMode(d, b.mode || "show"),
            g = {
                backgroundColor: d.css("backgroundColor")
            };
        "hide" === f && (g.opacity = 0), a.effects.save(d, e), d.show().css({
            backgroundImage: "none",
            backgroundColor: b.color || "#ffff99"
        }).animate(g, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: function () {
                "hide" === f && d.hide(), a.effects.restore(d, e), c()
            }
        })
    }
}(jQuery);
! function (a) {
    a.effects.effect.pulsate = function (b, c) {
        var d, e = a(this),
            f = a.effects.setMode(e, b.mode || "show"),
            g = "show" === f,
            h = "hide" === f,
            i = g || "hide" === f,
            j = 2 * (b.times || 5) + (i ? 1 : 0),
            k = b.duration / j,
            l = 0,
            m = e.queue(),
            n = m.length;
        for ((g || !e.is(":visible")) && (e.css("opacity", 0).show(), l = 1), d = 1; j > d; d++) e.animate({
            opacity: l
        }, k, b.easing), l = 1 - l;
        e.animate({
            opacity: l
        }, k, b.easing), e.queue(function () {
            h && e.hide(), c()
        }), n > 1 && m.splice.apply(m, [1, 0].concat(m.splice(n, j + 1))), e.dequeue()
    }
}(jQuery);
! function (a) {
    a.effects.effect.puff = function (b, c) {
        var d = a(this),
            e = a.effects.setMode(d, b.mode || "hide"),
            f = "hide" === e,
            g = parseInt(b.percent, 10) || 150,
            h = g / 100,
            i = {
                height: d.height(),
                width: d.width(),
                outerHeight: d.outerHeight(),
                outerWidth: d.outerWidth()
            };
        a.extend(b, {
            effect: "scale",
            queue: !1,
            fade: !0,
            mode: e,
            complete: c,
            percent: f ? g : 100,
            from: f ? i : {
                height: i.height * h,
                width: i.width * h,
                outerHeight: i.outerHeight * h,
                outerWidth: i.outerWidth * h
            }
        }), d.effect(b)
    }, a.effects.effect.scale = function (b, c) {
        var d = a(this),
            e = a.extend(!0, {}, b),
            f = a.effects.setMode(d, b.mode || "effect"),
            g = parseInt(b.percent, 10) || (0 === parseInt(b.percent, 10) ? 0 : "hide" === f ? 0 : 100),
            h = b.direction || "both",
            i = b.origin,
            j = {
                height: d.height(),
                width: d.width(),
                outerHeight: d.outerHeight(),
                outerWidth: d.outerWidth()
            }, k = {
                y: "horizontal" !== h ? g / 100 : 1,
                x: "vertical" !== h ? g / 100 : 1
            };
        e.effect = "size", e.queue = !1, e.complete = c, "effect" !== f && (e.origin = i || ["middle", "center"], e.restore = !0), e.from = b.from || ("show" === f ? {
            height: 0,
            width: 0,
            outerHeight: 0,
            outerWidth: 0
        } : j), e.to = {
            height: j.height * k.y,
            width: j.width * k.x,
            outerHeight: j.outerHeight * k.y,
            outerWidth: j.outerWidth * k.x
        }, e.fade && ("show" === f && (e.from.opacity = 0, e.to.opacity = 1), "hide" === f && (e.from.opacity = 1, e.to.opacity = 0)), d.effect(e)
    }, a.effects.effect.size = function (b, c) {
        var d, e, f, g = a(this),
            h = ["position", "top", "bottom", "left", "right", "width", "height", "overflow", "opacity"],
            i = ["position", "top", "bottom", "left", "right", "overflow", "opacity"],
            j = ["width", "height", "overflow"],
            k = ["fontSize"],
            l = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"],
            m = ["borderLeftWidth", "borderRightWidth", "paddingLeft", "paddingRight"],
            n = a.effects.setMode(g, b.mode || "effect"),
            o = b.restore || "effect" !== n,
            p = b.scale || "both",
            q = b.origin || ["middle", "center"],
            r = g.css("position"),
            s = o ? h : i,
            t = {
                height: 0,
                width: 0,
                outerHeight: 0,
                outerWidth: 0
            };
        "show" === n && g.show(), d = {
            height: g.height(),
            width: g.width(),
            outerHeight: g.outerHeight(),
            outerWidth: g.outerWidth()
        }, "toggle" === b.mode && "show" === n ? (g.from = b.to || t, g.to = b.from || d) : (g.from = b.from || ("show" === n ? t : d), g.to = b.to || ("hide" === n ? t : d)), f = {
            from: {
                y: g.from.height / d.height,
                x: g.from.width / d.width
            },
            to: {
                y: g.to.height / d.height,
                x: g.to.width / d.width
            }
        }, ("box" === p || "both" === p) && (f.from.y !== f.to.y && (s = s.concat(l), g.from = a.effects.setTransition(g, l, f.from.y, g.from), g.to = a.effects.setTransition(g, l, f.to.y, g.to)), f.from.x !== f.to.x && (s = s.concat(m), g.from = a.effects.setTransition(g, m, f.from.x, g.from), g.to = a.effects.setTransition(g, m, f.to.x, g.to))), ("content" === p || "both" === p) && f.from.y !== f.to.y && (s = s.concat(k).concat(j), g.from = a.effects.setTransition(g, k, f.from.y, g.from), g.to = a.effects.setTransition(g, k, f.to.y, g.to)), a.effects.save(g, s), g.show(), a.effects.createWrapper(g), g.css("overflow", "hidden").css(g.from), q && (e = a.effects.getBaseline(q, d), g.from.top = (d.outerHeight - g.outerHeight()) * e.y, g.from.left = (d.outerWidth - g.outerWidth()) * e.x, g.to.top = (d.outerHeight - g.to.outerHeight) * e.y, g.to.left = (d.outerWidth - g.to.outerWidth) * e.x), g.css(g.from), ("content" === p || "both" === p) && (l = l.concat(["marginTop", "marginBottom"]).concat(k), m = m.concat(["marginLeft", "marginRight"]), j = h.concat(l).concat(m), g.find("*[width]").each(function () {
            var c = a(this),
                d = {
                    height: c.height(),
                    width: c.width(),
                    outerHeight: c.outerHeight(),
                    outerWidth: c.outerWidth()
                };
            o && a.effects.save(c, j), c.from = {
                height: d.height * f.from.y,
                width: d.width * f.from.x,
                outerHeight: d.outerHeight * f.from.y,
                outerWidth: d.outerWidth * f.from.x
            }, c.to = {
                height: d.height * f.to.y,
                width: d.width * f.to.x,
                outerHeight: d.height * f.to.y,
                outerWidth: d.width * f.to.x
            }, f.from.y !== f.to.y && (c.from = a.effects.setTransition(c, l, f.from.y, c.from), c.to = a.effects.setTransition(c, l, f.to.y, c.to)), f.from.x !== f.to.x && (c.from = a.effects.setTransition(c, m, f.from.x, c.from), c.to = a.effects.setTransition(c, m, f.to.x, c.to)), c.css(c.from), c.animate(c.to, b.duration, b.easing, function () {
                o && a.effects.restore(c, j)
            })
        })), g.animate(g.to, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: function () {
                0 === g.to.opacity && g.css("opacity", g.from.opacity), "hide" === n && g.hide(), a.effects.restore(g, s), o || ("static" === r ? g.css({
                    position: "relative",
                    top: g.to.top,
                    left: g.to.left
                }) : a.each(["top", "left"], function (a, b) {
                    g.css(b, function (b, c) {
                        var d = parseInt(c, 10),
                            e = a ? g.to.left : g.to.top;
                        return "auto" === c ? e + "px" : d + e + "px"
                    })
                })), a.effects.removeWrapper(g), c()
            }
        })
    }
}(jQuery);
! function (a) {
    a.effects.effect.shake = function (b, c) {
        var d, e = a(this),
            f = ["position", "top", "bottom", "left", "right", "height", "width"],
            g = a.effects.setMode(e, b.mode || "effect"),
            h = b.direction || "left",
            i = b.distance || 20,
            j = b.times || 3,
            k = 2 * j + 1,
            l = Math.round(b.duration / k),
            m = "up" === h || "down" === h ? "top" : "left",
            n = "up" === h || "left" === h,
            o = {}, p = {}, q = {}, r = e.queue(),
            s = r.length;
        for (a.effects.save(e, f), e.show(), a.effects.createWrapper(e), o[m] = (n ? "-=" : "+=") + i, p[m] = (n ? "+=" : "-=") + 2 * i, q[m] = (n ? "-=" : "+=") + 2 * i, e.animate(o, l, b.easing), d = 1; j > d; d++) e.animate(p, l, b.easing).animate(q, l, b.easing);
        e.animate(p, l, b.easing).animate(o, l / 2, b.easing).queue(function () {
            "hide" === g && e.hide(), a.effects.restore(e, f), a.effects.removeWrapper(e), c()
        }), s > 1 && r.splice.apply(r, [1, 0].concat(r.splice(s, k + 1))), e.dequeue()
    }
}(jQuery);
! function (a) {
    a.effects.effect.slide = function (b, c) {
        var d, e = a(this),
            f = ["position", "top", "bottom", "left", "right", "width", "height"],
            g = a.effects.setMode(e, b.mode || "show"),
            h = "show" === g,
            i = b.direction || "left",
            j = "up" === i || "down" === i ? "top" : "left",
            k = "up" === i || "left" === i,
            l = {};
        a.effects.save(e, f), e.show(), d = b.distance || e["top" === j ? "outerHeight" : "outerWidth"](!0), a.effects.createWrapper(e).css({
            overflow: "hidden"
        }), h && e.css(j, k ? isNaN(d) ? "-" + d : -d : d), l[j] = (h ? k ? "+=" : "-=" : k ? "-=" : "+=") + d, e.animate(l, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: function () {
                "hide" === g && e.hide(), a.effects.restore(e, f), a.effects.removeWrapper(e), c()
            }
        })
    }
}(jQuery);
! function (a) {
    a.effects.effect.transfer = function (b, c) {
        var d = a(this),
            e = a(b.to),
            f = "fixed" === e.css("position"),
            g = a("body"),
            h = f ? g.scrollTop() : 0,
            i = f ? g.scrollLeft() : 0,
            j = e.offset(),
            k = {
                top: j.top - h,
                left: j.left - i,
                height: e.innerHeight(),
                width: e.innerWidth()
            }, l = d.offset(),
            m = a('<div class="ui-effects-transfer"></div>').appendTo(document.body).addClass(b.className).css({
                top: l.top - h,
                left: l.left - i,
                height: d.innerHeight(),
                width: d.innerWidth(),
                position: f ? "fixed" : "absolute"
            }).animate(k, b.duration, b.easing, function () {
                m.remove(), c()
            })
    }
}(jQuery);
! function (a) {
    function b(a, b) {
        if (!(a.originalEvent.touches.length > 1)) {
            a.preventDefault();
            var c = a.originalEvent.changedTouches[0],
                d = document.createEvent("MouseEvents");
            d.initMouseEvent(b, !0, !0, window, 1, c.screenX, c.screenY, c.clientX, c.clientY, !1, !1, !1, !1, 0, null);
            a.target.dispatchEvent(d)
        }
    }
    a.support.touch = "ontouchend" in document;
    if (a.support.touch) {
        var c, d = a.ui.mouse.prototype,
            e = d._mouseInit;
        d._touchStart = function (a) {
            var d = this;
            if (!c && d._mouseCapture(a.originalEvent.changedTouches[0])) {
                c = !0;
                d._touchMoved = !1;
                b(a, "mouseover");
                b(a, "mousemove");
                b(a, "mousedown")
            }
        };
        d._touchMove = function (a) {
            if (c) {
                this._touchMoved = !0;
                b(a, "mousemove")
            }
        };
        d._touchEnd = function (a) {
            if (c) {
                b(a, "mouseup");
                b(a, "mouseout");
                this._touchMoved || b(a, "click");
                c = !1
            }
        };
        d._mouseInit = function () {
            var b = this;
            b.element.bind("touchstart", a.proxy(b, "_touchStart")).bind("touchmove", a.proxy(b, "_touchMove")).bind("touchend", a.proxy(b, "_touchEnd"));
            e.call(b)
        }
    }
}(jQuery);
! function () {
    function a(a) {
        var c = !1;
        return function () {
            if (c) throw new Error("Callback was already called.");
            c = !0;
            a.apply(b, arguments)
        }
    }
    var b, c, d = {};
    b = this;
    null != b && (c = b.async);
    d.noConflict = function () {
        b.async = c;
        return d
    };
    var e = function (a, b) {
        if (a.forEach) return a.forEach(b);
        for (var c = 0; c < a.length; c += 1) b(a[c], c, a)
    }, f = function (a, b) {
            if (a.map) return a.map(b);
            var c = [];
            e(a, function (a, d, e) {
                c.push(b(a, d, e))
            });
            return c
        }, g = function (a, b, c) {
            if (a.reduce) return a.reduce(b, c);
            e(a, function (a, d, e) {
                c = b(c, a, d, e)
            });
            return c
        }, h = function (a) {
            if (Object.keys) return Object.keys(a);
            var b = [];
            for (var c in a) a.hasOwnProperty(c) && b.push(c);
            return b
        };
    if ("undefined" != typeof process && process.nextTick) {
        d.nextTick = process.nextTick;
        d.setImmediate = "undefined" != typeof setImmediate ? setImmediate : d.nextTick
    } else if ("function" == typeof setImmediate) {
        d.nextTick = function (a) {
            setImmediate(a)
        };
        d.setImmediate = d.nextTick
    } else {
        d.nextTick = function (a) {
            setTimeout(a, 0)
        };
        d.setImmediate = d.nextTick
    }
    d.each = function (b, c, d) {
        d = d || function () {};
        if (!b.length) return d();
        var f = 0;
        e(b, function (e) {
            c(e, a(function (a) {
                if (a) {
                    d(a);
                    d = function () {}
                } else {
                    f += 1;
                    f >= b.length && d(null)
                }
            }))
        })
    };
    d.forEach = d.each;
    d.eachSeries = function (a, b, c) {
        c = c || function () {};
        if (!a.length) return c();
        var d = 0,
            e = function () {
                b(a[d], function (b) {
                    if (b) {
                        c(b);
                        c = function () {}
                    } else {
                        d += 1;
                        d >= a.length ? c(null) : e()
                    }
                })
            };
        e()
    };
    d.forEachSeries = d.eachSeries;
    d.eachLimit = function (a, b, c, d) {
        var e = i(b);
        e.apply(null, [a, c, d])
    };
    d.forEachLimit = d.eachLimit;
    var i = function (a) {
        return function (b, c, d) {
            d = d || function () {};
            if (!b.length || 0 >= a) return d();
            var e = 0,
                f = 0,
                g = 0;
            ! function h() {
                if (e >= b.length) return d();
                for (; a > g && f < b.length;) {
                    f += 1;
                    g += 1;
                    c(b[f - 1], function (a) {
                        if (a) {
                            d(a);
                            d = function () {}
                        } else {
                            e += 1;
                            g -= 1;
                            e >= b.length ? d() : h()
                        }
                    })
                }
            }()
        }
    }, j = function (a) {
            return function () {
                var b = Array.prototype.slice.call(arguments);
                return a.apply(null, [d.each].concat(b))
            }
        }, k = function (a, b) {
            return function () {
                var c = Array.prototype.slice.call(arguments);
                return b.apply(null, [i(a)].concat(c))
            }
        }, l = function (a) {
            return function () {
                var b = Array.prototype.slice.call(arguments);
                return a.apply(null, [d.eachSeries].concat(b))
            }
        }, m = function (a, b, c, d) {
            var e = [];
            b = f(b, function (a, b) {
                return {
                    index: b,
                    value: a
                }
            });
            a(b, function (a, b) {
                c(a.value, function (c, d) {
                    e[a.index] = d;
                    b(c)
                })
            }, function (a) {
                d(a, e)
            })
        };
    d.map = j(m);
    d.mapSeries = l(m);
    d.mapLimit = function (a, b, c, d) {
        return n(b)(a, c, d)
    };
    var n = function (a) {
        return k(a, m)
    };
    d.reduce = function (a, b, c, e) {
        d.eachSeries(a, function (a, d) {
            c(b, a, function (a, c) {
                b = c;
                d(a)
            })
        }, function (a) {
            e(a, b)
        })
    };
    d.inject = d.reduce;
    d.foldl = d.reduce;
    d.reduceRight = function (a, b, c, e) {
        var g = f(a, function (a) {
            return a
        }).reverse();
        d.reduce(g, b, c, e)
    };
    d.foldr = d.reduceRight;
    var o = function (a, b, c, d) {
        var e = [];
        b = f(b, function (a, b) {
            return {
                index: b,
                value: a
            }
        });
        a(b, function (a, b) {
            c(a.value, function (c) {
                c && e.push(a);
                b()
            })
        }, function () {
            d(f(e.sort(function (a, b) {
                return a.index - b.index
            }), function (a) {
                return a.value
            }))
        })
    };
    d.filter = j(o);
    d.filterSeries = l(o);
    d.select = d.filter;
    d.selectSeries = d.filterSeries;
    var p = function (a, b, c, d) {
        var e = [];
        b = f(b, function (a, b) {
            return {
                index: b,
                value: a
            }
        });
        a(b, function (a, b) {
            c(a.value, function (c) {
                c || e.push(a);
                b()
            })
        }, function () {
            d(f(e.sort(function (a, b) {
                return a.index - b.index
            }), function (a) {
                return a.value
            }))
        })
    };
    d.reject = j(p);
    d.rejectSeries = l(p);
    var q = function (a, b, c, d) {
        a(b, function (a, b) {
            c(a, function (c) {
                if (c) {
                    d(a);
                    d = function () {}
                } else b()
            })
        }, function () {
            d()
        })
    };
    d.detect = j(q);
    d.detectSeries = l(q);
    d.some = function (a, b, c) {
        d.each(a, function (a, d) {
            b(a, function (a) {
                if (a) {
                    c(!0);
                    c = function () {}
                }
                d()
            })
        }, function () {
            c(!1)
        })
    };
    d.any = d.some;
    d.every = function (a, b, c) {
        d.each(a, function (a, d) {
            b(a, function (a) {
                if (!a) {
                    c(!1);
                    c = function () {}
                }
                d()
            })
        }, function () {
            c(!0)
        })
    };
    d.all = d.every;
    d.sortBy = function (a, b, c) {
        d.map(a, function (a, c) {
            b(a, function (b, d) {
                b ? c(b) : c(null, {
                    value: a,
                    criteria: d
                })
            })
        }, function (a, b) {
            if (a) return c(a);
            var d = function (a, b) {
                var c = a.criteria,
                    d = b.criteria;
                return d > c ? -1 : c > d ? 1 : 0
            };
            c(null, f(b.sort(d), function (a) {
                return a.value
            }))
        })
    };
    d.auto = function (a, b) {
        b = b || function () {};
        var c = h(a);
        if (!c.length) return b(null);
        var f = {}, i = [],
            j = function (a) {
                i.unshift(a)
            }, k = function (a) {
                for (var b = 0; b < i.length; b += 1)
                    if (i[b] === a) {
                        i.splice(b, 1);
                        return
                    }
            }, l = function () {
                e(i.slice(0), function (a) {
                    a()
                })
            };
        j(function () {
            if (h(f).length === c.length) {
                b(null, f);
                b = function () {}
            }
        });
        e(c, function (c) {
            var i = a[c] instanceof Function ? [a[c]] : a[c],
                m = function (a) {
                    var g = Array.prototype.slice.call(arguments, 1);
                    g.length <= 1 && (g = g[0]);
                    if (a) {
                        var i = {};
                        e(h(f), function (a) {
                            i[a] = f[a]
                        });
                        i[c] = g;
                        b(a, i);
                        b = function () {}
                    } else {
                        f[c] = g;
                        d.setImmediate(l)
                    }
                }, n = i.slice(0, Math.abs(i.length - 1)) || [],
                o = function () {
                    return g(n, function (a, b) {
                        return a && f.hasOwnProperty(b)
                    }, !0) && !f.hasOwnProperty(c)
                };
            if (o()) i[i.length - 1](m, f);
            else {
                var p = function () {
                    if (o()) {
                        k(p);
                        i[i.length - 1](m, f)
                    }
                };
                j(p)
            }
        })
    };
    d.waterfall = function (a, b) {
        b = b || function () {};
        if (a.constructor !== Array) {
            var c = new Error("First argument to waterfall must be an array of functions");
            return b(c)
        }
        if (!a.length) return b();
        var e = function (a) {
            return function (c) {
                if (c) {
                    b.apply(null, arguments);
                    b = function () {}
                } else {
                    var f = Array.prototype.slice.call(arguments, 1),
                        g = a.next();
                    g ? f.push(e(g)) : f.push(b);
                    d.setImmediate(function () {
                        a.apply(null, f)
                    })
                }
            }
        };
        e(d.iterator(a))()
    };
    var r = function (a, b, c) {
        c = c || function () {};
        if (b.constructor === Array) a.map(b, function (a, b) {
            a && a(function (a) {
                var c = Array.prototype.slice.call(arguments, 1);
                c.length <= 1 && (c = c[0]);
                b.call(null, a, c)
            })
        }, c);
        else {
            var d = {};
            a.each(h(b), function (a, c) {
                b[a](function (b) {
                    var e = Array.prototype.slice.call(arguments, 1);
                    e.length <= 1 && (e = e[0]);
                    d[a] = e;
                    c(b)
                })
            }, function (a) {
                c(a, d)
            })
        }
    };
    d.parallel = function (a, b) {
        r({
            map: d.map,
            each: d.each
        }, a, b)
    };
    d.parallelLimit = function (a, b, c) {
        r({
            map: n(b),
            each: i(b)
        }, a, c)
    };
    d.series = function (a, b) {
        b = b || function () {};
        if (a.constructor === Array) d.mapSeries(a, function (a, b) {
            a && a(function (a) {
                var c = Array.prototype.slice.call(arguments, 1);
                c.length <= 1 && (c = c[0]);
                b.call(null, a, c)
            })
        }, b);
        else {
            var c = {};
            d.eachSeries(h(a), function (b, d) {
                a[b](function (a) {
                    var e = Array.prototype.slice.call(arguments, 1);
                    e.length <= 1 && (e = e[0]);
                    c[b] = e;
                    d(a)
                })
            }, function (a) {
                b(a, c)
            })
        }
    };
    d.iterator = function (a) {
        var b = function (c) {
            var d = function () {
                a.length && a[c].apply(null, arguments);
                return d.next()
            };
            d.next = function () {
                return c < a.length - 1 ? b(c + 1) : null
            };
            return d
        };
        return b(0)
    };
    d.apply = function (a) {
        var b = Array.prototype.slice.call(arguments, 1);
        return function () {
            return a.apply(null, b.concat(Array.prototype.slice.call(arguments)))
        }
    };
    var s = function (a, b, c, d) {
        var e = [];
        a(b, function (a, b) {
            c(a, function (a, c) {
                e = e.concat(c || []);
                b(a)
            })
        }, function (a) {
            d(a, e)
        })
    };
    d.concat = j(s);
    d.concatSeries = l(s);
    d.whilst = function (a, b, c) {
        a() ? b(function (e) {
            if (e) return c(e);
            d.whilst(a, b, c);
            return void 0
        }) : c()
    };
    d.doWhilst = function (a, b, c) {
        a(function (e) {
            if (e) return c(e);
            b() ? d.doWhilst(a, b, c) : c();
            return void 0
        })
    };
    d.until = function (a, b, c) {
        a() ? c() : b(function (e) {
            if (e) return c(e);
            d.until(a, b, c);
            return void 0
        })
    };
    d.doUntil = function (a, b, c) {
        a(function (e) {
            if (e) return c(e);
            b() ? c() : d.doUntil(a, b, c);
            return void 0
        })
    };
    d.queue = function (b, c) {
        function f(a, b, f, g) {
            b.constructor !== Array && (b = [b]);
            e(b, function (b) {
                var e = {
                    data: b,
                    callback: "function" == typeof g ? g : null
                };
                f ? a.tasks.unshift(e) : a.tasks.push(e);
                a.saturated && a.tasks.length === c && a.saturated();
                d.setImmediate(a.process)
            })
        }
        void 0 === c && (c = 1);
        var g = 0,
            h = {
                tasks: [],
                concurrency: c,
                saturated: null,
                empty: null,
                drain: null,
                push: function (a, b) {
                    f(h, a, !1, b)
                },
                unshift: function (a, b) {
                    f(h, a, !0, b)
                },
                process: function () {
                    if (g < h.concurrency && h.tasks.length) {
                        var c = h.tasks.shift();
                        h.empty && 0 === h.tasks.length && h.empty();
                        g += 1;
                        var d = function () {
                            g -= 1;
                            c.callback && c.callback.apply(c, arguments);
                            h.drain && h.tasks.length + g === 0 && h.drain();
                            h.process()
                        }, e = a(d);
                        b(c.data, e)
                    }
                },
                length: function () {
                    return h.tasks.length
                },
                running: function () {
                    return g
                }
            };
        return h
    };
    d.cargo = function (a, b) {
        var c = !1,
            g = [],
            h = {
                tasks: g,
                payload: b,
                saturated: null,
                empty: null,
                drain: null,
                push: function (a, c) {
                    a.constructor !== Array && (a = [a]);
                    e(a, function (a) {
                        g.push({
                            data: a,
                            callback: "function" == typeof c ? c : null
                        });
                        h.saturated && g.length === b && h.saturated()
                    });
                    d.setImmediate(h.process)
                },
                process: function i() {
                    if (!c)
                        if (0 !== g.length) {
                            var d = "number" == typeof b ? g.splice(0, b) : g.splice(0),
                                j = f(d, function (a) {
                                    return a.data
                                });
                            h.empty && h.empty();
                            c = !0;
                            a(j, function () {
                                c = !1;
                                var a = arguments;
                                e(d, function (b) {
                                    b.callback && b.callback.apply(null, a)
                                });
                                i()
                            })
                        } else h.drain && h.drain()
                },
                length: function () {
                    return g.length
                },
                running: function () {
                    return c
                }
            };
        return h
    };
    var t = function (a) {
        return function (b) {
            var c = Array.prototype.slice.call(arguments, 1);
            b.apply(null, c.concat([
                function (b) {
                    var c = Array.prototype.slice.call(arguments, 1);
                    "undefined" != typeof console && (b ? console.error && console.error(b) : console[a] && e(c, function (b) {
                        console[a](b)
                    }))
                }
            ]))
        }
    };
    d.log = t("log");
    d.dir = t("dir");
    d.memoize = function (a, b) {
        var c = {}, d = {};
        b = b || function (a) {
            return a
        };
        var e = function () {
            var e = Array.prototype.slice.call(arguments),
                f = e.pop(),
                g = b.apply(null, e);
            if (g in c) f.apply(null, c[g]);
            else if (g in d) d[g].push(f);
            else {
                d[g] = [f];
                a.apply(null, e.concat([
                    function () {
                        c[g] = arguments;
                        var a = d[g];
                        delete d[g];
                        for (var b = 0, e = a.length; e > b; b++) a[b].apply(null, arguments)
                    }
                ]))
            }
        };
        e.memo = c;
        e.unmemoized = a;
        return e
    };
    d.unmemoize = function (a) {
        return function () {
            return (a.unmemoized || a).apply(null, arguments)
        }
    };
    d.times = function (a, b, c) {
        for (var e = [], f = 0; a > f; f++) e.push(f);
        return d.map(e, b, c)
    };
    d.timesSeries = function (a, b, c) {
        for (var e = [], f = 0; a > f; f++) e.push(f);
        return d.mapSeries(e, b, c)
    };
    d.compose = function () {
        var a = Array.prototype.reverse.call(arguments);
        return function () {
            var b = this,
                c = Array.prototype.slice.call(arguments),
                e = c.pop();
            d.reduce(a, c, function (a, c, d) {
                c.apply(b, a.concat([
                    function () {
                        var a = arguments[0],
                            b = Array.prototype.slice.call(arguments, 1);
                        d(a, b)
                    }
                ]))
            }, function (a, c) {
                e.apply(b, [a].concat(c))
            })
        }
    };
    var u = function (a, b) {
        var c = function () {
            var c = this,
                d = Array.prototype.slice.call(arguments),
                e = d.pop();
            return a(b, function (a, b) {
                a.apply(c, d.concat([b]))
            }, e)
        };
        if (arguments.length > 2) {
            var d = Array.prototype.slice.call(arguments, 2);
            return c.apply(this, d)
        }
        return c
    };
    d.applyEach = j(u);
    d.applyEachSeries = l(u);
    d.forever = function (a, b) {
        function c(d) {
            if (d) {
                if (b) return b(d);
                throw d
            }
            a(c)
        }
        c()
    };
    "undefined" != typeof module && module.exports ? module.exports = d : b.async = d
}();
var TCS = TCS || {};
! function (a, b) {
    "use strict";

    function c(a, b, c, d, e, f, g) {
        var h = a + (b & c | ~b & d) + e + g;
        return (h << f | h >>> 32 - f) + b
    }

    function d(a, b, c, d, e, f, g) {
        var h = a + (b & d | c & ~d) + e + g;
        return (h << f | h >>> 32 - f) + b
    }

    function e(a, b, c, d, e, f, g) {
        var h = a + (b ^ c ^ d) + e + g;
        return (h << f | h >>> 32 - f) + b
    }

    function f(a, b, c, d, e, f, g) {
        var h = a + (c ^ (b | ~d)) + e + g;
        return (h << f | h >>> 32 - f) + b
    }
    var g = b || {}, h = g.lib = {}, i = h.Base = function () {
            function a() {}
            return {
                extend: function (b) {
                    a.prototype = this;
                    var c = new a;
                    b && c.mixIn(b);
                    c.$super = this;
                    return c
                },
                create: function () {
                    var a = this.extend();
                    a.init.apply(a, arguments);
                    return a
                },
                init: function () {},
                mixIn: function (a) {
                    for (var b in a) a.hasOwnProperty(b) && (this[b] = a[b]);
                    a.hasOwnProperty("toString") && (this.toString = a.toString)
                },
                clone: function () {
                    return this.$super.extend(this)
                }
            }
        }(),
        j = h.WordArray = i.extend({
            init: function (a, b) {
                a = this.words = a || [];
                this.sigBytes = void 0 != b ? b : 4 * a.length
            },
            toString: function (a) {
                return (a || l).stringify(this)
            },
            concat: function (a) {
                var b = this.words,
                    c = a.words,
                    d = this.sigBytes,
                    e = a.sigBytes;
                this.clamp();
                if (d % 4)
                    for (var f = 0; e > f; f++) {
                        var g = c[f >>> 2] >>> 24 - f % 4 * 8 & 255;
                        b[d + f >>> 2] |= g << 24 - (d + f) % 4 * 8
                    } else if (c.length > 65535)
                        for (var f = 0; e > f; f += 4) b[d + f >>> 2] = c[f >>> 2];
                    else b.push.apply(b, c);
                this.sigBytes += e;
                return this
            },
            clamp: function () {
                var b = this.words,
                    c = this.sigBytes;
                b[c >>> 2] &= 4294967295 << 32 - c % 4 * 8;
                b.length = a.ceil(c / 4)
            },
            clone: function () {
                var a = i.clone.call(this);
                a.words = this.words.slice(0);
                return a
            },
            random: function (b) {
                for (var c = [], d = 0; b > d; d += 4) c.push(4294967296 * a.random() | 0);
                return j.create(c, b)
            }
        }),
        k = g.enc = {}, l = k.Hex = {
            stringify: function (a) {
                for (var b = a.words, c = a.sigBytes, d = [], e = 0; c > e; e++) {
                    var f = b[e >>> 2] >>> 24 - e % 4 * 8 & 255;
                    d.push((f >>> 4).toString(16));
                    d.push((15 & f).toString(16))
                }
                return d.join("")
            },
            parse: function (a) {
                for (var b = a.length, c = [], d = 0; b > d; d += 2) c[d >>> 3] |= parseInt(a.substr(d, 2), 16) << 24 - d % 8 * 4;
                return j.create(c, b / 2)
            }
        }, m = k.Latin1 = {
            stringify: function (a) {
                for (var b = a.words, c = a.sigBytes, d = [], e = 0; c > e; e++) {
                    var f = b[e >>> 2] >>> 24 - e % 4 * 8 & 255;
                    d.push(String.fromCharCode(f))
                }
                return d.join("")
            },
            parse: function (a) {
                for (var b = a.length, c = [], d = 0; b > d; d++) c[d >>> 2] |= (255 & a.charCodeAt(d)) << 24 - d % 4 * 8;
                return j.create(c, b)
            }
        }, n = k.Utf8 = {
            stringify: function (a) {
                try {
                    return decodeURIComponent(escape(m.stringify(a)))
                } catch (b) {
                    throw new Error("Malformed UTF-8 data")
                }
            },
            parse: function (a) {
                return m.parse(unescape(encodeURIComponent(a)))
            }
        }, o = h.BufferedBlockAlgorithm = i.extend({
            reset: function () {
                this._data = j.create();
                this._nDataBytes = 0
            },
            _append: function (a) {
                "string" == typeof a && (a = n.parse(a));
                this._data.concat(a);
                this._nDataBytes += a.sigBytes
            },
            _process: function (b) {
                var c = this._data,
                    d = c.words,
                    e = c.sigBytes,
                    f = this.blockSize,
                    g = 4 * f,
                    h = e / g;
                h = b ? a.ceil(h) : a.max((0 | h) - this._minBufferSize, 0);
                var i = h * f,
                    k = a.min(4 * i, e);
                if (i) {
                    for (var l = 0; i > l; l += f) this._doProcessBlock(d, l);
                    var m = d.splice(0, i);
                    c.sigBytes -= k
                }
                return j.create(m, k)
            },
            clone: function () {
                var a = i.clone.call(this);
                a._data = this._data.clone();
                return a
            },
            _minBufferSize: 0
        }),
        p = h.Hasher = o.extend({
            init: function () {
                this.reset()
            },
            reset: function () {
                o.reset.call(this);
                this._doReset()
            },
            update: function (a) {
                this._append(a);
                this._process();
                return this
            },
            finalize: function (a) {
                a && this._append(a);
                this._doFinalize();
                return this._hash
            },
            clone: function () {
                var a = o.clone.call(this);
                a._hash = this._hash.clone();
                return a
            },
            blockSize: 16,
            _createHelper: function (a) {
                return function (b, c) {
                    return a.create(c).finalize(b)
                }
            },
            _createHmacHelper: function (a) {
                return function (b, c) {
                    return C_algo.HMAC.create(a, c).finalize(b)
                }
            }
        }),
        q = [];
    ! function () {
        for (var b = 0; 64 > b; b++) q[b] = 4294967296 * a.abs(a.sin(b + 1)) | 0
    }();
    var r = b.MD5 = p.extend({
        _doReset: function () {
            this._hash = j.create([1732584193, 4023233417, 2562383102, 271733878])
        },
        _doProcessBlock: function (a, b) {
            for (var g = 0; 16 > g; g++) {
                var h = b + g,
                    i = a[h];
                a[h] = 16711935 & (i << 8 | i >>> 24) | 4278255360 & (i << 24 | i >>> 8)
            }
            for (var j = this._hash.words, k = j[0], l = j[1], m = j[2], n = j[3], g = 0; 64 > g; g += 4)
                if (16 > g) {
                    k = c(k, l, m, n, a[b + g], 7, q[g]);
                    n = c(n, k, l, m, a[b + g + 1], 12, q[g + 1]);
                    m = c(m, n, k, l, a[b + g + 2], 17, q[g + 2]);
                    l = c(l, m, n, k, a[b + g + 3], 22, q[g + 3])
                } else if (32 > g) {
                k = d(k, l, m, n, a[b + (g + 1) % 16], 5, q[g]);
                n = d(n, k, l, m, a[b + (g + 6) % 16], 9, q[g + 1]);
                m = d(m, n, k, l, a[b + (g + 11) % 16], 14, q[g + 2]);
                l = d(l, m, n, k, a[b + g % 16], 20, q[g + 3])
            } else if (48 > g) {
                k = e(k, l, m, n, a[b + (3 * g + 5) % 16], 4, q[g]);
                n = e(n, k, l, m, a[b + (3 * g + 8) % 16], 11, q[g + 1]);
                m = e(m, n, k, l, a[b + (3 * g + 11) % 16], 16, q[g + 2]);
                l = e(l, m, n, k, a[b + (3 * g + 14) % 16], 23, q[g + 3])
            } else {
                k = f(k, l, m, n, a[b + 3 * g % 16], 6, q[g]);
                n = f(n, k, l, m, a[b + (3 * g + 7) % 16], 10, q[g + 1]);
                m = f(m, n, k, l, a[b + (3 * g + 14) % 16], 15, q[g + 2]);
                l = f(l, m, n, k, a[b + (3 * g + 5) % 16], 21, q[g + 3])
            }
            j[0] = j[0] + k | 0;
            j[1] = j[1] + l | 0;
            j[2] = j[2] + m | 0;
            j[3] = j[3] + n | 0
        },
        _doFinalize: function () {
            var a = this._data,
                b = a.words,
                c = 8 * this._nDataBytes,
                d = 8 * a.sigBytes;
            b[d >>> 5] |= 128 << 24 - d % 32;
            b[(d + 64 >>> 9 << 4) + 14] = 16711935 & (c << 8 | c >>> 24) | 4278255360 & (c << 24 | c >>> 8);
            a.sigBytes = 4 * (b.length + 1);
            this._process();
            for (var e = this._hash.words, f = 0; 4 > f; f++) {
                var g = e[f];
                e[f] = 16711935 & (g << 8 | g >>> 24) | 4278255360 & (g << 24 | g >>> 8)
            }
        }
    });
    g.MD5 = p._createHelper(r);
    g.HmacMD5 = p._createHmacHelper(r)
}(Math, TCS);
var Opentip, firstAdapter, i, mouseMoved, mousePosition, mousePositionObservers, position, vendors, _i, _len, _ref, __slice = [].slice,
    __indexOf = [].indexOf || function (a) {
        for (var b = 0, c = this.length; c > b; b++)
            if (b in this && this[b] === a) return b;
        return -1
    }, __hasProp = {}.hasOwnProperty;
Opentip = function () {
    function a(b, c, d, e) {
        var f, g, h, i, j, k, l, m, n, o, p, q, r, s, t = this;
        this.id = ++a.lastId;
        this.debug("Creating Opentip.");
        a.tips.push(this);
        this.adapter = a.adapter;
        f = this.adapter.data(b, "opentips") || [];
        f.push(this);
        this.adapter.data(b, "opentips", f);
        this.triggerElement = this.adapter.wrap(b);
        if (this.triggerElement.length > 1) throw new Error("You can't call Opentip on multiple elements.");
        if (this.triggerElement.length < 1) throw new Error("Invalid element.");
        this.loaded = !1;
        this.loading = !1;
        this.visible = !1;
        this.waitingToShow = !1;
        this.waitingToHide = !1;
        this.currentPosition = {
            left: 0,
            top: 0
        };
        this.dimensions = {
            width: 100,
            height: 50
        };
        this.content = "";
        this.redraw = !0;
        this.currentObservers = {
            showing: !1,
            visible: !1,
            hiding: !1,
            hidden: !1
        };
        e = this.adapter.clone(e);
        if ("object" == typeof c) {
            e = c;
            c = d = void 0
        } else if ("object" == typeof d) {
            e = d;
            d = void 0
        }
        null != d && (e.title = d);
        null != c && this.setContent(c);
        null == e["extends"] && (e["extends"] = null != e.style ? e.style : a.defaultStyle);
        i = [e];
        s = e;
        for (; s["extends"];) {
            k = s["extends"];
            s = a.styles[k];
            if (null == s) throw new Error("Invalid style: " + k);
            i.unshift(s);
            null == s["extends"] && "standard" !== k && (s["extends"] = "standard")
        }
        e = (p = this.adapter).extend.apply(p, [{}].concat(__slice.call(i)));
        e.hideTriggers = function () {
            var a, b, c, d;
            c = e.hideTriggers;
            d = [];
            for (a = 0, b = c.length; b > a; a++) {
                g = c[a];
                d.push(g)
            }
            return d
        }();
        e.hideTrigger && 0 === e.hideTriggers.length && e.hideTriggers.push(e.hideTrigger);
        q = ["tipJoint", "targetJoint", "stem"];
        for (l = 0, n = q.length; n > l; l++) {
            j = q[l];
            e[j] && "string" == typeof e[j] && (e[j] = new a.Joint(e[j]))
        }!e.ajax || e.ajax !== !0 && e.ajax || (e.ajax = "A" === this.adapter.tagName(this.triggerElement) ? this.adapter.attr(this.triggerElement, "href") : !1);
        "click" === e.showOn && "A" === this.adapter.tagName(this.triggerElement) && this.adapter.observe(this.triggerElement, "click", function (a) {
            a.preventDefault();
            a.stopPropagation();
            return a.stopped = !0
        });
        e.target && (e.fixed = !0);
        e.stem === !0 && (e.stem = new a.Joint(e.tipJoint));
        e.target === !0 ? e.target = this.triggerElement : e.target && (e.target = this.adapter.wrap(e.target));
        this.currentStem = e.stem;
        null == e.delay && (e.delay = "mouseover" === e.showOn ? .2 : 0);
        null == e.targetJoint && (e.targetJoint = new a.Joint(e.tipJoint).flip());
        this.showTriggers = [];
        this.showTriggersWhenVisible = [];
        this.hideTriggers = [];
        e.showOn && "creation" !== e.showOn && this.showTriggers.push({
            element: this.triggerElement,
            event: e.showOn
        });
        if (null != e.ajaxCache) {
            e.cache = e.ajaxCache;
            delete e.ajaxCache
        }
        this.options = e;
        this.bound = {};
        r = ["prepareToShow", "prepareToHide", "show", "hide", "reposition"];
        for (m = 0, o = r.length; o > m; m++) {
            h = r[m];
            this.bound[h] = function (a) {
                return function () {
                    return t[a].apply(t, arguments)
                }
            }(h)
        }
        this.adapter.domReady(function () {
            t.activate();
            return "creation" === t.options.showOn ? t.prepareToShow() : void 0
        })
    }
    a.prototype.STICKS_OUT_TOP = 1;
    a.prototype.STICKS_OUT_BOTTOM = 2;
    a.prototype.STICKS_OUT_LEFT = 1;
    a.prototype.STICKS_OUT_RIGHT = 2;
    a.prototype["class"] = {
        container: "opentip-container",
        opentip: "opentip",
        header: "ot-header",
        content: "ot-content",
        loadingIndicator: "ot-loading-indicator",
        close: "ot-close",
        goingToHide: "ot-going-to-hide",
        hidden: "ot-hidden",
        hiding: "ot-hiding",
        goingToShow: "ot-going-to-show",
        showing: "ot-showing",
        visible: "ot-visible",
        loading: "ot-loading",
        ajaxError: "ot-ajax-error",
        fixed: "ot-fixed",
        showEffectPrefix: "ot-show-effect-",
        hideEffectPrefix: "ot-hide-effect-",
        stylePrefix: "style-"
    };
    a.prototype._setup = function () {
        var a, b, c, d, e, f, g, h, i, j, k;
        this.debug("Setting up the tooltip.");
        this._buildContainer();
        this.hideTriggers = [];
        i = this.options.hideTriggers;
        for (d = e = 0, g = i.length; g > e; d = ++e) {
            b = i[d];
            c = null;
            a = this.options.hideOn instanceof Array ? this.options.hideOn[d] : this.options.hideOn;
            if ("string" == typeof b) switch (b) {
            case "trigger":
                a = a || "mouseout";
                c = this.triggerElement;
                break;
            case "tip":
                a = a || "mouseover";
                c = this.container;
                break;
            case "target":
                a = a || "mouseover";
                c = this.options.target;
                break;
            case "closeButton":
                break;
            default:
                throw new Error("Unknown hide trigger: " + b + ".")
            } else {
                a = a || "mouseover";
                c = this.adapter.wrap(b)
            }
            c && this.hideTriggers.push({
                element: c,
                event: a,
                original: b
            })
        }
        j = this.hideTriggers;
        k = [];
        for (f = 0, h = j.length; h > f; f++) {
            b = j[f];
            k.push(this.showTriggersWhenVisible.push({
                element: b.element,
                event: "mouseover"
            }))
        }
        return k
    };
    a.prototype._buildContainer = function () {
        this.container = this.adapter.create('<div id="opentip-' + this.id + '" class="' + this["class"].container + " " + this["class"].hidden + " " + this["class"].stylePrefix + this.options.className + '"></div>');
        this.adapter.css(this.container, {
            position: "absolute"
        });
        this.options.ajax && this.adapter.addClass(this.container, this["class"].loading);
        this.options.fixed && this.adapter.addClass(this.container, this["class"].fixed);
        this.options.showEffect && this.adapter.addClass(this.container, "" + this["class"].showEffectPrefix + this.options.showEffect);
        return this.options.hideEffect ? this.adapter.addClass(this.container, "" + this["class"].hideEffectPrefix + this.options.hideEffect) : void 0
    };
    a.prototype._buildElements = function () {
        var a, b;
        this.tooltipElement = this.adapter.create('<div class="' + this["class"].opentip + '"><div class="' + this["class"].header + '"></div><div class="' + this["class"].content + '"></div></div>');
        this.backgroundCanvas = this.adapter.wrap(document.createElement("canvas"));
        this.adapter.css(this.backgroundCanvas, {
            position: "absolute"
        });
        "undefined" != typeof G_vmlCanvasManager && null !== G_vmlCanvasManager && G_vmlCanvasManager.initElement(this.adapter.unwrap(this.backgroundCanvas));
        a = this.adapter.find(this.tooltipElement, "." + this["class"].header);
        if (this.options.title) {
            b = this.adapter.create("<h1></h1>");
            this.adapter.update(b, this.options.title, this.options.escapeTitle);
            this.adapter.append(a, b)
        }
        this.options.ajax && !this.loaded && this.adapter.append(this.tooltipElement, this.adapter.create('<div class="' + this["class"].loadingIndicator + '"><span>↻</span></div>'));
        if (__indexOf.call(this.options.hideTriggers, "closeButton") >= 0) {
            this.closeButtonElement = this.adapter.create('<a href="javascript:undefined;" class="' + this["class"].close + '"><span>Close</span></a>');
            this.adapter.append(a, this.closeButtonElement)
        }
        this.adapter.append(this.container, this.backgroundCanvas);
        this.adapter.append(this.container, this.tooltipElement);
        this.adapter.append(document.body, this.container);
        this._newContent = !0;
        return this.redraw = !0
    };
    a.prototype.setContent = function (a) {
        this.content = a;
        this._newContent = !0;
        if ("function" == typeof this.content) {
            this._contentFunction = this.content;
            this.content = ""
        } else this._contentFunction = null;
        return this.visible ? this._updateElementContent() : void 0
    };
    a.prototype._updateElementContent = function () {
        var a;
        if (this._newContent || !this.options.cache && this._contentFunction) {
            a = this.adapter.find(this.container, "." + this["class"].content);
            if (null != a) {
                if (this._contentFunction) {
                    this.debug("Executing content function.");
                    this.content = this._contentFunction(this)
                }
                this.adapter.update(a, this.content, this.options.escapeContent)
            }
            this._newContent = !1
        }
        this._storeAndLockDimensions();
        return this.reposition()
    };
    a.prototype._storeAndLockDimensions = function () {
        var a;
        if (this.container) {
            a = this.dimensions;
            this.adapter.css(this.container, {
                width: "auto",
                left: "0px",
                top: "0px"
            });
            this.dimensions = this.adapter.dimensions(this.container);
            this.dimensions.width += 1;
            this.adapter.css(this.container, {
                width: "" + this.dimensions.width + "px",
                top: "" + this.currentPosition.top + "px",
                left: "" + this.currentPosition.left + "px"
            });
            if (!this._dimensionsEqual(this.dimensions, a)) {
                this.redraw = !0;
                return this._draw()
            }
        }
    };
    a.prototype.activate = function () {
        return this._setupObservers("hidden", "hiding")
    };
    a.prototype.deactivate = function () {
        this.debug("Deactivating tooltip.");
        this.hide();
        return this._setupObservers("-showing", "-visible", "-hidden", "-hiding")
    };
    a.prototype._setupObservers = function () {
        var a, b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q = this;
        d = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
        for (f = 0, j = d.length; j > f; f++) {
            c = d[f];
            b = !1;
            if ("-" === c.charAt(0)) {
                b = !0;
                c = c.substr(1)
            }
            if (this.currentObservers[c] !== !b) {
                this.currentObservers[c] = !b;
                a = function () {
                    var a, c, d;
                    a = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
                    return b ? (c = q.adapter).stopObserving.apply(c, a) : (d = q.adapter).observe.apply(d, a)
                };
                switch (c) {
                case "showing":
                    n = this.hideTriggers;
                    for (g = 0, k = n.length; k > g; g++) {
                        e = n[g];
                        a(e.element, e.event, this.bound.prepareToHide)
                    }
                    a(null != document.onresize ? document : window, "resize", this.bound.reposition);
                    a(window, "scroll", this.bound.reposition);
                    break;
                case "visible":
                    o = this.showTriggersWhenVisible;
                    for (h = 0, l = o.length; l > h; h++) {
                        e = o[h];
                        a(e.element, e.event, this.bound.prepareToShow)
                    }
                    break;
                case "hiding":
                    p = this.showTriggers;
                    for (i = 0, m = p.length; m > i; i++) {
                        e = p[i];
                        a(e.element, e.event, this.bound.prepareToShow)
                    }
                    break;
                case "hidden":
                    break;
                default:
                    throw new Error("Unknown state: " + c)
                }
            }
        }
        return null
    };
    a.prototype.prepareToShow = function () {
        this._abortHiding();
        this._abortShowing();
        if (!this.visible) {
            this.debug("Showing in " + this.options.delay + "s.");
            null == this.container && this._setup();
            this.options.group && a._abortShowingGroup(this.options.group, this);
            this.preparingToShow = !0;
            this._setupObservers("-hidden", "-hiding", "showing");
            this._followMousePosition();
            this.options.fixed && !this.options.target && (this.initialMousePosition = mousePosition);
            this.reposition();
            return this._showTimeoutId = this.setTimeout(this.bound.show, this.options.delay || 0)
        }
    };
    a.prototype.show = function () {
        var b = this;
        this._abortHiding();
        if (!this.visible) {
            this._clearTimeouts();
            if (!this._triggerElementExists()) return this.deactivate();
            this.debug("Showing now.");
            null == this.container && this._setup();
            this.options.group && a._hideGroup(this.options.group, this);
            this.visible = !0;
            this.preparingToShow = !1;
            null == this.tooltipElement && this._buildElements();
            this._updateElementContent();
            !this.options.ajax || this.loaded && this.options.cache || this._loadAjax();
            this._searchAndActivateCloseButtons();
            this._startEnsureTriggerElement();
            this.adapter.css(this.container, {
                zIndex: a.lastZIndex++
            });
            this._setupObservers("-hidden", "-hiding", "-showing", "-visible", "showing", "visible");
            this.options.fixed && !this.options.target && (this.initialMousePosition = mousePosition);
            this.reposition();
            this.adapter.removeClass(this.container, this["class"].hiding);
            this.adapter.removeClass(this.container, this["class"].hidden);
            this.adapter.addClass(this.container, this["class"].goingToShow);
            this.setCss3Style(this.container, {
                transitionDuration: "0s"
            });
            this.defer(function () {
                var a;
                if (b.visible && !b.preparingToHide) {
                    b.adapter.removeClass(b.container, b["class"].goingToShow);
                    b.adapter.addClass(b.container, b["class"].showing);
                    a = 0;
                    b.options.showEffect && b.options.showEffectDuration && (a = b.options.showEffectDuration);
                    b.setCss3Style(b.container, {
                        transitionDuration: "" + a + "s"
                    });
                    b._visibilityStateTimeoutId = b.setTimeout(function () {
                        b.adapter.removeClass(b.container, b["class"].showing);
                        return b.adapter.addClass(b.container, b["class"].visible)
                    }, a);
                    return b._activateFirstInput()
                }
            });
            return this._draw()
        }
    };
    a.prototype._abortShowing = function () {
        if (this.preparingToShow) {
            this.debug("Aborting showing.");
            this._clearTimeouts();
            this._stopFollowingMousePosition();
            this.preparingToShow = !1;
            return this._setupObservers("-showing", "-visible", "hiding", "hidden")
        }
    };
    a.prototype.prepareToHide = function () {
        this._abortShowing();
        this._abortHiding();
        if (this.visible) {
            this.debug("Hiding in " + this.options.hideDelay + "s");
            this.preparingToHide = !0;
            this._setupObservers("-showing", "visible", "-hidden", "hiding");
            return this._hideTimeoutId = this.setTimeout(this.bound.hide, this.options.hideDelay)
        }
    };
    a.prototype.hide = function () {
        var a = this;
        this._abortShowing();
        if (this.visible) {
            this._clearTimeouts();
            this.debug("Hiding!");
            this.visible = !1;
            this.preparingToHide = !1;
            this._stopEnsureTriggerElement();
            this._setupObservers("-showing", "-visible", "-hiding", "-hidden", "hiding", "hidden");
            this.options.fixed || this._stopFollowingMousePosition();
            if (this.container) {
                this.adapter.removeClass(this.container, this["class"].visible);
                this.adapter.removeClass(this.container, this["class"].showing);
                this.adapter.addClass(this.container, this["class"].goingToHide);
                this.setCss3Style(this.container, {
                    transitionDuration: "0s"
                });
                return this.defer(function () {
                    var b;
                    a.adapter.removeClass(a.container, a["class"].goingToHide);
                    a.adapter.addClass(a.container, a["class"].hiding);
                    b = 0;
                    a.options.hideEffect && a.options.hideEffectDuration && (b = a.options.hideEffectDuration);
                    a.setCss3Style(a.container, {
                        transitionDuration: "" + b + "s"
                    });
                    return a._visibilityStateTimeoutId = a.setTimeout(function () {
                        a.adapter.removeClass(a.container, a["class"].hiding);
                        a.adapter.addClass(a.container, a["class"].hidden);
                        a.setCss3Style(a.container, {
                            transitionDuration: "0s"
                        });
                        if (a.options.removeElementsOnHide) {
                            a.debug("Removing HTML elements.");
                            a.adapter.remove(a.container);
                            delete a.container;
                            return delete a.tooltipElement
                        }
                    }, b)
                })
            }
        }
    };
    a.prototype._abortHiding = function () {
        if (this.preparingToHide) {
            this.debug("Aborting hiding.");
            this._clearTimeouts();
            this.preparingToHide = !1;
            return this._setupObservers("-hiding", "showing", "visible")
        }
    };
    a.prototype.reposition = function () {
        var a, b, c, d = this;
        a = this.getPosition();
        if (null != a) {
            b = this.options.stem;
            this.options.containInViewport && (c = this._ensureViewportContainment(a), a = c.position, b = c.stem);
            if (!this._positionsEqual(a, this.currentPosition)) {
                this.options.stem && !b.eql(this.currentStem) && (this.redraw = !0);
                this.currentPosition = a;
                this.currentStem = b;
                this._draw();
                this.adapter.css(this.container, {
                    left: "" + a.left + "px",
                    top: "" + a.top + "px"
                });
                return this.defer(function () {
                    var a, b;
                    a = d.adapter.unwrap(d.container);
                    a.style.visibility = "hidden";
                    b = a.offsetHeight;
                    return a.style.visibility = "visible"
                })
            }
        }
    };
    a.prototype.getPosition = function (a, b, c) {
        var d, e, f, g, h, i, j, k, l;
        if (this.container) {
            null == a && (a = this.options.tipJoint);
            null == b && (b = this.options.targetJoint);
            g = {};
            if (this.options.target) {
                j = this.adapter.offset(this.options.target);
                i = this.adapter.dimensions(this.options.target);
                g = j;
                if (b.right) {
                    k = this.adapter.unwrap(this.options.target);
                    null != k.getBoundingClientRect ? g.left = k.getBoundingClientRect().right + (null != (l = window.pageXOffset) ? l : document.body.scrollLeft) : g.left += i.width
                } else b.center && (g.left += Math.round(i.width / 2));
                b.bottom ? g.top += i.height : b.middle && (g.top += Math.round(i.height / 2));
                if (this.options.borderWidth) {
                    this.options.tipJoint.left && (g.left += this.options.borderWidth);
                    this.options.tipJoint.right && (g.left -= this.options.borderWidth);
                    this.options.tipJoint.top ? g.top += this.options.borderWidth : this.options.tipJoint.bottom && (g.top -= this.options.borderWidth)
                }
            } else g = this.initialMousePosition ? {
                top: this.initialMousePosition.y,
                left: this.initialMousePosition.x
            } : {
                top: mousePosition.y,
                left: mousePosition.x
            }; if (this.options.autoOffset) {
                h = this.options.stem ? this.options.stemLength : 0;
                f = h && this.options.fixed ? 2 : 10;
                d = a.middle && !this.options.fixed ? 15 : 0;
                e = a.center && !this.options.fixed ? 15 : 0;
                a.right ? g.left -= f + d : a.left && (g.left += f + d);
                a.bottom ? g.top -= f + e : a.top && (g.top += f + e);
                if (h) {
                    null == c && (c = this.options.stem);
                    c.right ? g.left -= h : c.left && (g.left += h);
                    c.bottom ? g.top -= h : c.top && (g.top += h)
                }
            }
            g.left += this.options.offset[0];
            g.top += this.options.offset[1];
            a.right ? g.left -= this.dimensions.width : a.center && (g.left -= Math.round(this.dimensions.width / 2));
            a.bottom ? g.top -= this.dimensions.height : a.middle && (g.top -= Math.round(this.dimensions.height / 2));
            return g
        }
    };
    a.prototype._ensureViewportContainment = function (b) {
        var c, d, e, f, g, h, i, j, k, l, m, n;
        i = this.options.stem;
        e = {
            position: b,
            stem: i
        };
        if (!this.visible || !b) return e;
        j = this._sticksOut(b);
        if (!j[0] && !j[1]) return e;
        l = new a.Joint(this.options.tipJoint);
        this.options.targetJoint && (k = new a.Joint(this.options.targetJoint));
        h = this.adapter.scrollOffset();
        m = this.adapter.viewportDimensions();
        n = [b.left - h[0], b.top - h[1]];
        c = !1;
        if (m.width >= this.dimensions.width && j[0]) {
            c = !0;
            switch (j[0]) {
            case this.STICKS_OUT_LEFT:
                l.setHorizontal("left");
                this.options.targetJoint && k.setHorizontal("right");
                break;
            case this.STICKS_OUT_RIGHT:
                l.setHorizontal("right");
                this.options.targetJoint && k.setHorizontal("left")
            }
        }
        if (m.height >= this.dimensions.height && j[1]) {
            c = !0;
            switch (j[1]) {
            case this.STICKS_OUT_TOP:
                l.setVertical("top");
                this.options.targetJoint && k.setVertical("bottom");
                break;
            case this.STICKS_OUT_BOTTOM:
                l.setVertical("bottom");
                this.options.targetJoint && k.setVertical("top")
            }
        }
        if (!c) return e;
        this.options.stem && (i = l);
        b = this.getPosition(l, k, i);
        d = this._sticksOut(b);
        f = !1;
        g = !1;
        if (d[0] && d[0] !== j[0]) {
            f = !0;
            l.setHorizontal(this.options.tipJoint.horizontal);
            this.options.targetJoint && k.setHorizontal(this.options.targetJoint.horizontal)
        }
        if (d[1] && d[1] !== j[1]) {
            g = !0;
            l.setVertical(this.options.tipJoint.vertical);
            this.options.targetJoint && k.setVertical(this.options.targetJoint.vertical)
        }
        if (f && g) return e;
        if (f || g) {
            this.options.stem && (i = l);
            b = this.getPosition(l, k, i)
        }
        return {
            position: b,
            stem: i
        }
    };
    a.prototype._sticksOut = function (a) {
        var b, c, d, e;
        c = this.adapter.scrollOffset();
        e = this.adapter.viewportDimensions();
        b = [a.left - c[0], a.top - c[1]];
        d = [!1, !1];
        b[0] < 0 ? d[0] = this.STICKS_OUT_LEFT : b[0] + this.dimensions.width > e.width && (d[0] = this.STICKS_OUT_RIGHT);
        b[1] < 0 ? d[1] = this.STICKS_OUT_TOP : b[1] + this.dimensions.height > e.height && (d[1] = this.STICKS_OUT_BOTTOM);
        return d
    };
    a.prototype._draw = function () {
        var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u = this;
        if (this.backgroundCanvas && this.redraw) {
            this.debug("Drawing background.");
            this.redraw = !1;
            if (this.currentStem) {
                r = ["top", "right", "bottom", "left"];
                for (p = 0, q = r.length; q > p; p++) {
                    m = r[p];
                    this.adapter.removeClass(this.container, "stem-" + m)
                }
                this.adapter.addClass(this.container, "stem-" + this.currentStem.horizontal);
                this.adapter.addClass(this.container, "stem-" + this.currentStem.vertical)
            }
            g = [0, 0];
            h = [0, 0];
            if (__indexOf.call(this.options.hideTriggers, "closeButton") >= 0) {
                f = new a.Joint("top right" === (null != (s = this.currentStem) ? s.toString() : void 0) ? "top left" : "top right");
                g = [this.options.closeButtonRadius + this.options.closeButtonOffset[0], this.options.closeButtonRadius + this.options.closeButtonOffset[1]];
                h = [this.options.closeButtonRadius - this.options.closeButtonOffset[0], this.options.closeButtonRadius - this.options.closeButtonOffset[1]]
            }
            d = this.adapter.clone(this.dimensions);
            e = [0, 0];
            if (this.options.borderWidth) {
                d.width += 2 * this.options.borderWidth;
                d.height += 2 * this.options.borderWidth;
                e[0] -= this.options.borderWidth;
                e[1] -= this.options.borderWidth
            }
            if (this.options.shadow) {
                d.width += 2 * this.options.shadowBlur;
                d.width += Math.max(0, this.options.shadowOffset[0] - 2 * this.options.shadowBlur);
                d.height += 2 * this.options.shadowBlur;
                d.height += Math.max(0, this.options.shadowOffset[1] - 2 * this.options.shadowBlur);
                e[0] -= Math.max(0, this.options.shadowBlur - this.options.shadowOffset[0]);
                e[1] -= Math.max(0, this.options.shadowBlur - this.options.shadowOffset[1])
            }
            c = {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            };
            if (this.currentStem) {
                this.currentStem.left ? c.left = this.options.stemLength : this.currentStem.right && (c.right = this.options.stemLength);
                this.currentStem.top ? c.top = this.options.stemLength : this.currentStem.bottom && (c.bottom = this.options.stemLength)
            }
            if (f) {
                f.left ? c.left = Math.max(c.left, h[0]) : f.right && (c.right = Math.max(c.right, h[0]));
                f.top ? c.top = Math.max(c.top, h[1]) : f.bottom && (c.bottom = Math.max(c.bottom, h[1]))
            }
            d.width += c.left + c.right;
            d.height += c.top + c.bottom;
            e[0] -= c.left;
            e[1] -= c.top;
            this.currentStem && this.options.borderWidth && (t = this._getPathStemMeasures(this.options.stemBase, this.options.stemLength, this.options.borderWidth), o = t.stemLength, n = t.stemBase);
            b = this.adapter.unwrap(this.backgroundCanvas);
            b.width = d.width;
            b.height = d.height;
            this.adapter.css(this.backgroundCanvas, {
                width: "" + b.width + "px",
                height: "" + b.height + "px",
                left: "" + e[0] + "px",
                top: "" + e[1] + "px"
            });
            i = b.getContext("2d");
            i.setTransform(1, 0, 0, 1, 0, 0);
            i.clearRect(0, 0, b.width, b.height);
            i.beginPath();
            i.fillStyle = this._getColor(i, this.dimensions, this.options.background, this.options.backgroundGradientHorizontal);
            i.lineJoin = "miter";
            i.miterLimit = 500;
            l = this.options.borderWidth / 2;
            if (this.options.borderWidth) {
                i.strokeStyle = this.options.borderColor;
                i.lineWidth = this.options.borderWidth
            } else {
                o = this.options.stemLength;
                n = this.options.stemBase
            }
            null == n && (n = 0);
            k = function (a, b, c) {
                c && i.moveTo(Math.max(n, u.options.borderRadius, g[0]) + 1 - l, -l);
                if (b) {
                    i.lineTo(a / 2 - n / 2, -l);
                    i.lineTo(a / 2, -o - l);
                    return i.lineTo(a / 2 + n / 2, -l)
                }
            };
            j = function (a, b, c) {
                var d, e, f, h;
                if (a) {
                    i.lineTo(-n + l, 0 - l);
                    i.lineTo(o + l, -o - l);
                    return i.lineTo(l, n - l)
                }
                if (b) {
                    h = u.options.closeButtonOffset;
                    f = g[0];
                    if (c % 2 !== 0) {
                        h = [h[1], h[0]];
                        f = g[1]
                    }
                    d = Math.acos(h[1] / u.options.closeButtonRadius);
                    e = Math.acos(h[0] / u.options.closeButtonRadius);
                    i.lineTo(-f + l, -l);
                    return i.arc(l - h[0], -l + h[1], u.options.closeButtonRadius, -(Math.PI / 2 + d), e, !1)
                }
                i.lineTo(-u.options.borderRadius + l, -l);
                return i.quadraticCurveTo(l, -l, l, u.options.borderRadius - l)
            };
            i.translate(-e[0], -e[1]);
            i.save();
            ! function () {
                var b, c, d, e, g, h, l, m, n, o, p;
                p = [];
                for (c = n = 0, o = a.positions.length / 2; o >= 0 ? o > n : n > o; c = o >= 0 ? ++n : --n) {
                    g = 2 * c;
                    h = 0 === c || 3 === c ? 0 : u.dimensions.width;
                    l = 2 > c ? 0 : u.dimensions.height;
                    m = Math.PI / 2 * c;
                    d = c % 2 === 0 ? u.dimensions.width : u.dimensions.height;
                    e = new a.Joint(a.positions[g]);
                    b = new a.Joint(a.positions[g + 1]);
                    i.save();
                    i.translate(h, l);
                    i.rotate(m);
                    k(d, e.eql(u.currentStem), 0 === c);
                    i.translate(d, 0);
                    j(b.eql(u.currentStem), b.eql(f), c);
                    p.push(i.restore())
                }
                return p
            }();
            i.closePath();
            i.save();
            if (this.options.shadow) {
                i.shadowColor = this.options.shadowColor;
                i.shadowBlur = this.options.shadowBlur;
                i.shadowOffsetX = this.options.shadowOffset[0];
                i.shadowOffsetY = this.options.shadowOffset[1]
            }
            i.fill();
            i.restore();
            this.options.borderWidth && i.stroke();
            i.restore();
            return f ? function () {
                var a, b, c, d, e;
                c = b = 2 * u.options.closeButtonRadius;
                if ("top right" === f.toString()) {
                    e = [u.dimensions.width - u.options.closeButtonOffset[0], u.options.closeButtonOffset[1]];
                    a = [e[0] + l, e[1] - l]
                } else {
                    e = [u.options.closeButtonOffset[0], u.options.closeButtonOffset[1]];
                    a = [e[0] - l, e[1] - l]
                }
                i.translate(a[0], a[1]);
                d = u.options.closeButtonCrossSize / 2;
                i.save();
                i.beginPath();
                i.strokeStyle = u.options.closeButtonCrossColor;
                i.lineWidth = u.options.closeButtonCrossLineWidth;
                i.lineCap = "round";
                i.moveTo(-d, -d);
                i.lineTo(d, d);
                i.stroke();
                i.beginPath();
                i.moveTo(d, -d);
                i.lineTo(-d, d);
                i.stroke();
                i.restore();
                return u.adapter.css(u.closeButtonElement, {
                    left: "" + (e[0] - d - u.options.closeButtonLinkOverscan) + "px",
                    top: "" + (e[1] - d - u.options.closeButtonLinkOverscan) + "px",
                    width: "" + (u.options.closeButtonCrossSize + 2 * u.options.closeButtonLinkOverscan) + "px",
                    height: "" + (u.options.closeButtonCrossSize + 2 * u.options.closeButtonLinkOverscan) + "px"
                })
            }() : void 0
        }
    };
    a.prototype._getPathStemMeasures = function (a, b, c) {
        var d, e, f, g, h, i, j;
        g = c / 2;
        f = Math.atan(a / 2 / b);
        d = 2 * f;
        h = g / Math.sin(d);
        e = 2 * h * Math.cos(f);
        j = g + b - e;
        if (0 > j) throw new Error("Sorry but your stemLength / stemBase ratio is strange.");
        i = Math.tan(f) * j * 2;
        return {
            stemLength: j,
            stemBase: i
        }
    };
    a.prototype._getColor = function (a, b, c, d) {
        var e, f, g, h, i;
        null == d && (d = !1);
        if ("string" == typeof c) return c;
        f = d ? a.createLinearGradient(0, 0, b.width, 0) : a.createLinearGradient(0, 0, 0, b.height);
        for (g = h = 0, i = c.length; i > h; g = ++h) {
            e = c[g];
            f.addColorStop(e[0], e[1])
        }
        return f
    };
    a.prototype._searchAndActivateCloseButtons = function () {
        var a, b, c, d;
        d = this.adapter.findAll(this.container, "." + this["class"].close);
        for (b = 0, c = d.length; c > b; b++) {
            a = d[b];
            this.hideTriggers.push({
                element: this.adapter.wrap(a),
                event: "click"
            })
        }
        this.currentObservers.showing && this._setupObservers("-showing", "showing");
        return this.currentObservers.visible ? this._setupObservers("-visible", "visible") : void 0
    };
    a.prototype._activateFirstInput = function () {
        var a;
        a = this.adapter.unwrap(this.adapter.find(this.container, "input, textarea"));
        return null != a ? "function" == typeof a.focus ? a.focus() : void 0 : void 0
    };
    a.prototype._followMousePosition = function () {
        return this.options.fixed ? void 0 : a._observeMousePosition(this.bound.reposition)
    };
    a.prototype._stopFollowingMousePosition = function () {
        return this.options.fixed ? void 0 : a._stopObservingMousePosition(this.bound.reposition)
    };
    a.prototype._clearShowTimeout = function () {
        return clearTimeout(this._showTimeoutId)
    };
    a.prototype._clearHideTimeout = function () {
        return clearTimeout(this._hideTimeoutId)
    };
    a.prototype._clearTimeouts = function () {
        clearTimeout(this._visibilityStateTimeoutId);
        this._clearShowTimeout();
        return this._clearHideTimeout()
    };
    a.prototype._triggerElementExists = function () {
        var a;
        a = this.adapter.unwrap(this.triggerElement);
        for (; a.parentNode;) {
            if ("BODY" === a.parentNode.tagName) return !0;
            a = a.parentNode
        }
        return !1
    };
    a.prototype._loadAjax = function () {
        var a = this;
        if (!this.loading) {
            this.loaded = !1;
            this.loading = !0;
            this.adapter.addClass(this.container, this["class"].loading);
            this.setContent("");
            this.debug("Loading content from " + this.options.ajax);
            return this.adapter.ajax({
                url: this.options.ajax,
                method: this.options.ajaxMethod,
                onSuccess: function (b) {
                    a.debug("Loading successful.");
                    a.adapter.removeClass(a.container, a["class"].loading);
                    return a.setContent(b)
                },
                onError: function (b) {
                    var c;
                    c = a.options.ajaxErrorMessage;
                    a.debug(c, b);
                    a.setContent(c);
                    return a.adapter.addClass(a.container, a["class"].ajaxError)
                },
                onComplete: function () {
                    a.adapter.removeClass(a.container, a["class"].loading);
                    a.loading = !1;
                    a.loaded = !0;
                    a._searchAndActivateCloseButtons();
                    a._activateFirstInput();
                    return a.reposition()
                }
            })
        }
    };
    a.prototype._ensureTriggerElement = function () {
        if (!this._triggerElementExists()) {
            this.deactivate();
            return this._stopEnsureTriggerElement()
        }
    };
    a.prototype._ensureTriggerElementInterval = 1e3;
    a.prototype._startEnsureTriggerElement = function () {
        var a = this;
        return this._ensureTriggerElementTimeoutId = setInterval(function () {
            return a._ensureTriggerElement()
        }, this._ensureTriggerElementInterval)
    };
    a.prototype._stopEnsureTriggerElement = function () {
        return clearInterval(this._ensureTriggerElementTimeoutId)
    };
    return a
}();
vendors = ["khtml", "ms", "o", "moz", "webkit"];
Opentip.prototype.setCss3Style = function (a, b) {
    var c, d, e, f, g;
    a = this.adapter.unwrap(a);
    g = [];
    for (c in b)
        if (__hasProp.call(b, c)) {
            d = b[c];
            null != a.style[c] ? g.push(a.style[c] = d) : g.push(function () {
                var b, g, h;
                h = [];
                for (b = 0, g = vendors.length; g > b; b++) {
                    e = vendors[b];
                    f = "" + this.ucfirst(e) + this.ucfirst(c);
                    null != a.style[f] ? h.push(a.style[f] = d) : h.push(void 0)
                }
                return h
            }.call(this))
        }
    return g
};
Opentip.prototype.defer = function (a) {
    return setTimeout(a, 0)
};
Opentip.prototype.setTimeout = function (a, b) {
    return setTimeout(a, b ? 1e3 * b : 0)
};
Opentip.prototype.ucfirst = function (a) {
    return null == a ? "" : a.charAt(0).toUpperCase() + a.slice(1)
};
Opentip.prototype.dasherize = function (a) {
    return a.replace(/([A-Z])/g, function (a, b) {
        return "-" + b.toLowerCase()
    })
};
mousePositionObservers = [];
mousePosition = {
    x: 0,
    y: 0
};
mouseMoved = function (a) {
    var b, c, d, e;
    mousePosition = Opentip.adapter.mousePosition(a);
    e = [];
    for (c = 0, d = mousePositionObservers.length; d > c; c++) {
        b = mousePositionObservers[c];
        e.push(b())
    }
    return e
};
Opentip.followMousePosition = function () {
    return Opentip.adapter.observe(document.body, "mousemove", mouseMoved)
};
Opentip._observeMousePosition = function (a) {
    return mousePositionObservers.push(a)
};
Opentip._stopObservingMousePosition = function (a) {
    var b;
    return mousePositionObservers = function () {
        var c, d, e;
        e = [];
        for (c = 0, d = mousePositionObservers.length; d > c; c++) {
            b = mousePositionObservers[c];
            b !== a && e.push(b)
        }
        return e
    }()
};
Opentip.Joint = function () {
    function a(a) {
        if (null != a) {
            a instanceof Opentip.Joint && (a = a.toString());
            this.set(a)
        }
    }
    a.prototype.set = function (a) {
        a = a.toLowerCase();
        this.setHorizontal(a);
        this.setVertical(a);
        return this
    };
    a.prototype.setHorizontal = function (a) {
        var b, c, d, e, f, g, h;
        c = ["left", "center", "right"];
        for (d = 0, f = c.length; f > d; d++) {
            b = c[d];~
            a.indexOf(b) && (this.horizontal = b.toLowerCase())
        }
        null == this.horizontal && (this.horizontal = "center");
        h = [];
        for (e = 0, g = c.length; g > e; e++) {
            b = c[e];
            h.push(this[b] = this.horizontal === b ? b : void 0)
        }
        return h
    };
    a.prototype.setVertical = function (a) {
        var b, c, d, e, f, g, h;
        c = ["top", "middle", "bottom"];
        for (d = 0, f = c.length; f > d; d++) {
            b = c[d];~
            a.indexOf(b) && (this.vertical = b.toLowerCase())
        }
        null == this.vertical && (this.vertical = "middle");
        h = [];
        for (e = 0, g = c.length; g > e; e++) {
            b = c[e];
            h.push(this[b] = this.vertical === b ? b : void 0)
        }
        return h
    };
    a.prototype.eql = function (a) {
        return null != a && this.horizontal === a.horizontal && this.vertical === a.vertical
    };
    a.prototype.flip = function () {
        var a, b;
        b = Opentip.position[this.toString(!0)];
        a = (b + 4) % 8;
        this.set(Opentip.positions[a]);
        return this
    };
    a.prototype.toString = function (a) {
        var b, c;
        null == a && (a = !1);
        c = "middle" === this.vertical ? "" : this.vertical;
        b = "center" === this.horizontal ? "" : this.horizontal;
        c && b && (b = a ? Opentip.prototype.ucfirst(b) : " " + b);
        return "" + c + b
    };
    return a
}();
Opentip.prototype._positionsEqual = function (a, b) {
    return null != a && null != b && a.left === b.left && a.top === b.top
};
Opentip.prototype._dimensionsEqual = function (a, b) {
    return null != a && null != b && a.width === b.width && a.height === b.height
};
Opentip.prototype.debug = function () {
    var a;
    a = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
    if (Opentip.debug && null != ("undefined" != typeof console && null !== console ? console.debug : void 0)) {
        a.unshift("#" + this.id + " |");
        return console.debug.apply(console, a)
    }
};
Opentip.findElements = function () {
    var a, b, c, d, e, f, g, h, i, j;
    a = Opentip.adapter;
    i = a.findAll(document.body, "[data-ot]");
    j = [];
    for (g = 0, h = i.length; h > g; g++) {
        c = i[g];
        f = {};
        b = a.data(c, "ot");
        if ("" === b || "true" === b || "yes" === b) {
            b = a.attr(c, "title");
            a.attr(c, "title", "")
        }
        b = b || "";
        for (d in Opentip.styles.standard) {
            e = a.data(c, "ot" + Opentip.prototype.ucfirst(d));
            if (null != e) {
                "yes" === e || "true" === e || "on" === e ? e = !0 : ("no" === e || "false" === e || "off" === e) && (e = !1);
                f[d] = e
            }
        }
        j.push(new Opentip(c, b, f))
    }
    return j
};
Opentip.version = "2.4.6";
Opentip.debug = !1;
Opentip.lastId = 0;
Opentip.lastZIndex = 100;
Opentip.tips = [];
Opentip._abortShowingGroup = function (a, b) {
    var c, d, e, f, g;
    f = Opentip.tips;
    g = [];
    for (d = 0, e = f.length; e > d; d++) {
        c = f[d];
        c !== b && c.options.group === a ? g.push(c._abortShowing()) : g.push(void 0)
    }
    return g
};
Opentip._hideGroup = function (a, b) {
    var c, d, e, f, g;
    f = Opentip.tips;
    g = [];
    for (d = 0, e = f.length; e > d; d++) {
        c = f[d];
        c !== b && c.options.group === a ? g.push(c.hide()) : g.push(void 0)
    }
    return g
};
Opentip.adapters = {};
Opentip.adapter = null;
firstAdapter = !0;
Opentip.addAdapter = function (a) {
    Opentip.adapters[a.name] = a;
    if (firstAdapter) {
        Opentip.adapter = a;
        a.domReady(Opentip.findElements);
        a.domReady(Opentip.followMousePosition);
        return firstAdapter = !1
    }
};
Opentip.positions = ["top", "topRight", "right", "bottomRight", "bottom", "bottomLeft", "left", "topLeft"];
Opentip.position = {};
_ref = Opentip.positions;
for (i = _i = 0, _len = _ref.length; _len > _i; i = ++_i) {
    position = _ref[i];
    Opentip.position[position] = i
}
Opentip.styles = {
    standard: {
        "extends": null,
        title: void 0,
        escapeTitle: !0,
        escapeContent: !1,
        className: "standard",
        stem: !0,
        delay: null,
        hideDelay: .1,
        fixed: !1,
        showOn: "mouseover",
        hideTrigger: "trigger",
        hideTriggers: [],
        hideOn: null,
        removeElementsOnHide: !1,
        offset: [0, 0],
        containInViewport: !0,
        autoOffset: !0,
        showEffect: "appear",
        hideEffect: "fade",
        showEffectDuration: .3,
        hideEffectDuration: .2,
        stemLength: 5,
        stemBase: 8,
        tipJoint: "top left",
        target: null,
        targetJoint: null,
        cache: !0,
        ajax: !1,
        ajaxMethod: "GET",
        ajaxErrorMessage: "There was a problem downloading the content.",
        group: null,
        style: null,
        background: "#fff18f",
        backgroundGradientHorizontal: !1,
        closeButtonOffset: [5, 5],
        closeButtonRadius: 7,
        closeButtonCrossSize: 4,
        closeButtonCrossColor: "#d2c35b",
        closeButtonCrossLineWidth: 1.5,
        closeButtonLinkOverscan: 6,
        borderRadius: 5,
        borderWidth: 1,
        borderColor: "#f2e37b",
        shadow: !0,
        shadowBlur: 10,
        shadowOffset: [3, 3],
        shadowColor: "rgba(0, 0, 0, 0.1)"
    },
    glass: {
        "extends": "standard",
        className: "glass",
        background: [
            [0, "rgba(252, 252, 252, 0.8)"],
            [.5, "rgba(255, 255, 255, 0.8)"],
            [.5, "rgba(250, 250, 250, 0.9)"],
            [1, "rgba(245, 245, 245, 0.9)"]
        ],
        borderColor: "#eee",
        closeButtonCrossColor: "rgba(0, 0, 0, 0.2)",
        borderRadius: 15,
        closeButtonRadius: 10,
        closeButtonOffset: [8, 8]
    },
    dark: {
        "extends": "standard",
        className: "dark",
        borderRadius: 13,
        borderColor: "#444",
        closeButtonCrossColor: "rgba(240, 240, 240, 1)",
        shadowColor: "rgba(0, 0, 0, 0.3)",
        shadowOffset: [2, 2],
        background: [
            [0, "rgba(30, 30, 30, 0.7)"],
            [.5, "rgba(30, 30, 30, 0.8)"],
            [.5, "rgba(10, 10, 10, 0.8)"],
            [1, "rgba(10, 10, 10, 0.9)"]
        ]
    },
    alert: {
        "extends": "standard",
        className: "alert",
        borderRadius: 1,
        borderColor: "#AE0D11",
        closeButtonCrossColor: "rgba(255, 255, 255, 1)",
        shadowColor: "rgba(0, 0, 0, 0.3)",
        shadowOffset: [2, 2],
        background: [
            [0, "rgba(203, 15, 19, 0.7)"],
            [.5, "rgba(203, 15, 19, 0.8)"],
            [.5, "rgba(189, 14, 18, 0.8)"],
            [1, "rgba(179, 14, 17, 0.9)"]
        ]
    }
};
Opentip.defaultStyle = "standard";
"undefined" != typeof module && null !== module ? module.exports = Opentip : window.Opentip = Opentip;
var __slice = [].slice;
! function (a) {
    var b;
    a.fn.opentip = function (a, b, c) {
        return new Opentip(this, a, b, c)
    };
    b = function () {
        function b() {}
        b.prototype.name = "jquery";
        b.prototype.domReady = function (b) {
            return a(b)
        };
        b.prototype.create = function (b) {
            return a(b)
        };
        b.prototype.wrap = function (b) {
            b = a(b);
            if (b.length > 1) throw new Error("Multiple elements provided.");
            return b
        };
        b.prototype.unwrap = function (b) {
            return a(b)[0]
        };
        b.prototype.tagName = function (a) {
            return this.unwrap(a).tagName
        };
        b.prototype.attr = function () {
            var b, c, d;
            c = arguments[0], b = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return (d = a(c)).attr.apply(d, b)
        };
        b.prototype.data = function () {
            var b, c, d;
            c = arguments[0], b = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return (d = a(c)).data.apply(d, b)
        };
        b.prototype.find = function (b, c) {
            return a(b).find(c).get(0)
        };
        b.prototype.findAll = function (b, c) {
            return a(b).find(c)
        };
        b.prototype.update = function (b, c, d) {
            b = a(b);
            return d ? b.text(c) : b.html(c)
        };
        b.prototype.append = function (b, c) {
            return a(b).append(c)
        };
        b.prototype.remove = function (b) {
            return a(b).remove()
        };
        b.prototype.addClass = function (b, c) {
            return a(b).addClass(c)
        };
        b.prototype.removeClass = function (b, c) {
            return a(b).removeClass(c)
        };
        b.prototype.css = function (b, c) {
            return a(b).css(c)
        };
        b.prototype.dimensions = function (b) {
            return {
                width: a(b).outerWidth(),
                height: a(b).outerHeight()
            }
        };
        b.prototype.scrollOffset = function () {
            return [window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft, window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop]
        };
        b.prototype.viewportDimensions = function () {
            return {
                width: document.documentElement.clientWidth,
                height: document.documentElement.clientHeight
            }
        };
        b.prototype.mousePosition = function (a) {
            return null == a ? null : {
                x: a.pageX,
                y: a.pageY
            }
        };
        b.prototype.offset = function (b) {
            var c;
            c = a(b).offset();
            return {
                left: c.left,
                top: c.top
            }
        };
        b.prototype.observe = function (b, c, d) {
            return a(b).bind(c, d)
        };
        b.prototype.stopObserving = function (b, c, d) {
            return a(b).unbind(c, d)
        };
        b.prototype.ajax = function (b) {
            var c, d;
            if (null == b.url) throw new Error("No url provided");
            return a.ajax({
                url: b.url,
                type: null != (c = null != (d = b.method) ? d.toUpperCase() : void 0) ? c : "GET"
            }).done(function (a) {
                return "function" == typeof b.onSuccess ? b.onSuccess(a) : void 0
            }).fail(function (a) {
                return "function" == typeof b.onError ? b.onError("Server responded with status " + a.status) : void 0
            }).always(function () {
                return "function" == typeof b.onComplete ? b.onComplete() : void 0
            })
        };
        b.prototype.clone = function (b) {
            return a.extend({}, b)
        };
        b.prototype.extend = function () {
            var b, c;
            c = arguments[0], b = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return a.extend.apply(a, [c].concat(__slice.call(b)))
        };
        return b
    }();
    return Opentip.addAdapter(new b)
}(jQuery);
Opentip.styles.tcs = {
    background: "#ffdd2d",
    hideTrigger: "trigger",
    borderWidth: 0,
    hideDelay: 0,
    delay: 0,
    autoOffset: !1,
    fixed: !0,
    shadow: !1,
    borderRadius: 2
};
Opentip.styles.dark = {
    "extends": "tcs",
    background: "#3e4757",
    className: "dark",
    tipJoint: "bottom",
    offset: [0, -15],
    stemLength: 7
};
$(function () {
    "use strict";

    function a(a, b) {
        var d = $(b),
            e = d.find("." + i);
        e.find(k).show();
        d.find(h).not("." + i).find(k).hide();
        d.on("click", j, c).removeClass(g)
    }

    function b(a, b) {
        var d = $(b);
        d.find(k).removeAttr("style").end().off("click", c).addClass(g)
    }

    function c(a) {
        a.preventDefault();
        var b = $(a.currentTarget).parents(f),
            c = b.find("." + i),
            d = c.find(k),
            e = $(this).closest(h),
            g = e.find(k);
        if (e.hasClass(i)) {
            c.removeClass(i);
            d.slideUp(200)
        } else {
            if (c.length) {
                c.removeClass(i);
                d.slideUp(200)
            }
            e.addClass(i);
            g.slideDown(300)
        }
    }

    function d(c) {
        this.each("destroy" === c ? b : a)
    }
    var e = TCS || (window.TCS = {}),
        f = ".accordion",
        g = "accordion_js_off",
        h = ".accordion__item",
        i = "accordion__item_open",
        j = ".accordion__title",
        k = ".accordion__content",
        l = $(f);
    $.fn.accordion = d;
    l.length && e.isDesktopStyles && l.accordion()
});
$(function () {
    "use strict";

    function a() {
        if (!t) {
            t = !0;
            z[q](i);
            w[q](k);
            x[q](m);
            c()
        }
    }

    function b() {
        if (t) {
            w[r](k);
            x[r](m);
            z[r](i);
            t = !1
        }
    }

    function c() {
        if (u) {
            y[r](o);
            u = !1
        }
    }

    function d() {
        b();
        c()
    }

    function e() {
        A = x.outerHeight();
        v.on(s)
    }

    function f() {
        d();
        v.off(s);
        w.removeAttr("style", "")
    }

    function g(a, b) {
        w.css("height", b.height - A)
    }
    var h = "wrapper",
        i = h + "_scroll_no",
        j = "navigation",
        k = j + "_opened",
        l = j + "__open",
        m = j + "__open_active",
        n = "menu",
        o = n + "_opened",
        p = n + "__open",
        q = "addClass",
        r = "removeClass",
        s = {
            click: d,
            windowResize: g
        }, t = !1,
        u = !1,
        v = $(document),
        w = $("." + j),
        x = $("." + l),
        y = $("." + n),
        z = $("." + h),
        A = 0;
    x.on("click", function (c) {
        c.preventDefault();
        c.stopPropagation();
        t ? b() : a()
    });
    w.on("click", function (a) {
        if (a.target === a.currentTarget) {
            a.preventDefault();
            a.stopPropagation()
        }
    });
    y.on("click", "." + p, function (a) {
        a.preventDefault();
        a.stopPropagation();
        b();
        y[u ? r : q](o);
        u = !u
    });
    v.on({
        "desktop.version": f,
        "mobile.version": e
    });
    TCS.isDesktopStyles || e()
});
$(function () {
    "use strict";

    function a(a) {
        a && a.preventDefault();
        b.removeClass(n);
        s.removeClass(p);
        c.hide();
        window.scrollTo(0, t);
        h[i](g.hide());
        d.empty();
        k = !1
    }
    var b, c, d, e, f, g, h, i, j = !0,
        k = !1,
        l = "href",
        m = "wrapper",
        n = m + "_popup_open",
        o = "footer",
        p = o + "_hidden",
        q = '<div class="mobile-popup"><div class="mobile-popup__header"><p class="mobile-popup__title"></p><a href="#" class="mobile-popup__close"></a></div><div class="mobile-popup__content"></div></div>',
        r = TCS || (window.TCS = {}),
        s = $("." + o),
        t = 0;
    $(document).on("desktop.version", function () {
        k && a()
    }).on("click", ".Mobile-Popup", function (o) {
        if (!r.isDesktopStyles) {
            o.preventDefault();
            o.stopPropagation();
            var u = $(o.currentTarget),
                v = (u.attr(l) || u.data(l)).replace(/^#/, "");
            if (j) {
                j = !1;
                b = $("." + m);
                c = $(q).hide().appendTo(b);
                d = c.find(".mobile-popup__title");
                e = c.find(".mobile-popup__close").on("click", a);
                f = c.find(".mobile-popup__content")
            }
            if (!k && v && (g = $("#" + v)).length) {
                t = document.documentElement.scrollTop || document.body.scrollTop || window.scrollY || 0;
                h = g.prev();
                i = "after";
                if (!h.length) {
                    h = g.parent();
                    i = "prepend"
                }
                f.append(g.show());
                d.text(u.text());
                c.show();
                b.addClass(n);
                s.addClass(p);
                k = !0
            }
        }
    });
    r.isDesktopStyles || $(".Mobile-Popup").each(function (a, b) {
        b.addEventListener("click", function () {}, !0)
    })
});
! function (a) {
    "use strict";

    function b() {
        function b(a) {
            return +/^(\d+)/.exec(a)[0]
        }
        var d, e = [{
                browser: "msie",
                version: 9
            }, {
                browser: "chrome",
                version: 11
            }, {
                browser: "mozilla",
                version: 1
            }, {
                browser: "safari",
                version: 5
            }, {
                browser: "opera",
                version: 11
            }],
            f = a.browser;
        d = _.filter(e, function (a) {
            return f[a.browser]
        })[0];
        c.supportedBrowsers = e;
        return !d || d.version <= b(f.version)
    }
    var c = window.TCS || {};
    c.browserIsSupported = b
}(jQuery);
var TCS = TCS || {};
! function (a, b, c) {
    "use strict";
    var d = [8, 9, 13, 16, 17, 18, 19, 20, 27, 32, 33, 34, 35, 36, 37, 38, 39, 40, 44, 45, 46, 91, 144, 145],
        e = function (a) {
            var b = c(a.currentTarget),
                d = b.val().trim(),
                e = b.parents(".tcs-form-row"),
                f = e.find(".tcs-form-row-field");
            if (0 === d.length) {
                e.removeClass("tcs-form-row-field-input-fill");
                f.removeClass("tcs-form-row-field-input-label-focus-ready")
            }
            e.removeClass("tcs-form-row-field-input-onfocus")
        }, f = function (a) {
            if ("focusin" !== a.type && ("keydown" !== a.type || 16 !== a.keyCode)) {
                var b = a.type,
                    e = a.keyCode,
                    f = c(a.currentTarget),
                    h = f.val().trim(),
                    i = f.closest(".tcs-form-row"),
                    j = i.find(".tcs-form-row-field"),
                    k = !! f.data("mask"),
                    l = function () {
                        if ("keydown" !== b || -1 === c.inArray(e, d)) {
                            j.removeClass("tcs-form-row-field-focus-ready");
                            i.addClass("tcs-form-row-field-input-onfocus");
                            k && i.addClass("tcs-form-row-mask-field");
                            setTimeout(function () {
                                i.is(".tcs-form-row-field-input-onfocus") && j.addClass("tcs-form-row-field-focus-ready")
                            }, 10)
                        }
                    }, m = function () {
                        i.removeClass("tcs-form-row-field-input-fill");
                        j.removeClass("tcs-form-row-field-input-label-focus-ready");
                        i.removeClass("tcs-form-row-field-input-onfocus")
                    }, n = i.is(".tcs-form-row-field-input-fill, .tcs-form-row-field-input-onfocus");
                "keydown" === b && 8 === e && h.length - 1 === 0 && m();
                "keydown" === b && -1 === c.inArray(e, d) && i.addClass("tcs-form-row-active");
                if (n) i.addClass("tcs-form-row-focused");
                else if ("focus" === b || "click" === b) k ? l() : i.addClass("tcs-form-row-focused");
                else {
                    if ("change" === b && 0 === h.length) return;
                    l()
                }
                "selectField" === b && g(a)
            }
        }, g = function (a) {
            var b = c(a.currentTarget),
                d = b.val().trim(),
                e = b.parents(".tcs-form-row"),
                f = e.find(".tcs-form-row-field");
            if (d.length) {
                e.addClass("tcs-form-row-field-input-fill");
                f.addClass("tcs-form-row-field-input-label-focus-ready tcs-form-row-field-focus-ready")
            } else if (0 === d.length) {
                e.removeClass("tcs-form-row-field-input-fill");
                f.removeClass("tcs-form-row-field-input-label-focus-ready")
            }
            e.removeClass("tcs-form-row-focused").removeClass("tcs-form-row-field-input-onfocus").removeClass("tcs-form-row-active")
        }, h = function (a) {
            var b, d, e = c(a.currentTarget),
                f = e.next(".tcs-plugin-select2").length;
            if (f) {
                b = e.parents(".tcs-form-row");
                d = b.find(".tcs-form-row-field");
                d.length && d.addClass("tcs-form-row-field-select");
                "oldvalue:change" === a.type && e.trigger("change")
            }
        }, i = function (a) {
            var b = c(a.target);
            if (!b.data("superPlaceholder")) {
                b.data("superPlaceholder", !0);
                f(a);
                b.on("click focus keydown change", f).on("blur", g)
            }
        }, j = function (a) {
            var b = c(a.currentTarget),
                d = b.parents(".tcs-form-row-select"),
                e = d.find(".tcs-plugin-select2");
            e.trigger("click");
            return !1
        }, k = function (a) {
            var b = c(a.currentTarget),
                d = a.type;
            "focus" === d && b.parent("div").addClass("focused");
            "blur" === d && b.parent("div").removeClass("focused")
        };
    c(document).on("clearField", ".tcs-input-type-text", e);
    c(document).on("selectField", ".tcs-input-type-text", f);
    c(document).on("click focus keydown change", ".tcs-input-type-text", i);
    c(function () {
        var a = c(".tcs-input-type-text"),
            d = c("input.checkbox"),
            e = c("textarea");
        a.trigger("selectField");
        c(b).on("blur", function () {
            a.trigger("blur")
        });
        c(document).on("click", ".tcs-form-row-select .tcs-form-row-field", j);
        d.on("focus blur", k);
        c(document).on("change oldvalue:change", "select", h);
        "function" == typeof c.fn.autosize && e.autosize()
    })
}(TCS, window, jQuery);
! function (a) {
    var b, c, d, e, f, g, h, i = "Close",
        j = "BeforeClose",
        k = "AfterClose",
        l = "BeforeAppend",
        m = "MarkupParse",
        n = "Open",
        o = "Change",
        p = "mfp",
        q = "." + p,
        r = "mfp-ready",
        s = "mfp-removing",
        t = "mfp-prevent-close",
        u = function () {}, v = !! window.jQuery,
        w = a(window),
        x = function (a, c) {
            b.ev.on(p + a + q, c)
        }, y = function (b, c, d, e) {
            var f = document.createElement("div");
            f.className = "mfp-" + b;
            d && (f.innerHTML = d);
            if (e) c && c.appendChild(f);
            else {
                f = a(f);
                c && f.appendTo(c)
            }
            return f
        }, z = function (c, d) {
            b.ev.triggerHandler(p + c, d);
            if (b.st.callbacks) {
                c = c.charAt(0).toLowerCase() + c.slice(1);
                b.st.callbacks[c] && b.st.callbacks[c].apply(b, a.isArray(d) ? d : [d])
            }
        }, A = function (c) {
            if (c !== h || !b.currTemplate.closeBtn) {
                b.currTemplate.closeBtn = a(b.st.closeMarkup.replace("%title%", b.st.tClose));
                h = c
            }
            return b.currTemplate.closeBtn
        }, B = function () {
            if (!a.magnificPopup.instance) {
                b = new u;
                b.init();
                a.magnificPopup.instance = b
            }
        }, C = function () {
            var a = document.createElement("p").style,
                b = ["ms", "O", "Moz", "Webkit"];
            if (void 0 !== a.transition) return !0;
            for (; b.length;)
                if (b.pop() + "Transition" in a) return !0;
            return !1
        };
    u.prototype = {
        constructor: u,
        init: function () {
            var c = navigator.appVersion;
            b.isIE7 = -1 !== c.indexOf("MSIE 7.");
            b.isIE8 = -1 !== c.indexOf("MSIE 8.");
            b.isLowIE = b.isIE7 || b.isIE8;
            b.isAndroid = /android/gi.test(c);
            b.isIOS = /iphone|ipad|ipod/gi.test(c);
            b.supportsTransition = C();
            b.probablyMobile = b.isAndroid || b.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent);
            e = a(document);
            b.popupsCache = {}
        },
        open: function (c) {
            d || (d = a(document.body));
            var f;
            if (c.isObj === !1) {
                b.items = c.items.toArray();
                b.index = 0;
                var h, i = c.items;
                for (f = 0; f < i.length; f++) {
                    h = i[f];
                    h.parsed && (h = h.el[0]);
                    if (h === c.el[0]) {
                        b.index = f;
                        break
                    }
                }
            } else {
                b.items = a.isArray(c.items) ? c.items : [c.items];
                b.index = c.index || 0
            } if (!b.isOpen) {
                b.types = [];
                g = "";
                b.ev = c.mainEl && c.mainEl.length ? c.mainEl.eq(0) : e;
                if (c.key) {
                    b.popupsCache[c.key] || (b.popupsCache[c.key] = {});
                    b.currTemplate = b.popupsCache[c.key]
                } else b.currTemplate = {};
                b.st = a.extend(!0, {}, a.magnificPopup.defaults, c);
                b.fixedContentPos = "auto" === b.st.fixedContentPos ? !b.probablyMobile : b.st.fixedContentPos;
                if (b.st.modal) {
                    b.st.closeOnContentClick = !1;
                    b.st.closeOnBgClick = !1;
                    b.st.showCloseBtn = !1;
                    b.st.enableEscapeKey = !1
                }
                if (!b.bgOverlay) {
                    b.bgOverlay = y("bg").on("click" + q, function () {
                        b.close()
                    });
                    b.wrap = y("wrap").attr("tabindex", -1).on("click" + q, function (a) {
                        b._checkIfClose(a.target) && b.close()
                    });
                    b.container = y("container", b.wrap)
                }
                b.contentContainer = y("content");
                b.st.preloader && (b.preloader = y("preloader", b.container, b.st.tLoading));
                var j = a.magnificPopup.modules;
                for (f = 0; f < j.length; f++) {
                    var k = j[f];
                    k = k.charAt(0).toUpperCase() + k.slice(1);
                    b["init" + k].call(b)
                }
                z("BeforeOpen");
                if (b.st.showCloseBtn)
                    if (b.st.closeBtnInside) {
                        x(m, function (a, b, c, d) {
                            c.close_replaceWith = A(d.type)
                        });
                        g += " mfp-close-btn-in"
                    } else b.wrap.append(A());
                b.st.alignTop && (g += " mfp-align-top");
                b.fixedContentPos ? b.wrap.css({
                    overflow: b.st.overflowY,
                    overflowX: "hidden",
                    overflowY: b.st.overflowY
                }) : b.wrap.css({
                    top: w.scrollTop(),
                    position: "absolute"
                });
                (b.st.fixedBgPos === !1 || "auto" === b.st.fixedBgPos && !b.fixedContentPos) && b.bgOverlay.css({
                    height: e.height(),
                    position: "absolute"
                });
                b.st.enableEscapeKey && e.on("keyup" + q, function (a) {
                    27 === a.keyCode && b.close()
                });
                w.on("resize" + q, function () {
                    b.updateSize()
                });
                b.st.closeOnContentClick || (g += " mfp-auto-cursor");
                g && b.wrap.addClass(g);
                var l = b.wH = w.height(),
                    o = {};
                if (b.fixedContentPos && b._hasScrollBar(l)) {
                    var p = b._getScrollbarSize();
                    p && (o.marginRight = p)
                }
                b.fixedContentPos && (b.isIE7 ? a("body, html").css("overflow", "hidden") : o.overflow = "hidden");
                var s = b.st.mainClass;
                b.isIE7 && (s += " mfp-ie7");
                s && b._addClassToMFP(s);
                b.updateItemHTML();
                z("BuildControls");
                a("html").css(o);
                b.bgOverlay.add(b.wrap).prependTo(b.st.prependTo || d);
                b._lastFocusedEl = document.activeElement;
                setTimeout(function () {
                    if (b.content) {
                        b._addClassToMFP(r);
                        b._setFocus()
                    } else b.bgOverlay.addClass(r);
                    e.on("focusin" + q, b._onFocusIn)
                }, 16);
                b.isOpen = !0;
                b.updateSize(l);
                z(n);
                return c
            }
            b.updateItemHTML()
        },
        close: function () {
            if (b.isOpen) {
                z(j);
                b.isOpen = !1;
                if (b.st.removalDelay && !b.isLowIE && b.supportsTransition) {
                    b._addClassToMFP(s);
                    setTimeout(function () {
                        b._close()
                    }, b.st.removalDelay)
                } else b._close()
            }
        },
        _close: function () {
            z(i);
            var c = s + " " + r + " ";
            b.bgOverlay.detach();
            b.wrap.detach();
            b.container.empty();
            b.st.mainClass && (c += b.st.mainClass + " ");
            b._removeClassFromMFP(c);
            if (b.fixedContentPos) {
                var d = {
                    marginRight: ""
                };
                b.isIE7 ? a("body, html").css("overflow", "") : d.overflow = "";
                a("html").css(d)
            }
            e.off("keyup" + q + " focusin" + q);
            b.ev.off(q);
            b.wrap.attr("class", "mfp-wrap").removeAttr("style");
            b.bgOverlay.attr("class", "mfp-bg");
            b.container.attr("class", "mfp-container");
            !b.st.showCloseBtn || b.st.closeBtnInside && b.currTemplate[b.currItem.type] !== !0 || b.currTemplate.closeBtn && b.currTemplate.closeBtn.detach();
            b._lastFocusedEl && a(b._lastFocusedEl).focus();
            b.currItem = null;
            b.content = null;
            b.currTemplate = null;
            b.prevHeight = 0;
            z(k)
        },
        updateSize: function (a) {
            if (b.isIOS) {
                var c = document.documentElement.clientWidth / window.innerWidth,
                    d = window.innerHeight * c;
                b.wrap.css("height", d);
                b.wH = d
            } else b.wH = a || w.height();
            b.fixedContentPos || b.wrap.css("height", b.wH);
            z("Resize")
        },
        updateItemHTML: function () {
            var c = b.items[b.index];
            b.contentContainer.detach();
            b.content && b.content.detach();
            c.parsed || (c = b.parseEl(b.index));
            var d = c.type;
            z("BeforeChange", [b.currItem ? b.currItem.type : "", d]);
            b.currItem = c;
            if (!b.currTemplate[d]) {
                var e = b.st[d] ? b.st[d].markup : !1;
                z("FirstMarkupParse", e);
                b.currTemplate[d] = e ? a(e) : !0
            }
            f && f !== c.type && b.container.removeClass("mfp-" + f + "-holder");
            var g = b["get" + d.charAt(0).toUpperCase() + d.slice(1)](c, b.currTemplate[d]);
            b.appendContent(g, d);
            c.preloaded = !0;
            z(o, c);
            f = c.type;
            b.container.prepend(b.contentContainer);
            z("AfterChange")
        },
        appendContent: function (a, c) {
            b.content = a;
            a ? b.st.showCloseBtn && b.st.closeBtnInside && b.currTemplate[c] === !0 ? b.content.find(".mfp-close").length || b.content.append(A()) : b.content = a : b.content = "";
            z(l);
            b.container.addClass("mfp-" + c + "-holder");
            b.contentContainer.append(b.content)
        },
        parseEl: function (c) {
            var d, e = b.items[c];
            if (e.tagName) e = {
                el: a(e)
            };
            else {
                d = e.type;
                e = {
                    data: e,
                    src: e.src
                }
            } if (e.el) {
                for (var f = b.types, g = 0; g < f.length; g++)
                    if (e.el.hasClass("mfp-" + f[g])) {
                        d = f[g];
                        break
                    }
                e.src = e.el.attr("data-mfp-src");
                e.src || (e.src = e.el.attr("href"))
            }
            e.type = d || b.st.type || "inline";
            e.index = c;
            e.parsed = !0;
            b.items[c] = e;
            z("ElementParse", e);
            return b.items[c]
        },
        addGroup: function (a, c) {
            var d = function (d) {
                d.mfpEl = this;
                b._openClick(d, a, c)
            };
            c || (c = {});
            var e = "click.magnificPopup";
            c.mainEl = a;
            if (c.items) {
                c.isObj = !0;
                a.off(e).on(e, d)
            } else {
                c.isObj = !1;
                if (c.delegate) a.off(e).on(e, c.delegate, d);
                else {
                    c.items = a;
                    a.off(e).on(e, d)
                }
            }
        },
        _openClick: function (c, d, e) {
            var f = void 0 !== e.midClick ? e.midClick : a.magnificPopup.defaults.midClick;
            if (f || 2 !== c.which && !c.ctrlKey && !c.metaKey) {
                var g = void 0 !== e.disableOn ? e.disableOn : a.magnificPopup.defaults.disableOn;
                if (g)
                    if (a.isFunction(g)) {
                        if (!g.call(b)) return !0
                    } else if (w.width() < g) return !0;
                if (c.type) {
                    c.preventDefault();
                    b.isOpen && c.stopPropagation()
                }
                e.el = a(c.mfpEl);
                e.delegate && (e.items = d.find(e.delegate));
                b.open(e)
            }
        },
        updateStatus: function (a, d) {
            if (b.preloader) {
                c !== a && b.container.removeClass("mfp-s-" + c);
                d || "loading" !== a || (d = b.st.tLoading);
                var e = {
                    status: a,
                    text: d
                };
                z("UpdateStatus", e);
                a = e.status;
                d = e.text;
                b.preloader.html(d);
                b.preloader.find("a").on("click", function (a) {
                    a.stopImmediatePropagation()
                });
                b.container.addClass("mfp-s-" + a);
                c = a
            }
        },
        _checkIfClose: function (c) {
            if (!a(c).hasClass(t)) {
                var d = b.st.closeOnContentClick,
                    e = b.st.closeOnBgClick;
                if (d && e) return !0;
                if (!b.content || a(c).hasClass("mfp-close") || b.preloader && c === b.preloader[0]) return !0;
                if (c === b.content[0] || a.contains(b.content[0], c)) {
                    if (d) return !0
                } else if (e && a.contains(document, c)) return !0;
                return !1
            }
        },
        _addClassToMFP: function (a) {
            b.bgOverlay.addClass(a);
            b.wrap.addClass(a)
        },
        _removeClassFromMFP: function (a) {
            this.bgOverlay.removeClass(a);
            b.wrap.removeClass(a)
        },
        _hasScrollBar: function (a) {
            return (b.isIE7 ? e.height() : document.body.scrollHeight) > (a || w.height())
        },
        _setFocus: function () {
            (b.st.focus ? b.content.find(b.st.focus).eq(0) : b.wrap).focus()
        },
        _onFocusIn: function (c) {
            if (c.target !== b.wrap[0] && !a.contains(b.wrap[0], c.target)) {
                b._setFocus();
                return !1
            }
        },
        _parseMarkup: function (b, c, d) {
            var e;
            d.data && (c = a.extend(d.data, c));
            z(m, [b, c, d]);
            a.each(c, function (a, c) {
                if (void 0 === c || c === !1) return !0;
                e = a.split("_");
                if (e.length > 1) {
                    var d = b.find(q + "-" + e[0]);
                    if (d.length > 0) {
                        var f = e[1];
                        "replaceWith" === f ? d[0] !== c[0] && d.replaceWith(c) : "img" === f ? d.is("img") ? d.attr("src", c) : d.replaceWith('<img src="' + c + '" class="' + d.attr("class") + '" />') : d.attr(e[1], c)
                    }
                } else b.find(q + "-" + a).html(c)
            })
        },
        _getScrollbarSize: function () {
            if (void 0 === b.scrollbarSize) {
                var a = document.createElement("div");
                a.id = "mfp-sbm";
                a.style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;";
                document.body.appendChild(a);
                b.scrollbarSize = a.offsetWidth - a.clientWidth;
                document.body.removeChild(a)
            }
            return b.scrollbarSize
        }
    };
    a.magnificPopup = {
        instance: null,
        proto: u.prototype,
        modules: [],
        open: function (b, c) {
            B();
            b = b ? a.extend(!0, {}, b) : {};
            b.isObj = !0;
            b.index = c || 0;
            return this.instance.open(b)
        },
        close: function () {
            return a.magnificPopup.instance && a.magnificPopup.instance.close()
        },
        registerModule: function (b, c) {
            c.options && (a.magnificPopup.defaults[b] = c.options);
            a.extend(this.proto, c.proto);
            this.modules.push(b)
        },
        defaults: {
            disableOn: 0,
            key: null,
            midClick: !1,
            mainClass: "",
            preloader: !0,
            focus: "",
            closeOnContentClick: !1,
            closeOnBgClick: !0,
            closeBtnInside: !0,
            showCloseBtn: !0,
            enableEscapeKey: !0,
            modal: !1,
            alignTop: !1,
            removalDelay: 0,
            prependTo: null,
            fixedContentPos: "auto",
            fixedBgPos: "auto",
            overflowY: "auto",
            closeMarkup: '<button title="%title%" type="button" class="mfp-close">&times;</button>',
            tClose: "Close (Esc)",
            tLoading: "Loading..."
        }
    };
    a.fn.magnificPopup = function (c) {
        B();
        var d = a(this);
        if ("string" == typeof c)
            if ("open" === c) {
                var e, f = v ? d.data("magnificPopup") : d[0].magnificPopup,
                    g = parseInt(arguments[1], 10) || 0;
                if (f.items) e = f.items[g];
                else {
                    e = d;
                    f.delegate && (e = e.find(f.delegate));
                    e = e.eq(g)
                }
                b._openClick({
                    mfpEl: e
                }, d, f)
            } else b.isOpen && b[c].apply(b, Array.prototype.slice.call(arguments, 1));
            else {
                c = a.extend(!0, {}, c);
                v ? d.data("magnificPopup", c) : d[0].magnificPopup = c;
                b.addGroup(d, c)
            }
        return d
    };
    var D, E, F, G = "inline",
        H = function () {
            if (F) {
                E.after(F.addClass(D)).detach();
                F = null
            }
        };
    a.magnificPopup.registerModule(G, {
        options: {
            hiddenClass: "hide",
            markup: "",
            tNotFound: "Content not found"
        },
        proto: {
            initInline: function () {
                b.types.push(G);
                x(i + "." + G, function () {
                    H()
                })
            },
            getInline: function (c, d) {
                H();
                if (c.src) {
                    var e = b.st.inline,
                        f = a(c.src);
                    if (f.length) {
                        var g = f[0].parentNode;
                        if (g && g.tagName) {
                            if (!E) {
                                D = e.hiddenClass;
                                E = y(D);
                                D = "mfp-" + D
                            }
                            F = f.after(E).detach().removeClass(D)
                        }
                        b.updateStatus("ready")
                    } else {
                        b.updateStatus("error", e.tNotFound);
                        f = a("<div>")
                    }
                    c.inlineElement = f;
                    return f
                }
                b.updateStatus("ready");
                b._parseMarkup(d, {}, c);
                return d
            }
        }
    });
    var I, J = "ajax",
        K = function () {
            I && d.removeClass(I)
        }, L = function () {
            K();
            b.req && b.req.abort()
        };
    a.magnificPopup.registerModule(J, {
        options: {
            settings: null,
            cursor: "mfp-ajax-cur",
            tError: '<a href="%url%">The content</a> could not be loaded.'
        },
        proto: {
            initAjax: function () {
                b.types.push(J);
                I = b.st.ajax.cursor;
                x(i + "." + J, L);
                x("BeforeChange." + J, L)
            },
            getAjax: function (c) {
                I && d.addClass(I);
                b.updateStatus("loading");
                var e = a.extend({
                    url: c.src,
                    success: function (d, e, f) {
                        var g = {
                            data: d,
                            xhr: f
                        };
                        z("ParseAjax", g);
                        b.appendContent(a(g.data), J);
                        c.finished = !0;
                        K();
                        b._setFocus();
                        setTimeout(function () {
                            b.wrap.addClass(r)
                        }, 16);
                        b.updateStatus("ready");
                        z("AjaxContentAdded")
                    },
                    error: function () {
                        K();
                        c.finished = c.loadError = !0;
                        b.updateStatus("error", b.st.ajax.tError.replace("%url%", c.src))
                    }
                }, b.st.ajax.settings);
                b.req = a.ajax(e);
                return ""
            }
        }
    });
    var M, N = function (c) {
            if (c.data && void 0 !== c.data.title) return c.data.title;
            var d = b.st.image.titleSrc;
            if (d) {
                if (a.isFunction(d)) return d.call(b, c);
                if (c.el) return c.el.attr(d) || ""
            }
            return ""
        };
    a.magnificPopup.registerModule("image", {
        options: {
            markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
            cursor: "mfp-zoom-out-cur",
            titleSrc: "title",
            verticalFit: !0,
            tError: '<a href="%url%">The image</a> could not be loaded.'
        },
        proto: {
            initImage: function () {
                var a = b.st.image,
                    c = ".image";
                b.types.push("image");
                x(n + c, function () {
                    "image" === b.currItem.type && a.cursor && d.addClass(a.cursor)
                });
                x(i + c, function () {
                    a.cursor && d.removeClass(a.cursor);
                    w.off("resize" + q)
                });
                x("Resize" + c, b.resizeImage);
                b.isLowIE && x("AfterChange", b.resizeImage)
            },
            resizeImage: function () {
                var a = b.currItem;
                if (a && a.img && b.st.image.verticalFit) {
                    var c = 0;
                    b.isLowIE && (c = parseInt(a.img.css("padding-top"), 10) + parseInt(a.img.css("padding-bottom"), 10));
                    a.img.css("max-height", b.wH - c)
                }
            },
            _onImageHasSize: function (a) {
                if (a.img) {
                    a.hasSize = !0;
                    M && clearInterval(M);
                    a.isCheckingImgSize = !1;
                    z("ImageHasSize", a);
                    if (a.imgHidden) {
                        b.content && b.content.removeClass("mfp-loading");
                        a.imgHidden = !1
                    }
                }
            },
            findImageSize: function (a) {
                var c = 0,
                    d = a.img[0],
                    e = function (f) {
                        M && clearInterval(M);
                        M = setInterval(function () {
                            if (d.naturalWidth > 0) b._onImageHasSize(a);
                            else {
                                c > 200 && clearInterval(M);
                                c++;
                                3 === c ? e(10) : 40 === c ? e(50) : 100 === c && e(500)
                            }
                        }, f)
                    };
                e(1)
            },
            getImage: function (c, d) {
                var e = 0,
                    f = function () {
                        if (c)
                            if (c.img[0].complete) {
                                c.img.off(".mfploader");
                                if (c === b.currItem) {
                                    b._onImageHasSize(c);
                                    b.updateStatus("ready")
                                }
                                c.hasSize = !0;
                                c.loaded = !0;
                                z("ImageLoadComplete")
                            } else {
                                e++;
                                200 > e ? setTimeout(f, 100) : g()
                            }
                    }, g = function () {
                        if (c) {
                            c.img.off(".mfploader");
                            if (c === b.currItem) {
                                b._onImageHasSize(c);
                                b.updateStatus("error", h.tError.replace("%url%", c.src))
                            }
                            c.hasSize = !0;
                            c.loaded = !0;
                            c.loadError = !0
                        }
                    }, h = b.st.image,
                    i = d.find(".mfp-img");
                if (i.length) {
                    var j = document.createElement("img");
                    j.className = "mfp-img";
                    c.img = a(j).on("load.mfploader", f).on("error.mfploader", g);
                    j.src = c.src;
                    i.is("img") && (c.img = c.img.clone());
                    j = c.img[0];
                    j.naturalWidth > 0 ? c.hasSize = !0 : j.width || (c.hasSize = !1)
                }
                b._parseMarkup(d, {
                    title: N(c),
                    img_replaceWith: c.img
                }, c);
                b.resizeImage();
                if (c.hasSize) {
                    M && clearInterval(M);
                    if (c.loadError) {
                        d.addClass("mfp-loading");
                        b.updateStatus("error", h.tError.replace("%url%", c.src))
                    } else {
                        d.removeClass("mfp-loading");
                        b.updateStatus("ready")
                    }
                    return d
                }
                b.updateStatus("loading");
                c.loading = !0;
                if (!c.hasSize) {
                    c.imgHidden = !0;
                    d.addClass("mfp-loading");
                    b.findImageSize(c)
                }
                return d
            }
        }
    });
    var O, P = function () {
            void 0 === O && (O = void 0 !== document.createElement("p").style.MozTransform);
            return O
        };
    a.magnificPopup.registerModule("zoom", {
        options: {
            enabled: !1,
            easing: "ease-in-out",
            duration: 300,
            opener: function (a) {
                return a.is("img") ? a : a.find("img")
            }
        },
        proto: {
            initZoom: function () {
                var a, c = b.st.zoom,
                    d = ".zoom";
                if (c.enabled && b.supportsTransition) {
                    var e, f, g = c.duration,
                        h = function (a) {
                            var b = a.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),
                                d = "all " + c.duration / 1e3 + "s " + c.easing,
                                e = {
                                    position: "fixed",
                                    zIndex: 9999,
                                    left: 0,
                                    top: 0,
                                    "-webkit-backface-visibility": "hidden"
                                }, f = "transition";
                            e["-webkit-" + f] = e["-moz-" + f] = e["-o-" + f] = e[f] = d;
                            b.css(e);
                            return b
                        }, k = function () {
                            b.content.css("visibility", "visible")
                        };
                    x("BuildControls" + d, function () {
                        if (b._allowZoom()) {
                            clearTimeout(e);
                            b.content.css("visibility", "hidden");
                            a = b._getItemToZoom();
                            if (!a) {
                                k();
                                return
                            }
                            f = h(a);
                            f.css(b._getOffset());
                            b.wrap.append(f);
                            e = setTimeout(function () {
                                f.css(b._getOffset(!0));
                                e = setTimeout(function () {
                                    k();
                                    setTimeout(function () {
                                        f.remove();
                                        a = f = null;
                                        z("ZoomAnimationEnded")
                                    }, 16)
                                }, g)
                            }, 16)
                        }
                    });
                    x(j + d, function () {
                        if (b._allowZoom()) {
                            clearTimeout(e);
                            b.st.removalDelay = g;
                            if (!a) {
                                a = b._getItemToZoom();
                                if (!a) return;
                                f = h(a)
                            }
                            f.css(b._getOffset(!0));
                            b.wrap.append(f);
                            b.content.css("visibility", "hidden");
                            setTimeout(function () {
                                f.css(b._getOffset())
                            }, 16)
                        }
                    });
                    x(i + d, function () {
                        if (b._allowZoom()) {
                            k();
                            f && f.remove();
                            a = null
                        }
                    })
                }
            },
            _allowZoom: function () {
                return "image" === b.currItem.type
            },
            _getItemToZoom: function () {
                return b.currItem.hasSize ? b.currItem.img : !1
            },
            _getOffset: function (c) {
                var d;
                d = c ? b.currItem.img : b.st.zoom.opener(b.currItem.el || b.currItem);
                var e = d.offset(),
                    f = parseInt(d.css("padding-top"), 10),
                    g = parseInt(d.css("padding-bottom"), 10);
                e.top -= a(window).scrollTop() - f;
                var h = {
                    width: d.width(),
                    height: (v ? d.innerHeight() : d[0].offsetHeight) - g - f
                };
                if (P()) h["-moz-transform"] = h.transform = "translate(" + e.left + "px," + e.top + "px)";
                else {
                    h.left = e.left;
                    h.top = e.top
                }
                return h
            }
        }
    });
    var Q = "iframe",
        R = "//about:blank",
        S = function (a) {
            if (b.currTemplate[Q]) {
                var c = b.currTemplate[Q].find("iframe");
                if (c.length) {
                    a || (c[0].src = R);
                    b.isIE8 && c.css("display", a ? "block" : "none")
                }
            }
        };
    a.magnificPopup.registerModule(Q, {
        options: {
            markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',
            srcAction: "iframe_src",
            patterns: {
                youtube: {
                    index: "youtube.com",
                    id: "v=",
                    src: "//www.youtube.com/embed/%id%?autoplay=1"
                },
                vimeo: {
                    index: "vimeo.com/",
                    id: "/",
                    src: "//player.vimeo.com/video/%id%?autoplay=1"
                },
                gmaps: {
                    index: "//maps.google.",
                    src: "%id%&output=embed"
                }
            }
        },
        proto: {
            initIframe: function () {
                b.types.push(Q);
                x("BeforeChange", function (a, b, c) {
                    b !== c && (b === Q ? S() : c === Q && S(!0))
                });
                x(i + "." + Q, function () {
                    S()
                })
            },
            getIframe: function (c, d) {
                var e = c.src,
                    f = b.st.iframe;
                a.each(f.patterns, function () {
                    if (e.indexOf(this.index) > -1) {
                        this.id && (e = "string" == typeof this.id ? e.substr(e.lastIndexOf(this.id) + this.id.length, e.length) : this.id.call(this, e));
                        e = this.src.replace("%id%", e);
                        return !1
                    }
                });
                var g = {};
                f.srcAction && (g[f.srcAction] = e);
                b._parseMarkup(d, g, c);
                b.updateStatus("ready");
                return d
            }
        }
    });
    var T = function (a) {
        var c = b.items.length;
        return a > c - 1 ? a - c : 0 > a ? c + a : a
    }, U = function (a, b, c) {
            return a.replace(/%curr%/gi, b + 1).replace(/%total%/gi, c)
        };
    a.magnificPopup.registerModule("gallery", {
        options: {
            enabled: !1,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            preload: [0, 2],
            navigateByImgClick: !0,
            arrows: !0,
            tPrev: "Previous (Left arrow key)",
            tNext: "Next (Right arrow key)",
            tCounter: "%curr% of %total%"
        },
        proto: {
            initGallery: function () {
                var c = b.st.gallery,
                    d = ".mfp-gallery",
                    f = Boolean(a.fn.mfpFastClick);
                b.direction = !0;
                if (!c || !c.enabled) return !1;
                g += " mfp-gallery";
                x(n + d, function () {
                    c.navigateByImgClick && b.wrap.on("click" + d, ".mfp-img", function () {
                        if (b.items.length > 1) {
                            b.next();
                            return !1
                        }
                    });
                    e.on("keydown" + d, function (a) {
                        37 === a.keyCode ? b.prev() : 39 === a.keyCode && b.next()
                    })
                });
                x("UpdateStatus" + d, function (a, c) {
                    c.text && (c.text = U(c.text, b.currItem.index, b.items.length))
                });
                x(m + d, function (a, d, e, f) {
                    var g = b.items.length;
                    e.counter = g > 1 ? U(c.tCounter, f.index, g) : ""
                });
                x("BuildControls" + d, function () {
                    if (b.items.length > 1 && c.arrows && !b.arrowLeft) {
                        var d = c.arrowMarkup,
                            e = b.arrowLeft = a(d.replace(/%title%/gi, c.tPrev).replace(/%dir%/gi, "left")).addClass(t),
                            g = b.arrowRight = a(d.replace(/%title%/gi, c.tNext).replace(/%dir%/gi, "right")).addClass(t),
                            h = f ? "mfpFastClick" : "click";
                        e[h](function () {
                            b.prev()
                        });
                        g[h](function () {
                            b.next()
                        });
                        if (b.isIE7) {
                            y("b", e[0], !1, !0);
                            y("a", e[0], !1, !0);
                            y("b", g[0], !1, !0);
                            y("a", g[0], !1, !0)
                        }
                        b.container.append(e.add(g))
                    }
                });
                x(o + d, function () {
                    b._preloadTimeout && clearTimeout(b._preloadTimeout);
                    b._preloadTimeout = setTimeout(function () {
                        b.preloadNearbyImages();
                        b._preloadTimeout = null
                    }, 16)
                });
                x(i + d, function () {
                    e.off(d);
                    b.wrap.off("click" + d);
                    b.arrowLeft && f && b.arrowLeft.add(b.arrowRight).destroyMfpFastClick();
                    b.arrowRight = b.arrowLeft = null
                })
            },
            next: function () {
                b.direction = !0;
                b.index = T(b.index + 1);
                b.updateItemHTML()
            },
            prev: function () {
                b.direction = !1;
                b.index = T(b.index - 1);
                b.updateItemHTML()
            },
            goTo: function (a) {
                b.direction = a >= b.index;
                b.index = a;
                b.updateItemHTML()
            },
            preloadNearbyImages: function () {
                var a, c = b.st.gallery.preload,
                    d = Math.min(c[0], b.items.length),
                    e = Math.min(c[1], b.items.length);
                for (a = 1; a <= (b.direction ? e : d); a++) b._preloadItem(b.index + a);
                for (a = 1; a <= (b.direction ? d : e); a++) b._preloadItem(b.index - a)
            },
            _preloadItem: function (c) {
                c = T(c);
                if (!b.items[c].preloaded) {
                    var d = b.items[c];
                    d.parsed || (d = b.parseEl(c));
                    z("LazyLoad", d);
                    "image" === d.type && (d.img = a('<img class="mfp-img" />').on("load.mfploader", function () {
                        d.hasSize = !0
                    }).on("error.mfploader", function () {
                        d.hasSize = !0;
                        d.loadError = !0;
                        z("LazyLoadError", d)
                    }).attr("src", d.src));
                    d.preloaded = !0
                }
            }
        }
    });
    var V = "retina";
    a.magnificPopup.registerModule(V, {
        options: {
            replaceSrc: function (a) {
                return a.src.replace(/\.\w+$/, function (a) {
                    return "@2x" + a
                })
            },
            ratio: 1
        },
        proto: {
            initRetina: function () {
                if (window.devicePixelRatio > 1) {
                    var a = b.st.retina,
                        c = a.ratio;
                    c = isNaN(c) ? c() : c;
                    if (c > 1) {
                        x("ImageHasSize." + V, function (a, b) {
                            b.img.css({
                                "max-width": b.img[0].naturalWidth / c,
                                width: "100%"
                            })
                        });
                        x("ElementParse." + V, function (b, d) {
                            d.src = a.replaceSrc(d, c)
                        })
                    }
                }
            }
        }
    });
    ! function () {
        var b = 1e3,
            c = "ontouchstart" in window,
            d = function () {
                w.off("touchmove" + f + " touchend" + f)
            }, e = "mfpFastClick",
            f = "." + e;
        a.fn.mfpFastClick = function (e) {
            return a(this).each(function () {
                var g, h = a(this);
                if (c) {
                    var i, j, k, l, m, n;
                    h.on("touchstart" + f, function (a) {
                        l = !1;
                        n = 1;
                        m = a.originalEvent ? a.originalEvent.touches[0] : a.touches[0];
                        j = m.clientX;
                        k = m.clientY;
                        w.on("touchmove" + f, function (a) {
                            m = a.originalEvent ? a.originalEvent.touches : a.touches;
                            n = m.length;
                            m = m[0];
                            if (Math.abs(m.clientX - j) > 10 || Math.abs(m.clientY - k) > 10) {
                                l = !0;
                                d()
                            }
                        }).on("touchend" + f, function (a) {
                            d();
                            if (!(l || n > 1)) {
                                g = !0;
                                a.preventDefault();
                                clearTimeout(i);
                                i = setTimeout(function () {
                                    g = !1
                                }, b);
                                e()
                            }
                        })
                    })
                }
                h.on("click" + f, function () {
                    g || e()
                })
            })
        };
        a.fn.destroyMfpFastClick = function () {
            a(this).off("touchstart" + f + " click" + f);
            c && w.off("touchmove" + f + " touchend" + f)
        }
    }();
    B()
}(window.jQuery || window.Zepto);
! function (a, b) {
    var c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z;
    c = function (a) {
        return new c.prototype.init(a)
    };
    "undefined" != typeof require && "undefined" != typeof exports && "undefined" != typeof module ? module.exports = c : a.Globalize = c;
    c.cultures = {};
    c.prototype = {
        constructor: c,
        init: function (a) {
            this.cultures = c.cultures;
            this.cultureSelector = a;
            return this
        }
    };
    c.prototype.init.prototype = c.prototype;
    c.cultures["default"] = {
        name: "en",
        englishName: "English",
        nativeName: "English",
        isRTL: !1,
        language: "en",
        numberFormat: {
            pattern: ["-n"],
            decimals: 2,
            ",": ",",
            ".": ".",
            groupSizes: [3],
            "+": "+",
            "-": "-",
            NaN: "NaN",
            negativeInfinity: "-Infinity",
            positiveInfinity: "Infinity",
            percent: {
                pattern: ["-n %", "n %"],
                decimals: 2,
                groupSizes: [3],
                ",": ",",
                ".": ".",
                symbol: "%"
            },
            currency: {
                pattern: ["($n)", "$n"],
                decimals: 2,
                groupSizes: [3],
                ",": ",",
                ".": ".",
                symbol: "$"
            }
        },
        calendars: {
            standard: {
                name: "Gregorian_USEnglish",
                "/": "/",
                ":": ":",
                firstDay: 0,
                days: {
                    names: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    namesAbbr: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                    namesShort: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"]
                },
                months: {
                    names: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December", ""],
                    namesAbbr: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", ""]
                },
                AM: ["AM", "am", "AM"],
                PM: ["PM", "pm", "PM"],
                eras: [{
                    name: "A.D.",
                    start: null,
                    offset: 0
                }],
                twoDigitYearMax: 2029,
                patterns: {
                    d: "M/d/yyyy",
                    D: "dddd, MMMM dd, yyyy",
                    t: "h:mm tt",
                    T: "h:mm:ss tt",
                    f: "dddd, MMMM dd, yyyy h:mm tt",
                    F: "dddd, MMMM dd, yyyy h:mm:ss tt",
                    M: "MMMM dd",
                    Y: "yyyy MMMM",
                    S: "yyyy'-'MM'-'dd'T'HH':'mm':'ss"
                }
            }
        },
        messages: {}
    };
    c.cultures["default"].calendar = c.cultures["default"].calendars.standard;
    c.cultures.en = c.cultures["default"];
    c.cultureSelector = "en";
    d = /^0x[a-f0-9]+$/i;
    e = /^[+\-]?infinity$/i;
    f = /^[+\-]?\d*\.?\d*(e[+\-]?\d+)?$/;
    g = /^\s+|\s+$/g;
    h = function (a, b) {
        if (a.indexOf) return a.indexOf(b);
        for (var c = 0, d = a.length; d > c; c++)
            if (a[c] === b) return c;
        return -1
    };
    i = function (a, b) {
        return a.substr(a.length - b.length) === b
    };
    j = function () {
        var a, c, d, e, f, g, h = arguments[0] || {}, i = 1,
            n = arguments.length,
            o = !1;
        if ("boolean" == typeof h) {
            o = h;
            h = arguments[1] || {};
            i = 2
        }
        "object" == typeof h || l(h) || (h = {});
        for (; n > i; i++)
            if (null != (a = arguments[i]))
                for (c in a) {
                    d = h[c];
                    e = a[c];
                    if (h !== e)
                        if (o && e && (m(e) || (f = k(e)))) {
                            if (f) {
                                f = !1;
                                g = d && k(d) ? d : []
                            } else g = d && m(d) ? d : {};
                            h[c] = j(o, g, e)
                        } else e !== b && (h[c] = e)
                }
            return h
    };
    k = Array.isArray || function (a) {
        return "[object Array]" === Object.prototype.toString.call(a)
    };
    l = function (a) {
        return "[object Function]" === Object.prototype.toString.call(a)
    };
    m = function (a) {
        return "[object Object]" === Object.prototype.toString.call(a)
    };
    n = function (a, b) {
        return 0 === a.indexOf(b)
    };
    o = function (a) {
        return (a + "").replace(g, "")
    };
    p = function (a) {
        return isNaN(a) ? 0 / 0 : Math[0 > a ? "ceil" : "floor"](a)
    };
    q = function (a, b, c) {
        var d;
        for (d = a.length; b > d; d += 1) a = c ? "0" + a : a + "0";
        return a
    };
    r = function (a, b) {
        for (var c = 0, d = !1, e = 0, f = a.length; f > e; e++) {
            var g = a.charAt(e);
            switch (g) {
            case "'":
                d ? b.push("'") : c++;
                d = !1;
                break;
            case "\\":
                d && b.push("\\");
                d = !d;
                break;
            default:
                b.push(g);
                d = !1
            }
        }
        return c
    };
    s = function (a, b) {
        b = b || "F";
        var c, d = a.patterns,
            e = b.length;
        if (1 === e) {
            c = d[b];
            if (!c) throw "Invalid date format string '" + b + "'.";
            b = c
        } else 2 === e && "%" === b.charAt(0) && (b = b.charAt(1));
        return b
    };
    t = function (a, b, c) {
        function d(a, b) {
            var c, d = a + "";
            if (b > 1 && d.length < b) {
                c = u[b - 2] + d;
                return c.substr(c.length - b, b)
            }
            c = d;
            return c
        }

        function e() {
            if (o || p) return o;
            o = y.test(b);
            p = !0;
            return o
        }

        function f(a, b) {
            if (q) return q[b];
            switch (b) {
            case 0:
                return a.getFullYear();
            case 1:
                return a.getMonth();
            case 2:
                return a.getDate();
            default:
                throw "Invalid part value " + b
            }
        }
        var g, h = c.calendar,
            i = h.convert;
        if (!b || !b.length || "i" === b) {
            if (c && c.name.length)
                if (i) g = t(a, h.patterns.F, c);
                else {
                    var j = new Date(a.getTime()),
                        k = w(a, h.eras);
                    j.setFullYear(x(a, h, k));
                    g = j.toLocaleString()
                } else g = a.toString();
            return g
        }
        var l = h.eras,
            m = "s" === b;
        b = s(h, b);
        g = [];
        var n, o, p, q, u = ["0", "00", "000"],
            y = /([^d]|^)(d|dd)([^d]|$)/g,
            z = 0,
            A = v();
        !m && i && (q = i.fromGregorian(a));
        for (;;) {
            var B = A.lastIndex,
                C = A.exec(b),
                D = b.slice(B, C ? C.index : b.length);
            z += r(D, g);
            if (!C) break;
            if (z % 2) g.push(C[0]);
            else {
                var E = C[0],
                    F = E.length;
                switch (E) {
                case "ddd":
                case "dddd":
                    var G = 3 === F ? h.days.namesAbbr : h.days.names;
                    g.push(G[a.getDay()]);
                    break;
                case "d":
                case "dd":
                    o = !0;
                    g.push(d(f(a, 2), F));
                    break;
                case "MMM":
                case "MMMM":
                    var H = f(a, 1);
                    g.push(h.monthsGenitive && e() ? h.monthsGenitive[3 === F ? "namesAbbr" : "names"][H] : h.months[3 === F ? "namesAbbr" : "names"][H]);
                    break;
                case "M":
                case "MM":
                    g.push(d(f(a, 1) + 1, F));
                    break;
                case "y":
                case "yy":
                case "yyyy":
                    H = q ? q[0] : x(a, h, w(a, l), m);
                    4 > F && (H %= 100);
                    g.push(d(H, F));
                    break;
                case "h":
                case "hh":
                    n = a.getHours() % 12;
                    0 === n && (n = 12);
                    g.push(d(n, F));
                    break;
                case "H":
                case "HH":
                    g.push(d(a.getHours(), F));
                    break;
                case "m":
                case "mm":
                    g.push(d(a.getMinutes(), F));
                    break;
                case "s":
                case "ss":
                    g.push(d(a.getSeconds(), F));
                    break;
                case "t":
                case "tt":
                    H = a.getHours() < 12 ? h.AM ? h.AM[0] : " " : h.PM ? h.PM[0] : " ";
                    g.push(1 === F ? H.charAt(0) : H);
                    break;
                case "f":
                case "ff":
                case "fff":
                    g.push(d(a.getMilliseconds(), 3).substr(0, F));
                    break;
                case "z":
                case "zz":
                    n = a.getTimezoneOffset() / 60;
                    g.push((0 >= n ? "+" : "-") + d(Math.floor(Math.abs(n)), F));
                    break;
                case "zzz":
                    n = a.getTimezoneOffset() / 60;
                    g.push((0 >= n ? "+" : "-") + d(Math.floor(Math.abs(n)), 2) + ":" + d(Math.abs(a.getTimezoneOffset() % 60), 2));
                    break;
                case "g":
                case "gg":
                    h.eras && g.push(h.eras[w(a, l)].name);
                    break;
                case "/":
                    g.push(h["/"]);
                    break;
                default:
                    throw "Invalid date format pattern '" + E + "'."
                }
            }
        }
        return g.join("")
    };
    ! function () {
        var a;
        a = function (a, b, c) {
            var d = c.groupSizes,
                e = d[0],
                f = 1,
                g = Math.pow(10, b),
                h = Math.round(a * g) / g;
            isFinite(h) || (h = a);
            a = h;
            var i = a + "",
                j = "",
                k = i.split(/e/i),
                l = k.length > 1 ? parseInt(k[1], 10) : 0;
            i = k[0];
            k = i.split(".");
            i = k[0];
            j = k.length > 1 ? k[1] : "";
            if (l > 0) {
                j = q(j, l, !1);
                i += j.slice(0, l);
                j = j.substr(l)
            } else if (0 > l) {
                l = -l;
                i = q(i, l + 1, !0);
                j = i.slice(-l, i.length) + j;
                i = i.slice(0, -l)
            }
            j = b > 0 ? c["."] + (j.length > b ? j.slice(0, b) : q(j, b)) : "";
            for (var m = i.length - 1, n = c[","], o = ""; m >= 0;) {
                if (0 === e || e > m) return i.slice(0, m + 1) + (o.length ? n + o + j : j);
                o = i.slice(m - e + 1, m + 1) + (o.length ? n + o : "");
                m -= e;
                if (f < d.length) {
                    e = d[f];
                    f++
                }
            }
            return i.slice(0, m + 1) + n + o + j
        };
        u = function (b, c, d) {
            if (!isFinite(b)) return 1 / 0 === b ? d.numberFormat.positiveInfinity : b === -1 / 0 ? d.numberFormat.negativeInfinity : d.numberFormat.NaN;
            if (!c || "i" === c) return d.name.length ? b.toLocaleString() : b.toString();
            c = c || "D";
            var e, f = d.numberFormat,
                g = Math.abs(b),
                h = -1;
            c.length > 1 && (h = parseInt(c.slice(1), 10));
            var i, j = c.charAt(0).toUpperCase();
            switch (j) {
            case "D":
                e = "n";
                g = p(g); - 1 !== h && (g = q("" + g, h, !0));
                0 > b && (g = "-" + g);
                break;
            case "N":
                i = f;
            case "C":
                i = i || f.currency;
            case "P":
                i = i || f.percent;
                e = 0 > b ? i.pattern[0] : i.pattern[1] || "n"; - 1 === h && (h = i.decimals);
                g = a(g * ("P" === j ? 100 : 1), h, i);
                break;
            default:
                throw "Bad number format specifier: " + j
            }
            for (var k = /n|\$|-|%/g, l = "";;) {
                var m = k.lastIndex,
                    n = k.exec(e);
                l += e.slice(m, n ? n.index : e.length);
                if (!n) break;
                switch (n[0]) {
                case "n":
                    l += g;
                    break;
                case "$":
                    l += f.currency.symbol;
                    break;
                case "-":
                    /[1-9]/.test(g) && (l += f["-"]);
                    break;
                case "%":
                    l += f.percent.symbol
                }
            }
            return l
        }
    }();
    v = function () {
        return /\/|dddd|ddd|dd|d|MMMM|MMM|MM|M|yyyy|yy|y|hh|h|HH|H|mm|m|ss|s|tt|t|fff|ff|f|zzz|zz|z|gg|g/g
    };
    w = function (a, b) {
        if (!b) return 0;
        for (var c, d = a.getTime(), e = 0, f = b.length; f > e; e++) {
            c = b[e].start;
            if (null === c || d >= c) return e
        }
        return 0
    };
    x = function (a, b, c, d) {
        var e = a.getFullYear();
        !d && b.eras && (e -= b.eras[c].offset);
        return e
    };
    ! function () {
        var a, b, c, d, e, f, g;
        a = function (a, b) {
            if (100 > b) {
                var c = new Date,
                    d = w(c),
                    e = x(c, a, d),
                    f = a.twoDigitYearMax;
                f = "string" == typeof f ? (new Date).getFullYear() % 100 + parseInt(f, 10) : f;
                b += e - e % 100;
                b > f && (b -= 100)
            }
            return b
        };
        b = function (a, b, c) {
            var d, e = a.days,
                i = a._upperDays;
            i || (a._upperDays = i = [g(e.names), g(e.namesAbbr), g(e.namesShort)]);
            b = f(b);
            if (c) {
                d = h(i[1], b); - 1 === d && (d = h(i[2], b))
            } else d = h(i[0], b);
            return d
        };
        c = function (a, b, c) {
            var d = a.months,
                e = a.monthsGenitive || a.months,
                i = a._upperMonths,
                j = a._upperMonthsGen;
            if (!i) {
                a._upperMonths = i = [g(d.names), g(d.namesAbbr)];
                a._upperMonthsGen = j = [g(e.names), g(e.namesAbbr)]
            }
            b = f(b);
            var k = h(c ? i[1] : i[0], b);
            0 > k && (k = h(c ? j[1] : j[0], b));
            return k
        };
        d = function (a, b) {
            var c = a._parseRegExp;
            if (c) {
                var d = c[b];
                if (d) return d
            } else a._parseRegExp = c = {};
            for (var e, f = s(a, b).replace(/([\^\$\.\*\+\?\|\[\]\(\)\{\}])/g, "\\\\$1"), g = ["^"], h = [], i = 0, j = 0, k = v(); null !== (e = k.exec(f));) {
                var l = f.slice(i, e.index);
                i = k.lastIndex;
                j += r(l, g);
                if (j % 2) g.push(e[0]);
                else {
                    var m, n = e[0],
                        o = n.length;
                    switch (n) {
                    case "dddd":
                    case "ddd":
                    case "MMMM":
                    case "MMM":
                    case "gg":
                    case "g":
                        m = "(\\D+)";
                        break;
                    case "tt":
                    case "t":
                        m = "(\\D*)";
                        break;
                    case "yyyy":
                    case "fff":
                    case "ff":
                    case "f":
                        m = "(\\d{" + o + "})";
                        break;
                    case "dd":
                    case "d":
                    case "MM":
                    case "M":
                    case "yy":
                    case "y":
                    case "HH":
                    case "H":
                    case "hh":
                    case "h":
                    case "mm":
                    case "m":
                    case "ss":
                    case "s":
                        m = "(\\d\\d?)";
                        break;
                    case "zzz":
                        m = "([+-]?\\d\\d?:\\d{2})";
                        break;
                    case "zz":
                    case "z":
                        m = "([+-]?\\d\\d?)";
                        break;
                    case "/":
                        m = "(\\/)";
                        break;
                    default:
                        throw "Invalid date format pattern '" + n + "'."
                    }
                    m && g.push(m);
                    h.push(e[0])
                }
            }
            r(f.slice(i), g);
            g.push("$");
            var p = g.join("").replace(/\s+/g, "\\s+"),
                q = {
                    regExp: p,
                    groups: h
                };
            return c[b] = q
        };
        e = function (a, b, c) {
            return b > a || a > c
        };
        f = function (a) {
            return a.split(" ").join(" ").toUpperCase()
        };
        g = function (a) {
            for (var b = [], c = 0, d = a.length; d > c; c++) b[c] = f(a[c]);
            return b
        };
        y = function (f, g, h) {
            f = o(f);
            var i = h.calendar,
                j = d(i, g),
                k = new RegExp(j.regExp).exec(f);
            if (null === k) return null;
            for (var l, m = j.groups, p = null, q = null, r = null, s = null, t = null, u = 0, v = 0, w = 0, x = 0, y = null, z = !1, A = 0, B = m.length; B > A; A++) {
                var C = k[A + 1];
                if (C) {
                    var D = m[A],
                        E = D.length,
                        F = parseInt(C, 10);
                    switch (D) {
                    case "dd":
                    case "d":
                        s = F;
                        if (e(s, 1, 31)) return null;
                        break;
                    case "MMM":
                    case "MMMM":
                        r = c(i, C, 3 === E);
                        if (e(r, 0, 11)) return null;
                        break;
                    case "M":
                    case "MM":
                        r = F - 1;
                        if (e(r, 0, 11)) return null;
                        break;
                    case "y":
                    case "yy":
                    case "yyyy":
                        q = 4 > E ? a(i, F) : F;
                        if (e(q, 0, 9999)) return null;
                        break;
                    case "h":
                    case "hh":
                        u = F;
                        12 === u && (u = 0);
                        if (e(u, 0, 11)) return null;
                        break;
                    case "H":
                    case "HH":
                        u = F;
                        if (e(u, 0, 23)) return null;
                        break;
                    case "m":
                    case "mm":
                        v = F;
                        if (e(v, 0, 59)) return null;
                        break;
                    case "s":
                    case "ss":
                        w = F;
                        if (e(w, 0, 59)) return null;
                        break;
                    case "tt":
                    case "t":
                        z = i.PM && (C === i.PM[0] || C === i.PM[1] || C === i.PM[2]);
                        if (!z && (!i.AM || C !== i.AM[0] && C !== i.AM[1] && C !== i.AM[2])) return null;
                        break;
                    case "f":
                    case "ff":
                    case "fff":
                        x = F * Math.pow(10, 3 - E);
                        if (e(x, 0, 999)) return null;
                        break;
                    case "ddd":
                    case "dddd":
                        t = b(i, C, 3 === E);
                        if (e(t, 0, 6)) return null;
                        break;
                    case "zzz":
                        var G = C.split(/:/);
                        if (2 !== G.length) return null;
                        l = parseInt(G[0], 10);
                        if (e(l, -12, 13)) return null;
                        var H = parseInt(G[1], 10);
                        if (e(H, 0, 59)) return null;
                        y = 60 * l + (n(C, "-") ? -H : H);
                        break;
                    case "z":
                    case "zz":
                        l = F;
                        if (e(l, -12, 13)) return null;
                        y = 60 * l;
                        break;
                    case "g":
                    case "gg":
                        var I = C;
                        if (!I || !i.eras) return null;
                        I = o(I.toLowerCase());
                        for (var J = 0, K = i.eras.length; K > J; J++)
                            if (I === i.eras[J].name.toLowerCase()) {
                                p = J;
                                break
                            }
                        if (null === p) return null
                    }
                }
            }
            var L, M = new Date,
                N = i.convert;
            L = N ? N.fromGregorian(M)[0] : M.getFullYear();
            null === q ? q = L : i.eras && (q += i.eras[p || 0].offset);
            null === r && (r = 0);
            null === s && (s = 1);
            if (N) {
                M = N.toGregorian(q, r, s);
                if (null === M) return null
            } else {
                M.setFullYear(q, r, s);
                if (M.getDate() !== s) return null;
                if (null !== t && M.getDay() !== t) return null
            }
            z && 12 > u && (u += 12);
            M.setHours(u, v, w, x);
            if (null !== y) {
                var O = M.getMinutes() - (y + M.getTimezoneOffset());
                M.setHours(M.getHours() + parseInt(O / 60, 10), O % 60)
            }
            return M
        }
    }();
    z = function (a, b, c) {
        var d, e = b["-"],
            f = b["+"];
        switch (c) {
        case "n -":
            e = " " + e;
            f = " " + f;
        case "n-":
            i(a, e) ? d = ["-", a.substr(0, a.length - e.length)] : i(a, f) && (d = ["+", a.substr(0, a.length - f.length)]);
            break;
        case "- n":
            e += " ";
            f += " ";
        case "-n":
            n(a, e) ? d = ["-", a.substr(e.length)] : n(a, f) && (d = ["+", a.substr(f.length)]);
            break;
        case "(n)":
            n(a, "(") && i(a, ")") && (d = ["-", a.substr(1, a.length - 2)])
        }
        return d || ["", a]
    };
    c.prototype.findClosestCulture = function (a) {
        return c.findClosestCulture.call(this, a)
    };
    c.prototype.format = function (a, b, d) {
        return c.format.call(this, a, b, d)
    };
    c.prototype.localize = function (a, b) {
        return c.localize.call(this, a, b)
    };
    c.prototype.parseInt = function (a, b, d) {
        return c.parseInt.call(this, a, b, d)
    };
    c.prototype.parseFloat = function (a, b, d) {
        return c.parseFloat.call(this, a, b, d)
    };
    c.prototype.culture = function (a) {
        return c.culture.call(this, a)
    };
    c.addCultureInfo = function (a, b, c) {
        var d = {}, e = !1;
        if ("string" != typeof a) {
            c = a;
            a = this.culture().name;
            d = this.cultures[a]
        } else if ("string" != typeof b) {
            c = b;
            e = null == this.cultures[a];
            d = this.cultures[a] || this.cultures["default"]
        } else {
            e = !0;
            d = this.cultures[b]
        }
        this.cultures[a] = j(!0, {}, d, c);
        e && (this.cultures[a].calendar = this.cultures[a].calendars.standard)
    };
    c.findClosestCulture = function (a) {
        var b;
        if (!a) return this.findClosestCulture(this.cultureSelector) || this.cultures["default"];
        "string" == typeof a && (a = a.split(","));
        if (k(a)) {
            var c, d, e = this.cultures,
                f = a,
                g = f.length,
                h = [];
            for (d = 0; g > d; d++) {
                a = o(f[d]);
                var i, j = a.split(";");
                c = o(j[0]);
                if (1 === j.length) i = 1;
                else {
                    a = o(j[1]);
                    if (0 === a.indexOf("q=")) {
                        a = a.substr(2);
                        i = parseFloat(a);
                        i = isNaN(i) ? 0 : i
                    } else i = 1
                }
                h.push({
                    lang: c,
                    pri: i
                })
            }
            h.sort(function (a, b) {
                return a.pri < b.pri ? 1 : a.pri > b.pri ? -1 : 0
            });
            for (d = 0; g > d; d++) {
                c = h[d].lang;
                b = e[c];
                if (b) return b
            }
            for (d = 0; g > d; d++) {
                c = h[d].lang;
                for (;;) {
                    var l = c.lastIndexOf("-");
                    if (-1 === l) break;
                    c = c.substr(0, l);
                    b = e[c];
                    if (b) return b
                }
            }
            for (d = 0; g > d; d++) {
                c = h[d].lang;
                for (var m in e) {
                    var n = e[m];
                    if (n.language == c) return n
                }
            }
        } else if ("object" == typeof a) return a;
        return b || null
    };
    c.format = function (a, b, c) {
        var d = this.findClosestCulture(c);
        a instanceof Date ? a = t(a, b, d) : "number" == typeof a && (a = u(a, b, d));
        return a
    };
    c.localize = function (a, b) {
        return this.findClosestCulture(b).messages[a] || this.cultures["default"].messages[a]
    };
    c.parseDate = function (a, b, c) {
        c = this.findClosestCulture(c);
        var d, e, f;
        if (b) {
            "string" == typeof b && (b = [b]);
            if (b.length)
                for (var g = 0, h = b.length; h > g; g++) {
                    var i = b[g];
                    if (i) {
                        d = y(a, i, c);
                        if (d) break
                    }
                }
        } else {
            f = c.calendar.patterns;
            for (e in f) {
                d = y(a, f[e], c);
                if (d) break
            }
        }
        return d || null
    };
    c.parseInt = function (a, b, d) {
        return p(c.parseFloat(a, b, d))
    };
    c.parseFloat = function (a, b, c) {
        if ("number" != typeof b) {
            c = b;
            b = 10
        }
        var g = this.findClosestCulture(c),
            h = 0 / 0,
            i = g.numberFormat;
        if (a.indexOf(g.numberFormat.currency.symbol) > -1) {
            a = a.replace(g.numberFormat.currency.symbol, "");
            a = a.replace(g.numberFormat.currency["."], g.numberFormat["."])
        }
        a.indexOf(g.numberFormat.percent.symbol) > -1 && (a = a.replace(g.numberFormat.percent.symbol, ""));
        a = a.replace(/ /g, "");
        if (e.test(a)) h = parseFloat(a);
        else if (!b && d.test(a)) h = parseInt(a, 16);
        else {
            var j = z(a, i, i.pattern[0]),
                k = j[0],
                l = j[1];
            if ("" === k && "(n)" !== i.pattern[0]) {
                j = z(a, i, "(n)");
                k = j[0];
                l = j[1]
            }
            if ("" === k && "-n" !== i.pattern[0]) {
                j = z(a, i, "-n");
                k = j[0];
                l = j[1]
            }
            k = k || "+";
            var m, n, o = l.indexOf("e");
            0 > o && (o = l.indexOf("E"));
            if (0 > o) {
                n = l;
                m = null
            } else {
                n = l.substr(0, o);
                m = l.substr(o + 1)
            }
            var p, q, r = i["."],
                s = n.indexOf(r);
            if (0 > s) {
                p = n;
                q = null
            } else {
                p = n.substr(0, s);
                q = n.substr(s + r.length)
            }
            var t = i[","];
            p = p.split(t).join("");
            var u = t.replace(/\u00A0/g, " ");
            t !== u && (p = p.split(u).join(""));
            var v = k + p;
            null !== q && (v += "." + q);
            if (null !== m) {
                var w = z(m, i, "-n");
                v += "e" + (w[0] || "+") + w[1]
            }
            f.test(v) && (h = parseFloat(v))
        }
        return h
    };
    c.culture = function (a) {
        "undefined" != typeof a && (this.cultureSelector = a);
        return this.findClosestCulture(a) || this.cultures["default"]
    }
}(this);
! function (a) {
    var b;
    b = "undefined" != typeof require && "undefined" != typeof exports && "undefined" != typeof module ? require("globalize") : a.Globalize;
    b.addCultureInfo("ru-RU", "default", {
        name: "ru-RU",
        englishName: "Russian (Russia)",
        nativeName: "русский (Россия)",
        language: "ru",
        numberFormat: {
            ",": " ",
            ".": ",",
            negativeInfinity: "-бесконечность",
            positiveInfinity: "бесконечность",
            percent: {
                pattern: ["-n%", "n%"],
                ",": " ",
                ".": ","
            },
            currency: {
                pattern: ["-n$", "n$"],
                ",": " ",
                ".": ",",
                symbol: " р."
            }
        },
        calendars: {
            standard: {
                "/": ".",
                firstDay: 1,
                days: {
                    names: ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"],
                    namesAbbr: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    namesShort: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"]
                },
                months: {
                    names: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь", ""],
                    namesAbbr: ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек", ""]
                },
                monthsGenitive: {
                    names: ["января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря", ""],
                    namesAbbr: ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек", ""]
                },
                monthsPrepositional: {
                    names: ["январе", "феврале", "марте", "апреле", "мае", "июне", "июле", "августе", "сентябре", "октябре", "ноябре", "декабре", ""],
                    namesAbbr: ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек", ""]
                },
                AM: null,
                PM: null,
                patterns: {
                    d: "dd.MM.yyyy",
                    D: "d MMMM yyyy 'г.'",
                    t: "H:mm",
                    T: "H:mm:ss",
                    f: "d MMMM yyyy 'г.' H:mm",
                    F: "d MMMM yyyy 'г.' H:mm:ss",
                    Y: "MMMM yyyy"
                }
            }
        }
    })
}(this);
! function (a) {
    var b;
    b = "undefined" != typeof require && "undefined" != typeof exports && "undefined" != typeof module ? require("globalize") : a.Globalize;
    b.addCultureInfo("de-DE", "default", {
        name: "de-DE",
        englishName: "German (Germany)",
        nativeName: "Deutsch (Deutschland)",
        language: "de",
        numberFormat: {
            ",": ".",
            ".": ",",
            NaN: "n. def.",
            negativeInfinity: "-unendlich",
            positiveInfinity: "+unendlich",
            percent: {
                pattern: ["-n%", "n%"],
                ",": ".",
                ".": ","
            },
            currency: {
                pattern: ["-n $", "n $"],
                ",": ".",
                ".": ",",
                symbol: "€"
            }
        },
        calendars: {
            standard: {
                "/": ".",
                firstDay: 1,
                days: {
                    names: ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"],
                    namesAbbr: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
                    namesShort: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"]
                },
                months: {
                    names: ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember", ""],
                    namesAbbr: ["Jan", "Feb", "Mrz", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez", ""]
                },
                AM: null,
                PM: null,
                eras: [{
                    name: "n. Chr.",
                    start: null,
                    offset: 0
                }],
                patterns: {
                    d: "dd.MM.yyyy",
                    D: "dddd, d. MMMM yyyy",
                    t: "HH:mm",
                    T: "HH:mm:ss",
                    f: "dddd, d. MMMM yyyy HH:mm",
                    F: "dddd, d. MMMM yyyy HH:mm:ss",
                    M: "dd MMMM",
                    Y: "MMMM yyyy"
                }
            }
        }
    })
}(this);
Globalize.culture("ru-RU");
Date.CultureInfo = {
    name: "ru-RU",
    englishName: "Russian (Russia)",
    nativeName: "русский (Россия)",
    dayNames: ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"],
    abbreviatedDayNames: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
    shortestDayNames: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
    firstLetterDayNames: ["В", "П", "В", "С", "Ч", "П", "С"],
    monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
    abbreviatedMonthNames: ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек"],
    amDesignator: "",
    pmDesignator: "",
    firstDayOfWeek: 1,
    twoDigitYearMax: 2029,
    dateElementOrder: "dmy",
    formatPatterns: {
        shortDate: "dd.MM.yyyy",
        longDate: "d MMMM yyyy 'г.'",
        shortTime: "H:mm",
        longTime: "H:mm:ss",
        fullDateTime: "d MMMM yyyy 'г.' H:mm:ss",
        sortableDateTime: "yyyy-MM-ddTHH:mm:ss",
        universalSortableDateTime: "yyyy-MM-dd HH:mm:ssZ",
        rfc1123: "ddd, dd MMM yyyy HH:mm:ss GMT",
        monthDay: "MMMM dd",
        yearMonth: "MMMM yyyy 'г.'"
    },
    regexPatterns: {
        jan: /^янв(арь)?/i,
        feb: /^фев(раль)?/i,
        mar: /^мар(т)?/i,
        apr: /^апр(ель)?/i,
        may: /^май/i,
        jun: /^июн(ь)?/i,
        jul: /^июл(ь)?/i,
        aug: /^авг(уст)?/i,
        sep: /^сен(тябрь)?/i,
        oct: /^окт(ябрь)?/i,
        nov: /^ноя(брь)?/i,
        dec: /^дек(абрь)?/i,
        sun: /^воскресенье/i,
        mon: /^понедельник/i,
        tue: /^вторник/i,
        wed: /^среда/i,
        thu: /^четверг/i,
        fri: /^пятница/i,
        sat: /^суббота/i,
        future: /^next/i,
        past: /^last|past|prev(ious)?/i,
        add: /^(\+|after|from)/i,
        subtract: /^(\-|before|ago)/i,
        yesterday: /^yesterday/i,
        today: /^t(oday)?/i,
        tomorrow: /^tomorrow/i,
        now: /^n(ow)?/i,
        millisecond: /^ms|milli(second)?s?/i,
        second: /^sec(ond)?s?/i,
        minute: /^min(ute)?s?/i,
        hour: /^h(ou)?rs?/i,
        week: /^w(ee)?k/i,
        month: /^m(o(nth)?s?)?/i,
        day: /^d(ays?)?/i,
        year: /^y((ea)?rs?)?/i,
        shortMeridian: /^(a|p)/i,
        longMeridian: /^(a\.?m?\.?|p\.?m?\.?)/i,
        timezone: /^((e(s|d)t|c(s|d)t|m(s|d)t|p(s|d)t)|((gmt)?\s*(\+|\-)\s*\d\d\d\d?)|gmt)/i,
        ordinalSuffix: /^\s*(st|nd|rd|th)/i,
        timeContext: /^\s*(\:|a|p)/i
    },
    abbreviatedTimeZoneStandard: {
        GMT: "-000",
        EST: "-0400",
        CST: "-0500",
        MST: "-0600",
        PST: "-0700"
    },
    abbreviatedTimeZoneDST: {
        GMT: "-000",
        EDT: "-0500",
        CDT: "-0600",
        MDT: "-0700",
        PDT: "-0800"
    }
};
Date.getMonthNumberFromName = function (a) {
    for (var b = Date.CultureInfo.monthNames, c = Date.CultureInfo.abbreviatedMonthNames, d = a.toLowerCase(), e = 0; e < b.length; e++)
        if (b[e].toLowerCase() == d || c[e].toLowerCase() == d) return e;
    return -1
};
Date.getDayNumberFromName = function (a) {
    for (var b = Date.CultureInfo.dayNames, c = Date.CultureInfo.abbreviatedDayNames, d = (Date.CultureInfo.shortestDayNames, a.toLowerCase()), e = 0; e < b.length; e++)
        if (b[e].toLowerCase() == d || c[e].toLowerCase() == d) return e;
    return -1
};
Date.isLeapYear = function (a) {
    return a % 4 === 0 && a % 100 !== 0 || a % 400 === 0
};
Date.getDaysInMonth = function (a, b) {
    return [31, Date.isLeapYear(a) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][b]
};
Date.getTimezoneOffset = function (a, b) {
    return b ? Date.CultureInfo.abbreviatedTimeZoneDST[a.toUpperCase()] : Date.CultureInfo.abbreviatedTimeZoneStandard[a.toUpperCase()]
};
Date.getTimezoneAbbreviation = function (a, b) {
    var c, d = b ? Date.CultureInfo.abbreviatedTimeZoneDST : Date.CultureInfo.abbreviatedTimeZoneStandard;
    for (c in d)
        if (d[c] === a) return c;
    return null
};
Date.prototype.clone = function () {
    return new Date(this.getTime())
};
Date.prototype.compareTo = function (a) {
    if (isNaN(this)) throw new Error(this);
    if (a instanceof Date && !isNaN(a)) return this > a ? 1 : a > this ? -1 : 0;
    throw new TypeError(a)
};
Date.prototype.equals = function (a) {
    return 0 === this.compareTo(a)
};
Date.prototype.between = function (a, b) {
    var c = this.getTime();
    return c >= a.getTime() && c <= b.getTime()
};
Date.prototype.addMilliseconds = function (a) {
    this.setMilliseconds(this.getMilliseconds() + a);
    return this
};
Date.prototype.addSeconds = function (a) {
    return this.addMilliseconds(1e3 * a)
};
Date.prototype.addMinutes = function (a) {
    return this.addMilliseconds(6e4 * a)
};
Date.prototype.addHours = function (a) {
    return this.addMilliseconds(36e5 * a)
};
Date.prototype.addDays = function (a) {
    return this.addMilliseconds(864e5 * a)
};
Date.prototype.addWeeks = function (a) {
    return this.addMilliseconds(6048e5 * a)
};
Date.prototype.addMonths = function (a) {
    var b = this.getDate();
    this.setDate(1);
    this.setMonth(this.getMonth() + a);
    this.setDate(Math.min(b, this.getDaysInMonth()));
    return this
};
Date.prototype.addYears = function (a) {
    return this.addMonths(12 * a)
};
Date.prototype.add = function (a) {
    if ("number" == typeof a) {
        this._orient = a;
        return this
    }
    var b = a;
    (b.millisecond || b.milliseconds) && this.addMilliseconds(b.millisecond || b.milliseconds);
    (b.second || b.seconds) && this.addSeconds(b.second || b.seconds);
    (b.minute || b.minutes) && this.addMinutes(b.minute || b.minutes);
    (b.hour || b.hours) && this.addHours(b.hour || b.hours);
    (b.month || b.months) && this.addMonths(b.month || b.months);
    (b.year || b.years) && this.addYears(b.year || b.years);
    (b.day || b.days) && this.addDays(b.day || b.days);
    return this
};
Date._validate = function (a, b, c, d) {
    if ("number" != typeof a) throw new TypeError(a + " is not a Number.");
    if (b > a || a > c) throw new RangeError(a + " is not a valid value for " + d + ".");
    return !0
};
Date.validateMillisecond = function (a) {
    return Date._validate(a, 0, 999, "milliseconds")
};
Date.validateSecond = function (a) {
    return Date._validate(a, 0, 59, "seconds")
};
Date.validateMinute = function (a) {
    return Date._validate(a, 0, 59, "minutes")
};
Date.validateHour = function (a) {
    return Date._validate(a, 0, 23, "hours")
};
Date.validateDay = function (a, b, c) {
    return Date._validate(a, 1, Date.getDaysInMonth(b, c), "days")
};
Date.validateMonth = function (a) {
    return Date._validate(a, 0, 11, "months")
};
Date.validateYear = function (a) {
    return Date._validate(a, 1, 9999, "seconds")
};
Date.prototype.set = function (a) {
    var b = a;
    b.millisecond || 0 === b.millisecond || (b.millisecond = -1);
    b.second || 0 === b.second || (b.second = -1);
    b.minute || 0 === b.minute || (b.minute = -1);
    b.hour || 0 === b.hour || (b.hour = -1);
    b.day || 0 === b.day || (b.day = -1);
    b.month || 0 === b.month || (b.month = -1);
    b.year || 0 === b.year || (b.year = -1); - 1 != b.millisecond && Date.validateMillisecond(b.millisecond) && this.addMilliseconds(b.millisecond - this.getMilliseconds()); - 1 != b.second && Date.validateSecond(b.second) && this.addSeconds(b.second - this.getSeconds()); - 1 != b.minute && Date.validateMinute(b.minute) && this.addMinutes(b.minute - this.getMinutes()); - 1 != b.hour && Date.validateHour(b.hour) && this.addHours(b.hour - this.getHours()); - 1 !== b.month && Date.validateMonth(b.month) && this.addMonths(b.month - this.getMonth()); - 1 != b.year && Date.validateYear(b.year) && this.addYears(b.year - this.getFullYear()); - 1 != b.day && Date.validateDay(b.day, this.getFullYear(), this.getMonth()) && this.addDays(b.day - this.getDate());
    b.timezone && this.setTimezone(b.timezone);
    b.timezoneOffset && this.setTimezoneOffset(b.timezoneOffset);
    return this
};
Date.prototype.clearTime = function () {
    this.setHours(0);
    this.setMinutes(0);
    this.setSeconds(0);
    this.setMilliseconds(0);
    return this
};
Date.prototype.isLeapYear = function () {
    var a = this.getFullYear();
    return a % 4 === 0 && a % 100 !== 0 || a % 400 === 0
};
Date.prototype.isWeekday = function () {
    return !(this.is().sat() || this.is().sun())
};
Date.prototype.getDaysInMonth = function () {
    return Date.getDaysInMonth(this.getFullYear(), this.getMonth())
};
Date.prototype.moveToFirstDayOfMonth = function () {
    return this.set({
        day: 1
    })
};
Date.prototype.moveToLastDayOfMonth = function () {
    return this.set({
        day: this.getDaysInMonth()
    })
};
Date.prototype.moveToDayOfWeek = function (a, b) {
    var c = (a - this.getDay() + 7 * (b || 1)) % 7;
    return this.addDays(0 === c ? c += 7 * (b || 1) : c)
};
Date.prototype.moveToMonth = function (a, b) {
    var c = (a - this.getMonth() + 12 * (b || 1)) % 12;
    return this.addMonths(0 === c ? c += 12 * (b || 1) : c)
};
Date.prototype.getDayOfYear = function () {
    return Math.floor((this - new Date(this.getFullYear(), 0, 1)) / 864e5)
};
Date.prototype.getWeekOfYear = function (a) {
    var b = this.getFullYear(),
        c = this.getMonth(),
        d = this.getDate(),
        e = a || Date.CultureInfo.firstDayOfWeek,
        f = 8 - new Date(b, 0, 1).getDay();
    8 == f && (f = 1);
    var g = (Date.UTC(b, c, d, 0, 0, 0) - Date.UTC(b, 0, 1, 0, 0, 0)) / 864e5 + 1,
        h = Math.floor((g - f + 7) / 7);
    if (h === e) {
        b--;
        var i = 8 - new Date(b, 0, 1).getDay();
        h = 2 == i || 8 == i ? 53 : 52
    }
    return h
};
Date.prototype.isDST = function () {
    console.log("isDST");
    return "D" == this.toString().match(/(E|C|M|P)(S|D)T/)[2]
};
Date.prototype.getTimezone = function () {
    return Date.getTimezoneAbbreviation(this.getUTCOffset, this.isDST())
};
Date.prototype.setTimezoneOffset = function (a) {
    var b = this.getTimezoneOffset(),
        c = -6 * Number(a) / 10;
    this.addMinutes(c - b);
    return this
};
Date.prototype.setTimezone = function (a) {
    return this.setTimezoneOffset(Date.getTimezoneOffset(a))
};
Date.prototype.getUTCOffset = function () {
    var a, b = -10 * this.getTimezoneOffset() / 6;
    if (0 > b) {
        a = (b - 1e4).toString();
        return a[0] + a.substr(2)
    }
    a = (b + 1e4).toString();
    return "+" + a.substr(1)
};
Date.prototype.getDayName = function (a) {
    return a ? Date.CultureInfo.abbreviatedDayNames[this.getDay()] : Date.CultureInfo.dayNames[this.getDay()]
};
Date.prototype.getMonthName = function (a) {
    return a ? Date.CultureInfo.abbreviatedMonthNames[this.getMonth()] : Date.CultureInfo.monthNames[this.getMonth()]
};
Date.prototype._toString = Date.prototype.toString;
Date.prototype.toString = function (a) {
    var b = this,
        c = function (a) {
            return 1 == a.toString().length ? "0" + a : a
        };
    return a ? a.replace(/dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|zz?z?/g, function (a) {
        switch (a) {
        case "hh":
            return c(b.getHours() < 13 ? b.getHours() : b.getHours() - 12);
        case "h":
            return b.getHours() < 13 ? b.getHours() : b.getHours() - 12;
        case "HH":
            return c(b.getHours());
        case "H":
            return b.getHours();
        case "mm":
            return c(b.getMinutes());
        case "m":
            return b.getMinutes();
        case "ss":
            return c(b.getSeconds());
        case "s":
            return b.getSeconds();
        case "yyyy":
            return b.getFullYear();
        case "yy":
            return b.getFullYear().toString().substring(2, 4);
        case "dddd":
            return b.getDayName();
        case "ddd":
            return b.getDayName(!0);
        case "dd":
            return c(b.getDate());
        case "d":
            return b.getDate().toString();
        case "MMMM":
            return b.getMonthName();
        case "MMM":
            return b.getMonthName(!0);
        case "MM":
            return c(b.getMonth() + 1);
        case "M":
            return b.getMonth() + 1;
        case "t":
            return b.getHours() < 12 ? Date.CultureInfo.amDesignator.substring(0, 1) : Date.CultureInfo.pmDesignator.substring(0, 1);
        case "tt":
            return b.getHours() < 12 ? Date.CultureInfo.amDesignator : Date.CultureInfo.pmDesignator;
        case "zzz":
        case "zz":
        case "z":
            return ""
        }
    }) : this._toString()
};
Date.now = function () {
    return new Date
};
Date.today = function () {
    return Date.now().clearTime()
};
Date.prototype._orient = 1;
Date.prototype.next = function () {
    this._orient = 1;
    return this
};
Date.prototype.last = Date.prototype.prev = Date.prototype.previous = function () {
    this._orient = -1;
    return this
};
Date.prototype._is = !1;
Date.prototype.is = function () {
    this._is = !0;
    return this
};
Number.prototype._dateElement = "day";
Number.prototype.fromNow = function () {
    var a = {};
    a[this._dateElement] = this;
    return Date.now().add(a)
};
Number.prototype.ago = function () {
    var a = {};
    a[this._dateElement] = -1 * this;
    return Date.now().add(a)
};
! function () {
    for (var a, b = Date.prototype, c = Number.prototype, d = "sunday monday tuesday wednesday thursday friday saturday".split(/\s/), e = "january february march april may june july august september october november december".split(/\s/), f = "Millisecond Second Minute Hour Day Week Month Year".split(/\s/), g = function (a) {
            return function () {
                if (this._is) {
                    this._is = !1;
                    return this.getDay() == a
                }
                return this.moveToDayOfWeek(a, this._orient)
            }
        }, h = 0; h < d.length; h++) b[d[h]] = b[d[h].substring(0, 3)] = g(h);
    for (var i = function (a) {
        return function () {
            if (this._is) {
                this._is = !1;
                return this.getMonth() === a
            }
            return this.moveToMonth(a, this._orient)
        }
    }, j = 0; j < e.length; j++) b[e[j]] = b[e[j].substring(0, 3)] = i(j);
    for (var k = function (a) {
        return function () {
            "s" != a.substring(a.length - 1) && (a += "s");
            return this["add" + a](this._orient)
        }
    }, l = function (a) {
            return function () {
                this._dateElement = a;
                return this
            }
        }, m = 0; m < f.length; m++) {
        a = f[m].toLowerCase();
        b[a] = b[a + "s"] = k(f[m]);
        c[a] = c[a + "s"] = l(a)
    }
}();
Date.prototype.toJSONString = function () {
    return this.toString("yyyy-MM-ddThh:mm:ssZ")
};
Date.prototype.toShortDateString = function () {
    return this.toString(Date.CultureInfo.formatPatterns.shortDatePattern)
};
Date.prototype.toLongDateString = function () {
    return this.toString(Date.CultureInfo.formatPatterns.longDatePattern)
};
Date.prototype.toShortTimeString = function () {
    return this.toString(Date.CultureInfo.formatPatterns.shortTimePattern)
};
Date.prototype.toLongTimeString = function () {
    return this.toString(Date.CultureInfo.formatPatterns.longTimePattern)
};
Date.prototype.getOrdinal = function () {
    switch (this.getDate()) {
    case 1:
    case 21:
    case 31:
        return "st";
    case 2:
    case 22:
        return "nd";
    case 3:
    case 23:
        return "rd";
    default:
        return "th"
    }
};
! function () {
    Date.Parsing = {
        Exception: function (a) {
            this.message = "Parse error at '" + a.substring(0, 10) + " ...'"
        }
    };
    for (var a = Date.Parsing, b = a.Operators = {
            rtoken: function (b) {
                return function (c) {
                    var d = c.match(b);
                    if (d) return [d[0], c.substring(d[0].length)];
                    throw new a.Exception(c)
                }
            },
            token: function () {
                return function (a) {
                    return b.rtoken(new RegExp("^s*" + a + "s*"))(a)
                }
            },
            stoken: function (a) {
                return b.rtoken(new RegExp("^" + a))
            },
            until: function (a) {
                return function (b) {
                    for (var c = [], d = null; b.length;) {
                        try {
                            d = a.call(this, b)
                        } catch (e) {
                            c.push(d[0]);
                            b = d[1];
                            continue
                        }
                        break
                    }
                    return [c, b]
                }
            },
            many: function (a) {
                return function (b) {
                    for (var c = [], d = null; b.length;) {
                        try {
                            d = a.call(this, b)
                        } catch (e) {
                            return [c, b]
                        }
                        c.push(d[0]);
                        b = d[1]
                    }
                    return [c, b]
                }
            },
            optional: function (a) {
                return function (b) {
                    var c = null;
                    try {
                        c = a.call(this, b)
                    } catch (d) {
                        return [null, b]
                    }
                    return [c[0], c[1]]
                }
            },
            not: function (b) {
                return function (c) {
                    try {
                        b.call(this, c)
                    } catch (d) {
                        return [null, c]
                    }
                    throw new a.Exception(c)
                }
            },
            ignore: function (a) {
                return a ? function (b) {
                    var c = null;
                    c = a.call(this, b);
                    return [null, c[1]]
                } : null
            },
            product: function () {
                for (var a = arguments[0], c = Array.prototype.slice.call(arguments, 1), d = [], e = 0; e < a.length; e++) d.push(b.each(a[e], c));
                return d
            },
            cache: function (b) {
                var c = {}, d = null;
                return function (e) {
                    try {
                        d = c[e] = c[e] || b.call(this, e)
                    } catch (f) {
                        d = c[e] = f
                    }
                    if (d instanceof a.Exception) throw d;
                    return d
                }
            },
            any: function () {
                var b = arguments;
                return function (c) {
                    for (var d = null, e = 0; e < b.length; e++)
                        if (null != b[e]) {
                            try {
                                d = b[e].call(this, c)
                            } catch (f) {
                                d = null
                            }
                            if (d) return d
                        }
                    throw new a.Exception(c)
                }
            },
            each: function () {
                var b = arguments;
                return function (c) {
                    for (var d = [], e = null, f = 0; f < b.length; f++)
                        if (null != b[f]) {
                            try {
                                e = b[f].call(this, c)
                            } catch (g) {
                                throw new a.Exception(c)
                            }
                            d.push(e[0]);
                            c = e[1]
                        }
                    return [d, c]
                }
            },
            all: function () {
                var a = arguments,
                    b = b;
                return b.each(b.optional(a))
            },
            sequence: function (c, d, e) {
                d = d || b.rtoken(/^\s*/);
                e = e || null;
                return 1 == c.length ? c[0] : function (b) {
                    for (var f = null, g = null, h = [], i = 0; i < c.length; i++) {
                        try {
                            f = c[i].call(this, b)
                        } catch (j) {
                            break
                        }
                        h.push(f[0]);
                        try {
                            g = d.call(this, f[1])
                        } catch (k) {
                            g = null;
                            break
                        }
                        b = g[1]
                    }
                    if (!f) throw new a.Exception(b);
                    if (g) throw new a.Exception(g[1]);
                    if (e) try {
                        f = e.call(this, f[1])
                    } catch (l) {
                        throw new a.Exception(f[1])
                    }
                    return [h, f ? f[1] : b]
                }
            },
            between: function (a, c, d) {
                d = d || a;
                var e = b.each(b.ignore(a), c, b.ignore(d));
                return function (a) {
                    var b = e.call(this, a);
                    return [[b[0][0], r[0][2]], b[1]]
                }
            },
            list: function (a, c, d) {
                c = c || b.rtoken(/^\s*/);
                d = d || null;
                return a instanceof Array ? b.each(b.product(a.slice(0, -1), b.ignore(c)), a.slice(-1), b.ignore(d)) : b.each(b.many(b.each(a, b.ignore(c))), px, b.ignore(d))
            },
            set: function (c, d, e) {
                d = d || b.rtoken(/^\s*/);
                e = e || null;
                return function (f) {
                    for (var g = null, h = null, i = null, j = null, k = [
                            [], f
                        ], l = !1, m = 0; m < c.length; m++) {
                        i = null;
                        h = null;
                        g = null;
                        l = 1 == c.length;
                        try {
                            g = c[m].call(this, f)
                        } catch (n) {
                            continue
                        }
                        j = [
                            [g[0]], g[1]
                        ];
                        if (g[1].length > 0 && !l) try {
                            i = d.call(this, g[1])
                        } catch (o) {
                            l = !0
                        } else l = !0;
                        l || 0 !== i[1].length || (l = !0);
                        if (!l) {
                            for (var p = [], q = 0; q < c.length; q++) m != q && p.push(c[q]);
                            h = b.set(p, d).call(this, i[1]);
                            if (h[0].length > 0) {
                                j[0] = j[0].concat(h[0]);
                                j[1] = h[1]
                            }
                        }
                        j[1].length < k[1].length && (k = j);
                        if (0 === k[1].length) break
                    }
                    if (0 === k[0].length) return k;
                    if (e) {
                        try {
                            i = e.call(this, k[1])
                        } catch (r) {
                            throw new a.Exception(k[1])
                        }
                        k[1] = i[1]
                    }
                    return k
                }
            },
            forward: function (a, b) {
                return function (c) {
                    return a[b].call(this, c)
                }
            },
            replace: function (a, b) {
                return function (c) {
                    var d = a.call(this, c);
                    return [b, d[1]]
                }
            },
            process: function (a, b) {
                return function (c) {
                    var d = a.call(this, c);
                    return [b.call(this, d[0]), d[1]]
                }
            },
            min: function (b, c) {
                return function (d) {
                    var e = c.call(this, d);
                    if (e[0].length < b) throw new a.Exception(d);
                    return e
                }
            }
        }, c = function (a) {
            return function () {
                var b = null,
                    c = [];
                arguments.length > 1 ? b = Array.prototype.slice.call(arguments) : arguments[0] instanceof Array && (b = arguments[0]);
                if (!b) return a.apply(null, arguments);
                for (var d = 0, e = b.shift(); d < e.length; d++) {
                    b.unshift(e[d]);
                    c.push(a.apply(null, b));
                    b.shift();
                    return c
                }
            }
        }, d = "optional not ignore cache".split(/\s/), e = 0; e < d.length; e++) b[d[e]] = c(b[d[e]]);
    for (var f = function (a) {
        return function () {
            return arguments[0] instanceof Array ? a.apply(null, arguments[0]) : a.apply(null, arguments)
        }
    }, g = "each any all".split(/\s/), h = 0; h < g.length; h++) b[g[h]] = f(b[g[h]])
}();
! function () {
    var a = function (b) {
        for (var c = [], d = 0; d < b.length; d++) b[d] instanceof Array ? c = c.concat(a(b[d])) : b[d] && c.push(b[d]);
        return c
    };
    Date.Grammar = {};
    Date.Translator = {
        hour: function (a) {
            return function () {
                this.hour = Number(a)
            }
        },
        minute: function (a) {
            return function () {
                this.minute = Number(a)
            }
        },
        second: function (a) {
            return function () {
                this.second = Number(a)
            }
        },
        meridian: function (a) {
            return function () {
                this.meridian = a.slice(0, 1).toLowerCase()
            }
        },
        timezone: function (a) {
            return function () {
                var b = a.replace(/[^\d\+\-]/g, "");
                b.length ? this.timezoneOffset = Number(b) : this.timezone = a.toLowerCase()
            }
        },
        day: function (a) {
            var b = a[0];
            return function () {
                this.day = Number(b.match(/\d+/)[0])
            }
        },
        month: function (a) {
            return function () {
                this.month = 3 == a.length ? Date.getMonthNumberFromName(a) : Number(a) - 1
            }
        },
        year: function (a) {
            return function () {
                var b = Number(a);
                this.year = a.length > 2 ? b : b + (b + 2e3 < Date.CultureInfo.twoDigitYearMax ? 2e3 : 1900)
            }
        },
        rday: function (a) {
            return function () {
                switch (a) {
                case "yesterday":
                    this.days = -1;
                    break;
                case "tomorrow":
                    this.days = 1;
                    break;
                case "today":
                    this.days = 0;
                    break;
                case "now":
                    this.days = 0;
                    this.now = !0
                }
            }
        },
        finishExact: function (a) {
            a = a instanceof Array ? a : [a];
            var b = new Date;
            this.year = b.getFullYear();
            this.month = b.getMonth();
            this.day = 1;
            this.hour = 0;
            this.minute = 0;
            this.second = 0;
            for (var c = 0; c < a.length; c++) a[c] && a[c].call(this);
            this.hour = "p" == this.meridian && this.hour < 13 ? this.hour + 12 : this.hour;
            if (this.day > Date.getDaysInMonth(this.year, this.month)) throw new RangeError(this.day + " is not a valid value for days.");
            var d = new Date(this.year, this.month, this.day, this.hour, this.minute, this.second);
            this.timezone ? d.set({
                timezone: this.timezone
            }) : this.timezoneOffset && d.set({
                timezoneOffset: this.timezoneOffset
            });
            return d
        },
        finish: function (b) {
            b = b instanceof Array ? a(b) : [b];
            if (0 === b.length) return null;
            for (var c = 0; c < b.length; c++) "function" == typeof b[c] && b[c].call(this);
            if (this.now) return new Date;
            var d = Date.today(),
                e = !(null == this.days && !this.orient && !this.operator);
            if (e) {
                var f, g, h;
                h = "past" == this.orient || "subtract" == this.operator ? -1 : 1;
                if (this.weekday) {
                    this.unit = "day";
                    f = Date.getDayNumberFromName(this.weekday) - d.getDay();
                    g = 7;
                    this.days = f ? (f + h * g) % g : h * g
                }
                if (this.month) {
                    this.unit = "month";
                    f = this.month - d.getMonth();
                    g = 12;
                    this.months = f ? (f + h * g) % g : h * g;
                    this.month = null
                }
                this.unit || (this.unit = "day");
                if (null == this[this.unit + "s"] || null != this.operator) {
                    this.value || (this.value = 1);
                    if ("week" == this.unit) {
                        this.unit = "day";
                        this.value = 7 * this.value
                    }
                    this[this.unit + "s"] = this.value * h
                }
                return d.add(this)
            }
            this.meridian && this.hour && (this.hour = this.hour < 13 && "p" == this.meridian ? this.hour + 12 : this.hour);
            this.weekday && !this.day && (this.day = d.addDays(Date.getDayNumberFromName(this.weekday) - d.getDay()).getDate());
            this.month && !this.day && (this.day = 1);
            return d.set(this)
        }
    };
    var b, c = Date.Parsing.Operators,
        d = Date.Grammar,
        e = Date.Translator;
    d.datePartDelimiter = c.rtoken(/^([\s\-\.\,\/\x27]+)/);
    d.timePartDelimiter = c.stoken(":");
    d.whiteSpace = c.rtoken(/^\s*/);
    d.generalDelimiter = c.rtoken(/^(([\s\,]|at|on)+)/);
    var f = {};
    d.ctoken = function (a) {
        var b = f[a];
        if (!b) {
            for (var d = Date.CultureInfo.regexPatterns, e = a.split(/\s+/), g = [], h = 0; h < e.length; h++) g.push(c.replace(c.rtoken(d[e[h]]), e[h]));
            b = f[a] = c.any.apply(null, g)
        }
        return b
    };
    d.ctoken2 = function (a) {
        return c.rtoken(Date.CultureInfo.regexPatterns[a])
    };
    d.h = c.cache(c.process(c.rtoken(/^(0[0-9]|1[0-2]|[1-9])/), e.hour));
    d.hh = c.cache(c.process(c.rtoken(/^(0[0-9]|1[0-2])/), e.hour));
    d.H = c.cache(c.process(c.rtoken(/^([0-1][0-9]|2[0-3]|[0-9])/), e.hour));
    d.HH = c.cache(c.process(c.rtoken(/^([0-1][0-9]|2[0-3])/), e.hour));
    d.m = c.cache(c.process(c.rtoken(/^([0-5][0-9]|[0-9])/), e.minute));
    d.mm = c.cache(c.process(c.rtoken(/^[0-5][0-9]/), e.minute));
    d.s = c.cache(c.process(c.rtoken(/^([0-5][0-9]|[0-9])/), e.second));
    d.ss = c.cache(c.process(c.rtoken(/^[0-5][0-9]/), e.second));
    d.hms = c.cache(c.sequence([d.H, d.mm, d.ss], d.timePartDelimiter));
    d.t = c.cache(c.process(d.ctoken2("shortMeridian"), e.meridian));
    d.tt = c.cache(c.process(d.ctoken2("longMeridian"), e.meridian));
    d.z = c.cache(c.process(c.rtoken(/^(\+|\-)?\s*\d\d\d\d?/), e.timezone));
    d.zz = c.cache(c.process(c.rtoken(/^(\+|\-)\s*\d\d\d\d/), e.timezone));
    d.zzz = c.cache(c.process(d.ctoken2("timezone"), e.timezone));
    d.timeSuffix = c.each(c.ignore(d.whiteSpace), c.set([d.tt, d.zzz]));
    d.time = c.each(c.optional(c.ignore(c.stoken("T"))), d.hms, d.timeSuffix);
    d.d = c.cache(c.process(c.each(c.rtoken(/^([0-2]\d|3[0-1]|\d)/), c.optional(d.ctoken2("ordinalSuffix"))), e.day));
    d.dd = c.cache(c.process(c.each(c.rtoken(/^([0-2]\d|3[0-1])/), c.optional(d.ctoken2("ordinalSuffix"))), e.day));
    d.ddd = d.dddd = c.cache(c.process(d.ctoken("sun mon tue wed thu fri sat"), function (a) {
        return function () {
            this.weekday = a
        }
    }));
    d.M = c.cache(c.process(c.rtoken(/^(1[0-2]|0\d|\d)/), e.month));
    d.MM = c.cache(c.process(c.rtoken(/^(1[0-2]|0\d)/), e.month));
    d.MMM = d.MMMM = c.cache(c.process(d.ctoken("jan feb mar apr may jun jul aug sep oct nov dec"), e.month));
    d.y = c.cache(c.process(c.rtoken(/^(\d\d?)/), e.year));
    d.yy = c.cache(c.process(c.rtoken(/^(\d\d)/), e.year));
    d.yyy = c.cache(c.process(c.rtoken(/^(\d\d?\d?\d?)/), e.year));
    d.yyyy = c.cache(c.process(c.rtoken(/^(\d\d\d\d)/), e.year));
    b = function () {
        return c.each(c.any.apply(null, arguments), c.not(d.ctoken2("timeContext")))
    };
    d.day = b(d.d, d.dd);
    d.month = b(d.M, d.MMM);
    d.year = b(d.yyyy, d.yy);
    d.orientation = c.process(d.ctoken("past future"), function (a) {
        return function () {
            this.orient = a
        }
    });
    d.operator = c.process(d.ctoken("add subtract"), function (a) {
        return function () {
            this.operator = a
        }
    });
    d.rday = c.process(d.ctoken("yesterday tomorrow today now"), e.rday);
    d.unit = c.process(d.ctoken("minute hour day week month year"), function (a) {
        return function () {
            this.unit = a
        }
    });
    d.value = c.process(c.rtoken(/^\d\d?(st|nd|rd|th)?/), function (a) {
        return function () {
            this.value = a.replace(/\D/g, "")
        }
    });
    d.expression = c.set([d.rday, d.operator, d.value, d.unit, d.orientation, d.ddd, d.MMM]);
    b = function () {
        return c.set(arguments, d.datePartDelimiter)
    };
    d.mdy = b(d.ddd, d.month, d.day, d.year);
    d.ymd = b(d.ddd, d.year, d.month, d.day);
    d.dmy = b(d.ddd, d.day, d.month, d.year);
    d.date = function (a) {
        return (d[Date.CultureInfo.dateElementOrder] || d.mdy).call(this, a)
    };
    d.format = c.process(c.many(c.any(c.process(c.rtoken(/^(dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|zz?z?)/), function (a) {
        if (d[a]) return d[a];
        throw Date.Parsing.Exception(a)
    }), c.process(c.rtoken(/^[^dMyhHmstz]+/), function (a) {
        return c.ignore(c.stoken(a))
    }))), function (a) {
        return c.process(c.each.apply(null, a), e.finishExact)
    });
    var g = {}, h = function (a) {
            return g[a] = g[a] || d.format(a)[0]
        };
    d.formats = function (a) {
        if (a instanceof Array) {
            for (var b = [], d = 0; d < a.length; d++) b.push(h(a[d]));
            return c.any.apply(null, b)
        }
        return h(a)
    };
    d._formats = d.formats(["yyyy-MM-ddTHH:mm:ss", "ddd, MMM dd, yyyy H:mm:ss tt", "ddd MMM d yyyy HH:mm:ss zzz", "d"]);
    d._start = c.process(c.set([d.date, d.time, d.expression], d.generalDelimiter, d.whiteSpace), e.finish);
    d.start = function (a) {
        try {
            var b = d._formats.call({}, a);
            if (0 === b[1].length) return b
        } catch (c) {}
        return d._start.call({}, a)
    }
}();
Date._parse = Date.parse;
Date.parse = function (a) {
    var b = null;
    if (!a) return null;
    try {
        b = Date.Grammar.start.call({}, a)
    } catch (c) {
        return null
    }
    return 0 === b[1].length ? b[0] : null
};
Date.getParseFunction = function (a) {
    var b = Date.Grammar.formats(a);
    return function (a) {
        var c = null;
        try {
            c = b.call({}, a)
        } catch (d) {
            return null
        }
        return 0 === c[1].length ? c[0] : null
    }
};
Date.parseExact = function (a, b) {
    return Date.getParseFunction(b)(a)
};
var TCS = TCS || {};
TCS.utils = TCS.utils || {};
! function (a, b, c) {
    "use strict";

    function d(a, b) {
        var e = a.shift(),
            f = b || c;
        return e ? d(a, f[e]) : b
    }

    function e(a, b) {
        var c = a && a.length > 0 ? a.split(".") : [];
        return c.length > 0 ? d(c, b) : null
    }
    a.utils.getParameterByName = function (a, b) {
        var d = b || c.location.search,
            e = new RegExp("[?&]" + a + "=([^&]*)").exec(d);
        return e && decodeURIComponent(e[1].replace(/\+/g, " "))
    };
    a.utils.filterGoogleValue = function (a) {
        var c = ["(direct)", "(not set)", "(organic)", "(referral)"];
        return -1 === b.inArray(a, c) ? a : ""
    };
    a.utils.addTracker = function (a, c, d) {
        var e, f, g, h = ["img", "script", "iframe"],
            i = -1 !== b.inArray(c, h) ? c : "img",
            j = "tcs-wrapper-trackers";
        if (!a) return !1;
        e = document.createElement(i);
        if ("iframe" === i) {
            e.setAttribute("frameborder", "0");
            e.setAttribute("scrolling", "no")
        } else if ("script" === i) {
            e.setAttribute("type", "text/javascript");
            e.setAttribute("async", "true")
        }
        e.setAttribute("border", "0");
        e.setAttribute("width", "1");
        e.setAttribute("height", "1");
        e.setAttribute("src", a);
        if (d && document.getElementById(d)) g = document.getElementById(d);
        else if (document.getElementById(j)) g = document.getElementById(j);
        else {
            f = document.createElement("div");
            f.setAttribute("id", j);
            f.setAttribute("class", "tcs-trackers-container");
            document.body.appendChild(f);
            g = document.getElementById(j)
        }
        g.appendChild(e);
        return !0
    };
    a.utils.checkEmailRFC = function (a) {
        return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/.test(a)
    };
    a.utils.singleOutMasterId = function (a) {
        var c, d, e, f, g, h = ["NORM", "CLBL", "DNBL", "TUNL", "OVDU"],
            i = null;
        if (b.isArray(a)) {
            e = a.length;
            for (c = 0; e > c; c++)
                if ("Дебетовые карты" === a[c].name) {
                    f = a[c].accounts;
                    g = f.length;
                    if (f && g)
                        for (d = 0; g > d; d++)
                            if (-1 !== b.inArray(f[d].status, h) && "RUB" === f[d].moneyAmount.currency.name) return f[d].id
                }
            for (c = 0; e > c; c++)
                if ("Кредитные карты" === a[c].name) {
                    f = a[c].accounts;
                    g = f.length;
                    if (f && g)
                        for (d = 0; g > d; d++) - 1 !== b.inArray(f[d].status, h) && (i = f[d].id)
                }
        }
        return i
    };
    a.utils.paramsToObject = function (a) {
        var b, d, e, f, g, h = a || c.location.search,
            i = {};
        if (!h) return null;
        b = h.slice(1).split("&");
        for (g = 0; g < b.length; g++) {
            f = b[g].indexOf("=");
            if (-1 === f) {
                d = b[g];
                e = null
            } else {
                d = b[g].substr(0, f);
                e = decodeURIComponent(b[g].substr(f + 1))
            }
            i[d] = e
        }
        return i
    };
    a.utils.newGoogleAnalyticsPush = function (a) {
        try {
            "undefined" != typeof ga && ga.apply(null, a)
        } catch (b) {
            c.console && c.console.log && c.console.log(b)
        }
    };
    a.utils._gaqPush = function (a) {
        try {
            "undefined" != typeof c._gaq && c._gaq.push(a)
        } catch (b) {
            c.console && c.console.log && c.console.log(b)
        }
    };
    a.utils.getUniqueIDByGA = function () {
        var a, c = b.cookie("__utma");
        if (c && "string" == typeof c) {
            a = c.split(".");
            if (a.length > 2) return a[1]
        }
        return null
    };
    a.utils.yandexMetricaHit = function (a) {
        try {
            "object" == typeof yaCounter10655317 && yaCounter10655317.hit(a)
        } catch (b) {}
    };
    a.utils.decodeResponse = function (a, b, c, d) {
        "OK" === b.resultCode ? a.resolve(b.payload, c, d) : a.reject(b.errorMessage || b.resultCode)
    };
    a.utils.getGadgetViewById = function (a) {
        return b("body").find("[data-gadget=" + a + "]").data("gadget")
    };
    a.utils.getGadgetTrackerById = function (b) {
        var c = a.utils.getGadgetViewById(b);
        return c.tracker
    };
    a.cacheTemplates = {};
    a.utils.getCompileTemplate = function (c) {
        if (a.cacheTemplates[c]) return a.cacheTemplates[c];
        var d = b("[data-template-name=" + c + "]");
        if (!d.length) throw new Error("Not found template: '" + c + "'");
        a.cacheTemplates[c] = Handlebars.compile(d.html());
        return a.cacheTemplates[c]
    };
    a.utils.lpad = function (a, b, c) {
        for (; a.length < b;) a = c + a;
        return a
    };
    var f = /\s+/,
        g = {
            on: function (a, b, c) {
                var d, e, g;
                if (!b) return this;
                a = a.split(f);
                d = this._callbacks || (this._callbacks = {});
                for (; e = a.shift();) {
                    g = d[e] || (d[e] = []);
                    g.push(b, c)
                }
                return this
            },
            off: function (a, b, c) {
                var d, e, g, h;
                if (!(e = this._callbacks)) return this;
                if (!(a || b || c)) {
                    delete this._callbacks;
                    return this
                }
                a = a ? a.split(f) : _.keys(e);
                for (; d = a.shift();)
                    if ((g = e[d]) && (b || c))
                        for (h = g.length - 2; h >= 0; h -= 2) b && g[h] !== b || c && g[h + 1] !== c || g.splice(h, 2);
                    else delete e[d];
                return this
            },
            trigger: function (a) {
                var b, c, d, e, g, h, i, j;
                if (!(c = this._callbacks)) return this;
                j = [];
                a = a.split(f);
                for (e = 1, g = arguments.length; g > e; e++) j[e - 1] = arguments[e];
                for (; b = a.shift();) {
                    (i = c.all) && (i = i.slice());
                    (d = c[b]) && (d = d.slice());
                    if (d)
                        for (e = 0, g = d.length; g > e; e += 2) d[e].apply(d[e + 1] || this, j);
                    if (i) {
                        h = [b].concat(j);
                        for (e = 0, g = i.length; g > e; e += 2) i[e].apply(i[e + 1] || this, h)
                    }
                }
                return this
            }
        }, h = _.clone(g);
    _.extend(h, {
        getSessionID: function () {
            return b.cookie("sessionid") || null
        },
        setSessionId: function (a) {
            b.cookie("sessionid", a, {
                path: "/"
            });
            TCS.Auth.Cfg.isAuthenticated = !0
        },
        isAuthenticate: function () {
            return !!this.getSessionID()
        },
        trashSession: function () {
            b.cookie("sessionid", null, {
                path: "/"
            });
            TCS.Auth.Cfg.isAuthenticated = !1
        },
        logout: function () {
            this.trashSession();
            this.trigger("session:end")
        },
        _prevTime: (new Date).getTime(),
        pingTimeout: 6e4,
        _pingTimer: null,
        _ping: function () {
            if (this._needPing && (new Date).getTime() - this._prevTime >= this.pingTimeout) {
                b.getJSON(TCS.getServiceURL("ping"), this.parsePing.bind(this));
                this._needPing = !1;
                this._prevTime = (new Date).getTime()
            }
        },
        _needPing: !1,
        start: function () {
            b(document).on("click", function (a) {
                this._needPing = !0;
                if (a.target && ("input" === a.target.tagName.toLowerCase() || "textarea" === a.target.tagName.toLocaleLowerCase()) && !b(a.target).data("isPinging")) {
                    b(a.target).data("isPinging", !0);
                    b(a.target).on("keypress", function () {
                        this._needPing = !0
                    }.bind(this))
                }
            }.bind(this));
            this._pingTimer = setInterval(this._ping.bind(this), 300)
        },
        parsePing: function (a) {
            var b = a.payload && a.payload.accessLevel;
            if (("ANONYMOUS" === b || "INSUFFICIENT_PRIVILEGES" === b || "NOT_AUTHENTICATED" === b) && c.location.pathname.indexOf("/bank/") >= 0) {
                clearInterval(this._pingTimer);
                this.trashSession();
                if (!TCS.windowExpiredMessage) {
                    TCS.Session.trashSession();
                    TCS.showExpiredMessage ? TCS.showExpiredMessage() : document.location.reload()
                }
            }
        }
    });
    b(function () {
        c.location.pathname.indexOf("/bank/") >= 0 && h.start()
    });
    a.utils.resolvePath = e;
    a.Events = g;
    a.Session = h
}(TCS, jQuery, window);
TCS.stripSlashes = function (a) {
    "use strict";
    return a.replace(/^\//, "").replace(/\/$/, "")
};
TCS.getStaticURL = function (a) {
    var b = TCS.staticServer ? TCS.staticServer : "https://static.tcsbank.ru",
        c = TCS.stripSlashes(a || "");
    "/" !== c[0] && (c = "/" + c);
    return b + c
};
TCS.getServiceURL = function (a) {
    "use strict";
    if (!a) throw new Error("Specify URL");
    var b = TCS.apiServer && /\w+/.test(TCS.apiServer) ? TCS.apiServer : "https://www.tcsbank.ru/api/v1/",
        c = TCS.Session.getSessionID(),
        d = TCS.stripSlashes(a),
        e = -1 === d.indexOf("?") ? "?" : "&",
        f = "";
    if ("/" !== d.substr(d.length - 1)) {
        -1 === d.indexOf("?") ? d += "/" : d = TCS.stripSlashes(d.substring(0, d.indexOf("?"))) + "/" + d.substring(d.indexOf("?")); - 1 !== d.indexOf("http:") && (d += "/")
    }
    f = c ? b + d + e + "sessionid=" + c : b + d;
    return f
};
TCS.getSocketURL = function (a) {
    "use strict";
    return TCS.getServiceURL(a).replace("https", "wss")
};
TCS.animateNumber = function (a, b, c, d) {
    "use strict";
    var e, f = a,
        g = Math.abs(a - b),
        h = +(Math.ceil(g / 200) + (.1 * Math.random() + .1).toFixed(d || 0));
    a > b && (h = -h);
    e = setInterval(function () {
        f += h;
        if (f >= b && h > 0 || b >= f && 0 > h) {
            clearInterval(e);
            f = b
        }
        return c(f.toFixed(d || 0))
    }, 15)
};
TCS.utils.checkResponse = function (a, b) {
    "use strict";
    if (a && a.resultCode && "ok" === a.resultCode.toLowerCase() && a.payload) {
        if (b && b.length > 0) {
            for (var c = 0; b[c] in a.payload;) c++;
            return c === b.length
        }
        return !0
    }
    return !1
};
TCS.utils.toFixed = function (a, b) {
    function c(a, b) {
        var c = Math.pow(10, b);
        return Math.round(a * c) / c
    }
    if (0 > b) throw new Error("toFixed() digits argument must be between 0 and 20");
    if (-1 === a.toString().indexOf(".")) return parseFloat(a);
    for (var d = a.toString().split(".")[1], e = a, f = 1; f <= d.length - b; f++) e = c(parseFloat(e), d.length - f);
    return parseFloat(e)
};
TCS.utils.getQueryDate = function (a, b) {
    "use strict";
    if (0 > a) {
        a = 11;
        b--
    }
    if (a > 11) {
        a = 0;
        b++
    }
    var c = new Date(b, a, 1, 0, 0, 0, 0);
    return c.getTime()
};
TCS.utils.getShortMonthYear = function (a) {
    "use strict";
    a = new Date(a);
    return a.toString("MM'yy")
};
TCS.utils.getCardInfoById = function (a) {
    "use strict";
    var b = TCS.getAccounts(),
        c = {
            name: null,
            icon: null,
            value: null,
            id: null
        };
    _.each(b, function (b) {
        b.accounts && _.each(b.accounts, function (b) {
            b.cardNumbers && _.each(b.cardNumbers, function (d) {
                if (d.id === a) {
                    c.name = d.name;
                    c.icon = b.accountIconType;
                    c.value = d.value;
                    c.id = a
                }
            })
        })
    });
    return c
};
TCS.utils.getFullMonthYear = function (a) {
    "use strict";
    a = new Date(a);
    return Date.CultureInfo.monthNames[a.getMonth()] + " " + a.getFullYear().toString()
};
TCS.utils.getWhenDate = function (a) {
    "use strict";
    a = new Date(a);
    var b = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"];
    return a.getDate() + " " + b[a.getMonth()] + " " + a.getFullYear().toString()
};
TCS.utils.dateToAccusativeMonth = function (a, b) {
    Date.CultureInfo.monthNamesDefault = Date.CultureInfo.monthNames;
    Date.CultureInfo.monthNames = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"];
    var c = a.toString(b);
    Date.CultureInfo.monthNames = Date.CultureInfo.monthNamesDefault;
    return c
};
TCS.utils.getMoneyWeight = function (a) {
    "use strict";
    var b = "";
    a = parseInt(a, 10);
    if (Math.abs(a) >= 1e3) {
        a /= 1e3;
        b = "K"
    }
    if (Math.abs(a) >= 1e3) {
        a /= 1e3;
        b = "M"
    }
    return Math.round(a).toString() + b
};
TCS.utils.getMoneyWeightWithCurrency = function (a) {
    "use strict";
    var b = "",
        c = accounting.settings.locale.ru.RUB;
    a = parseInt(a, 10);
    if (Math.abs(a) >= 1e3) {
        a /= 1e3;
        b = c.shortThousand
    }
    if (Math.abs(a) >= 1e3) {
        a /= 1e3;
        b = c.shortMillion
    }
    parseInt(a) !== a && (a = a.toFixed(1));
    return a + " " + b
};
TCS.utils.getCrossCurrencyRate = function (a, b, c, d, e) {
    $.getJSON(TCS.getServiceURL("currency_rates"), function (f) {
        var g, h, i;
        if (f.payload && "OK" === f.resultCode) {
            g = _.filter(f.payload.rates, function (b) {
                return b.category === a
            });
            h = _.find(g, function (a) {
                return a.fromCurrency.name === b && a.toCurrency.name === c
            });
            i = _.find(g, function (a) {
                return a.fromCurrency.name === c && a.toCurrency.name === b
            })
        }
        g.length && (h || i) ? d(h ? h[e || "buy"] : 1 / i[e || "buy"], i ? i[e || "buy"] : 1 / h[e || "buy"]) : d(0, 0)
    })
};
TCS.utils.getOperationType = function (a, b) {
    var c = {
        RUB: {
            USD: "sell",
            EUR: "sell"
        },
        USD: {
            RUB: "buy",
            EUR: "sell"
        },
        EUR: {
            RUB: "buy",
            USD: "sell"
        }
    };
    return c[a][b]
};
TCS.utils.convertMoney = function (a, b, c, d, e, f) {
    var g = TCS.utils.getOperationType(b, c);
    TCS.utils.getCrossCurrencyRate(a, b, c, function (a, b) {
        var c;
        c = TCS.utils.toFixed(d * b, f || 2);
        e(c, a, b)
    }, g)
};
TCS.utils.getSocialAppId = function (a, b) {
    "use strict";
    b = b || window.location.hostname;
    var c = {
        fb: {
            localhost: 355767164492039,
            "uat.tcsbank.ru": 0xeab199a1ef29,
            "www.uat.tcsbank.ru": 0xeab199a1ef29,
            "192.168.1.232": 0xeadc4ee94a1d,
            "192.168.16.32": 337382189671251,
            "www.tcsbank.ru": 0x77c048183139,
            "tcsbank.ru": 0x77c048183139
        },
        vk: {
            localhost: 3013656,
            "testtcsbank.ru": 3488857,
            "uat.tcsbank.ru": 3012772,
            "www.uat.tcsbank.ru": 3012772,
            "192.168.1.232": 3013657,
            "192.168.16.32": 3013663,
            "www.tcsbank.ru": 3013666,
            "tcsbank.ru": 3013666
        },
        ok: {
            localhost: 91066368,
            "uat.tcsbank.ru": 91066368,
            "www.uat.tcsbank.ru": 91066368,
            "www.tcsbank.ru": 91066368,
            "tcsbank.ru": 91066368,
            "192.168.1.232": 91066368,
            "192.168.16.32": 91066368
        },
        google: {
            localhost: "705012429335-hvsvbkghon72k10cln792irksmslenni.apps.googleusercontent.com"
        }
    };
    return c[a] && c[a][b]
};
TCS.utils.convertTimeZone = function (a) {
    "use strict";
    return a.addMinutes(a.getTimezoneOffset()).addMinutes(240)
};
TCS.utils.getGMTTime = function (a) {
    "use strict";
    return a + -6e4 * (new Date).getTimezoneOffset()
};
TCS.utils.withoutTimezoneOffset = function (a) {
    "use strict";
    return new Date(a).addMinutes(new Date(a).getTimezoneOffset())
};
TCS.utils.getStartDayTimestamp = function (a) {
    "use strict";
    try {
        var b = a.match(/^(\d{2})\.(\d{2})\.(\d{4})$/);
        return new Date([b[2], b[1], b[3]].join("/") + " 00:00 UTC+0").getTime()
    } catch (c) {
        return 0
    }
};
TCS.utils.getStartDay = function (a) {
    "use strict";
    try {
        var b = a.match(/^(\d{2})\.(\d{2})\.(\d{4})$/);
        return new Date([b[2], b[1], b[3]].join("/") + " 00:00 UTC+0")
    } catch (c) {
        return 0
    }
};
String.prototype.isJSON = function () {
    "use strict";
    var a = this;
    if (/^\s*$/.test(a)) return !1;
    a = a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@");
    a = a.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]");
    a = a.replace(/(?:^|:|,)(?:\s*\[)+/g, "");
    return /^[\],:{}\s]*$/.test(a)
};
TCS.utils.caret = function (a, b) {
    if (0 != this.length) {
        if ("number" == typeof a) {
            b = "number" == typeof b ? b : a;
            return this.each(function () {
                if (this.setSelectionRange) this.setSelectionRange(a, b);
                else if (this.createTextRange) {
                    var c = this.createTextRange();
                    c.collapse(!0);
                    c.moveEnd("character", b);
                    c.moveStart("character", a);
                    c.select()
                }
            })
        }
        if (this[0].setSelectionRange) {
            a = this[0].selectionStart;
            b = this[0].selectionEnd
        } else if (document.selection && document.selection.createRange) {
            var c = document.selection.createRange();
            a = 0 - c.duplicate().moveStart("character", -1e5);
            b = a + c.text.length
        }
        return {
            begin: a,
            end: b
        }
    }
};
TCS.utils.waiting = function (a, b) {
    "use strict";
    var c = $(a),
        d = c.find(".tcs-view-waiting");
    if (!d.length) {
        d = $('<span class="tcs-view tcs-view-waiting"></span>').hide();
        c.append(d)
    }
    return d[b ? "show" : "hide"]()
};
TCS.utils.checkAddress = function (a) {
    "use strict";
    return null === a || "" === a || void 0 === a ? !1 : null === a.zipCode || "" === a.zipCode || void 0 === a.zipCode ? !1 : !0
};
TCS.utils.formatAddress = function (a) {
    "use strict";
    return TCS.utils.checkAddress(a) ? (a.zipCode.value + " " + (a.state ? " " + a.state : "") + (a.district ? " " + a.district : "") + (a.city ? " " + a.city : "") + (a.settlement ? " " + a.settlement : "") + (a.streetAddress ? " " + a.streetAddress : "") + (a.houseNumber ? " " + a.houseNumber : "") + (a.buildingNumber ? " К. " + a.buildingNumber : "") + (a.constructionNumber ? " СТР. " + a.constructionNumber : "") + (a.apartmentNumber ? " КВ. " + a.apartmentNumber : "")).toUpperCase() : "<Не указан>"
};
TCS.utils.clearEmberMeta = function (a, b) {
    "use strict";
    a[b] = a[b + "_meta"] = void 0;
    return a
};
Function.prototype.bind || (Function.prototype.bind = function (a) {
    "use strict";
    if ("function" != typeof this) throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
    var b = Array.prototype.slice.call(arguments, 1),
        c = this,
        d = function () {}, e = function () {
            return c.apply(this instanceof d ? this : a || window, b.concat(Array.prototype.slice.call(arguments)))
        };
    d.prototype = this.prototype;
    e.prototype = new d;
    e.prototype.toString = function () {
        return "Bound: " + d.toString()
    };
    return e
});
TCS.utils.declOfNum = function (a, b) {
    "use strict";
    var c = [2, 0, 1, 1, 1, 2];
    return b[a % 100 > 4 && 20 > a % 100 ? 2 : c[5 > a % 10 ? a % 10 : 5]]
};
String.prototype.trim || (String.prototype.trim = function () {
    "use strict";
    return this.replace(/^\s+|\s+$/g, "")
});
jQuery.cachedScript = function (a, b) {
    "use strict";
    b = $.extend(b || {}, {
        dataType: "script",
        cache: !0,
        url: a
    });
    return jQuery.ajax(b)
};
! function () {
    "use strict";

    function a() {
        var a = _.map(e.anotherCurrenciesName, function (a) {
            return {
                operation: "currency_rate_history",
                key: a,
                params: {
                    currency: a
                }
            }
        });
        $.post(TCS.getServiceURL("grouped_requests"), {
            requestsData: JSON.stringify(a)
        }).done(function (a) {
            b(a) ? c(a.payload) : f.rejectWith(this, [a])
        });
        return f
    }

    function b(a) {
        return a && a.payload && "OK" === a.resultCode && _.every(a.payload, function (a) {
            return a && "OK" === a.resultCode
        })
    }

    function c(a) {
        _.each(e.anotherCurrenciesName, function (b) {
            var c = a[b];
            c && c.payload && _.each(c.payload, function (a) {
                var c = new Date(a[0].milliseconds).toString("yyMMdd"),
                    d = this.anotherCurrenciesRates[c] || {};
                d[b] = a[1].value;
                this.anotherCurrenciesRates[c] = d
            }, this)
        }, e);
        f.resolve(e.anotherCurrenciesRates)
    }

    function d(a, b, c, d) {
        var e, f, g, h = d ? new Date(d) : (new Date).add(-1).days(),
            i = 0;
        g = TCS.utils.getCurrencyRatesObj(h);
        e = g.USD;
        f = g.EUR;
        var j = {
            RUB: {
                to: {
                    RUB: 1,
                    USD: 1 / e,
                    EUR: 1 / f
                }
            },
            EUR: {
                to: {
                    RUB: f,
                    USD: f / e,
                    EUR: 1
                }
            },
            USD: {
                to: {
                    RUB: e,
                    USD: 1,
                    EUR: e / f
                }
            }
        };
        j[a] && j[a].to[b] && (i = parseFloat((j[a].to[b] * parseFloat(c)).toFixed(2)));
        return i
    }
    var e = {
        anotherCurrenciesName: ["USD", "EUR"],
        anotherCurrenciesRates: {},
        dataCurrency: "RUB"
    }, f = new $.Deferred;
    TCS.utils.getCurrencyRatesObj = function (a) {
        var b, c = a.toString("yyMMdd"),
            d = _.keys(e.anotherCurrenciesRates).sort();
        b = _.find(d, function (a) {
            return a >= c
        });
        b = b || d[0];
        return e.anotherCurrenciesRates[b]
    };
    TCS.utils.convertToCurrency = function (b, c, e, g, h) {
        if (b && c && b === c) return e;
        var i = new $.Deferred;
        if ("resolved" !== f.state()) {
            a();
            f.done(function () {
                i.resolveWith(TCS.utils, [d(b, c, e, g)])
            });
            return h ? 0 / 0 : i.promise()
        }
        i.resolveWith(TCS.utils, [d(b, c, e, g)]);
        if (h) return d(b, c, e, g);
        i.resolveWith(TCS.utils, [d(b, c, e, g)]);
        return i.promise()
    };
    TCS.utils.getPluralForm = function (a) {
        return a % 10 === 1 && a % 100 !== 11 ? 0 : a % 10 >= 2 && 4 >= a % 10 && (10 > a % 100 || a % 100 >= 20) ? 1 : 2
    };
    $(function () {
        var a = TCS.Session.isAuthenticate(),
            b = $(".Mob-App-Link");
        a && b.length && b.attr("href", "/mob-app/")
    })
}();
var TCS = TCS || {};
! function (a, b, c) {
    "use strict";
    var d = function (a, d) {
        if (a && a.name && d) {
            this.name = a.name;
            this.timeout = a.timeout || 1e4;
            this.PERIOD = 100;
            this.time = 0;
            this.callback = d;
            this.old_value = c.cookie(a.name);
            this._init()
        } else b.console && b.console.log && b.console.log("Need required parameters")
    };
    d.prototype._init = function () {
        if (c.cookie(this.name) === this.old_value) {
            this.time += this.PERIOD;
            this.time < this.timeout ? setTimeout(function () {
                this._init()
            }.bind(this), this.PERIOD) : this.callback(!1)
        } else this.callback(!0)
    };
    a.CookieWatcher = d
}(TCS, window, jQuery);
var TCS = TCS || {};
! function (a, b, c) {
    "use strict";
    var d = function (a) {
        this.name = a.name;
        this.value = a.value;
        this.status = a.status;
        this.method = a.method;
        a.update && a.update()
    };
    d.prototype.toObject = function () {
        return {
            name: this.name,
            value: this.value,
            method: this.method
        }
    };
    d.prototype.onReady = function (a) {
        var c = this;
        "ready" === this.status ? a(c.toObject()) : b.setTimeout(function () {
            c.onReady(a)
        }, 100)
    };
    a.properties = a.properties || {
        _init: !1,
        PRIMARY_PARAMS: ["utm_source", "utm_content", "utm_campaign", "utm_term", "utm_medium", "sid", "wm"],
        params: {},
        _fillParamsFromGet: function () {
            var b, d = a.utils.paramsToObject();
            if (d)
                for (b in d) d.hasOwnProperty(b) && this.setParam({
                    name: b,
                    value: d[b],
                    method: "get",
                    status: -1 !== c.inArray(b, this.PRIMARY_PARAMS) ? "processed" : "ready"
                })
        },
        _processPrimaryParamsLaw: function (b) {
            return a.utils.getParameterByName("utm_source") ? a.utils.getParameterByName(b) || "" : null
        },
        _processPrimaryParamsUtmTerm: function () {
            var d, e, f, g, h, i = "";
            if (c.cookie("__utmz") && -1 !== c.cookie("__utmz").indexOf("utmctr=")) {
                d = c.cookie("__utmz");
                e = d.indexOf("utmctr=") + "utmctr=".length;
                f = -1 === d.indexOf("|", e) ? d.length : d.indexOf("|", e);
                i = d.substring(e, f);
                h = "parsing __utmz"
            } else {
                g = a.utils.getParameterByName("text", b.document.referrer) || a.utils.getParameterByName("oq", b.document.referrer) || a.utils.getParameterByName("q", b.document.referrer) || a.utils.getParameterByName("query", b.document.referrer);
                if (g) {
                    i = g;
                    h = "search inside referrer"
                }
            }
            a.utils.filterGoogleValue(i) ? this.setParam({
                name: "utm_term",
                status: "ready",
                value: i,
                method: h
            }) : this.setParam({
                name: "utm_term",
                status: "ready",
                value: "",
                method: "no_info"
            })
        },
        _processPrimaryParamsUtmCampaign: function () {
            var b, d, e, f = "";
            if (c.cookie("__utmz") && -1 !== c.cookie("__utmz").indexOf("utmccn=")) {
                b = c.cookie("__utmz");
                d = b.indexOf("utmccn=") + "utmccn=".length;
                e = -1 === b.indexOf("|", d) ? b.length : b.indexOf("|", d);
                f = b.substring(d, e)
            }
            a.utils.filterGoogleValue(f) ? this.setParam({
                name: "utm_campaign",
                status: "ready",
                value: f,
                method: "parsing __utmz"
            }) : this.setParam({
                name: "utm_campaign",
                status: "ready",
                value: "",
                method: "no_info"
            })
        },
        _processPrimaryParamsHandler: function (b) {
            var d, e = {}, f = this._processPrimaryParamsLaw,
                g = document.referrer.split("/")[2];
            g = g && -1 === g.indexOf("tcsbank.ru") ? g : "";
            e.name = b;
            e.status = "ready";
            if (null !== f(b)) {
                e.method = "get";
                e.value = f(b)
            } else if (null !== c.cookie(b)) {
                e.method = "cookie";
                e.value = c.cookie(b)
            } else if ("utm_source" === b && g) {
                e.method = "referer";
                e.value = g
            } else if ("utm_campaign" === b || "utm_term" === b) {
                e.status = "processed";
                d = new TCS.CookieWatcher({
                    name: "__utmz",
                    timeout: 5e3
                }, function () {
                    "utm_campaign" === b && a.properties._processPrimaryParamsUtmCampaign();
                    "utm_term" === b && a.properties._processPrimaryParamsUtmTerm()
                })
            } else {
                e.method = "no_info";
                e.value = ""
            }
            return e
        },
        _processPrimaryParams: function () {
            function a(a, b, c) {
                var d = new Date;
                d.setDate(d.getDate() + c);
                var e = _.map(b.split("|"), function (a) {
                    return encodeURIComponent(a)
                }).join("|");
                document.cookie = a + "=" + e + ";expires=" + d.toUTCString() + ";path=/"
            }
            var b;
            for (b = 0; b < this.PRIMARY_PARAMS.length; b++) this.setParam(this._processPrimaryParamsHandler(this.PRIMARY_PARAMS[b]), function (b) {
                var d = b.name,
                    e = b.value,
                    f = c.cookie(d),
                    g = d + "_history",
                    h = "|",
                    i = c.cookie(g) || "",
                    j = i ? i.split(h) : null,
                    k = 10;
                if (f && f !== e) {
                    j && j.length >= k - 1 && (i = j.slice(0, k - 1).join(h));
                    i = i ? f + h + i : f;
                    a(g, i, 365)
                }
                a(d, e, 60)
            })
        },
        init: function () {
            this._fillParamsFromGet();
            this._processPrimaryParams();
            this._init = !0
        },
        setParam: function (a, b) {
            this.params[a.name] = this.params[a.name] ? c.extend(!0, this.params[a.name], a) : new d(a);
            b && this.params[a.name].onReady(b)
        },
        getParam: function (a) {
            return this.params[a] ? this.params[a] : {}
        },
        isReady: function () {
            var a, b = this.params,
                c = !0;
            for (a in b) b.hasOwnProperty(a) && "function" != typeof b[a] && (c &= "ready" === this.params[a].status);
            return this._init && c
        },
        onReady: function (a) {
            var c = this;
            this.isReady() ? a(this.params) : b.setTimeout(function () {
                c.onReady(a)
            }, 100)
        }
    };
    a.properties.init()
}(TCS, window, jQuery);
var TCS = TCS || {};
! function (a, b, c) {
    "use strict";
    a.identify = a.identify || {
        dataIsTooOld: function (a) {
            return !a || (new Date).getTime() - a > 864e5
        },
        getUTMA: function () {
            return a.utils.getUniqueIDByGA()
        },
        sendData: function (b) {
            var d = a.getServiceURL("webuser");
            return b ? c.getJSON(d, b) : c.getJSON(d)
        },
        set: function (a, b) {
            c.permaCookie("wuid", a);
            c.permaCookie("wuid_last_call_time", (new Date).getTime());
            c.permaCookie("wuid_auth", b)
        },
        callTracker: function (a) {
            var b = document.createElement("img"),
                c = document.getElementById("datamind_container");
            if (c) {
                b.setAttribute("border", "0");
                b.setAttribute("width", "1");
                b.setAttribute("height", "1");
                b.setAttribute("src", "https://sync.pool.datamind.ru/image?source=tcs&id=" + a);
                c.appendChild(b)
            }
        },
        init: function (b) {
            var d = this.getUTMA(),
                e = c.permaCookie("wuid"),
                f = {};
            if (!e || this.dataIsTooOld(c.permaCookie("wuid_last_call_time")) || b && "true" != c.permaCookie("wuid_auth")) {
                d && (f.ga_utma = d);
                e && (f.wuid = e);
                this.sendData(f).done(function (c) {
                    if (a.utils.checkResponse(c, ["wuid"])) {
                        this.set(c.payload.wuid, b);
                        this.callTracker(c.payload.wuid)
                    }
                }.bind(this))
            }
        }
    }
}(TCS, window, jQuery);
! function (a) {
    a.fingerprint = function () {
        return [navigator.userAgent.replace(/;|::/g, ""), [screen.height, screen.width, screen.colorDepth].join("x"), (new Date).getTimezoneOffset(), !! window.sessionStorage, !! window.localStorage, a.map(navigator.plugins, function (b) {
            return [b.name.replace(/;|::/g, ""), b.description.replace(/;|::/g, ""), a.map(b, function (a) {
                return [a.type, a.suffixes].join("~").replace(/;|::/g, "")
            }).join(",")].join("::")
        }).join(";")].join("###")
    }
}(jQuery);
var TCS = TCS || {};
! function (a, b, c) {
    "use strict";
    a.Log = b.inherit({
        __constructor: function () {
            this.level = void 0;
            this.component = void 0;
            this.rows = [];
            this.messageSeparate = "; "
        },
        setLevel: function (a) {
            this.level = a;
            return this.level
        },
        setComponent: function (a) {
            this.component = a;
            return this.component
        },
        addRow: function (a) {
            return this.rows.push(a)
        },
        send: function () {
            (new c).src = this.__self.config.url + "&" + b.param({
                message: this._getSendMessage(),
                component: this.component,
                level: this.level
            })
        },
        _getSendMessage: function () {
            return this.rows.join(this.messageSeparate)
        }
    }, {
        config: {
            url: TCS.getServiceURL("log?")
        }
    })
}(TCS, jQuery, Image);
var TCS = TCS || {};
! function (a, b) {
    "use strict";
    b.onerror = function (c, d, e) {
        var f = new a;
        f.setLevel("error");
        f.setComponent("js");
        f.addRow("Error message: " + c);
        f.addRow("URL: " + d);
        f.addRow("Line number: " + e);
        f.addRow("User-agent: " + b.navigator.userAgent);
        f.addRow("Href: " + b.location.href);
        f.send()
    }
}(TCS.Log, this);
var TCS = TCS || {};
! function (a, b) {
    "use strict";
    var c = b.performance,
        d = new a;
    c && c.timing && setTimeout(function () {
        d.setLevel("info");
        d.setComponent("browser_performance_timing");
        d.addRow("Timing: " + JSON.stringify(c.timing));
        d.addRow("User-agent: " + b.navigator.userAgent);
        d.addRow("Href: " + b.location.href);
        d.send();
        d = null
    }, 1e4)
}(TCS.Log, this);
var swfobject = function () {
    function a() {
        if (!R) {
            try {
                var a = K.getElementsByTagName("body")[0].appendChild(q("span"));
                a.parentNode.removeChild(a)
            } catch (b) {
                return
            }
            R = !0;
            for (var c = N.length, d = 0; c > d; d++) N[d]()
        }
    }

    function b(a) {
        R ? a() : N[N.length] = a
    }

    function c(a) {
        if (typeof J.addEventListener != C) J.addEventListener("load", a, !1);
        else if (typeof K.addEventListener != C) K.addEventListener("load", a, !1);
        else if (typeof J.attachEvent != C) r(J, "onload", a);
        else if ("function" == typeof J.onload) {
            var b = J.onload;
            J.onload = function () {
                b();
                a()
            }
        } else J.onload = a
    }

    function d() {
        M ? e() : f()
    }

    function e() {
        var a = K.getElementsByTagName("body")[0],
            b = q(D);
        b.setAttribute("type", G);
        var c = a.appendChild(b);
        if (c) {
            var d = 0;
            ! function () {
                if (typeof c.GetVariable != C) {
                    var e = c.GetVariable("$version");
                    if (e) {
                        e = e.split(" ")[1].split(",");
                        U.pv = [parseInt(e[0], 10), parseInt(e[1], 10), parseInt(e[2], 10)]
                    }
                } else if (10 > d) {
                    d++;
                    setTimeout(arguments.callee, 10);
                    return
                }
                a.removeChild(b);
                c = null;
                f()
            }()
        } else f()
    }

    function f() {
        var a = O.length;
        if (a > 0)
            for (var b = 0; a > b; b++) {
                var c = O[b].id,
                    d = O[b].callbackFn,
                    e = {
                        success: !1,
                        id: c
                    };
                if (U.pv[0] > 0) {
                    var f = p(c);
                    if (f)
                        if (!s(O[b].swfVersion) || U.wk && U.wk < 312)
                            if (O[b].expressInstall && h()) {
                                var k = {};
                                k.data = O[b].expressInstall;
                                k.width = f.getAttribute("width") || "0";
                                k.height = f.getAttribute("height") || "0";
                                f.getAttribute("class") && (k.styleclass = f.getAttribute("class"));
                                f.getAttribute("align") && (k.align = f.getAttribute("align"));
                                for (var l = {}, m = f.getElementsByTagName("param"), n = m.length, o = 0; n > o; o++) "movie" != m[o].getAttribute("name").toLowerCase() && (l[m[o].getAttribute("name")] = m[o].getAttribute("value"));
                                i(k, l, c, d)
                            } else {
                                j(f);
                                d && d(e)
                            } else {
                                u(c, !0);
                                if (d) {
                                    e.success = !0;
                                    e.ref = g(c);
                                    d(e)
                                }
                            }
                } else {
                    u(c, !0);
                    if (d) {
                        var q = g(c);
                        if (q && typeof q.SetVariable != C) {
                            e.success = !0;
                            e.ref = q
                        }
                        d(e)
                    }
                }
            }
    }

    function g(a) {
        var b = null,
            c = p(a);
        if (c && "OBJECT" == c.nodeName)
            if (typeof c.SetVariable != C) b = c;
            else {
                var d = c.getElementsByTagName(D)[0];
                d && (b = d)
            }
        return b
    }

    function h() {
        return !S && s("6.0.65") && (U.win || U.mac) && !(U.wk && U.wk < 312)
    }

    function i(a, b, c, d) {
        S = !0;
        y = d || null;
        z = {
            success: !1,
            id: c
        };
        var e = p(c);
        if (e) {
            if ("OBJECT" == e.nodeName) {
                w = k(e);
                x = null
            } else {
                w = e;
                x = c
            }
            a.id = H;
            (typeof a.width == C || !/%$/.test(a.width) && parseInt(a.width, 10) < 310) && (a.width = "310");
            (typeof a.height == C || !/%$/.test(a.height) && parseInt(a.height, 10) < 137) && (a.height = "137");
            K.title = K.title.slice(0, 47) + " - Flash Player Installation";
            var f = U.ie && U.win ? "ActiveX" : "PlugIn",
                g = "MMredirectURL=" + J.location.toString().replace(/&/g, "%26") + "&MMplayerType=" + f + "&MMdoctitle=" + K.title;
            typeof b.flashvars != C ? b.flashvars += "&" + g : b.flashvars = g;
            if (U.ie && U.win && 4 != e.readyState) {
                var h = q("div");
                c += "SWFObjectNew";
                h.setAttribute("id", c);
                e.parentNode.insertBefore(h, e);
                e.style.display = "none";
                ! function () {
                    4 == e.readyState ? e.parentNode.removeChild(e) : setTimeout(arguments.callee, 10)
                }()
            }
            l(a, b, c)
        }
    }

    function j(a) {
        if (U.ie && U.win && 4 != a.readyState) {
            var b = q("div");
            a.parentNode.insertBefore(b, a);
            b.parentNode.replaceChild(k(a), b);
            a.style.display = "none";
            ! function () {
                4 == a.readyState ? a.parentNode.removeChild(a) : setTimeout(arguments.callee, 10)
            }()
        } else a.parentNode.replaceChild(k(a), a)
    }

    function k(a) {
        var b = q("div");
        if (U.win && U.ie) b.innerHTML = a.innerHTML;
        else {
            var c = a.getElementsByTagName(D)[0];
            if (c) {
                var d = c.childNodes;
                if (d)
                    for (var e = d.length, f = 0; e > f; f++) 1 == d[f].nodeType && "PARAM" == d[f].nodeName || 8 == d[f].nodeType || b.appendChild(d[f].cloneNode(!0))
            }
        }
        return b
    }

    function l(a, b, c) {
        var d, e = p(c);
        if (U.wk && U.wk < 312) return d;
        if (e) {
            typeof a.id == C && (a.id = c);
            if (U.ie && U.win) {
                var f = "";
                for (var g in a) a[g] != Object.prototype[g] && ("data" == g.toLowerCase() ? b.movie = a[g] : "styleclass" == g.toLowerCase() ? f += ' class="' + a[g] + '"' : "classid" != g.toLowerCase() && (f += " " + g + '="' + a[g] + '"'));
                var h = "";
                for (var i in b) b[i] != Object.prototype[i] && (h += '<param name="' + i + '" value="' + b[i] + '" />');
                e.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + f + ">" + h + "</object>";
                P[P.length] = a.id;
                d = p(a.id)
            } else {
                var j = q(D);
                j.setAttribute("type", G);
                for (var k in a) a[k] != Object.prototype[k] && ("styleclass" == k.toLowerCase() ? j.setAttribute("class", a[k]) : "classid" != k.toLowerCase() && j.setAttribute(k, a[k]));
                for (var l in b) b[l] != Object.prototype[l] && "movie" != l.toLowerCase() && m(j, l, b[l]);
                e.parentNode.replaceChild(j, e);
                d = j
            }
        }
        return d
    }

    function m(a, b, c) {
        var d = q("param");
        d.setAttribute("name", b);
        d.setAttribute("value", c);
        a.appendChild(d)
    }

    function n(a) {
        var b = p(a);
        if (b && "OBJECT" == b.nodeName)
            if (U.ie && U.win) {
                b.style.display = "none";
                ! function () {
                    4 == b.readyState ? o(a) : setTimeout(arguments.callee, 10)
                }()
            } else b.parentNode.removeChild(b)
    }

    function o(a) {
        var b = p(a);
        if (b) {
            for (var c in b) "function" == typeof b[c] && (b[c] = null);
            b.parentNode.removeChild(b)
        }
    }

    function p(a) {
        var b = null;
        try {
            b = K.getElementById(a)
        } catch (c) {}
        return b
    }

    function q(a) {
        return K.createElement(a)
    }

    function r(a, b, c) {
        a.attachEvent(b, c);
        Q[Q.length] = [a, b, c]
    }

    function s(a) {
        var b = U.pv,
            c = a.split(".");
        c[0] = parseInt(c[0], 10);
        c[1] = parseInt(c[1], 10) || 0;
        c[2] = parseInt(c[2], 10) || 0;
        return b[0] > c[0] || b[0] == c[0] && b[1] > c[1] || b[0] == c[0] && b[1] == c[1] && b[2] >= c[2] ? !0 : !1
    }

    function t(a, b, c, d) {
        if (!U.ie || !U.mac) {
            var e = K.getElementsByTagName("head")[0];
            if (e) {
                var f = c && "string" == typeof c ? c : "screen";
                if (d) {
                    A = null;
                    B = null
                }
                if (!A || B != f) {
                    var g = q("style");
                    g.setAttribute("type", "text/css");
                    g.setAttribute("media", f);
                    A = e.appendChild(g);
                    U.ie && U.win && typeof K.styleSheets != C && K.styleSheets.length > 0 && (A = K.styleSheets[K.styleSheets.length - 1]);
                    B = f
                }
                U.ie && U.win ? A && typeof A.addRule == D && A.addRule(a, b) : A && typeof K.createTextNode != C && A.appendChild(K.createTextNode(a + " {" + b + "}"))
            }
        }
    }

    function u(a, b) {
        if (T) {
            var c = b ? "visible" : "hidden";
            R && p(a) ? p(a).style.visibility = c : t("#" + a, "visibility:" + c)
        }
    }

    function v(a) {
        var b = /[\\\"<>\.;]/,
            c = null != b.exec(a);
        return c && typeof encodeURIComponent != C ? encodeURIComponent(a) : a
    } {
        var w, x, y, z, A, B, C = "undefined",
            D = "object",
            E = "Shockwave Flash",
            F = "ShockwaveFlash.ShockwaveFlash",
            G = "application/x-shockwave-flash",
            H = "SWFObjectExprInst",
            I = "onreadystatechange",
            J = window,
            K = document,
            L = navigator,
            M = !1,
            N = [d],
            O = [],
            P = [],
            Q = [],
            R = !1,
            S = !1,
            T = !0,
            U = function () {
                var a = typeof K.getElementById != C && typeof K.getElementsByTagName != C && typeof K.createElement != C,
                    b = L.userAgent.toLowerCase(),
                    c = L.platform.toLowerCase(),
                    d = c ? /win/.test(c) : /win/.test(b),
                    e = c ? /mac/.test(c) : /mac/.test(b),
                    f = /webkit/.test(b) ? parseFloat(b.replace(/^.*webkit\/(\d+(\.\d+)?).*$/, "$1")) : !1,
                    g = !1,
                    h = [0, 0, 0],
                    i = null;
                if (typeof L.plugins != C && typeof L.plugins[E] == D) {
                    i = L.plugins[E].description;
                    if (i && (typeof L.mimeTypes == C || !L.mimeTypes[G] || L.mimeTypes[G].enabledPlugin)) {
                        M = !0;
                        g = !1;
                        i = i.replace(/^.*\s+(\S+\s+\S+$)/, "$1");
                        h[0] = parseInt(i.replace(/^(.*)\..*$/, "$1"), 10);
                        h[1] = parseInt(i.replace(/^.*\.(.*)\s.*$/, "$1"), 10);
                        h[2] = /[a-zA-Z]/.test(i) ? parseInt(i.replace(/^.*[a-zA-Z]+(.*)$/, "$1"), 10) : 0
                    }
                } else if (typeof J.ActiveXObject != C) try {
                    var j = new ActiveXObject(F);
                    if (j) {
                        i = j.GetVariable("$version");
                        if (i) {
                            g = !0;
                            i = i.split(" ")[1].split(",");
                            h = [parseInt(i[0], 10), parseInt(i[1], 10), parseInt(i[2], 10)]
                        }
                    }
                } catch (k) {}
                return {
                    w3: a,
                    pv: h,
                    wk: f,
                    ie: g,
                    win: d,
                    mac: e
                }
            }();
        ! function () {
            if (U.w3) {
                (typeof K.readyState != C && "complete" == K.readyState || typeof K.readyState == C && (K.getElementsByTagName("body")[0] || K.body)) && a();
                if (!R) {
                    typeof K.addEventListener != C && K.addEventListener("DOMContentLoaded", a, !1);
                    if (U.ie && U.win) {
                        K.attachEvent(I, function () {
                            if ("complete" == K.readyState) {
                                K.detachEvent(I, arguments.callee);
                                a()
                            }
                        });
                        J == top && ! function () {
                            if (!R) {
                                try {
                                    K.documentElement.doScroll("left")
                                } catch (b) {
                                    setTimeout(arguments.callee, 0);
                                    return
                                }
                                a()
                            }
                        }()
                    }
                    U.wk && ! function () {
                        R || (/loaded|complete/.test(K.readyState) ? a() : setTimeout(arguments.callee, 0))
                    }();
                    c(a)
                }
            }
        }(),
        function () {
            U.ie && U.win && window.attachEvent("onunload", function () {
                for (var a = Q.length, b = 0; a > b; b++) Q[b][0].detachEvent(Q[b][1], Q[b][2]);
                for (var c = P.length, d = 0; c > d; d++) n(P[d]);
                for (var e in U) U[e] = null;
                U = null;
                for (var f in swfobject) swfobject[f] = null;
                swfobject = null
            })
        }()
    }
    return {
        registerObject: function (a, b, c, d) {
            if (U.w3 && a && b) {
                var e = {};
                e.id = a;
                e.swfVersion = b;
                e.expressInstall = c;
                e.callbackFn = d;
                O[O.length] = e;
                u(a, !1)
            } else d && d({
                success: !1,
                id: a
            })
        },
        getObjectById: function (a) {
            return U.w3 ? g(a) : void 0
        },
        embedSWF: function (a, c, d, e, f, g, j, k, m, n) {
            var o = {
                success: !1,
                id: c
            };
            if (U.w3 && !(U.wk && U.wk < 312) && a && c && d && e && f) {
                u(c, !1);
                b(function () {
                    d += "";
                    e += "";
                    var b = {};
                    if (m && typeof m === D)
                        for (var p in m) b[p] = m[p];
                    b.data = a;
                    b.width = d;
                    b.height = e;
                    var q = {};
                    if (k && typeof k === D)
                        for (var r in k) q[r] = k[r];
                    if (j && typeof j === D)
                        for (var t in j) typeof q.flashvars != C ? q.flashvars += "&" + t + "=" + j[t] : q.flashvars = t + "=" + j[t];
                    if (s(f)) {
                        var v = l(b, q, c);
                        b.id == c && u(c, !0);
                        o.success = !0;
                        o.ref = v
                    } else {
                        if (g && h()) {
                            b.data = g;
                            i(b, q, c, n);
                            return
                        }
                        u(c, !0)
                    }
                    n && n(o)
                })
            } else n && n(o)
        },
        switchOffAutoHideShow: function () {
            T = !1
        },
        ua: U,
        getFlashPlayerVersion: function () {
            return {
                major: U.pv[0],
                minor: U.pv[1],
                release: U.pv[2]
            }
        },
        hasFlashPlayerVersion: s,
        createSWF: function (a, b, c) {
            return U.w3 ? l(a, b, c) : void 0
        },
        showExpressInstall: function (a, b, c, d) {
            U.w3 && h() && i(a, b, c, d)
        },
        removeSWF: function (a) {
            U.w3 && n(a)
        },
        createCSS: function (a, b, c, d) {
            U.w3 && t(a, b, c, d)
        },
        addDomLoadEvent: b,
        addLoadEvent: c,
        getQueryParamValue: function (a) {
            var b = K.location.search || K.location.hash;
            if (b) {
                /\?/.test(b) && (b = b.split("?")[1]);
                if (null == a) return v(b);
                for (var c = b.split("&"), d = 0; d < c.length; d++)
                    if (c[d].substring(0, c[d].indexOf("=")) == a) return v(c[d].substring(c[d].indexOf("=") + 1))
            }
            return ""
        },
        expressInstallCallback: function () {
            if (S) {
                var a = p(H);
                if (a && w) {
                    a.parentNode.replaceChild(w, a);
                    if (x) {
                        u(x, !0);
                        U.ie && U.win && (w.style.display = "block")
                    }
                    y && y(z)
                }
                S = !1
            }
        }
    }
}();
TCS.dynamicRedirectUrls = TCS.dynamicRedirectUrls || {};
! function (a, b, c, d) {
    "use strict";
    var e, f = null;
    a.utils.getRedirectUrl = function (c, d, g) {
        var h = b.extend({}, d);
        if (f) g(f[c.toLowerCase()]);
        else {
            if (!e) {
                e = b.Deferred();
                b.getJSON(a.getServiceURL("social_networks"), h).done(function (b) {
                    if (a.utils.checkResponse(b)) {
                        f = b.payload;
                        e.resolve(f[c.toLowerCase()])
                    } else e.reject()
                }).fail(function () {
                    e.reject()
                })
            }
            e.always(g)
        }
    };
    a.socialApi = a.socialApi || {
        getInstance: function (b, c) {
            c || (c = a.utils.getSocialAppId(b.toString().toLowerCase()));
            if (void 0 === a.socialApi[b]) throw new ReferenceError("Class TCS.socialApi." + b + " not Found.");
            void 0 === a.socialApi[b + "Obj"] && (a.socialApi[b + "Obj"] = new a.socialApi[b](c));
            return a.socialApi[b + "Obj"]
        },
        getSocialNetworks: function (c) {
            b.get(a.getServiceURL("pointers"), {}, function (a) {
                c(a)
            }).fail(function () {
                c({
                    resultCode: "CONNECT_ERROR",
                    errorMessage: "Can't connect to server",
                    status: "error"
                })
            })
        },
        removeSocialNetwork: function (c, d) {
            b.get(a.getServiceURL("remove_pointer"), {
                social_network_id: c
            }, function (b) {
                a.socialApi.getInstance(c.toString().toUpperCase()).clearKeys();
                d(b)
            }).fail(function () {
                d({
                    resultCode: "CONNECT_ERROR",
                    errorMessage: "Can't connect to server",
                    status: "error"
                })
            })
        }
    };
    a.socialApi.defaultApi = b.inherit({
        __constructor: function (b) {
            this.name = void 0 !== b ? b : null;
            if (null === this.name) throw new TypeError("Error create Social API: name is null");
            if (void 0 !== this.instance) return this.instance;
            this.instance = a.socialApi[this.name + "Obj"] = this;
            this.keys = {};
            this.defer = null;
            this.window = null;
            this.requestData = null;
            this.keyPrefix = "__social__" + this.name + "__";
            this.readKeys()
        },
        renderAuthUrl: function (a) {
            return a.replace(/\{(\w+)\}/g, function (a, b) {
                return this[b] || a
            }.bind(this))
        },
        getUserInfo: function (a, c) {
            if (!this.getKey("networkAccountId")) {
                c({
                    resultCode: "REQUEST_ERROR",
                    errorMessage: "network user id not found",
                    status: "error",
                    error_code: 301
                });
                return !1
            }
            this.send("social_get_user_info", b.extend({
                uid: this.getKey.bind(this, "networkAccountId"),
                fields: this.getUserInfoFields()
            }, a), c)
        },
        getFriends: function (a, c) {
            if (!this.getKey("networkAccountId")) {
                c({
                    resultCode: "REQUEST_ERROR",
                    errorMessage: "network user id not found",
                    status: "error",
                    error_code: 302
                });
                return !1
            }
            this.send("social_get_friends", b.extend({
                uid: this.getKey.bind(this, "networkAccountId"),
                fields: this.getUserInfoFields()
            }, a), c)
        },
        shareOnWall: function (a, c) {
            if (!this.getKey("networkAccountId")) {
                c({
                    resultCode: "REQUEST_ERROR",
                    errorMessage: "network user id not found",
                    status: "error",
                    error_code: 303
                });
                return !1
            }
            this.send("share", b.extend({
                uid: this.getKey("networkAccountId"),
                message: "hello world!"
            }, a), c)
        },
        send: function (a, b, c) {
            this.requestData = {
                method: a,
                params: b,
                callback: c
            };
            this.authenticate().done(this.sendAfterAuthenticateSuccess.bind(this)).fail(this.sendAfterAuthenticateFail.bind(this))
        },
        sendAfterAuthenticateSuccess: function () {
            var a, b = this.requestData.method,
                c = this.requestData.params,
                d = this.requestData.callback;
            try {
                a = this.renderParams(c)
            } catch (e) {
                d({
                    errorMessage: "AccessToken not found",
                    resultCode: "AUTHENTICATE_ERROR",
                    status: "error",
                    error_code: 102
                });
                return
            }
            this.doSend(this.getRestUrl(b), a, d)
        },
        sendAfterAuthenticateFail: function (a) {
            var b = this.requestData.callback;
            b(a)
        },
        doSend: function (a, c, d) {
            c.social_user_id = this.getKey("networkAccountId");
            b.get(a, c, function (a) {
                if (!0 === this.checkResponseData(a)) {
                    this.requestData = null;
                    d(a)
                }
            }.bind(this), this.getRequestType()).fail(function () {
                d({
                    errorMessage: "server is not available",
                    resultCode: "API_RESPONSE_ERROR",
                    status: "error",
                    error_code: 201
                })
            }.bind(this))
        },
        getRequestType: function () {
            return "json"
        },
        renderParams: function (a) {
            var c = a || {};
            if (!this.getKey("networkAccountId")) throw new Error("Network account id not found");
            return b.extend(c, {
                social_network_id: this.name.toLocaleLowerCase(),
                uid: this.getKey("networkAccountId")
            })
        },
        getRestUrl: function (b) {
            return a.getServiceURL(b)
        },
        checkResponseData: function (a) {
            if ("[object Object]" === Object.prototype.toString.call(a)) {
                if (void 0 !== a.resultCode && "WRONG_CONFIRMATION_CODE" === a.resultCode) {
                    this.clearKeys();
                    this.authenticate().done(this.sendAfterAuthenticateSuccess.bind(this)).fail(this.sendAfterAuthenticateFail.bind(this));
                    return !1
                }
                return !0
            }
            return !1
        },
        authenticate: function (a) {
            this.defer = b.Deferred();
            this.authOptions = a;
            this.getKey("networkAccountId") ? this.defer.resolve() : this.openWindow();
            return this.defer.promise()
        },
        insertSessionId: function (b) {
            return b.replace(/SESSION_ID/, a.Session.getSessionID())
        },
        openWindow: function (c) {
            if (c) this._openWindow(c);
            else {
                this._openWindow("");
                this.window && b(this.window.document.body).css("background", "url(https://www.tcsbank.ru/static/media/indicator.gif) no-repeat 50% 50%");
                a.utils.getRedirectUrl(this.name, this.authOptions, function (a) {
                    a ? this.window && (this.window.location = this.insertSessionId(this.renderAuthUrl(a))) : this.window && b(this.window.document.body).css("background", "").html('<div style="position: absolute;top:45%;width:98%; text-align: center;">Произошла ошибка</div>')
                }.bind(this))
            }
        },
        _openWindow: function (a) {
            this.window = c.open(this.insertSessionId(a), this.name, "left=500,top=200,width=850,height=480,resizable=yes,scrollbars=yes,status=yes");
            this.window && this.window.focus();
            this.windowCloseInterval = window.setInterval(function () {
                if (!this.window || this.window.closed) {
                    window.clearInterval(this.windowCloseInterval);
                    this.window = null;
                    this.onWindowClose();
                    this.authenticationCancel()
                }
            }.bind(this), 500)
        },
        closeWindow: function () {
            this.window = null
        },
        onWindowClose: function () {},
        authenticationSuccess: function (a) {
            if (this.defer) {
                try {
                    this.setKeys(a)
                } catch (c) {
                    this.defer.reject(b.extend({
                        errorMessage: "Data from server is incorrect",
                        resultCode: "AUTHENTICATE_ERROR"
                    }, {
                        status: "error",
                        errorCode: 100
                    }));
                    this.defer = null;
                    return
                }
                this.defer.resolve({
                    status: "success"
                });
                this.defer = null
            }
        },
        authenticationCancel: function () {
            if (this.defer) {
                this.defer.reject({
                    status: "authCancel",
                    error: !1
                });
                this.defer = null
            }
        },
        authenticationError: function (a) {
            if (this.defer) {
                this.defer.reject(b.extend(a, {
                    status: "error"
                }));
                this.defer = null
            }
            this.closeWindow()
        },
        requestAccessToken: function () {
            this.doSend(a.getServiceURL("social_get_access_token"), {
                code: "234234etergaergaerg",
                social_network_id: this.name
            }, function (a) {
                console.log(a)
            })
        },
        keys: {},
        setKeys: function (a) {
            var b;
            if (!_.isObject(a)) throw new TypeError("type of data parameter must be a Object");
            if (!a.socialNetworkUser || !a.socialNetworkUser.networkId) throw new Error("missing fields");
            for (b in a) a.hasOwnProperty(b) && this.setKey(b, a[b])
        },
        clearKeys: function () {
            _.each(this.keys, this.clearKey, this);
            return this
        },
        clearKey: function (a, b) {
            this.keys[b] = d.store(b, null);
            return this
        },
        getKey: function (a) {
            a = this.keyPrefix + a;
            this.keys[a] = d.store(a);
            return this.keys[a]
        },
        setKey: function (a, b) {
            if (!_.isObject(b)) {
                a = this.keyPrefix + a;
                this.keys[a] = d.store(a, b);
                return this.keys[a]
            }
            _.each(b, function (a, b) {
                this.setKey(b, a)
            }, this)
        },
        readKeys: function () {
            _.each(d.store(), this.readKey, this);
            return this
        },
        readKey: function (a, b) {
            0 === b.indexOf(this.keyPrefix) && (this.keys[b] = a);
            return this
        }
    }, {})
}(TCS, jQuery, window, amplify);
TCS.socialApi.FB = $.inherit(TCS.socialApi.defaultApi, {
    __constructor: function () {
        this.__base("FB")
    },
    checkResponseData: function (a) {
        if (!1 === this.__base(a)) return !1;
        if (void 0 !== a.error && 190 === +a.error.code) {
            this.clearKeys();
            this.authenticate().done(this.sendAfterAuthenticateSuccess.bind(this)).fail(this.sendAfterAuthenticateFail.bind(this));
            return !1
        }
        return !0
    },
    getUserInfoFields: function () {
        return "name,first_name,last_name,gender"
    }
}, {});
TCS.socialApi.FQ = $.inherit(TCS.socialApi.defaultApi, {
    __constructor: function () {
        this.__base("FQ")
    }
}, {});
TCS.socialApi.GL = TCS.socialApi.GPLUS = $.inherit(TCS.socialApi.defaultApi, {
    __constructor: function () {
        this.__base("GL")
    }
}, {});
TCS.socialApi.IN = $.inherit(TCS.socialApi.defaultApi, {
    __constructor: function () {
        this.__base("IN")
    },
    checkResponseData: function (a) {
        return !1 === this.__base(a) ? !1 : !0
    },
    getUserInfoFields: function () {
        return "name,first_name,last_name,gender"
    }
}, {});
TCS.socialApi.OK = $.inherit(TCS.socialApi.defaultApi, {
    __constructor: function () {
        this.__base("OK")
    },
    getRequestType: function () {
        return "json"
    },
    checkResponseData: function (a) {
        if (!1 === this.__base(a)) return !1;
        if (void 0 !== a.error && 5 === +a.error.error_code) {
            this.clearKeys();
            this.authenticate().done(this.sendAfterAuthenticateSuccess.bind(this)).fail(this.sendAfterAuthenticateFail.bind(this));
            return !1
        }
        return !0
    },
    getUserInfoFields: function () {
        return "uid,first_name,last_name,pic_1"
    }
}, {});
TCS.socialApi.TW = $.inherit(TCS.socialApi.defaultApi, {
    __constructor: function () {
        this.name = "TW";
        this.url = $.getJSON(TCS.getServiceURL("socialcallback/redirecturl"), {
            social_network_id: "tw",
            origin: "web",
            portal: 1
        }).pipe(function (a) {
            var b = $.Deferred();
            "OK" === a.resultCode && a.payload && a.payload.tw ? b.resolve(a.payload.tw) : b.reject();
            return b
        })
    },
    openWindow: function () {
        this.url.then(function (a) {
            TCS.socialApi.defaultApi.prototype.openWindow(a)
        }.bind(this))
    },
    shareOnWall: function (a) {
        window.open("http://twitter.com/home?status=" + encodeURI(a.message), "Loading", "width=700,height=400,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0")
    }
}, {});
TCS.socialApi.VK = $.inherit(TCS.socialApi.defaultApi, {
    __constructor: function () {
        this.__base("VK")
    },
    checkResponseData: function (a) {
        if (!1 === this.__base(a)) return !1;
        if (void 0 !== a.error && 5 === +a.error.error_code) {
            this.clearKeys();
            this.authenticate().done(this.sendAfterAuthenticateSuccess.bind(this)).fail(this.sendAfterAuthenticateFail.bind(this));
            return !1
        }
        return !0
    },
    getUserInfoFields: function () {
        return "name,first_name,last_name,sex,gender,photo"
    },
    shareOnWall: function (a, b) {
        TCS.loadVk().done(function () {
            VK.Auth.login(function (c) {
                c.session && VK.Api.call("wall.post", a, function (a) {
                    a.error || b()
                })
            }.bind(this), 2048)
        }.bind(this))
    }
}, {});
! function (a, b, c) {
    function d(c, d, e) {
        var f = b.createElement(c);
        return d && (f.id = _ + d), e && (f.style.cssText = e), a(f)
    }

    function e() {
        return c.innerHeight ? c.innerHeight : a(c).height()
    }

    function f(a) {
        var b = y.length,
            c = (Q + a) % b;
        return 0 > c ? b + c : c
    }

    function g(a, b) {
        return Math.round((/%/.test(a) ? ("x" === b ? z.width() : e()) / 100 : 1) * parseInt(a, 10))
    }

    function h(a, b) {
        return a.photo || a.photoRegex.test(b)
    }

    function i(a, b) {
        return a.retinaUrl && c.devicePixelRatio > 1 ? b.replace(a.photoRegex, a.retinaSuffix) : b
    }

    function j(a) {
        "contains" in r[0] && !r[0].contains(a.target) && (a.stopPropagation(), r.focus())
    }

    function k() {
        var b, c = a.data(P, $);
        null == c ? (K = a.extend({}, Z), console && console.log && console.log("Error: cboxElement missing settings object")) : K = a.extend({}, c);
        for (b in K) a.isFunction(K[b]) && "on" !== b.slice(0, 2) && (K[b] = K[b].call(P));
        K.rel = K.rel || P.rel || a(P).data("rel") || "nofollow", K.href = K.href || a(P).attr("href"), K.title = K.title || P.title, "string" == typeof K.href && (K.href = a.trim(K.href))
    }

    function l(c, d) {
        a(b).trigger(c), hb.triggerHandler(c), a.isFunction(d) && d.call(P)
    }

    function m(c) {
        U || (P = c, k(), y = a(P), Q = 0, "nofollow" !== K.rel && (y = a("." + ab).filter(function () {
            var b, c = a.data(this, $);
            return c && (b = a(this).data("rel") || c.rel || this.rel), b === K.rel
        }), Q = y.index(P), -1 === Q && (y = y.add(P), Q = y.length - 1)), q.css({
            opacity: parseFloat(K.opacity),
            cursor: K.overlayClose ? "pointer" : "auto",
            visibility: "visible"
        }).show(), X && r.add(q).removeClass(X), K.className && r.add(q).addClass(K.className), X = K.className, K.closeButton ? I.html(K.close).appendTo(t) : I.appendTo("<div/>"), S || (S = T = !0, r.css({
            visibility: "hidden",
            display: "block"
        }), A = d(ib, "LoadedContent", "width:0; height:0; overflow:hidden"), t.css({
            width: "",
            height: ""
        }).append(A), L = u.height() + x.height() + t.outerHeight(!0) - t.height(), M = v.width() + w.width() + t.outerWidth(!0) - t.width(), N = A.outerHeight(!0), O = A.outerWidth(!0), K.w = g(K.initialWidth, "x"), K.h = g(K.initialHeight, "y"), A.css({
            width: "",
            height: K.h
        }), W.position(), l(bb, K.onOpen), J.add(D).hide(), r.focus(), K.trapFocus && b.addEventListener && (b.addEventListener("focus", j, !0), hb.one(fb, function () {
            b.removeEventListener("focus", j, !0)
        })), K.returnFocus && hb.one(fb, function () {
            a(P).focus()
        })), p())
    }

    function n() {
        !r && b.body && (Y = !1, z = a(c), r = d(ib).attr({
            id: $,
            "class": a.support.opacity === !1 ? _ + "IE" : "",
            role: "dialog",
            tabindex: "-1"
        }).hide(), q = d(ib, "Overlay").hide(), C = a([d(ib, "LoadingOverlay")[0], d(ib, "LoadingGraphic")[0]]), s = d(ib, "Wrapper"), t = d(ib, "Content").append(D = d(ib, "Title"), E = d(ib, "Current"), H = a('<button type="button"/>').attr({
            id: _ + "Previous"
        }), G = a('<button type="button"/>').attr({
            id: _ + "Next"
        }), F = d("button", "Slideshow"), C), I = a('<button type="button"/>').attr({
            id: _ + "Close"
        }), s.append(d(ib).append(d(ib, "TopLeft"), u = d(ib, "TopCenter"), d(ib, "TopRight")), d(ib, !1, "clear:left").append(v = d(ib, "MiddleLeft"), t, w = d(ib, "MiddleRight")), d(ib, !1, "clear:left").append(d(ib, "BottomLeft"), x = d(ib, "BottomCenter"), d(ib, "BottomRight"))).find("div div").css({
            "float": "left"
        }), B = d(ib, !1, "position:absolute; width:9999px; visibility:hidden; display:none; max-width:none;"), J = G.add(H).add(E).add(F), a(b.body).append(q, r.append(s, B)))
    }

    function o() {
        function c(a) {
            a.which > 1 || a.shiftKey || a.altKey || a.metaKey || a.ctrlKey || (a.preventDefault(), m(this))
        }
        return r ? (Y || (Y = !0, G.click(function () {
            W.next()
        }), H.click(function () {
            W.prev()
        }), I.click(function () {
            W.close()
        }), q.click(function () {
            K.overlayClose && W.close()
        }), a(b).bind("keydown." + _, function (a) {
            var b = a.keyCode;
            S && K.escKey && 27 === b && (a.preventDefault(), W.close()), S && K.arrowKey && y[1] && !a.altKey && (37 === b ? (a.preventDefault(), H.click()) : 39 === b && (a.preventDefault(), G.click()))
        }), a.isFunction(a.fn.on) ? a(b).on("click." + _, "." + ab, c) : a("." + ab).live("click." + _, c)), !0) : !1
    }

    function p() {
        var e, f, j, m = W.prep,
            n = ++jb;
        T = !0, R = !1, P = y[Q], k(), l(gb), l(cb, K.onLoad), K.h = K.height ? g(K.height, "y") - N - L : K.innerHeight && g(K.innerHeight, "y"), K.w = K.width ? g(K.width, "x") - O - M : K.innerWidth && g(K.innerWidth, "x"), K.mw = K.w, K.mh = K.h, K.maxWidth && (K.mw = g(K.maxWidth, "x") - O - M, K.mw = K.w && K.w < K.mw ? K.w : K.mw), K.maxHeight && (K.mh = g(K.maxHeight, "y") - N - L, K.mh = K.h && K.h < K.mh ? K.h : K.mh), e = K.href, V = setTimeout(function () {
            C.show()
        }, 100), K.inline ? (j = d(ib).hide().insertBefore(a(e)[0]), hb.one(gb, function () {
            j.replaceWith(A.children())
        }), m(a(e))) : K.iframe ? m(" ") : K.html ? m(K.html) : h(K, e) ? (e = i(K, e), R = b.createElement("img"), a(R).addClass(_ + "Photo").bind("error", function () {
            K.title = !1, m(d(ib, "Error").html(K.imgError))
        }).one("load", function () {
            var b;
            n === jb && (a.each(["alt", "longdesc", "aria-describedby"], function (b, c) {
                var d = a(P).attr(c) || a(P).attr("data-" + c);
                d && R.setAttribute(c, d)
            }), K.retinaImage && c.devicePixelRatio > 1 && (R.height = R.height / c.devicePixelRatio, R.width = R.width / c.devicePixelRatio), K.scalePhotos && (f = function () {
                R.height -= R.height * b, R.width -= R.width * b
            }, K.mw && R.width > K.mw && (b = (R.width - K.mw) / R.width, f()), K.mh && R.height > K.mh && (b = (R.height - K.mh) / R.height, f())), K.h && (R.style.marginTop = Math.max(K.mh - R.height, 0) / 2 + "px"), y[1] && (K.loop || y[Q + 1]) && (R.style.cursor = "pointer", R.onclick = function () {
                W.next()
            }), R.style.width = R.width + "px", R.style.height = R.height + "px", setTimeout(function () {
                m(R)
            }, 1))
        }), setTimeout(function () {
            R.src = e
        }, 1)) : e && B.load(e, K.data, function (b, c) {
            n === jb && m("error" === c ? d(ib, "Error").html(K.xhrError) : a(this).contents())
        })
    }
    var q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z = {
            html: !1,
            photo: !1,
            iframe: !1,
            inline: !1,
            transition: "elastic",
            speed: 300,
            fadeOut: 300,
            width: !1,
            initialWidth: "600",
            innerWidth: !1,
            maxWidth: !1,
            height: !1,
            initialHeight: "450",
            innerHeight: !1,
            maxHeight: !1,
            scalePhotos: !0,
            scrolling: !0,
            href: !1,
            title: !1,
            rel: !1,
            opacity: .9,
            preloading: !0,
            className: !1,
            overlayClose: !0,
            escKey: !0,
            arrowKey: !0,
            top: !1,
            bottom: !1,
            left: !1,
            right: !1,
            fixed: !1,
            data: void 0,
            closeButton: !0,
            fastIframe: !0,
            open: !1,
            reposition: !0,
            loop: !0,
            slideshow: !1,
            slideshowAuto: !0,
            slideshowSpeed: 2500,
            slideshowStart: "start slideshow",
            slideshowStop: "stop slideshow",
            photoRegex: /\.(gif|png|jp(e|g|eg)|bmp|ico|webp|jxr)((#|\?).*)?$/i,
            retinaImage: !1,
            retinaUrl: !1,
            retinaSuffix: "@2x.$1",
            current: "image {current} of {total}",
            previous: "previous",
            next: "next",
            close: "close",
            xhrError: "This content failed to load.",
            imgError: "This image failed to load.",
            returnFocus: !0,
            trapFocus: !0,
            onOpen: !1,
            onLoad: !1,
            onComplete: !1,
            onCleanup: !1,
            onClosed: !1
        }, $ = "colorbox",
        _ = "cbox",
        ab = _ + "Element",
        bb = _ + "_open",
        cb = _ + "_load",
        db = _ + "_complete",
        eb = _ + "_cleanup",
        fb = _ + "_closed",
        gb = _ + "_purge",
        hb = a("<a/>"),
        ib = "div",
        jb = 0,
        kb = {}, lb = function () {
            function a() {
                clearTimeout(g)
            }

            function b() {
                (K.loop || y[Q + 1]) && (a(), g = setTimeout(W.next, K.slideshowSpeed))
            }

            function c() {
                F.html(K.slideshowStop).unbind(i).one(i, d), hb.bind(db, b).bind(cb, a), r.removeClass(h + "off").addClass(h + "on")
            }

            function d() {
                a(), hb.unbind(db, b).unbind(cb, a), F.html(K.slideshowStart).unbind(i).one(i, function () {
                    W.next(), c()
                }), r.removeClass(h + "on").addClass(h + "off")
            }

            function e() {
                f = !1, F.hide(), a(), hb.unbind(db, b).unbind(cb, a), r.removeClass(h + "off " + h + "on")
            }
            var f, g, h = _ + "Slideshow_",
                i = "click." + _;
            return function () {
                f ? K.slideshow || (hb.unbind(eb, e), e()) : K.slideshow && y[1] && (f = !0, hb.one(eb, e), K.slideshowAuto ? c() : d(), F.show())
            }
        }();
    a.colorbox || (a(n), W = a.fn[$] = a[$] = function (b, c) {
        var d = this;
        if (b = b || {}, n(), o()) {
            if (a.isFunction(d)) d = a("<a/>"), b.open = !0;
            else if (!d[0]) return d;
            c && (b.onComplete = c), d.each(function () {
                a.data(this, $, a.extend({}, a.data(this, $) || Z, b))
            }).addClass(ab), (a.isFunction(b.open) && b.open.call(d) || b.open) && m(d[0])
        }
        return d
    }, W.position = function (b, c) {
        function d() {
            u[0].style.width = x[0].style.width = t[0].style.width = parseInt(r[0].style.width, 10) - M + "px", t[0].style.height = v[0].style.height = w[0].style.height = parseInt(r[0].style.height, 10) - L + "px"
        }
        var f, h, i, j = 0,
            k = 0,
            l = r.offset();
        if (z.unbind("resize." + _), r.css({
            top: -9e4,
            left: -9e4
        }), h = z.scrollTop(), i = z.scrollLeft(), K.fixed ? (l.top -= h, l.left -= i, r.css({
            position: "fixed"
        })) : (j = h, k = i, r.css({
            position: "absolute"
        })), k += K.right !== !1 ? Math.max(z.width() - K.w - O - M - g(K.right, "x"), 0) : K.left !== !1 ? g(K.left, "x") : Math.round(Math.max(z.width() - K.w - O - M, 0) / 2), j += K.bottom !== !1 ? Math.max(e() - K.h - N - L - g(K.bottom, "y"), 0) : K.top !== !1 ? g(K.top, "y") : Math.round(Math.max(e() - K.h - N - L, 0) / 2), r.css({
            top: l.top,
            left: l.left,
            visibility: "visible"
        }), s[0].style.width = s[0].style.height = "9999px", f = {
            width: K.w + O + M,
            height: K.h + N + L,
            top: j,
            left: k
        }, b) {
            var m = 0;
            a.each(f, function (a) {
                return f[a] !== kb[a] ? (m = b, void 0) : void 0
            }), b = m
        }
        kb = f, b || r.css(f), r.dequeue().animate(f, {
            duration: b || 0,
            complete: function () {
                d(), T = !1, s[0].style.width = K.w + O + M + "px", s[0].style.height = K.h + N + L + "px", K.reposition && setTimeout(function () {
                    z.bind("resize." + _, function () {
                        W.position()
                    })
                }, 1), a.isFunction(c) && c()
            },
            step: d
        })
    }, W.resize = function (a) {
        var b;
        S && (a = a || {}, a.width && (K.w = g(a.width, "x") - O - M), a.innerWidth && (K.w = g(a.innerWidth, "x")), A.css({
            width: K.w
        }), a.height && (K.h = g(a.height, "y") - N - L), a.innerHeight && (K.h = g(a.innerHeight, "y")), a.innerHeight || a.height || (b = A.scrollTop(), A.css({
            height: "auto"
        }), K.h = A.height()), A.css({
            height: K.h
        }), b && A.scrollTop(b), W.position("none" === K.transition ? 0 : K.speed))
    }, W.prep = function (c) {
        function e() {
            return K.w = K.w || A.width(), K.w = K.mw && K.mw < K.w ? K.mw : K.w, K.w
        }

        function g() {
            return K.h = K.h || A.height(), K.h = K.mh && K.mh < K.h ? K.mh : K.h, K.h
        }
        if (S) {
            var j, k = "none" === K.transition ? 0 : K.speed;
            A.empty().remove(), A = d(ib, "LoadedContent").append(c), A.hide().appendTo(B.show()).css({
                width: e(),
                overflow: K.scrolling ? "auto" : "hidden"
            }).css({
                height: g()
            }).prependTo(t), B.hide(), a(R).css({
                "float": "none"
            }), j = function () {
                function c() {
                    a.support.opacity === !1 && r[0].style.removeAttribute("filter")
                }
                var e, g, j = y.length,
                    m = "frameBorder",
                    n = "allowTransparency";
                S && (g = function () {
                    clearTimeout(V), C.hide(), l(db, K.onComplete)
                }, D.html(K.title).add(A).show(), j > 1 ? ("string" == typeof K.current && E.html(K.current.replace("{current}", Q + 1).replace("{total}", j)).show(), G[K.loop || j - 1 > Q ? "show" : "hide"]().html(K.next), H[K.loop || Q ? "show" : "hide"]().html(K.previous), lb(), K.preloading && a.each([f(-1), f(1)], function () {
                    var c, d, e = y[this],
                        f = a.data(e, $);
                    f && f.href ? (c = f.href, a.isFunction(c) && (c = c.call(e))) : c = a(e).attr("href"), c && h(f, c) && (c = i(f, c), d = b.createElement("img"), d.src = c)
                })) : J.hide(), K.iframe ? (e = d("iframe")[0], m in e && (e[m] = 0), n in e && (e[n] = "true"), K.scrolling || (e.scrolling = "no"), a(e).attr({
                    src: K.href,
                    name: (new Date).getTime(),
                    "class": _ + "Iframe",
                    allowFullScreen: !0,
                    webkitAllowFullScreen: !0,
                    mozallowfullscreen: !0
                }).one("load", g).appendTo(A), hb.one(gb, function () {
                    e.src = "//about:blank"
                }), K.fastIframe && a(e).trigger("load")) : g(), "fade" === K.transition ? r.fadeTo(k, 1, c) : c())
            }, "fade" === K.transition ? r.fadeTo(k, 0, function () {
                W.position(0, j)
            }) : W.position(k, j)
        }
    }, W.next = function () {
        !T && y[1] && (K.loop || y[Q + 1]) && (Q = f(1), m(y[Q]))
    }, W.prev = function () {
        !T && y[1] && (K.loop || Q) && (Q = f(-1), m(y[Q]))
    }, W.close = function () {
        S && !U && (U = !0, S = !1, l(eb, K.onCleanup), z.unbind("." + _), q.fadeTo(K.fadeOut || 0, 0), r.stop().fadeTo(K.fadeOut || 0, 0, function () {
            r.add(q).css({
                opacity: 1,
                cursor: "auto"
            }).hide(), l(gb), A.empty().remove(), setTimeout(function () {
                U = !1, l(fb, K.onClosed)
            }, 1)
        }))
    }, W.remove = function () {
        r && (r.stop(), a.colorbox.close(), r.stop().remove(), q.remove(), U = !1, r = null, a("." + ab).removeData($).removeClass(ab), a(b).unbind("click." + _))
    }, W.element = function () {
        return a(P)
    }, W.settings = Z)
}(jQuery, document, window);
$(document).on("click", ".JS-VideoYoutube", function (a) {
    if (TCS.isDesktopStyles) {
        a.preventDefault();
        $.colorbox({
            href: this.getAttribute("href"),
            iframe: !0,
            innerWidth: 962,
            innerHeight: 537,
            opacity: .6,
            close: ""
        })
    }
}).on("desktop.version mobile.version", $.colorbox.close);
var TCS = "undefined" == typeof TCS ? {} : TCS;
! function (a, b) {
    "use strict";
    var c = a.inherit({
        tagName: "div",
        className: "tcs-view",
        __constructor: function (b) {
            this.freeDimensions = !1;
            a.extend(this, b);
            this.parentView = null;
            this.container || (this.container = a(document.createElement(this.tagName)));
            !this.container instanceof jQuery && (this.container = a(this.container));
            this.container.addClass(this.className);
            this.children = [];
            this.width = 0;
            this.height = 0;
            this.rendered = !1;
            return this
        },
        setWidth: function (a) {
            this.width = a;
            return this.render()
        },
        setHeight: function (a) {
            this.height = a;
            return this.render()
        },
        render: function () {
            this.freeDimensions || this.container.css({
                width: this.width,
                height: this.height
            });
            this.rendered = !0;
            this.onRender();
            return this
        },
        onRender: function () {},
        show: function () {
            if (!this.rendered) return this;
            a(this.container).show();
            this.onShow();
            return this
        },
        onShow: function () {},
        hide: function () {
            a(this.container).hide();
            this.onHide();
            return this
        },
        onHide: function () {},
        makeAbsoluteIfNeeded: function () {
            "absolute" !== this.container.css("position") && this.container.css("position", "absolute")
        },
        position: function (a) {
            this.makeAbsoluteIfNeeded();
            this.container.css({
                left: a.left,
                top: a.top
            });
            return this
        },
        positionCenter: function () {
            this.makeAbsoluteIfNeeded();
            this.position({
                left: this.container.parent().width() / 2 - this.container.width() / 2,
                top: this.container.parent().height() / 2 - this.container.height() / 2
            });
            return this
        },
        setAlwaysCentered: function () {
            this.makeAbsoluteIfNeeded();
            this.container.css({
                left: "50%",
                top: "50%",
                "margin-left": -this.container.width() / 2,
                "margin-top": -this.container.height() / 2 + a(window).scrollTop()
            });
            return this
        }
    }, {
        toString: function () {
            return "View"
        }
    });
    b.View = c
}(jQuery, TCS);
var TCS = "undefined" == typeof TCS ? {} : TCS;
! function (a, b) {
    "use strict";
    var c = a.inherit(b.View, {
        className: "tcs-window-view tcs-view",
        __constructor: function (b) {
            this.__base(b);
            this.contentContainer = null;
            this.contentContainerClass = "tcs-window-content-view";
            this.closeButton = null;
            this.closeButtonClass = "tcs-window-button tcs-window-button-close";
            this.closeButton = a(document.createElement("div"));
            this.closeButton.addClass(this.closeButtonClass);
            this.contentContainer = a(document.createElement("div"));
            this.contentContainer.addClass(this.contentContainerClass);
            this.isStatic || this.container.append(this.closeButton);
            this.container.append(this.contentContainer);
            this.closeButton.click(this.onCloseButtonClick.bind(this));
            return this
        },
        onCloseButtonClick: function () {
            this.hide()
        },
        setContent: function (a) {
            this.contentContainer.html(a);
            return this
        }
    }, {
        toString: function () {
            return "Window"
        }
    });
    b.Window = c
}(jQuery, TCS);
var TCS = "undefined" == typeof TCS ? {} : TCS;
! function (a, b) {
    "use strict";
    var c = a.inherit(b.Window, {
        className: "tcs-modal-window-view tcs-window-view tcs-view",
        __constructor: function (b) {
            this.__base(b);
            this.fader = document.createElement("div");
            this.fader.className = "tcs-modal-window-fader";
            this.fader = a(this.fader);
            this.isStatic || this.fader.bind("click", this.onFaderClick.bind(this));
            this.open = this.show;
            this.close = this.hide;
            return this
        },
        onKeyDown: function (a) {
            27 === a.keyCode && this.hide()
        },
        onFaderClick: function () {
            this.hide()
        },
        show: function () {
            a(document.body).bind("keydown.modalwindow", this.onKeyDown.bind(this));
            this.fader.show();
            return this.__base()
        },
        onClose: function () {},
        hide: function () {
            a(document.body).unbind("keydown.modalwindow");
            this.fader.hide();
            this.onClose();
            return this.__base()
        },
        render: function () {
            if (!this.rendered) {
                this.container.css({
                    position: "fixed"
                });
                a(document.body).append(this.fader);
                a(document.body).append(this.container)
            }
            this.__base();
            return this
        },
        centering: function () {
            return this.setAlwaysCentered()
        }
    }, {
        toString: function () {
            return "ModalWindow"
        }
    });
    b.ModalWindow = c
}(jQuery, TCS);
var initMobileBankGadget = function (a) {
    "use strict";
    var b = "tcs-slide_mob-bank",
        c = "tcs-mob-bank__slideshow",
        d = {
            transition_name: "fade",
            transition_ease: "Power1.easeInOut",
            transition_speed: 700
        };
    return function (e) {
        var f = a(e.body),
            g = f.find("." + b);
        g.slideshow(a.extend({}, d, {
            css_pagination: b + "__device-switcher",
            css_page: "tcs-mob-devices",
            css_page_active: "tcs-mob-devices_active",
            css_slides: b,
            css_slide: c
        })).on("slideshow.init", function (a, b) {
            initGetAppLink(b.$slideshow)
        }).find("." + c).slideshow(d)
    }
}(jQuery);
! function (a) {
    "use strict";
    a.fn.shareButtons = function (b) {
        var c = a.extend(!0, {
            text: "",
            link: document.location.pathname,
            social: {
                fb: !0,
                tw: !0,
                gp: !0,
                vk: !0
            }
        }, b),
            d = a(this),
            e = /^\/eng\//.test(document.location.pathname) ? "en" : "ru",
            f = document.location.hostname,
            g = TCS.utils.getSocialAppId("vk", f),
            h = ("https:" === document.location.protocol, {
                fb: '<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-like" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true"></div>',
                tw: '<a href="//twitter.com/share" class="twitter-share-button" data-via="tcsbank" data-lang="' + e + '" data-url="http://www.tcsbank.ru' + c.link + '" data-text="' + c.text + '">Твитнуть</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>',
                gp: '<div class="g-plusone" data-size="medium" data-prefilltext="' + c.text + '"></div><script type="text/javascript">window.___gcfg = {lang: \'' + e + "'};(function() {var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;po.src = 'https://apis.google.com/js/plusone.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);})();</script>",
                vk: '<script type="text/javascript">VK.init({apiId: ' + g + ', onlyWidgets: true});</script><div id="vk_like"></div><script type="text/javascript">VK.Widgets.Like("vk_like", {type: "mini", height: 20, pageUrl : "http://www.tcsbank.ru' + c.link + '", pageDescription : "' + c.text + '"});</script>'
            }),
            i = {
                fb: 71,
                tw: 105,
                gp: 59,
                vk: 79
            }, j = "";
        a.each(c.social, function (a, b) {
            b && (j += '<li style="width:' + i[a] + 'px; display: inline-block; margin-right: 10px">' + h[a] + "</li>")
        });
        a.when(TCS.loadVk(), TCS.loadFacebook()).done(function () {
            d.html('<ul style="display: inline-block">' + j + "</ul>")
        })
    }
}(jQuery);
! function (a, b) {
    "use strict";
    a.AjaxSocialSharing = {
        createObject: function (a) {
            if (!(a && a.header && a.body && a.uri)) throw new Error("Wrong options");
            return b.get(TCS.getServiceURL("create_shared_object"), a)
        },
        getObject: function (a) {
            return b.get(TCS.getServiceURL("shared_object/" + a))
        },
        getURLRating: function (a) {
            var c = b.Deferred();
            b.get(TCS.getServiceURL("social_get_likes"), a).done(function (a) {
                TCS.utils.checkResponse(a, ["likes_count"]) ? c.resolve(a.payload.likes_count) : c.reject()
            });
            return c.promise()
        },
        getShareURL: function (a) {
            var c = b.Deferred(),
                d = TCS.apiServer && /\w+/.test(TCS.apiServer) ? TCS.apiServer : "https://www.tcsbank.ru/api/v1/";
            this.createObject(a).done(function (a) {
                TCS.utils.checkResponse(a, ["id"]) ? c.resolve(d + "shared_object/" + a.payload.id) : c.reject()
            }.bind(this));
            return c.promise()
        },
        getShareURLForFriends: function (a) {
            var c = b.Deferred(),
                d = a || {};
            TCS.Session.getSessionID() ? b.get(TCS.getServiceURL("generate_invite_link"), {
                utmCampaign: "directlink",
                utmContent: "directlink"
            }).done(function (a) {
                if (TCS.utils.checkResponse(a, ["directlink"])) {
                    var e = a.payload.directlink;
                    if (d.header) {
                        var f = {
                            header: d.header,
                            body: d.body || "После активации новой карты — Вы получаете в подарок от банка 500 рублей! Ваш друг также получит на свой счет 500 рублей!",
                            uri: e
                        };
                        d && d.image && (f.image = d.image);
                        this.getShareURL(f).done(function (a) {
                            c.resolve(a)
                        }).fail(function () {
                            c.reject()
                        })
                    } else b.get(TCS.getServiceURL("personal_info")).done(function (a) {
                        if (TCS.utils.checkResponse(a, ["personalInfo"])) {
                            var b = {
                                header: a.payload.personalInfo.fullName.firstName + " " + a.payload.personalInfo.fullName.lastName + ", клиент банка «Тинькофф Кредитные Системы», рекомендует Вам оформить нашу карту!",
                                body: d.body || "После активации новой карты — Вы получаете в подарок от банка 500 рублей! Ваш друг также получит на свой счет 500 рублей!",
                                uri: e
                            };
                            d && d.image && (b.image = d.image);
                            this.getShareURL(b).done(function (a) {
                                c.resolve(a)
                            }).fail(function () {
                                c.reject()
                            })
                        }
                    }.bind(this))
                }
            }.bind(this)) : c.reject();
            return c.promise()
        }
    };
    var c = {
        shareLinks: {
            fb: "http://www.facebook.com/sharer.php?u={{link}}",
            tw: "http://twitter.com/intent/tweet?status={{link}}",
            vk: "http://vk.com/share.php?url={{link}}",
            od: "",
            gplus: "https://plus.google.com/share?url={{link}}"
        },
        toggle: function (a) {
            a.preventDefault();
            a.stopPropagation();
            b(a.target).toggleClass("tcs-social-popup-box_drop")
        },
        share: function (a) {
            var c = b(a.target).attr("data-type"),
                d = b(a.target).attr("data-url");
            this.openSocialWindow(this.getSocialWindowLink(d, c))
        },
        openSocialWindow: function (a) {
            var b = (window.screen.width - 700) / 2,
                c = (window.screen.height - 500) / 2;
            try {
                window.open(a, "TCS", "width=700,height=500,left=" + b + ",top=" + c + ",status=no,toolbar=no,menubar=no")
            } catch (d) {}
            return !1
        },
        getSocialWindowLink: function (a, b) {
            return this.shareLinks[b].replace("{{link}}", a)
        }
    };
    a.SocialPopup = c;
    b.fn.socialPopup = function (a) {
        var d = {
            types: ["fb", "tw", "gp", "od", "vk"],
            css: "tcs-social-popup",
            url: "https://www.tcsbank.ru/"
        }, e = b.extend(!0, d, a || {}),
            f = '<div class="tcs-social-popup {{css}}"><ul class="tcs-social-popup__list">{{list}}</ul></div>',
            g = '<li class="tcs-social-popup__list-item tcs-social-popup__list-item_{{type}}" data-type="{{type}}" data-url="{{url}}"></li>';
        return this.each(function (a, d) {
            for (var h = b(d), i = "", j = "", k = {
                    types: h.attr("data-social") || e.types,
                    css: h.attr("data-social-css") || e.css,
                    url: h.attr("data-social-url") || e.url
                }, l = 0; l < e.types.length; l++) j += g.replace(/\{\{type\}\}/gi, k.types[l]).replace("{{url}}", k.url);
            i += f.replace("{{list}}", j);
            h.append(i.replace("{{css}}", k.css));
            h.data("socialPopup", {
                options: k
            });
            h.on("click.socialpopup", c.toggle.bind(c));
            b(".tcs-social-popup__list-item").get(0).onclick = c.share.bind(c);
            h.addClass("tcs-social-popup-box");
            /absolute|relative/.test(h.css("position")) || h.css("position", "relative")
        })
    };
    b(function () {
        b(document).on("click.socialpopup", function (a) {
            var c = b(a.target);
            c.data("socialPopup") || "social" !== c.attr("data-action") || c.socialPopup();
            b("body").find(".tcs-social-popup-box_drop").each(function (a, c) {
                b(c).removeClass("tcs-social-popup-box_drop")
            })
        })
    })
}(TCS || {}, jQuery);
var TCS = TCS || {};
! function ($, global) {
    function getPersonalInfo(a) {
        if (personalInfo) {
            $.isFunction(a) && a(null, personalInfo);
            return $.extend(!0, {}, personalInfo)
        }
        $.getJSON(TCS.getServiceURL("personal_info")).success(didGetPersonalInfo.bind(this, a)).fail(didFailToGetPersonalInfo.bind(this, a));
        return $.extend(!0, {}, personalInfo)
    }

    function didGetPersonalInfo(a, b, c, d) {
        if (b && b.payload && b.payload.personalInfo) {
            personalInfo = b.payload;
            $.isFunction(a) && a(null, personalInfo)
        } else didFailToGetPersonalInfo(a, d, c)
    }

    function didFailToGetPersonalInfo(a) {
        personalInfo = null;
        $.isFunction(a) && a(!0)
    }

    function getMobilePhoneFormatting() {
        return personalInfo ? "+" + personalInfo.personalInfo.mobilePhoneNumber.countryCode + " (" + personalInfo.personalInfo.mobilePhoneNumber.innerCode + ") " + personalInfo.personalInfo.mobilePhoneNumber.number : "not load"
    }

    function getFullName() {
        if (!personalInfo) return "not load";
        with(personalInfo.personalInfo.fullName) return [lastName, firstName, patronymic].join(" ")
    }

    function getAddrNode(a) {
        if (!personalInfo) return !1;
        switch (a) {
        case "REGISTRATION":
            addrNode = personalInfo.passport.registrationAddress;
            break;
        case "HOME":
            addrNode = personalInfo.personalInfo.homeAddress;
            break;
        case "WORK":
            addrNode = personalInfo.employer.address;
            break;
        default:
            addrNode = !1
        }
        return addrNode
    }

    function formatAddress(a, b) {
        return (b ? "" : a.state + ", ") + (a.city ? a.city + ", " : "") + a.streetAddress + ", " + a.houseNumber + (a.buildingNumber ? "/" + a.buildingNumber : "") + (a.constructionNumber ? ", ст." + a.constructionNumber : "") + (a.apartmentNumber ? ", кв." + a.apartmentNumber : "")
    }

    function getAddress(a) {
        if (!personalInfo) return "not load";
        var b = getAddrNode(a);
        return b ? formatAddress(b) : "not load"
    }

    function getShipmentAddress() {
        var a = getAddrNode(personalInfo.subscriptionDestination);
        return personalInfo && a ? formatAddress(a) : "not load"
    }
    var personalInfo = null;
    global.getPersonalInfo = getPersonalInfo;
    global.getMobilePhoneFormatting = getMobilePhoneFormatting;
    global.getFullName = getFullName;
    global.getShipmentAddress = getShipmentAddress;
    global.getAddress = getAddress;
    global.getAddressNode = getAddrNode;
    global.formatAddress = formatAddress
}(jQuery, TCS);
$(function (a) {
    function b(a, b) {
        this.findByName(b).parents(".tcs-form-row").addClass("tcs-form-row-error");
        $("label[for=" + b + "].tcs-form-row-message", ".form-short-version").addClass("tcs-form-message-error tcs-form-input-error")
    }
    var c = {
        body: ".form-short-version",
        rules: {
            surname: {
                required: !0,
                cyrillic: !0,
                maxlength: 33
            },
            name: {
                required: !0,
                cyrillic: !0,
                maxlength: 40
            },
            patronymic: {
                cyrillic: !0,
                maxlength: 40,
                required: !1
            },
            phone_mobile: {
                required: !0,
                phoneRU1: !0,
                ruPhonePrefix: !0
            },
            email: {
                email: !0,
                required: !1
            },
            agreement: {
                required: !0
            }
        },
        messages: {
            phone_mobile: {
                required: "Необходимо указать номер телефона"
            },
            agreement: {
                required: "Для продолжения нужно согласие с условиями"
            }
        },
        init: function () {
            this.bindMasks();
            this.bindPlugins();
            this.bindValidation();
            this.bindActions()
        },
        bindMasks: function () {
            $('input[name="phone_mobile"]', this.body).mask("8(999) 999-99-99")
        },
        bindPlugins: function () {
            $('input[name="agreement"]', this.body).pluginCheckbox()
        },
        bindValidation: function () {
            $(this.body).validate({
                errorClass: "tcs-form-input-error",
                rules: this.rules,
                messages: this.messages,
                ignoreTitle: !0,
                showErrors: this.showErrors,
                onkeyup: !1,
                errorPlacement: this.notificationPlacement,
                debug: !1
            })
        },
        bindActions: function () {
            $(this.body).on("click", ".js_forward", this.submit.bind(this)).on("click", ".form-short-rules-open", this.onShowRulesClick).on("click", ".form-short-rules-close", this.onCloseRulesClick)
        },
        onShowRulesClick: function (b) {
            $(".tcs-form-rules", this.body).fadeIn();
            $("body").append('<div class="user-agree-transfer-data-overlay" id="user-agree-transfer-data-overlay"></div>');
            $("#user-agree-transfer-data-overlay").css({
                opacity: "0.2"
            });
            a.utils._gaqPush(["_trackPageview", window.location.pathname + "rules-splash"]);
            b.preventDefault()
        },
        onCloseRulesClick: function (a) {
            $(".tcs-form-rules", this.body).hide();
            $("#user-agree-transfer-data-overlay").remove();
            a.preventDefault()
        },
        showErrors: function (a) {
            var c = $.data(this.currentForm, "validator"),
                d = _.extend(a, c.invalid);
            $(".tcs-form-row-error", ".form-short-version").removeClass("tcs-form-row-error");
            _.each(d, b, this);
            this.defaultShowErrors()
        },
        notificationPlacement: function (a, b) {
            if (void 0 !== a || void 0 !== b) {
                var c = ".tcs-form-row";
                b && b[0] && "agreement" === b[0].name && (c = ".tcs-form-input-field");
                a.addClass("tcs-form-row-message tcs-form-message-error");
                a.appendTo(b.parents(c));
                return a
            }
        },
        serialize: function () {
            var a = "";
            $(this.body).find("input").each(function (b, c) {
                var d = $(c),
                    e = d.val(),
                    f = d.attr("name");
                if (e) {
                    "phone_mobile" === f && (e = e.replace(/[\(\)\-\s]/g, ""));
                    a += (a ? "&" : "") + f + "=" + e
                }
            });
            a += (a ? "&" : "") + "step_1=submitted";
            return a
        },
        submit: function () {
            var a = this.serialize();
            event.preventDefault();
            $(this.body).valid() && (document.location = "/credit/form-credit-allairlines/?" + a)
        }
    };
    a.errorListingCurrentStep = [];
    $(".form-short-version").length && c.init()
}(TCS));
jQuery.easing.jswing = jQuery.easing.swing;
jQuery.extend(jQuery.easing, {
    def: "easeOutQuad",
    swing: function (a, b, c, d, e) {
        return jQuery.easing[jQuery.easing.def](a, b, c, d, e)
    },
    easeInQuad: function (a, b, c, d, e) {
        return d * (b /= e) * b + c
    },
    easeOutQuad: function (a, b, c, d, e) {
        return -d * (b /= e) * (b - 2) + c
    },
    easeInOutQuad: function (a, b, c, d, e) {
        return (b /= e / 2) < 1 ? d / 2 * b * b + c : -d / 2 * (--b * (b - 2) - 1) + c
    },
    easeInCubic: function (a, b, c, d, e) {
        return d * (b /= e) * b * b + c
    },
    easeOutCubic: function (a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b + 1) + c
    },
    easeInOutCubic: function (a, b, c, d, e) {
        return (b /= e / 2) < 1 ? d / 2 * b * b * b + c : d / 2 * ((b -= 2) * b * b + 2) + c
    },
    easeInQuart: function (a, b, c, d, e) {
        return d * (b /= e) * b * b * b + c
    },
    easeOutQuart: function (a, b, c, d, e) {
        return -d * ((b = b / e - 1) * b * b * b - 1) + c
    },
    easeInOutQuart: function (a, b, c, d, e) {
        return (b /= e / 2) < 1 ? d / 2 * b * b * b * b + c : -d / 2 * ((b -= 2) * b * b * b - 2) + c
    },
    easeInQuint: function (a, b, c, d, e) {
        return d * (b /= e) * b * b * b * b + c
    },
    easeOutQuint: function (a, b, c, d, e) {
        return d * ((b = b / e - 1) * b * b * b * b + 1) + c
    },
    easeInOutQuint: function (a, b, c, d, e) {
        return (b /= e / 2) < 1 ? d / 2 * b * b * b * b * b + c : d / 2 * ((b -= 2) * b * b * b * b + 2) + c
    },
    easeInSine: function (a, b, c, d, e) {
        return -d * Math.cos(b / e * (Math.PI / 2)) + d + c
    },
    easeOutSine: function (a, b, c, d, e) {
        return d * Math.sin(b / e * (Math.PI / 2)) + c
    },
    easeInOutSine: function (a, b, c, d, e) {
        return -d / 2 * (Math.cos(Math.PI * b / e) - 1) + c
    },
    easeInExpo: function (a, b, c, d, e) {
        return 0 == b ? c : d * Math.pow(2, 10 * (b / e - 1)) + c
    },
    easeOutExpo: function (a, b, c, d, e) {
        return b == e ? c + d : d * (-Math.pow(2, -10 * b / e) + 1) + c
    },
    easeInOutExpo: function (a, b, c, d, e) {
        return 0 == b ? c : b == e ? c + d : (b /= e / 2) < 1 ? d / 2 * Math.pow(2, 10 * (b - 1)) + c : d / 2 * (-Math.pow(2, -10 * --b) + 2) + c
    },
    easeInCirc: function (a, b, c, d, e) {
        return -d * (Math.sqrt(1 - (b /= e) * b) - 1) + c
    },
    easeOutCirc: function (a, b, c, d, e) {
        return d * Math.sqrt(1 - (b = b / e - 1) * b) + c
    },
    easeInOutCirc: function (a, b, c, d, e) {
        return (b /= e / 2) < 1 ? -d / 2 * (Math.sqrt(1 - b * b) - 1) + c : d / 2 * (Math.sqrt(1 - (b -= 2) * b) + 1) + c
    },
    easeInElastic: function (a, b, c, d, e) {
        var a = 1.70158,
            f = 0,
            g = d;
        if (0 == b) return c;
        if (1 == (b /= e)) return c + d;
        f || (f = .3 * e);
        g < Math.abs(d) ? (g = d, a = f / 4) : a = f / (2 * Math.PI) * Math.asin(d / g);
        return -(g * Math.pow(2, 10 * (b -= 1)) * Math.sin(2 * (b * e - a) * Math.PI / f)) + c
    },
    easeOutElastic: function (a, b, c, d, e) {
        var a = 1.70158,
            f = 0,
            g = d;
        if (0 == b) return c;
        if (1 == (b /= e)) return c + d;
        f || (f = .3 * e);
        g < Math.abs(d) ? (g = d, a = f / 4) : a = f / (2 * Math.PI) * Math.asin(d / g);
        return g * Math.pow(2, -10 * b) * Math.sin(2 * (b * e - a) * Math.PI / f) + d + c
    },
    easeInOutElastic: function (a, b, c, d, e) {
        var a = 1.70158,
            f = 0,
            g = d;
        if (0 == b) return c;
        if (2 == (b /= e / 2)) return c + d;
        f || (f = .3 * e * 1.5);
        g < Math.abs(d) ? (g = d, a = f / 4) : a = f / (2 * Math.PI) * Math.asin(d / g);
        return 1 > b ? -.5 * g * Math.pow(2, 10 * (b -= 1)) * Math.sin(2 * (b * e - a) * Math.PI / f) + c : g * Math.pow(2, -10 * (b -= 1)) * Math.sin(2 * (b * e - a) * Math.PI / f) * .5 + d + c
    },
    easeInBack: function (a, b, c, d, e, f) {
        void 0 == f && (f = 1.70158);
        return d * (b /= e) * b * ((f + 1) * b - f) + c
    },
    easeOutBack: function (a, b, c, d, e, f) {
        void 0 == f && (f = 1.70158);
        return d * ((b = b / e - 1) * b * ((f + 1) * b + f) + 1) + c
    },
    easeInOutBack: function (a, b, c, d, e, f) {
        void 0 == f && (f = 1.70158);
        return (b /= e / 2) < 1 ? d / 2 * b * b * (((f *= 1.525) + 1) * b - f) + c : d / 2 * ((b -= 2) * b * (((f *= 1.525) + 1) * b + f) + 2) + c
    },
    easeInBounce: function (a, b, c, d, e) {
        return d - jQuery.easing.easeOutBounce(a, e - b, 0, d, e) + c
    },
    easeOutBounce: function (a, b, c, d, e) {
        return (b /= e) < 1 / 2.75 ? 7.5625 * d * b * b + c : 2 / 2.75 > b ? d * (7.5625 * (b -= 1.5 / 2.75) * b + .75) + c : 2.5 / 2.75 > b ? d * (7.5625 * (b -= 2.25 / 2.75) * b + .9375) + c : d * (7.5625 * (b -= 2.625 / 2.75) * b + .984375) + c
    },
    easeInOutBounce: function (a, b, c, d, e) {
        return e / 2 > b ? .5 * jQuery.easing.easeInBounce(a, 2 * b, 0, d, e) + c : .5 * jQuery.easing.easeOutBounce(a, 2 * b - e, 0, d, e) + .5 * d + c
    }
});
! function (a) {
    function b(b) {
        var c = b || window.event,
            d = [].slice.call(arguments, 1),
            e = 0,
            f = 0,
            g = 0;
        b = a.event.fix(c);
        b.type = "mousewheel";
        c.wheelDelta && (e = c.wheelDelta / 120);
        c.detail && (e = -c.detail / 3);
        g = e;
        if (void 0 !== c.axis && c.axis === c.HORIZONTAL_AXIS) {
            g = 0;
            f = -1 * e
        }
        void 0 !== c.wheelDeltaY && (g = c.wheelDeltaY / 120);
        void 0 !== c.wheelDeltaX && (f = -1 * c.wheelDeltaX / 120);
        d.unshift(b, e, f, g);
        return (a.event.dispatch || a.event.handle).apply(this, d)
    }
    var c = ["DOMMouseScroll", "mousewheel"];
    if (a.event.fixHooks)
        for (var d = c.length; d;) a.event.fixHooks[c[--d]] = a.event.mouseHooks;
    a.event.special.mousewheel = {
        setup: function () {
            if (this.addEventListener)
                for (var a = c.length; a;) this.addEventListener(c[--a], b, !1);
            else this.onmousewheel = b
        },
        teardown: function () {
            if (this.removeEventListener)
                for (var a = c.length; a;) this.removeEventListener(c[--a], b, !1);
            else this.onmousewheel = null
        }
    };
    a.fn.extend({
        mousewheel: function (a) {
            return a ? this.bind("mousewheel", a) : this.trigger("mousewheel")
        },
        unmousewheel: function (a) {
            return this.unbind("mousewheel", a)
        }
    })
}(jQuery);
! function (a, b, c, d, e) {
    "use strict";
    var f = c.Collection.extend({
        url: function () {
            return a.getServiceURL("regions")
        },
        parse: function (a) {
            if (a && "OK" === a.resultCode) return a.payload;
            this.trigger("error");
            return void 0
        }
    }),
        g = c.Model.extend({
            initialize: function () {
                this.requestAuthStatus()
            },
            requestAuthStatus: function () {
                b.getJSON(TCS.getServiceURL("ping")).success(this.onStatusReceived.bind(this))
            },
            onStatusReceived: function (c) {
                var d = this;
                b(function () {
                    if (c.payload && "ANONYMOUS" !== c.payload.accessLevel && "OK" === c.resultCode) {
                        TCS.identify.init(!0);
                        b.getJSON(TCS.getServiceURL("personal_info")).success(function (c) {
                            if (a.utils.checkResponse(c, ["personalInfo"])) {
                                TCS.Auth.personalInfo = c.payload.personalInfo;
                                if (!b.isEmptyObject(c.payload.personalInfo.fullName)) {
                                    var e = c.payload.personalInfo.fullName;
                                    TCS.Auth.Cfg.userFullName = e.firstName + " " + e.lastName;
                                    d.set("firstName", e.firstName);
                                    d.set("lastName", e.lastName);
                                    d.set("nameLoadFail", !1)
                                }
                            } else d.set("nameLoadFail", !0);
                            d.trigger("signin")
                        }).error(function () {
                            d.set("nameLoadFail", !0);
                            d.trigger("signin")
                        })
                    } else TCS.identify.init(!1)
                })
            },
            getAbbr: function () {
                return this.get("lastName") + " " + this.get("firstName").substr(0, 1) + "."
            }
        }),
        h = c.View.extend({
            events: {
                "click .HeaderToolbar-Logout": "logout",
                "click .HeaderToolbar-UserName": "toggle"
            },
            initialize: function () {
                this.$engMenuItem = b(".Eng-Menu-Item");
                this.model = new g;
                this.template = a.utils.getCompileTemplate("HeaderToolbar-LoginTemplate");
                this.listenTo(c, "user:signin", function () {
                    this.model.requestAuthStatus()
                }, this);
                this.model.on("signin", this.render, this)
            },
            logout: function () {
                window.Backbone && "function" == typeof window.Backbone.trigger && c.trigger("session:end")
            },
            render: function () {
                var a = this.model.get("nameLoadFail");
                this.setElement(this.template({
                    fullName: a ? "" : this.model.getAbbr(),
                    nameLoadFail: a
                }));
                this.$el.appendTo(b(".Header-Navigation"));
                this.$engMenuItem.hide()
            },
            toggle: function () {
                this.$(".HeaderToolbar-UserName").parent().toggleClass("header__user_active")
            }
        }),
        i = c.Model.extend({
            initialize: function () {
                var c = b.cookie("client-region-name-kladr"),
                    d = b.cookie("client-region-id-kladr");
                d && c || this.chooseRegion(!0).always(function (b) {
                    if (a.utils.checkResponse(b, ["id"])) {
                        this.set("id", b.payload.id);
                        this.set("name", b.payload.name)
                    } else this.trigger("error")
                }.bind(this));
                this.set("name", c);
                this.set("id", d);
                this.on("change:name", this.chooseRegion.bind(this, !1), this)
            },
            chooseRegion: function (d) {
                var e = this.get("id"),
                    f = this.get("name"),
                    g = {
                        expires: 365,
                        path: "/"
                    };
                this.trigger("region:choose");
                b.cookie("client-region-id-kladr", e, g);
                b.cookie("client-region-name-kladr", f, g);
                b.cookie("client-region-deliverable", !1, g);
                return b.ajax({
                    url: a.getServiceURL("nearest_region"),
                    data: {
                        regionId: e,
                        feature: "deliverable",
                        required: d ? "true" : "false"
                    },
                    success: function (d) {
                        a.utils.checkResponse(d, ["id"]) ? b.cookie("client-region-deliverable", !0, g) : b.cookie("client-region-deliverable", !1, g);
                        c.trigger("region:choosed", {
                            id: e,
                            name: f
                        });
                        this.trigger("region:choosed", {
                            id: e,
                            name: f
                        })
                    }.bind(this),
                    error: function () {}
                })
            }
        }),
        j = c.View.extend({
            events: {
                "click .HeaderToolbar-Region": "chooseLocation",
                "click .HeaderToolbar-Item-Suggest": "chooseLocation",
                "keyup .HeaderToolbar-Search": "livesearch",
                "focus .HeaderToolbar-Search": "onfocus",
                "blur .HeaderToolbar-Search": "onblur",
                "click .HeaderToolbar-Close": "hide"
            },
            initialize: function () {
                this.collection = new f;
                this.model = new i;
                b("body").on("click.cityselect", function (a) {
                    this.visible && !b(a.target).closest(this.$el).length && this.header[0] !== a.target && this.hide()
                }.bind(this));
                this.collection.on("reset", function (a) {
                    this.suggestPossible = !(!a || !a.length);
                    if (this.suggestPossible) {
                        this.renderSuggest();
                        this.suggestNecessary && this.showSuggest()
                    } else this.suggestNecessary && this.hideSuggest()
                }, this);
                this.model.on("region:choosed", function (a) {
                    this.hideWaiting();
                    this.hide();
                    this.setHeader(a.name)
                }, this);
                this.model.on("error", function () {
                    this.header.html("город не определен")
                }, this);
                this.render()
            },
            chooseLocation: function (a) {
                var c = b(a.target);
                this.model.set({
                    id: c.data("id"),
                    name: c.data("name") || c.text()
                });
                this.$(".HeaderToolbar-Region").removeClass("header-toolbar__region_selected").filter("[data-id=" + c.data("id") + "]").addClass("header-toolbar__region_selected");
                this.collection.reset([], {
                    silent: !0
                });
                this.showWaiting()
            },
            onfocus: function (a) {
                if (this.collection.length) {
                    this.liftSearchLabel(a);
                    this.suggestNecessary = !0;
                    this.suggestPossible && this.showSuggest()
                } else this.$(".HeaderToolbar-SearchBox").addClass("header-toolbar__search_filled")
            },
            onblur: function (a) {
                a.preventDefault();
                setTimeout(function () {
                    this.lowerSearchLabel(a);
                    this.suggestNecessary = !1;
                    this.suggestPossible && this.hideSuggest()
                }.bind(this), 300)
            },
            livesearch: function (a) {
                var c = b(a.target).val();
                this.searchKeyupTimeout && clearInterval(this.searchKeyupTimeout);
                c.length > 2 ? this.searchKeyupTimeout = setTimeout(function () {
                    this.collection.fetch({
                        data: {
                            q: c,
                            query: c,
                            feature: "selectable",
                            limit: 10
                        }
                    });
                    this.model.set({
                        query: c
                    })
                }.bind(this), 700) : 0 === c.length && this.hideSuggest()
            },
            liftSearchLabel: function (a) {
                var b = this.$(".HeaderToolbar-Search"),
                    c = this.$(".tcs-form-row"),
                    d = this.$(".HeaderToolbar-SearchBox"),
                    e = this.$(".tcs-form-row-field"),
                    f = function () {
                        e.removeClass("tcs-form-row-field-focus-ready");
                        c.addClass("tcs-form-row-field-input-onfocus");
                        setTimeout(function () {
                            if (c.is(".tcs-form-row-field-input-onfocus")) {
                                e.addClass("tcs-form-row-field-focus-ready");
                                d.addClass("header-toolbar__search_filled")
                            }
                        }, 10)
                    }, g = c.is(".tcs-form-row-field-input-fill, .tcs-form-row-field-input-onfocus");
                g || f();
                "selectField" === a.type && b.trigger("blur")
            },
            lowerSearchLabel: function () {
                var a = this.$(".HeaderToolbar-Search").val(),
                    b = this.$(".tcs-form-row"),
                    c = this.$(".tcs-form-row-field");
                this.$(".HeaderToolbar-SearchBox").removeClass("header-toolbar__search_filled");
                if (0 === a.length) {
                    b.removeClass("tcs-form-row-field-input-fill");
                    c.removeClass("tcs-form-row-field-input-label-focus-ready")
                }
                b.removeClass("tcs-form-row-field-input-onfocus")
            },
            position: function () {
                var a = b(".header__content"),
                    c = a.offset();
                this.$el.css({
                    top: c.top + a.outerHeight()
                })
            },
            render: function () {
                var c = a.utils.getCompileTemplate("HeaderToolbar-Template");
                this.setElement(c());
                this.wrapper = b(".wrapper");
                this.footer = b(".footer");
                this.header = b(".Header-ToggleHeaderToolbar");
                this.$el.appendTo("body");
                this.search = this.$(".HeaderToolbar-Search");
                this.suggest = this.$(".HeaderToolbar-Suggest");
                this.suggestTemplate = a.utils.getCompileTemplate("HeaderToolbar-SuggestTemplate");
                this.hide();
                this.$(".HeaderToolbar-Region").removeClass("header-toolbar__region_selected").filter("[data-id=" + this.model.get("id") + "]").addClass("header-toolbar__region_selected")
            },
            renderSuggest: function () {
                var a = this.collection.slice(0, 8),
                    b = e.map(a, function (a) {
                        return a.toJSON()
                    });
                this.suggest.html(this.suggestTemplate({
                    regions: b
                }));
                this.showSuggest()
            },
            setHeader: function (a) {
                this.header.text(a)
            },
            show: function () {
                if (a.isDesktopStyles) {
                    this.position();
                    this.hideWaiting();
                    this.showBackground();
                    this.header.addClass("navigation__second-link_location_dropped")
                } else {
                    this.suggest.show();
                    this.wrapper.addClass("wrapper_popup_open");
                    this.footer.addClass("footer_hidden")
                }
                this.visible = !0;
                this.$el.show()
            },
            hide: function () {
                this.visible = !1;
                this.$el.hide();
                this.wrapper.removeClass("wrapper_popup_open");
                this.footer.removeClass("footer_hidden");
                this.hideBackground();
                this.hideSuggest();
                if (a.isDesktopStyles) {
                    this.$el.removeAttr("style");
                    this.lowerSearchLabel();
                    this.header.removeClass("navigation__second-link_location_dropped")
                }
            },
            hideBackground: function () {
                b(".wrapper__content").removeClass("header-toolbar__background")
            },
            hideSuggest: function () {
                this.suggestNecessary = !1;
                a.isDesktopStyles && this.suggest.hide();
                this.suggest.empty();
                this.search.val("")
            },
            hideWaiting: function () {
                a.utils.waiting(this.$el, !1)
            },
            showBackground: function () {
                b(".wrapper__content").addClass("header-toolbar__background")
            },
            showSuggest: function () {
                this.suggest.show()
            },
            showWaiting: function () {
                a.utils.waiting(this.$el, !0)
            },
            toggle: function () {
                this.visible ? this.hide() : this.show()
            }
        });
    b(function () {
        var c, d, e;
        try {
            d = a.utils.getCompileTemplate("HeaderToolbar-HeaderTemplate")
        } catch (f) {
            return
        }
        e = b(d({
            name: b.cookie("client-region-name-kladr")
        })).prependTo(".Header-Navigation");
        c = new j;
        new h;
        e.find(".Header-ToggleHeaderToolbar").on("click", function () {
            c.toggle()
        })
    })
}(TCS, jQuery, Backbone, Handlebars, _);
$(function () {
    var a = $(".js-footer-online-call"),
        b = $(".footer__call-icon");
    b.on("click", function (a) {
        a.stopPropagation();
        a.preventDefault();
        var b = "https://zingaya.com/widget/2e626751c27b41c27132dfd279444479?preferred_region=11&strict_region_route=1",
            c = {
                width: 380,
                height: 300
            }, d = window.open("", "_blank", "resizable=0,toolbar=0,titlebar=no,scrollbars=0,width=" + c.width + ",height=" + c.height + ",top=" + (window.screen.availHeight / 2 - c.height / 2) + ",left=" + (window.screen.availWidth / 2 - c.width / 2));
        d.document.write(TCS.utils.getCompileTemplate("OnlineCallFrame")({
            url: b,
            width: "380",
            height: "263"
        }))
    });
    a.on("click", function (a) {
        a.preventDefault();
        var b = "https://zingaya.com/widget/2e626751c27b41c27132dfd279444479?preferred_region=11&strict_region_route=1",
            c = {
                width: 380,
                height: 300
            }, d = window.open("", "_blank", "resizable=0,toolbar=0,titlebar=no,scrollbars=0,width=" + c.width + ",height=" + c.height + ",top=" + (window.screen.availHeight / 2 - c.height / 2) + ",left=" + (window.screen.availWidth / 2 - c.width / 2));
        d.document.write(TCS.utils.getCompileTemplate("OnlineCallFrame")({
            url: b,
            width: "380",
            height: "263"
        }))
    })
});
! function (a) {
    "use strict";

    function b() {
        var a, b = $(".JS-mobile-app-link"),
            c = navigator.userAgent;
        c.match(/Android/i) ? a = "https://play.google.com/store/apps/details?id=com.idamob.tinkoff.android" : c.match(/iPhone/i) || c.match(/iPod/i) ? a = "https://itunes.apple.com/ru/app/tin-koff/id455652438?mt=8" : c.match(/iPad/i) ? a = "https://itunes.apple.com/ru/app/tcs-for-ipad/id599934583?mt=8" : c.match(/BlackBerry/i) ? a = "http://appworld.blackberry.com/webstore/content/23005886/?countrycode=RU&lang=en" : c.match(/Windows Phone/i) ? a = "http://www.windowsphone.com/ru-ru/store/app/%D1%82%D0%BA%D1%81-%D0%B1%D0%B0%D0%BD%D0%BA/1b8d170b-afef-485f-83d7-c3c7fb7b11fe" : c.match(/Trident/i) && c.match(/Touch/i) && (a = "http://apps.microsoft.com/windows/ru-ru/app/f3cc03d8-f1a4-43dc-9769-7597b0ae02ba");
        b.attr("href", a)
    }
    a = a || {};
    a.initMobileAppButton = b;
    $(function () {
        b()
    })
}(TCS);
$(function () {
    "use strict";

    function a() {
        var a = void 0 !== window.pageYOffset ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop,
            b = k.height() + 30;
        a > b && !n ? e(!0) : b > a && n && e(!1)
    }

    function b(a) {
        a.preventDefault();
        o && c() ? l.slideshow("first") : m.animate({
            scrollTop: 0
        }, 600)
    }

    function c() {
        return l.hasClass("wrapper_slideshow_active")
    }

    function d(a, b) {
        if (b.$slideshow.hasClass(i)) {
            var c = b.new_index > 0;
            n !== c && e()
        }
    }

    function e(a) {
        if (a !== n) {
            void 0 === a && (a = !n);
            a ? j.addClass(o && c() ? g : f) : j.removeClass(h);
            n = a
        }
    }
    if (TCS.isDesktopStyles) {
        var f = "back-to-top_show",
            g = "back-to-top_show_slideshow",
            h = f + " " + g,
            i = "wrapper_slideshow_on",
            j = $(".Back-To-Top"),
            k = $(".wrapper__header"),
            l = $("." + i),
            m = $("html").add("body"),
            n = !1,
            o = !! l.length,
            p = _.throttle(a, 150);
        if (j.length) {
            j.on("click", b).css("display", "block");
            $(document).on("scroll", p);
            if (o) {
                l.on("slideshow.init slideshow.changestart", d);
                c() ? l.slideshow("getSlideNumber") > 0 && e(!0) : a()
            } else a()
        }
    }
});
$(function () {
    "use strict";

    function a(a, b) {
        if (!a || !b) return !1;
        var c = a.length;
        if (c !== b.length) return !1;
        for (; c--;)
            if (a[c] !== b[c]) return !1;
        return !0
    }

    function b() {
        $.getScript(n).done(function () {
            ymaps.ready(function () {
                Q.trigger("api:ready")
            })
        })
    }

    function c() {
        this._isOpened = !1;
        this._firstScroll = !0;
        this._$footer = $("." + q);
        this._$wrapper = $("." + o).on("click", ".PaymentPoints-Open", this._open.bind(this)).on("click", ".PaymentPoints-Close", this._close.bind(this));
        this._resize = this._resize.bind(this);
        this._resizeTimer = 0;
        this._$win = $(window)
    }

    function d(a) {
        this._isApiLoaded = !1;
        this.zoom = 12;
        this._$text = a.find("." + w).on("keyup", this._keypress.bind(this));
        this._suggest = new e(a);
        this._suggest.on("change", this._update.bind(this));
        this._$suggestText = a.find("." + K);
        this._$suggestClose = a.find("." + L);
        this._$suggestBlock = a.find("." + t);
        this._$suggestText.on("click", function () {
            this._suggest._$textfield.trigger("submit")
        }.bind(this));
        this._$suggestClose.on("click", function (a) {
            a.preventDefault();
            this._set()
        }.bind(this));
        Q.on("overlay:close", this._set.bind(this, null));
        Q.on("map:ready", this.apiReady.bind(this))
    }

    function e(a) {
        this._isApiLoaded = !1;
        this._isOpened = !1;
        this._text = "";
        this._timeout = 0;
        this._$container = a.find("." + v).hide();
        this._$textfield = a.find("." + w);
        var b = this._keypress.bind(this);
        this._$container.on("click", "." + x, this._click.bind(this));
        this._$textfield.on("keyup", b).parents("form").on("submit", b)
    }

    function f(a) {
        var b = this;
        b.isApiLoaded = !1;
        b.doInit = !1;
        b._$container = a.find("." + z);
        b._map = null;
        b._clusterer = null;
        b.points = [];
        b._boundschangeTimer = 0;
        TCS.utils.waiting(b._$container, !0);
        b.defaultPosition();
        Q.on("api:ready", b.apiReady.bind(b))
    }

    function g(a) {
        var b = this;
        b.$block = a;
        b.isDeposit = "Deposit" === l.accountType;
        b.$block.html(m.map({
            deposit: b.isDeposit
        }));
        b._position = null;
        b._bounds = void 0;
        b._partner = {};
        b._activePartner = "";
        b._blockWidth = 0;
        b._limit = 0;
        b._filterTimer = 0;
        b.renderMap()
    }
    var h, i, j, k, l, m, n = "https://api-maps.yandex.ru/2.0/?load=package.map,package.controls,package.geoObjects,package.clusters,package.geocode&lang=ru-RU",
        o = "wrapper",
        p = o + "_overlay",
        q = "footer",
        r = "payment-points",
        s = r + "__header",
        t = r + "__sidebar",
        u = r + "__search",
        v = u + "-list",
        w = u + "-city",
        x = u + "-item",
        y = x + "_active",
        z = r + "__map",
        A = r + "__filter-slider-s",
        B = r + "__filter-money",
        C = r + "__filter-money-data",
        D = r + "__partner",
        E = r + "__partners",
        F = r + "__wrap-scroll",
        G = D + "-title",
        H = D + "-info",
        I = H + "-header",
        J = r + "__points-item",
        K = r + "__sidebar-suggest-text",
        L = r + "__sidebar-suggest-close",
        M = r + "__sidebar-suggest-active",
        N = r + "__partner-detail",
        O = r + "__partner-info",
        P = $("." + r),
        Q = $({});
    if (P.length) {
        l = {
            money: [100, 15e3, 1e5, 5e5],
            accountType: function () {
                var a = "Credit",
                    b = window.location.pathname;
                /debit/.test(b) ? a = "Current" : /deposit/.test(b) && (a = "Deposit");
                return a
            }()
        };
        m = {
            partnersBalloon: TCS.utils.getCompileTemplate("payment-partner-balloon"),
            suggestList: TCS.utils.getCompileTemplate("payment-suggest-list"),
            detail: TCS.utils.getCompileTemplate("payment-detail"),
            map: TCS.utils.getCompileTemplate("payment-map"),
            partners: TCS.utils.getCompileTemplate("payment-partners")
        };
        h = {
            COORDS: {
                "default": {
                    lat: 55.755768,
                    "long": 37.617671,
                    name: "Москва",
                    dativeName: "Москве"
                },
                msk: {
                    lat: 55.755768,
                    "long": 37.617671,
                    name: "Москва",
                    dativeName: "Москве"
                },
                spb: {
                    lat: 59.938805999991004,
                    "long": 30.31427800000002,
                    name: "Санкт-Петербург",
                    dativeName: "Санкт-Петербурге"
                },
                ekb: {
                    lat: 56.838002,
                    "long": 60.597295,
                    name: "Екатеринбург",
                    dativeName: "Екатеринбурге"
                },
                nsk: {
                    lat: 55.028739,
                    "long": 82.906928,
                    name: "Новосибирск",
                    dativeName: "Новосибирске"
                },
                krd: {
                    lat: 45.034942,
                    "long": 38.976032,
                    name: "Краснодар",
                    dativeName: "Краснодаре"
                },
                sam: {
                    lat: 53.205225999997744,
                    "long": 50.191183999999986,
                    name: "Самара",
                    dativeName: "Самаре"
                },
                rnd: {
                    lat: 47.2271510000131,
                    "long": 39.74497199999999,
                    name: "Ростов-на-Дону",
                    dativeName: "Ростове-на-Дону"
                },
                kaz: {
                    lat: 55.795772,
                    "long": 49.106585,
                    name: "Казань",
                    dativeName: "Казани"
                },
                nnov: {
                    lat: 56.323922,
                    "long": 44.002258,
                    name: "Нижний Новгород",
                    dativeName: "Нижнем Новгороде"
                },
                chb: {
                    lat: 55.1521609999945,
                    "long": 61.38710299999997,
                    name: "Челябинск",
                    dativeName: "Челябинске"
                }
            },
            getPosition: function () {
                var a = this.getRegion();
                return a && a in this.COORDS ? this.COORDS[a] : this.COORDS["default"]
            },
            getRegion: function () {
                for (var a = document.location.pathname.split("/"), b = a.length - 1, c = 0; b > c; c++)
                    if ("payment" === a[c] && b > c + 1) return a[c + 1];
                return ""
            }
        };
        c.prototype = {
            constructor: c,
            _open: function (a) {
                a.preventDefault();
                if (!this._isOpened) {
                    this._isOpened = !0;
                    if (this._firstScroll) {
                        this._firstScroll = !1;
                        window.scrollTo(0, 0)
                    }
                    this._$footer.hide();
                    this._$wrapper.addClass(p);
                    this._$win.on("resize", this._resize);
                    Q.trigger("overlay:open")
                }
            },
            _close: function (a) {
                a.preventDefault();
                this._$win.off("resize", this._resize);
                this._$wrapper.removeClass(p);
                this._$footer.show();
                this._isOpened = !1;
                Q.trigger("overlay:close")
            },
            _resize: function () {
                if (this._isOpened) {
                    clearTimeout(this._resizeTimer);
                    this._resizeTimer = setTimeout(function () {
                        Q.trigger("overlay:resize")
                    }, 500)
                }
            }
        };
        d.prototype = {
            constructor: d,
            ERR_LOCATION: "Невозможно определить Ваше местоположение",
            _keypress: function (a) {
                var b = a.key || a.keyCode || a.which;
                27 === b && this._$text.val("")
            },
            _enable: function () {
                this._$text.attr("disabled", !1)
            },
            _disable: function () {
                this._$text.attr("disabled", !0)
            },
            _setLocation: function (a) {
                var b = this;
                this._disable();
                this._isApiLoaded && ymaps.geocode(a).then(function (c) {
                    c = c.geoObjects;
                    if (c.getLength() > 0) {
                        for (var d, e, f, g = c.getIterator(); d = g.getNext();) switch (d.properties.get("metaDataProperty.GeocoderMetaData.kind")) {
                        case "street":
                            e = d;
                            break;
                        case "locality":
                            f = d
                        }
                        e = e || f || c.getIterator().getNext();
                        b._set(e, a)
                    } else b.trigger("error", b.ERR_LOCATION)
                }, function () {
                    b.trigger("error", b.ERR_LOCATION)
                }).then(function () {
                    b._enable()
                });
                this.trigger("change", a)
            },
            _set: function (a, b) {
                var c = a ? this.getLocationName(a) : "";
                this._$text.val(c);
                this._$suggestText.text(c);
                this._$suggestBlock.toggleClass(M, !! a);
                this._$suggestClose.toggle( !! a);
                Q.trigger("locationPlacemark", [a, b, c])
            },
            getLocationName: function (a) {
                var b = a.properties.get("metaDataProperty.GeocoderMetaData"),
                    c = b.AddressDetails.Country;
                return "RU" === c.CountryNameCode ? c.AddressLine : b.text
            },
            _update: function (a) {
                if (a && a.properties) {
                    var b = a.properties.get("metaDataProperty.GeocoderMetaData").kind;
                    switch (b) {
                    case "house":
                        this.zoom = 16;
                        break;
                    case "street":
                        this.zoom = 14;
                        break;
                    case "metro":
                        this.zoom = 12;
                        break;
                    case "district":
                        this.zoom = 11;
                        break;
                    case "locality":
                        this.zoom = 10;
                        break;
                    default:
                        this.zoom = 12
                    }
                    this.coords = f.prototype.getGeoObjectCenter(a);
                    this._set(a, this.coords);
                    this.trigger("change", this.coords, !0, this.zoom)
                } else this.trigger("change", a, !0)
            },
            apiReady: function () {
                this._isApiLoaded = !0;
                this._suggest.apiReady();
                var a = h.getPosition();
                this._setLocation([a.lat, a.long])
            }
        };
        _.extend(d.prototype, TCS.Events);
        e.prototype = {
            constructor: e,
            _keypress: function (a) {
                var b = a.which;
                clearTimeout(this._timeout);
                if ("submit" === a.type) {
                    a.preventDefault();
                    b = 13
                }
                switch (b) {
                case 16:
                case 17:
                case 18:
                case 36:
                case 35:
                case 45:
                case 20:
                case 144:
                case 39:
                case 37:
                    return;
                case 13:
                    if ("" != $.trim(this._$textfield.val())) {
                        this._changeActiveItem(Math.max(this._position, 0));
                        this._changeLocation()
                    }
                    return !1;
                case 33:
                case 38:
                    this._up();
                    return;
                case 34:
                case 40:
                    this._down();
                    return;
                case 27:
                    this.hide();
                    return
                }
                var c = this._$textfield.val();
                c.length < 2 ? this.hide() : c !== this._text && (this._timeout = setTimeout(this._getSuggests.bind(this, c), 300))
            },
            _getSuggests: function (a) {
                var b = this;
                this._text = a;
                this._isApiLoaded && ymaps.geocode(a, {
                    results: 20,
                    boundedBy: j._map.getBounds(),
                    searchCoordOrder: "latlong"
                }).then(function (a) {
                    a = a.geoObjects;
                    if (a.getLength() > 0) {
                        for (var c, d, e = [], f = a.getIterator(), g = 0; c = f.getNext();) {
                            d = c.properties.get("metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryNameCode");
                            if ("RU" === d) {
                                e.push(c);
                                if (++g >= 5) break
                            }
                        }
                        b._update(e)
                    } else b.hide()
                }, function () {
                    b.hide()
                })
            },
            _click: function (a) {
                this._changeActiveItem($(a.target).index());
                this._changeLocation()
            },
            hide: function () {
                if (this._isOpened) {
                    this._position = -1;
                    this._positionMax = 0;
                    this._$active = null;
                    this._items = null;
                    this._isOpened = !1;
                    this._$container.slideUp().empty()
                }
            },
            _update: function (a) {
                var b, c, e = d.prototype.getLocationName,
                    f = a.length,
                    g = this._items ? this._items.length : 0,
                    h = 0;
                if (f) {
                    c = {
                        className: x,
                        list: _.map(a, e)
                    };
                    this._items = a;
                    b = this._$container.html(m.suggestList(c));
                    this._position = -1;
                    this._positionMax = f;
                    this._$active = null;
                    if (this._isOpened) {
                        if (g !== f) {
                            h = b.find("." + x).eq(0).outerHeight();
                            b.css("height", h * g + g - 1).animate({
                                height: h * f + f - 1
                            }, function () {
                                b.css("height", "auto")
                            })
                        }
                    } else {
                        this._isOpened = !0;
                        b.slideDown()
                    }
                } else this.hide()
            },
            _up: function () {
                if (!(this._positionMax < 0)) {
                    var a = this._position - 1;
                    0 > a && (a = 0);
                    this._changeActiveItem(a)
                }
            },
            _down: function () {
                if (!(this._positionMax < 0)) {
                    var a = this._position + 1;
                    a >= this._positionMax && (a = this._positionMax - 1);
                    this._changeActiveItem(a)
                }
            },
            _changeActiveItem: function (a) {
                if (this._position !== a) {
                    this._$active && this._$active.length && this._$active.removeClass(y);
                    this._$active = this._$container.children().eq(a).addClass(y);
                    this._position = a
                }
            },
            _changeLocation: function () {
                var a = null;
                this._items && this._items.length && this._items[this._position] ? a = this._items[this._position] : j.placemark && (a = j.placemark.geometry.getCoordinates());
                this.trigger("change", a);
                this.hide()
            },
            apiReady: function () {
                this._isApiLoaded = !0
            }
        };
        _.extend(e.prototype, TCS.Events);
        f.prototype = {
            constructor: f,
            apiReady: function () {
                this.isApiLoaded = !0;
                this.init()
            },
            init: function () {
                var a = this;
                if (a.isApiLoaded && !a.doInit) {
                    a.doInit = !0;
                    a._map = new ymaps.Map(a._$container[0], {
                        center: a._position,
                        zoom: a.zoom
                    });
                    a.setMapCenter(a._position, !0);
                    a._map.behaviors.enable("scrollZoom").disable("rightMouseButtonMagnifier");
                    a._map.events.add("boundschange", a._boundschangeHelper.bind(a));
                    a._map.events.add("sizechange", a._boundschangeHelper.bind(a));
                    a._map.controls.add("zoomControl", {
                        top: 410,
                        right: 20
                    });
                    a._map.controls.add("mapTools", {
                        top: 160,
                        right: 20
                    });
                    a._map.controls.add("scaleLine");
                    a._clusterer = new ymaps.Clusterer({
                        gridSize: 128
                    });
                    a._map.geoObjects.add(a._clusterer);
                    a._clusterer.events.add("objectsaddtomap", a.openBalloon.bind(a));
                    Q.on("locationPlacemark", a.setPlacemark.bind(a));
                    Q.on("overlay:resize", a.updateUI.bind(a));
                    Q.on("overlay:open", a.defaultPosition.bind(a));
                    Q.trigger("map:ready");
                    TCS.utils.waiting(a._$container, !1)
                }
            },
            _boundschangeHelper: function (a) {
                clearTimeout(this._boundschangeTimer);
                this._boundschangeTimer = setTimeout(this._boundschange.bind(this, a), 200)
            },
            _boundschange: function (b) {
                var c = (b.get("newZoom"), b.get("oldZoom"), b.get("newCenter")),
                    d = b.get("oldCenter");
                i.balloonOpen || this._map.balloon && this._map.balloon.isOpen() || a(c, d) || this.trigger("change", c, this._map.getBounds())
            },
            setMapCenter: function (b, c, d) {
                clearTimeout(this.balloonTimer);
                if (c || !a(this._position, b)) {
                    var e = this;
                    e.zoom = d || e.zoom;
                    e._position = b || e._position;
                    this._map.zoomRange.get(e._position).then(function (a) {
                        var b = a[1];
                        b < e.zoom && (e.zoom = b);
                        e._map.setCenter(e._position, e.zoom);
                        e.trigger("change", e._position, e._map.getBounds())
                    }, function () {
                        e.zoom = 11;
                        e._map.setCenter(e._position, e.zoom);
                        e.trigger("change", e._position, e._map.getBounds())
                    })
                }
            },
            openBalloon: function () {
                var a = this;
                if (i.balloonOpen) {
                    _.forEach(a.points, function (b) {
                        if (b.balloon) {
                            b.balloon.open(a._map.getCenter());
                            1 === i.balloonOpen && a._map.setCenter(b.geometry.getCoordinates(), 16)
                        }
                    });
                    i.balloonOpen = 2
                }
            },
            updatePartners: function (a) {
                var b, c, d = this,
                    e = a.length;
                d.points = [];
                for (; e--;) {
                    b = a[e];
                    c = new ymaps.Placemark(b.coords, {
                        clusterCaption: b.name,
                        balloonContentBody: m.partnersBalloon(b)
                    }, b.icon);
                    c.events.add("balloonopen", function (a) {
                        clearTimeout(d.balloonTimer);
                        d._map.setCenter(a.originalEvent.target.geometry.getCoordinates())
                    });
                    c.events.add("balloonclose", function (a) {
                        d.balloonTimer = setTimeout(function () {
                            d.setMapCenter(a.originalEvent.target.geometry.getCoordinates())
                        }, 50)
                    });
                    d.points.push(c)
                }
                d._clusterer.removeAll();
                d._clusterer.add(d.points)
            },
            updateUI: function () {
                this._map.container.fitToViewport()
            },
            getGeoObjectCenter: function (a) {
                var b = a.geometry.getBounds();
                return [(b[1][0] + b[0][0]) / 2, (b[1][1] + b[0][1]) / 2]
            },
            setPlacemark: function (a, b, c, d) {
                var e = this;
                e.placemark || (e.placemark = new ymaps.Placemark([], {}, {
                    preset: "twirl#yellowStretchyIcon",
                    zIndexHover: 0
                }));
                if (b) {
                    e.placemark.geometry.setCoordinates(c);
                    e.placemark.properties.set("iconContent", d || "");
                    e._map.geoObjects.add(e.placemark)
                } else e._map.geoObjects.remove(e.placemark)
            },
            defaultPosition: function () {
                var a = this,
                    b = h.getPosition();
                a.zoom = 15;
                a._position = [b.lat, b.long];
                a._map && a.setMapCenter(a._position, !0)
            }
        };
        _.extend(f.prototype, TCS.Events);
        g.prototype = {
            constructor: g,
            ERR_PARTNERS: "Невозможно получить список точек оплаты",
            renderMap: function () {
                var a = this;
                a._$sidebar = a.$block.find("." + t);
                a._$slider = a.$block.find("." + A);
                a._$money = a.$block.find("." + B);
                a._$moneyData = a.$block.find("." + C);
                a._$info = a.$block.find("." + O);
                a._$detail = a.$block.find("." + N);
                a._$partners = a.$block.find("." + E);
                a._$slider.slider({
                    range: "min",
                    animate: !0,
                    slide: a._updateLimit.bind(a)
                });
                a.$block.on("click", "." + G, a._showPartner.bind(a)).on("click", "." + O + " ." + I, a._hidePartner.bind(a));
                a.$block.on("click", "." + J, a.showDetail.bind(a));
                a.$block.on("click", "." + N + " ." + I, a.hideDetail.bind(a));
                a._$money.on("input", a._setMoneyInput.bind(a));
                a._$moneyData.on("click", "[data-money]", function (b) {
                    a._setLimit($(b.target).data("money"))
                });
                a.getInitData();
                Q.on("overlay:resize map:ready", a.getBoundLimits.bind(a))
            },
            getInitData: function () {
                var a = this;
                TCS.utils.waiting(a._$sidebar, !0);
                $.ajax({
                    type: "GET",
                    url: TCS.getServiceURL("deposition_partners"),
                    data: {
                        accountType: l.accountType
                    },
                    success: function (b) {
                        if ("OK" === b.resultCode) {
                            a.partnersData = b.payload;
                            a.calcData();
                            TCS.utils.waiting(a._$sidebar, !1)
                        } else a.trigger("error", a.ERR_PARTNERS).trigger("update", [])
                    }
                })
            },
            calcData: function () {
                var a = this;
                _.each(a.partnersData, function (b) {
                    var c = b.id.toLowerCase();
                    b.icon = {
                        iconLogoHref: "https://static.tcsbank.ru/deposition/" + b.picture,
                        iconImageHref: "https://static.tcsbank.ru/deposition/map-icons/" + b.picture,
                        iconImageSize: [24, 31],
                        iconImageOffset: [-12, -31]
                    };
                    b.points = [];
                    b.limit = a._getLimit(b.limitations);
                    a._partner[c] = b
                })
            },
            _getPartnersList: function () {
                var a, b = this._partner,
                    c = [];
                if (this._activePartner && this._partner[this._activePartner]) c.push(this._partner[this._activePartner].id.toLocaleUpperCase());
                else
                    for (a in b) b.hasOwnProperty(a) && c.push(a);
                return c.join(",").toLocaleUpperCase()
            },
            getBoundLimits: function () {
                var a = this;
                a.limitSize = {
                    top: a.$block.find("." + s).height() / $(window).height() + .05,
                    bottom: .05,
                    left: a._$sidebar.width() / $(window).width() + .05,
                    right: .05
                }
            },
            _loadPartners: function () {
                if (this._position) {
                    var a = this,
                        b = a._bounds,
                        c = a._getPartnersList(),
                        d = {
                            partners: c
                        }, e = {
                            minLongitude: b[0][1] + (b[1][1] - b[0][1]) * a.limitSize.left,
                            maxLongitude: b[1][1] - (b[1][1] - b[0][1]) * a.limitSize.right,
                            minLatitude: b[0][0] + (b[1][0] - b[0][0]) * a.limitSize.bottom,
                            maxLatitude: b[1][0] - (b[1][0] - b[0][0]) * a.limitSize.top
                        };
                    if (c.length && a._position) {
                        if (j._map.getZoom() > 15) _.extend(d, e);
                        else {
                            d.latitude = a._position[0];
                            d.longitude = a._position[1];
                            d.radius = 1e4
                        }
                        a.ajaxPoints && a.ajaxPoints.abort && a.ajaxPoints.abort();
                        a.ajaxPoints = $.ajax({
                            type: "GET",
                            url: TCS.getServiceURL("deposition_points"),
                            data: d,
                            dataType: "json",
                            success: function (b) {
                                if ("OK" === b.resultCode) {
                                    for (var c, d, f, g, h, i = [], j = b.payload, k = 0, l = j.length; l > k; k++) {
                                        d = j[k];
                                        if (!(d.location.latitude > e.maxLatitude || d.location.latitude < e.minLatitude || d.location.longitude > e.maxLongitude || d.location.longitude < e.minLongitude)) {
                                            f = d.partnerName.toLowerCase();
                                            c = "";
                                            if (d.bankInfo) c = d.bankInfo + " (" + d.bankName + ")";
                                            else if (!d.addressInfo && d.bankName) {
                                                -1 === d.bankName.toLowerCase().indexOf("банк") && (c = "Банк: ");
                                                c += d.bankName
                                            } else d.addressInfo && "POST" !== d.partnerName && (c = d.addressInfo);
                                            g = Math.round(ymaps.coordSystem.geo.getDistance(a._position, [d.location.latitude, d.location.longitude]));
                                            h = ymaps.formatter.distance(g);
                                            i.push({
                                                id: f,
                                                name: a._partner[f].name,
                                                coords: [d.location.latitude, d.location.longitude],
                                                distance: g,
                                                distancetext: h,
                                                icon: a._partner[f].icon,
                                                addr: $.trim(d.fullAddress).replace(/(\s{2})/g, "").replace(/,,/g, ",").replace(/,$/g, ""),
                                                desc: c,
                                                phone: d.phones,
                                                hours: d.workHours
                                            })
                                        }
                                    }
                                    a._activePartner && i.sort(function (a, b) {
                                        return a.distance < b.distance ? 1 : a.distance > b.distance ? -1 : 0
                                    });
                                    a._updatePartners(i)
                                } else a.trigger("error", a.ERR_PARTNERS).trigger("update", [])
                            },
                            error: function () {
                                a.trigger("error", a.ERR_PARTNERS).trigger("update", [])
                            }
                        })
                    } else a._updatePartners([])
                }
            },
            _updatePartners: function (a) {
                var b, c = this,
                    d = c._partner,
                    e = a.length;
                for (b in d)
                    if (d.hasOwnProperty(b)) {
                        d[b].points = [];
                        d[b].isDeposit = c.isDeposit
                    }
                for (; e--;) d[a[e].id].points.push(a[e]);
                this.renderPartner();
                this._filterLimit()
            },
            update: function (b, c) {
                if (!this._position || !a(this._position, b)) {
                    this._position = b;
                    this._bounds = c;
                    this._loadPartners()
                }
            },
            renderPartner: function () {
                if (this._activePartner) {
                    var a = this._partner[this._activePartner];
                    this._$info.html(m.detail(a));
                    this._$wrap = this._$info.find("." + F)
                }
            },
            _showPartner: function (a) {
                a.preventDefault();
                var b = this,
                    c = $(a.currentTarget).parent("." + D).data("id").toLowerCase();
                b._activePartner = c;
                b.renderPartner();
                if (!b._blockWidth) {
                    b._blockWidth = b._$sidebar.outerWidth();
                    b._$sidebar = null
                }
                b._$info.css({
                    display: "block",
                    left: b._blockWidth
                });
                b._$info.animate({
                    left: 0
                }, 400, "easeOutSine");
                b._loadPartners()
            },
            _hidePartner: function (a) {
                a.preventDefault();
                var b = this;
                b._activePartner = "";
                b.wrappos = 0;
                b._$info.animate({
                    left: this._blockWidth
                }, 400, "easeInSine", function () {
                    b._$info.css("display", "none")
                });
                b._loadPartners()
            },
            showDetail: function (a) {
                var b = this,
                    c = $(a.currentTarget).data("id"),
                    d = b._partner[b._activePartner],
                    e = _.extend({
                        one: !0
                    }, d, d.points[c]);
                b.balloonOpen = 1;
                b.wrappos = b._$wrap[0] ? b._$wrap[0].scrollTop : 0;
                b.trigger("update", [d.points[c]]);
                b._$detail.css({
                    display: "block",
                    left: b._blockWidth
                }).html(m.detail(e)).show().animate({
                    left: 0
                }, 400, "easeOutSine")
            },
            hideDetail: function () {
                var a = this;
                a.balloonOpen = 0;
                a._bounds = j._map.getBounds();
                a._position = j._map.getCenter();
                a._loadPartners();
                a.trigger("update", a._partner[a._activePartner].points);
                a._$detail.animate({
                    left: a._blockWidth
                }, 400, "easeOutSine", function () {
                    a._$detail.hide()
                })
            },
            _getLimit: function (a) {
                for (var b, c = (a || "").replace(/&nbsp;/, "").replace(/\W/g, " ").trim().replace(/\b\s\b/g, "").replace(/\s{2,}/g, " ").split(/\s/g), d = c.length, e = 0; d--;) {
                    b = parseInt(c[d], 10);
                    !isNaN(b) && b > e && (e = b)
                }
                return e
            },
            _setMoneyInput: function (a) {
                var b = a.target.value || "";
                b = b.replace(/\D/g, "") - 0;
                this._setLimit(b)
            },
            _setLimit: function (a) {
                var b, c, d, e, f = l.money.length - 1;
                "number" != typeof a && (a = l.money[0]);
                this._$money.val(a);
                if (this._limit !== a) {
                    this._limit = a;
                    e = function (a) {
                        for (var b = 0; a >= l.money[b];) b++;
                        return b
                    }(a);
                    c = l.money[e];
                    d = l.money[e - 1];
                    b = "number" != typeof c ? 100 : "number" != typeof d ? 0 : (e - 1 + (a - d) / (c - d)) * (100 / f);
                    this._$slider.slider("value", b);
                    clearTimeout(this._updateLimitTimer);
                    this._updateLimitTimer = setTimeout(this._filterLimit.bind(this), 300)
                }
            },
            _updateLimit: function (a, b) {
                var c, d, e, f, g, h = b.value,
                    i = l.money.length - 1;
                f = Math.ceil(h / (100 / i));
                if (f) {
                    d = l.money[f];
                    e = l.money[f - 1];
                    g = (h - (f - 1) / i * 100) * i;
                    c = 100 * Math.round(((d - e) * g / 100 + e) / 100)
                }
                this._setLimit(c)
            },
            _filterLimit: function () {
                var a, b = this,
                    c = b._limit,
                    d = b._partner,
                    e = b._activePartner,
                    f = [];
                for (a in d) {
                    d[a].show = d[a].points.length && (d[a].limit >= c || 0 === d[a].limit);
                    f.push(d[a])
                }
                b._$partners.html(m.partners(f));
                b._$wrap && b._$wrap[0] && (b._$wrap[0].scrollTop = b.wrappos || 0);
                clearTimeout(b._filterTimer);
                b._filterTimer = setTimeout(function () {
                    e ? d[e].show ? b.trigger("update", d[e].points) : b._hidePartner() : b.trigger("update", b._getPoints())
                }, 100)
            },
            _getPoints: function () {
                var a, b = [],
                    c = this._partner;
                for (a in c) c.hasOwnProperty(a) && c[a].show && (b = b.concat(c[a].points));
                return b
            }
        };
        _.extend(g.prototype, TCS.Events);
        i = new g(P);
        j = new f(P);
        k = new d(P);
        i.on("update", j.updatePartners.bind(j));
        j.on("change", i.update.bind(i));
        k.on("change", j.setMapCenter.bind(j));
        Q.one("overlay:open", b);
        new c
    }
});