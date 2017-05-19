//script added by Get Em Fast Web Designs
var isAnimating = false;
$(function () {
$('#dropMenu .level1 .submenu.submenu').hover(function() {
if (!isAnimating) {
$(this).find('ul.level2,.level3 li,.level4 li,.level5 li,.level6 li').stop(true, true).slideUp(500);
$(this).find('ul.level2,.level3 li,.level4 li,.level5 li,.level6 li').stop(true, true).slideDown(500);
isAnimating = true;
}
}, function() {
$(this).find('ul.level2,.level3 li,.level4 li,.level5 li,.level6 li').stop(true, true).slideDown(500);
$(this).find('ul.level2,.level3 li,.level4 li,.level5 li,.level6 li').stop(true, true).slideUp(500);
isAnimating = false;
});}); 