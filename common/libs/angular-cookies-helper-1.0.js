
define(function (require) {
    return function($cookies) {
        var self = this;

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
            if($cookies[self.getCookieName(id)]) {
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
        self.getVariableFromCookie = function(id) {
            var cookie = $cookies[self.getCookieName(id)];

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
            if(variable) {
                try {
                    if( typeof(variable) == 'object' ) {
                        $cookies[self.getCookieName(id)] = JSON.stringify(variable);
                    }
                    else {
                        $cookies[self.getCookieName(id)] = variable;
                    }
                }
                catch(e) {
                    $cookies[self.getCookieName(id)] = null;
                }
            }
        };

        /**
         * Initial the service.
         */
        self.register = function (scope, key, id) {

            if(self.isExistsCookie(id)) {
                scope[key] = self.getVariableFromCookie(id);
            }

            scope.$watch(key, function(variable) {
                if(variable) {
                    self.saveVariableToCookie(variable, id);
                }
            }, true)
        };
    };
});