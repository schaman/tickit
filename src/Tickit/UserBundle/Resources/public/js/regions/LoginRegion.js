define(['marionette'], function (Marionette) {
    return Backbone.Marionette.Region.extend({

        el: '#container',

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
            view.fadeOut(function () {
                if (view.close) {
                    view.close();
                }
                me.trigger("view:closed", view);
                if (cb) {
                    cb.call(me);
                }
            });
        },

        open: function (view, cb) {
            var me = this;
            this.$el.html(view.$el.hide());
            view.fadeIn(function () {
                cb.call(me);
            });
        }

    });
});
