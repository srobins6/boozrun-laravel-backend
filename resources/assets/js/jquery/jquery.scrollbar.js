(function ($) {
	$.fn.hasVerticalScrollBar = function () {
		return this.get(0) ? this.get(0).scrollHeight > this.innerHeight() : false;
	}
})(jQuery);
/**
 * Created by sol on 3/3/16.
 */
