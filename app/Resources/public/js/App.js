/**
 * Base application object.
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
var App = new Backbone.Marionette.Application();

App.addRegions({
    mainRegion: '#container',
    navRegion: '', // nav container ID here
    footerRegion: '#footer'
});