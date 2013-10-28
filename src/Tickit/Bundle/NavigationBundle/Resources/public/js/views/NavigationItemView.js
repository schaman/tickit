/**
 * Navigation item view.
 *
 * Provides a view for rendering a single navigation item.
 *
 * @type {Marionette.ItemView}
 */
define(['marionette', 'text!navigation/views/NavigationItem.html'], function(Marionette, tpl) {
    return Marionette.ItemView.extend({
        tagName: 'li',

        /**
         * Renders the HTML markup for the navigation item
         *
         * @return {Marionette.ItemView}
         */
        render: function() {
            var d = this.model.attributes;
            this.$el.html(_.template($(tpl).html(), {
                name: d.name,
                active: d.active,
                uri: this.model.getUri()
            }, { variable: 'item' }));
            return this;
        }
    });
});
