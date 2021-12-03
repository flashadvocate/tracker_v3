<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

<script src="{{ asset('/js/main.js?v=3.2') }}"></script>
<script src="https://cdn.ravenjs.com/3.26.2/raven.min.js" crossorigin="anonymous"></script>

@auth
    @if(getSnowSetting() && getSnowSetting() != 'no_snow')
        <script>
            var snowStorm = function (e, t) {
                this.autoStart = !0, this.excludeMobile = !0,
                    this.flakesMax = {{ getSnowSetting() === 'some_snow' ? 32 : 64 }},
                    this.flakesMaxActive = this.flakesMax / 2,
                    this.animationInterval = 33, this.useGPU = !0, this.className = null, this.flakeBottom = null, this.followMouse = !0, this.snowColor = "#fff", this.snowCharacter = "&bull;", this.snowStick = !0, this.targetElement = null, this.useMeltEffect = !0, this.useTwinkleEffect = !1, this.usePositionFixed = !1, this.usePixelPosition = !1, this.freezeOnBlur = !0, this.flakeLeftOffset = 0, this.flakeRightOffset = 0, this.flakeWidth = 8, this.flakeHeight = 8, this.vMaxX = 5, this.vMaxY = 4, this.zIndex = 0;
                var i, s = this, n = navigator.userAgent.match(/msie/i), l = navigator.userAgent.match(/msie 6/i),
                    o = navigator.userAgent.match(/mobile|opera m(ob|in)/i),
                    a = n && "BackCompat" === t.compatMode || l, r = null,
                    f = null, m = null, h = null, u = null, c = null, d = null, v = 1, p = !1, y = !1, k = function () {
                        try {
                            t.createElement("div").style.opacity = "0.5"
                        } catch (e) {
                            return !1
                        }
                        return !0
                    }(), g = !1, x = t.createDocumentFragment();

                function w(e, t) {
                    return isNaN(t) && (t = 0), Math.random() * e + t
                }

                function F() {
                    e.setTimeout(function () {
                        s.start(!0)
                    }, 20), s.events.remove(n ? t : e, "mousemove", F)
                }

                return i = function () {
                    var i;
                    var n,
                        l = e.requestAnimationFrame || e.webkitRequestAnimationFrame || e.mozRequestAnimationFrame || e.oRequestAnimationFrame || e.msRequestAnimationFrame || function (t) {
                            e.setTimeout(t, 1e3 / (s.animationInterval || 20))
                        };

                    function o(e) {
                        return void 0 !== n.style[e] ? e : null
                    }

                    i = l ? function () {
                        return l.apply(e, arguments)
                    } : null, n = t.createElement("div");
                    var a = {
                        transform: {
                            ie: o("-ms-transform"),
                            moz: o("MozTransform"),
                            opera: o("OTransform"),
                            webkit: o("webkitTransform"),
                            w3: o("transform"),
                            prop: null
                        }, getAnimationFrame: i
                    };
                    return a.transform.prop = a.transform.w3 || a.transform.moz || a.transform.webkit || a.transform.ie || a.transform.opera, n = null, a
                }(), this.timer = null, this.flakes = [], this.disabled = !1, this.active = !1, this.meltFrameCount = 20, this.meltFrames = [], this.setXY = function (e, t, i) {
                    if (!e) return !1;
                    s.usePixelPosition || y ? (e.style.left = t - s.flakeWidth + "px", e.style.top = i - s.flakeHeight + "px") : a ? (e.style.right = 100 - t / r * 100 + "%", e.style.top = Math.min(i, u - s.flakeHeight) + "px") : s.flakeBottom ? (e.style.right = 100 - t / r * 100 + "%", e.style.top = Math.min(i, u - s.flakeHeight) + "px") : (e.style.right = 100 - t / r * 100 + "%", e.style.bottom = 100 - i / m * 100 + "%")
                }, this.events = function () {
                    var t = !e.addEventListener && e.attachEvent, i = Array.prototype.slice,
                        s = {
                            add: t ? "attachEvent" : "addEventListener",
                            remove: t ? "detachEvent" : "removeEventListener"
                        };

                    function n(e) {
                        var s = i.call(e), n = s.length;
                        return t ? (s[1] = "on" + s[1], n > 3 && s.pop()) : 3 === n && s.push(!1), s
                    }

                    function l(e, i) {
                        var n = e.shift(), l = [s[i]];
                        t ? n[l](e[0], e[1]) : n[l].apply(n, e)
                    }

                    return {
                        add: function () {
                            l(n(arguments), "add")
                        }, remove: function () {
                            l(n(arguments), "remove")
                        }
                    }
                }(), this.randomizeWind = function () {
                    var e, t;
                    if (t = w(s.vMaxX, .2), c = 1 === parseInt(w(2), 10) ? -1 * t : t, d = w(s.vMaxY, .2), this.flakes) for (e = 0; e < this.flakes.length; e++) this.flakes[e].active && this.flakes[e].setVelocities()
                }, this.scrollHandler = function () {
                    var i;
                    if (h = s.flakeBottom ? 0 : parseInt(e.scrollY || t.documentElement.scrollTop || (a ? t.body.scrollTop : 0), 10), isNaN(h) && (h = 0), !p && !s.flakeBottom && s.flakes) for (i = 0; i < s.flakes.length; i++) 0 === s.flakes[i].active && s.flakes[i].stick()
                }, this.resizeHandler = function () {
                    e.innerWidth || e.innerHeight ? (r = e.innerWidth - 16 - s.flakeRightOffset, m = s.flakeBottom || e.innerHeight) : (r = (t.documentElement.clientWidth || t.body.clientWidth || t.body.scrollWidth) - (n ? 0 : 8) - s.flakeRightOffset, m = s.flakeBottom || t.documentElement.clientHeight || t.body.clientHeight || t.body.scrollHeight), u = t.body.offsetHeight, f = parseInt(r / 2, 10)
                }, this.resizeHandlerAlt = function () {
                    r = s.targetElement.offsetWidth - s.flakeRightOffset, m = s.flakeBottom || s.targetElement.offsetHeight, f = parseInt(r / 2, 10), u = t.body.offsetHeight
                }, this.freeze = function () {
                    if (s.disabled) return !1;
                    s.disabled = 1, s.timer = null
                }, this.resume = function () {
                    if (!s.disabled) return !1;
                    s.disabled = 0, s.timerInit()
                }, this.toggleSnow = function () {
                    s.flakes.length ? (s.active = !s.active, s.active ? (s.show(), s.resume()) : (s.stop(), s.freeze())) : s.start()
                }, this.stop = function () {
                    var i;
                    for (this.freeze(), i = 0; i < this.flakes.length; i++) this.flakes[i].o.style.display = "none";
                    s.events.remove(e, "scroll", s.scrollHandler), s.events.remove(e, "resize", s.resizeHandler), s.freezeOnBlur && (n ? (s.events.remove(t, "focusout", s.freeze), s.events.remove(t, "focusin", s.resume)) : (s.events.remove(e, "blur", s.freeze), s.events.remove(e, "focus", s.resume)))
                }, this.show = function () {
                    var e;
                    for (e = 0; e < this.flakes.length; e++) this.flakes[e].o.style.display = "block"
                }, this.SnowFlake = function (e, n, l) {
                    var o = this;
                    this.type = e, this.x = n || parseInt(w(r - 20), 10), this.y = isNaN(l) ? -w(m) - 12 : l, this.vX = null, this.vY = null, this.vAmpTypes = [1, 1.2, 1.4, 1.6, 1.8], this.vAmp = this.vAmpTypes[this.type] || 1, this.melting = !1, this.meltFrameCount = s.meltFrameCount, this.meltFrames = s.meltFrames, this.meltFrame = 0, this.twinkleFrame = 0, this.active = 1, this.fontSize = 10 + this.type / 5 * 10, this.o = t.createElement("div"), this.o.innerHTML = s.snowCharacter, s.className && this.o.setAttribute("class", s.className), this.o.style.color = s.snowColor, this.o.style.position = p ? "fixed" : "absolute", s.useGPU && i.transform.prop && (this.o.style[i.transform.prop] = "translate3d(0px, 0px, 0px)"), this.o.style.width = s.flakeWidth + "px", this.o.style.height = s.flakeHeight + "px", this.o.style.fontFamily = "arial,verdana", this.o.style.cursor = "default", this.o.style.overflow = "hidden", this.o.style.fontWeight = "normal", this.o.style.zIndex = s.zIndex, x.appendChild(this.o), this.refresh = function () {
                        if (isNaN(o.x) || isNaN(o.y)) return !1;
                        s.setXY(o.o, o.x, o.y)
                    }, this.stick = function () {
                        a || s.targetElement !== t.documentElement && s.targetElement !== t.body ? o.o.style.top = m + h - s.flakeHeight + "px" : s.flakeBottom ? o.o.style.top = s.flakeBottom + "px" : (o.o.style.display = "none", o.o.style.top = "auto", o.o.style.bottom = "0%", o.o.style.position = "fixed", o.o.style.display = "block")
                    }, this.vCheck = function () {
                        o.vX >= 0 && o.vX < .2 ? o.vX = .2 : o.vX < 0 && o.vX > -.2 && (o.vX = -.2), o.vY >= 0 && o.vY < .2 && (o.vY = .2)
                    }, this.move = function () {
                        var e = o.vX * v;
                        o.x += e, o.y += o.vY * o.vAmp, o.x >= r || r - o.x < s.flakeWidth ? o.x = 0 : e < 0 && o.x - s.flakeLeftOffset < -s.flakeWidth && (o.x = r - s.flakeWidth - 1), o.refresh(), m + h - o.y + s.flakeHeight < s.flakeHeight ? (o.active = 0, s.snowStick ? o.stick() : o.recycle()) : (s.useMeltEffect && o.active && o.type < 3 && !o.melting && Math.random() > .998 && (o.melting = !0, o.melt()), s.useTwinkleEffect && (o.twinkleFrame < 0 ? Math.random() > .97 && (o.twinkleFrame = parseInt(8 * Math.random(), 10)) : (o.twinkleFrame--, k ? o.o.style.opacity = o.twinkleFrame && o.twinkleFrame % 2 == 0 ? 0 : 1 : o.o.style.visibility = o.twinkleFrame && o.twinkleFrame % 2 == 0 ? "hidden" : "visible")))
                    }, this.animate = function () {
                        o.move()
                    }, this.setVelocities = function () {
                        o.vX = c + w(.12 * s.vMaxX, .1), o.vY = d + w(.12 * s.vMaxY, .1)
                    }, this.setOpacity = function (e, t) {
                        if (!k) return !1;
                        e.style.opacity = t
                    }, this.melt = function () {
                        s.useMeltEffect && o.melting && o.meltFrame < o.meltFrameCount ? (o.setOpacity(o.o, o.meltFrames[o.meltFrame]), o.o.style.fontSize = o.fontSize - o.fontSize * (o.meltFrame / o.meltFrameCount) + "px", o.o.style.lineHeight = s.flakeHeight + 2 + .75 * s.flakeHeight * (o.meltFrame / o.meltFrameCount) + "px", o.meltFrame++) : o.recycle()
                    }, this.recycle = function () {
                        o.o.style.display = "none", o.o.style.position = p ? "fixed" : "absolute", o.o.style.bottom = "auto", o.setVelocities(), o.vCheck(), o.meltFrame = 0, o.melting = !1, o.setOpacity(o.o, 1), o.o.style.padding = "0px", o.o.style.margin = "0px", o.o.style.fontSize = o.fontSize + "px", o.o.style.lineHeight = s.flakeHeight + 2 + "px", o.o.style.textAlign = "center", o.o.style.verticalAlign = "baseline", o.x = parseInt(w(r - s.flakeWidth - 20), 10), o.y = parseInt(-1 * w(m), 10) - s.flakeHeight, o.refresh(), o.o.style.display = "block", o.active = 1
                    }, this.recycle(), this.refresh()
                }, this.snow = function () {
                    var e, t, n = 0, l = null;
                    for (e = 0, t = s.flakes.length; e < t; e++) 1 === s.flakes[e].active && (s.flakes[e].move(), n++), s.flakes[e].melting && s.flakes[e].melt();
                    n < s.flakesMaxActive && 0 === (l = s.flakes[parseInt(w(s.flakes.length), 10)]).active && (l.melting = !0), s.timer && i.getAnimationFrame(s.snow)
                }, this.mouseMove = function (e) {
                    if (!s.followMouse) return !0;
                    var t = parseInt(e.clientX, 10);
                    v = t < f ? t / f * 2 - 2 : (t -= f) / f * 2
                }, this.createSnow = function (e, t) {
                    var i;
                    for (i = 0; i < e; i++) s.flakes[s.flakes.length] = new s.SnowFlake(parseInt(w(6), 10)), (t || i > s.flakesMaxActive) && (s.flakes[s.flakes.length - 1].active = -1);
                    s.targetElement.appendChild(x)
                }, this.timerInit = function () {
                    s.timer = !0, s.snow()
                }, this.init = function () {
                    var i;
                    for (i = 0; i < s.meltFrameCount; i++) s.meltFrames.push(1 - i / s.meltFrameCount);
                    s.randomizeWind(), s.createSnow(s.flakesMax), s.events.add(e, "resize", s.resizeHandler), s.events.add(e, "scroll", s.scrollHandler), s.freezeOnBlur && (n ? (s.events.add(t, "focusout", s.freeze), s.events.add(t, "focusin", s.resume)) : (s.events.add(e, "blur", s.freeze), s.events.add(e, "focus", s.resume))), s.resizeHandler(), s.scrollHandler(), s.followMouse && s.events.add(n ? t : e, "mousemove", s.mouseMove), s.animationInterval = Math.max(20, s.animationInterval), s.timerInit()
                }, this.start = function (i) {
                    if (g) {
                        if (i) return !0
                    } else g = !0;
                    if ("string" == typeof s.targetElement) {
                        var n = s.targetElement;
                        if (s.targetElement = t.getElementById(n), !s.targetElement) throw new Error('Snowstorm: Unable to get targetElement "' + n + '"')
                    }
                    if (s.targetElement || (s.targetElement = t.body || t.documentElement), s.targetElement !== t.documentElement && s.targetElement !== t.body && (s.resizeHandler = s.resizeHandlerAlt, s.usePixelPosition = !0), s.resizeHandler(), s.usePositionFixed = s.usePositionFixed && !a && !s.flakeBottom, e.getComputedStyle) try {
                        y = "relative" === e.getComputedStyle(s.targetElement, null).getPropertyValue("position")
                    } catch (e) {
                        y = !1
                    }
                    p = s.usePositionFixed, r && m && !s.disabled && (s.init(), s.active = !0)
                }, s.autoStart && s.events.add(e, "load", function t() {
                    s.excludeMobile && o || F(), s.events.remove(e, "load", t)
                }, !1), this
            }(window, document);
        </script>
    @endif
@endauth