<script>
	var button = document.querySelector('.item-menu-superior');
	button.addEventListener("mouseover", function(){
		var hover = document.querySelector('.hover');
		if(hover.style.display === "none"){
			hover.style.display = "block";
		}else{
			hover.style.display = "none";
		}
	});
</script>


<script type="text/javascript" src="/dpmt/assets/plugins/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="/dpmt/assets/plugins/jquery.isotope.js"></script>
<script type="text/javascript" src="/dpmt/assets/plugins/masonry.js"></script>
<script type="text/javascript" src="/dpmt/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="/dpmt/assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script> -->
<script type="text/javascript" src="/dpmt/assets/plugins/owl-carousel/owl.carousel.min.js"></script>
<script type="text/javascript" src="/dpmt/assets/plugins/knob/js/jquery.knob.js"></script>
<script type="text/javascript" src="/dpmt/assets/plugins/flexslider/jquery.flexslider-min.js"></script>


<!-- <script type="text/javascript" src="/dpmt/assets/plugins/slick/slick/slick.js"></script>
<script type="text/javascript" src="/dpmt/portal/incs/uteis/slick_carousel.js"></script> -->

<!-- REVOLUTION SLIDER -->
<script type="text/javascript" src="/dpmt/assets/plugins/revolution-slider/js/jquery.themepunch.tools.min.js"></script>
<script type="text/javascript" src="/dpmt/assets/plugins/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>
<script type="text/javascript" src="/dpmt/assets/js/revolution_slider.js"></script>
<script type="text/javascript" src="/dpmt/assets/js/scripts.js"></script>


<script>

jQuery("document").ready(function($){

var nav = $('.menu_fixo');

$(window).scroll(function () {
	if ($(this).scrollTop() > 0) {
		nav.addClass("f-nav");
	} else {
		nav.removeClass("f-nav");
	}
});

});
/*Jquery - Menu lateral*/
$(".sublinks").hide();
$(".menu_link").mouseover(function () {    //$(".menu_link").click(function () {
	
	$header = $(this);
	//getting the next element
	$content = $header.next();
	//open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
	$content.slideToggle(900);

});
</script>
