$(document).ready(function() {
  //Carousel init
	var owl = $(".team-project-carousel");

	var mode = owl.attr('data-mode');

	owl.owlCarousel({
		items: 1,
		nav: false,
		responsiveClass: true,
    responsive: {
        0: {
            items: 1
        },
        1000: {
            items: mode === 'full' ? 2 : 1,
        }
    }
	});

	// Go to the next item
	$('.team-c-next').click(function() {
		owl.trigger('next.owl.carousel');
	});

	// Go to the previous item
	$('.team-c-prev').click(function() {
		// With optional speed parameter
		// Parameters has to be in square bracket '[]'
		owl.trigger('prev.owl.carousel');
	});

	owl.on('changed.owl.carousel', function(event) {
		var current = event.item.index;
		$(".current-team").text(current + 1);

		$('.team-c-prev').attr('data-index', current + 1);
		$('.team-c-next').attr('data-index', current + 1);

		var length = parseInt($('.team-c-next').attr('data-length'));

		$('.team-c-next').attr('data-last', length === current + 1 ? "yes" : "no");
	});
});
