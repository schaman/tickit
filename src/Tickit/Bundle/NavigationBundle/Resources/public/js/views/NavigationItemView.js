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
            var m = this.model;
            this.$el.html(
                _.template($(tpl).html(), {
                    name: m.get('name'),
                    class: 'icon-' + m.getIconName() + ' ' + m.get('class'),
                    showText: m.get('showText'),
                    uri: m.getUri()
                }, { variable: 'item' })
            );
            return this;
        },
    });
});
