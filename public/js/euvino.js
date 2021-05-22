let BweinEuvino = (function () {
        'use strict';

        let Constructor = function () {
            let _widgetCallbacks = [];
            let _blockerCallbacks = [];

            this.addCallback = function ($callback, isWidget = true) {
                if (isWidget) {
                    _widgetCallbacks.push($callback);
                    return;
                }
                _blockerCallbacks.push($callback);
            };
            this.initEuvinoScripts = function (scriptSrc) {
                var self = this;
                var loader = new BweinScriptLoader();
                loader.requireScripts(scriptSrc,
                    function () {
                        self.runWidgetCallbacks();
                    });
            };
            this.runWidgetCallbacks = function () {
                for (var i = 0; i < _widgetCallbacks.length; i++) {
                    var functionName = _widgetCallbacks[i];
                    if (typeof window[functionName] !== "function") {
                        console.error(functionName + ' is missing');
                        continue;
                    }
                    window[functionName]();
                }
            };
            this.initBlocker = function (cookieId, scriptSrc) {
                for (var i = 0; i < _blockerCallbacks.length; i++) {
                    var functionName = _blockerCallbacks[i];
                    if (typeof window[functionName] !== "function") {
                        console.error(functionName + ' is missing');
                        continue;
                    }
                    window[functionName]();
                }
                let unblockWidgets = function () {
                    bwein_euvino.initEuvinoScripts(scriptSrc)
                }
                document.addEventListener("DOMContentLoaded", function() {
                    cookiebar.addModule(cookieId, unblockWidgets);
                });
            };
        }
        return Constructor;
    }
)();
var bwein_euvino = new BweinEuvino();

let BweinScriptLoader = (function () {
        'use strict';

        let Constructor = function () {
            let _loadCount = 0;
            let _totalRequired = 0;
            let _callback = null;

            this.requireScripts = function (scripts, callback) {
                _loadCount = 0;
                _totalRequired = scripts.length;
                _callback = callback;

                for (var i = 0; i < scripts.length; i++) {
                    this.writeScript(scripts[i]);
                }
            };
            this.loadedScripts = function (evt) {
                _loadCount++;
                if (_loadCount == _totalRequired && typeof _callback === 'function') _callback.call();
            };
            this.writeScript = function (src) {
                var self = this;
                var s = document.createElement('script');
                s.type = "text/javascript";
                s.async = true;
                s.src = src;
                s.addEventListener('load', function (e) {
                    self.loadedScripts(e);
                }, false);
                var head = document.getElementsByTagName('head')[0];
                head.appendChild(s);
            }
        }
        return Constructor;
    }
)();
