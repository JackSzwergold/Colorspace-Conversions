/**
 * Common (common.js) (c) by Jack Szwergold
 *
 * Common is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>. 
 *
 * w: http://www.preworn.com
 * e: me@preworn.com
 *
 * Created: 2014-01-19, js
 * Version: 2014-01-19, js: creation
 *          2014-01-19, js: ...
 *
 */

(function($) {

$(document).ready(function() {

  var item = $('.PixelBoxWrapper');
  item.css({ marginTop: ($(window).height() / 2) - (item.outerHeight() / 2) });
  $(window).resize(function () {
    item.css({ marginTop: ($(window).height() / 2) - (item.outerHeight() / 2) });
  });

});
})(jQuery);