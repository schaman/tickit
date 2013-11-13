define(['marionette'], function (Marionette) {
    return Marionette.Region.extend({

        /**
         * Called when a view is shown in the region
         *
         * @param {Backbone.View} view The view to be displayed in the region
         */
        show: function(view) {
            this.ensureEl();
            view.render();

            this.close(function() {
                if (this.currentView && this.currentView !== view) {
                    return;
                }
                this.currentView = view;

                this.open(view, function() {
                    if (view.onShow) {
                        view.onShow();
                    }
                    view.trigger("show");

                    if (this.onShow) {
                        this.onShow(view);
                    }
                    this.trigger("view:show", view);
                });
            });
        },

        /**
         * Called when the region is closed
         *
         * @param {function} cb A callback function
         */
        close: function (cb) {
            var view = this.currentView;
            delete this.currentView;

            if (!view) {
                if (cb) {
                    cb.call(this);
                }
                return;
            }

            var me = this;
            view.animateOut(function () {
                if (view.close) {
                    view.close();
                }
                me.trigger("view:closed", view);
                if (cb) {
                    cb.call(me);
                }
            });
        },

        /**
         * Called when the region is opened
         *
         * @param {Backbone.View} view The view that is displayed when the region is opened
         * @param {function}      cb   A callback function
         */
        open: function (view, cb) {
            var me = this;
            this.$el.html(view.$el.hide());
            view.animateIn(function () {
                cb.call(me);
            });
        }
    });
});
