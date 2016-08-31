
define(function (require) {
    return function($cookies, $interval) {
        var self = this;
        self.scopes = {};

        /**
         * Watching cookie storage.
         */
        self.startWatch = function() {
            var id, scopeItems, index, item, cookies, variable, cookiesTime;
            $interval(function() {
                for(id in self.scopes) {
                    scopeItems = self.scopes[id];
                    cookies    = self.getVariableFromCookie(id);
                    cookiesTime = self.getTimestampFromCookie(id);
                    for(index in scopeItems) {
                        item = scopeItems[index];
                        if(item.watchFlag && (cookiesTime > item.time)) {
                            self.scopes[id][index].time = cookiesTime;
                            variable = self.scopes[id][index]["scope"][item.key];
                            if(!(variable == cookies)) {
                                if(typeof(cookies) == 'object') {
                                    try {
                                        if(JSON.stringify(variable) != JSON.stringify(cookies)) {
                                            self.scopes[id][index]["scope"][item.key] = cookies;
                                        }
                                    }
                                    catch(e) {
                                    }
                                }
                                else {
                                    self.scopes[id][index]["scope"][item.key] = cookies;
                                }
                            }
                        }
                    }
                }
            }, 300);
        };

        /**
         * Get cookie name.
         * @param id
         * @returns {string}
         */
        self.getCookieName = function(id) {
            return "angular-cookies-" + id;
        };

        /**
         * Check cookie id is exists.
         * @param id
         * @returns {boolean}
         */
        self.isExistsCookie = function(id) {
            var cookie = $cookies.get(self.getCookieName(id));
            if(cookie) {
                return true;
            }
            else {
                return false;
            }
        };

        /**
         * Get variable from cookies.
         * @param id
         * @returns {*}
         */
        self.getTimestampFromCookie = function(id) {
            var cookie = $cookies.get(self.getCookieName(id) + "$t");
            try {
                return new Date(parseFloat(cookie));
            }
            catch(e) {
                return new Date();
            }
        };

        /**
         * Get variable from cookies.
         * @param id
         * @returns {*}
         */
        self.getVariableFromCookie = function(id) {
            var cookie = $cookies.get(self.getCookieName(id));
            try {
                var variable = JSON.parse(cookie);
                self.clearHashKey(variable);
                return variable;
            }
            catch(e) {
                // Do nothings.
            }

            return cookie;
        };

        /**
         * Clear json object key of "$$hashKey"
         * @param json
         */
        self.clearHashKey = function(json) {
            for(var key in json) {
                if(key == "$$hashKey") {
                    delete json[key];
                }

                if(typeof(json[key]) == 'object') {
                    self.clearHashKey(json[key]);
                }
            }
        };

        /**
         * Save variable to cookie.
         * @param variable
         * @param id
         */
        self.saveVariableToCookie = function(variable, id) {

            var expire = new Date();
            var now = new Date();
            expire.setDate(expire.getDate() + 9999);

            if(variable) {
                // time
                try {
                    $cookies.put(self.getCookieName(id) + "$t", now.getTime(), {'expires': expire});
                }
                catch(e) {

                }

                // var
                try {
                    if( typeof(variable) == 'object' ) {
                        $cookies.put(self.getCookieName(id), JSON.stringify(variable), {'expires': expire});
                    }
                    else {
                        $cookies.put(self.getCookieName(id), variable,  {'expires': expire});
                    }
                }
                catch(e) {
                    $cookies.put(self.getCookieName(id), null,  {'expires': expire});
                }
            }
        };

        /**
         * Set watching flag.
         * @param flag
         */
        self.watch = function(flag) {
            self.watchFlag = flag;
        };

        /**
         * Initial the service.
         */
        self.register = function (scope, key, id, watch, events) {
            events = events || {};
            self.scopes[id] = self.scopes[id] || [];
            self.scopes[id].push({
                scope: scope,
                key: key,
                watchFlag: watch,
                time: new Date(),
                events: events
            });

            if(self.isExistsCookie(id)) {
                scope[key] = self.getVariableFromCookie(id);
            }

            if(typeof(events.onReady) == 'function') {
                events.onReady();
            }

            scope.$watch(key, function(variable) {
                if(variable) {
                    self.saveVariableToCookie(variable, id);
                }
            }, true);
        };

        self.startWatch();
    };
});