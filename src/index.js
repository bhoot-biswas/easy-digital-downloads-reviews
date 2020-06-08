import $ from "jquery";
import "./scss/index.scss";

(function($) {
	"use strict";

	$(document).ready(function() {
		$("#rating")
			.hide()
			.before(
				'<p class="stars">\
					<span>\
						<a class="star-1" href="#">1</a>\
						<a class="star-2" href="#">2</a>\
						<a class="star-3" href="#">3</a>\
						<a class="star-4" href="#">4</a>\
						<a class="star-5" href="#">5</a>\
					</span>\
				</p>'
			);

		$("body").on("click", "#respond p.stars a", function() {
			var $star = $(this),
				$rating = $(this)
					.closest("#respond")
					.find("#rating"),
				$container = $(this).closest(".stars");

			$rating.val($star.text());
			$star.siblings("a").removeClass("active");
			$star.addClass("active");
			$container.addClass("selected");

			return false;
		});
	});
})(jQuery);
