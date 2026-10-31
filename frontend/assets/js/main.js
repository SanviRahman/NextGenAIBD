"user strict";

$(document).ready(function () {
	/* Owl Carousel Slider*/
	$(".tweet-slider").owlCarousel({
		loop: true,
		margin: 30,
		smartSpeed: 600,
		autoplayTimeout: 2000,
		autoplay: true,
		nav: true,
		responsiveClass: true,
		navText: [
			'<i class="fas fa-arrow-left"></i>',
			'<i class="fas fa-arrow-right"></i>',
		],
		responsive: {
			0: {
				items: 1,
			},
			500: {
				items: 1,
			},
			767: {
				items: 2,
			},
			991: {
				items: 2,
			},
			1199: {
				items: 3,
			},
			1399: {
				items: 3,
			},
		},
	});

	$(".partner-slider").owlCarousel({
		loop: true,
		margin: 30,
		smartSpeed: 600,
		autoplayTimeout: 2000,
		autoplay: true,
		responsiveClass: true,
		nav: true,
		responsive: {
			0: {
				items: 1,
			},
			430: {
				items: 2,
			},
			767: {
				items: 3,
			},
			991: {
				items: 3,
			},
			1199: {
				items: 4,
			},
		},
	});
	$(".customer-wrapper").owlCarousel({
		loop: true,
		margin: 30,
		autoplay: true,
		nav: true,
		smartSpeed: 600,
		autoplayTimeout: 3000,
		responsiveClass: true,
		navText: [
			'<i class="fas fa-arrow-left"></i>',
			'<i class="fas fa-arrow-right"></i>',
		],
		responsive: {
			0: {
				items: 1,
			},
			500: {
				items: 1,
			},
			767: {
				items: 1,
			},
			991: {
				items: 1,
			},
			1199: {
				items: 1,
			},
			1399: {
				items: 1,
			},
		},
	});

	$(".pricing-customize-wrapper").owlCarousel({
		loop: true,
		margin: 0,
		autoplay: true,
		responsiveClass: true,
		smartSpeed: 600,
		autoplayTimeout: 2000,
		responsive: {
			0: {
				items: 1,
			},
			500: {
				items: 2,
			},
			767: {
				items: 2,
			},
			991: {
				items: 3,
			},
			1199: {
				items: 4,
			},
		},
	});
	$(".pricing-customize-wrapperfour").owlCarousel({
		loop: true,
		margin: 0,
		autoplay: true,
		responsiveClass: true,
		smartSpeed: 600,
		autoplayTimeout: 2000,
		responsive: {
			0: {
				items: 1,
			},
			500: {
				items: 2,
			},
			767: {
				items: 2,
			},
			991: {
				items: 3,
			},
			1199: {
				items: 6,
			},
		},
	});
	$(".client-slider").owlCarousel({
		loop: true,
		margin: 30,
		autoplay: true,
		responsiveClass: true,
		setTimeout: 2000,
		smartSpeed: 600,
		autoplayTimeout: 2000,
		responsive: {
			0: {
				items: 1,
			},
			500: {
				items: 1,
			},
			767: {
				items: 1,
			},
			991: {
				items: 1,
			},
			1199: {
				items: 1,
			},
		},
	});
	/* Owl Carousel Slider*/

	/* Tab Section Js area */
	$(".header-bar").on("click", function (e) {
		$(".main-menu, .header-bar").toggleClass("active");
	});
	$(".main-menu li a").on("click", function (e) {
		var element = $(this).parent("li");
		if (element.hasClass("open")) {
			element.removeClass("open");
			element.find("li").removeClass("open");
			element.find("ul").slideUp(300, "swing");
		} else {
			element.addClass("open");
			element.children("ul").slideDown(300, "swing");
			element.siblings("li").children("ul").slideUp(300, "swing");
			element.siblings("li").removeClass("open");
			element.siblings("li").find("li").removeClass("open");
			element.siblings("li").find("ul").slideUp(300, "swing");
		}
	});
	//menu top fixed start
	var fixed_top = $(".header-section");
	$(window).on("scroll", function () {
		if ($(this).scrollTop() > 220) {
			fixed_top.addClass("menu-fixed animated fadeInDown");
			fixed_top.removeClass("slideInUp");
			$("body").addClass("body-padding");
		} else {
			fixed_top.removeClass("menu-fixed fadeInDown");
			fixed_top.addClass("slideInUp");
			$("body").removeClass("body-padding");
		}
	});
	// Click event to scroll bar start
	$(window).on("scroll", function () {
		if ($(this).scrollTop() > 300) {
			$(".scrollToTop").fadeIn();
		} else {
			$(".scrollToTop").fadeOut();
		}
	});
	//Click event to scroll to top
	$(".scrollToTop").on("click", function () {
		$("html, body").animate(
			{
				scrollTop: 0,
			},
			700
		);
		return false;
	});
	//Click event to scroll to top end

	/*-- Odometer Counting Start--*/
	$(".odometer-item").each(function () {
		$(this).isInViewport(function (status) {
			if (status === "entered") {
				for (
					var i = 0;
					i < document.querySelectorAll(".odometer").length;
					i++
				) {
					var el = document.querySelectorAll(".odometer")[i];
					el.innerHTML = el.getAttribute("data-odometer-final");
				}
			}
		});
	});
	/*-- Odometer Counting End--*/

	/*-- Woe Animation Start--*/
	new WOW().init();
	/*-- Wow Animation End--*/

	/*-- Filtering Isotope Start--*/
	var $grid = $(".grid").isotope({
		itemSelector: ".element-item",
		layoutMode: "fitRows",
		getSortData: {
			name: ".name",
			symbol: ".symbol",
			number: ".number parseInt",
			category: "[data-category]",
			weight: function (itemElem) {
				var weight = $(itemElem).find(".weight").text();
				return parseFloat(weight.replace(/[\(\)]/g, ""));
			},
		},
	});
	// filter functions
	var filterFns = {
		// show if number is greater than 50
		numberGreaterThan50: function () {
			var number = $(this).find(".number").text();
			return parseInt(number, 10) > 50;
		},
		// show if name ends with -ium
		ium: function () {
			var name = $(this).find(".name").text();
			return name.match(/ium$/);
		},
	};
	// bind filter button click
	$("#filters").on("click", "button", function () {
		var filterValue = $(this).attr("data-filter");
		// use filterFn if matches value
		filterValue = filterFns[filterValue] || filterValue;
		$grid.isotope({ filter: filterValue });
	});
	// change is-checked class on buttons
	$(".button-group").each(function (i, buttonGroup) {
		var $buttonGroup = $(buttonGroup);
		$buttonGroup.on("click", "button", function () {
			$buttonGroup.find(".is-checked").removeClass("is-checked");
			$(this).addClass("is-checked");
		});
	});
	/*-- Filtering Isotope End--*/

	/*-- Scroll Top Bottom End--*/
	let calcScrollValue = () => {
		let scrollProgress = document.getElementById("progress");
		let progressValue = document.getElementById("valu");
		let pos = document.documentElement.scrollTop;
		let calcHeight =
		document.documentElement.scrollHeight - 
		document.documentElement.clientHeight;
		let scrollValue = Math.round((pos * 250) / calcHeight);
		
		if (pos > 250){
			scrollProgress.style.display = "grid";
		} else{
			scrollProgress.style.display = "none";
		}
		scrollProgress.addEventListener("click", () => {
			document.documentElement.scrollTop = 0;
		}); 
	};
	window.onscroll = calcScrollValue;
	window.onload = calcScrollValue;
	/*-- Scroll Top Bottom End--*/

	/*-- Preloader Start--*/
	setTimeout(function(){
		$('#preloader').fadeToggle();
	}, 500);
	/*-- Preloader End--*/

	/*-- Magnific Popup Start--*/
	$('.video-btn').magnificPopup({
		type: 'iframe',
		callbacks: {
			
	  	}
	});
	/*-- Magnific Popup End--*/


});
