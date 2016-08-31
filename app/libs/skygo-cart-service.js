
define(function (require) {
    return function($cookiesHelper) {
        var self        = this;
        self.scope      = null;
        self.collection = null;
        self.list       = null;

        self.getInvalidProductMsg = function() {
            return "喔哦！您忘了選擇規格了哦！請重新選擇規格，才能下單哦！";
        };

        self.getDuplicateProductMsg = function() {
            return "購物車已有此項商品, 調整購買數量請於購買時調整";
        };

        self.getMissingProductAmountMsg = function() {
            return "尚未選擇購買數量";
        };

        /**
         * Register a mapping scope and collection node.
         * @param scope
         * @param collection
         */
        self.register = function(scope, collection) {
            self.scope      = scope;
            self.collection = collection;

            $cookiesHelper.register(scope, collection, "ng-cart", true);
            self.scope[self.collection] = self.scope[self.collection] || [];
            self.list = self.scope[self.collection];

        };

        /**
         * Add a new product item to cart.
         * @param newItem
         * @param error
         * @param success
         */
        self.add = function(newItem, error, success) {
            var item = null;
            try {
                if(!(newItem.id)) {
                        throw self.getInvalidProductMsg();
                }

                if(!(newItem.amount)) {
                    throw self.getMissingProductAmountMsg();
                }

                if(!(newItem.product_id)) {
                    throw self.getInvalidProductMsg();
                }

                if(!(newItem.activity_id)) {
                    if(!(newItem.activity_id===0)) {
                        throw self.getInvalidProductMsg();
                    }
                }
                
                for(var index = 0; index < self.list.length; index++) {
                    item = self.list[index];
                    if(item.id == newItem.id) {
                        throw self.getDuplicateProductMsg();
                    }
                }

                self.list.push(newItem);
                if(typeof(success) == 'function') {
                    success(newItem);
                }
            }
            catch(e) {
                if(typeof(error) == 'function') {
                    error(newItem, e);
                }
            }
        };
    };
});