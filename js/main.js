// For IE8 and earlier version.
if (!Date.now) {
  Date.now = function() {
    return new Date().valueOf();
  }
}

var next_page = 2;
var ajax_currently_loading = false;
var brokenImagesCheckInterval;
var expectedImages = 28;
var loadedImages = 0;
var ultima_diferenca = 0; //numero de possiveis fotos quebradas
var turnosIguais = 0;

function tabClicked(event){
  var aside_id = $(this).attr('href');
  if ( (aside_id.indexOf('#') == -1) ||
      (aside_id.length == 1) ){return};
  var linked_aside = $(aside_id);
  event.preventDefault();
  event.stopPropagation();
  var tab = $(this.parentNode);
  if (linked_aside.hasClass('opened')){
    linked_aside.removeClass('opened');
    tab.removeClass('active');
  }else{
    $('#content aside').removeClass('opened');
    $(aside_id).addClass('opened');
    $('header nav ul li').removeClass('active');
    tab.addClass('active');
  }
}
function updateCounter(miliseconds_left){
  if (miliseconds_left === false){
    var final_date = new Date(2012, 8, 20 , 11, 0, 0, 0).getTime(); //20 de setembro de 2012
    var current_date = Date.now();
    var miliseconds_left = final_date - current_date;
  }
  var days_left =  Math.max(0, Math.floor(miliseconds_left / 86400000));
  var hours_left = Math.max(0, Math.floor((miliseconds_left % 86400000)/1000/60/60));
  var digits = [
    Math.floor(days_left / 10),
    days_left % 10,
    Math.floor(hours_left / 10),
    hours_left % 10
  ];
  $('#contador .digit .number').each(function(i){
    $(this).html(digits[i]);
  });
  if ((digits[0] == 0)&&(digits[1] == 0)&&(digits[2] == 0)&&(digits[3] == 0)){
    $('#counter-msg').html('a qualquer momento as primeiras fotos aparecerão aqui, fique ligado');
    setTimeout(function(){
      window.location.href = window.location.href;
    }, 10*1000);
  }
}

function bumpPopupLauncher(){
  var popup_launcher = $('#popup-opener');
  popup_launcher.css('z-index', 2);
  popup_launcher.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
}
function bumpMessagePopup(){
  var popup = $('#message-popup');
  popup.css('z-index', 2);
  popup.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
}
function showMessagePopup(){
  var popup = $('#message-popup');
  var popup_launcher = $('#popup-opener');
  if (!popup_launcher.hasClass('closed')){ return; }
  popup_launcher.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
  popup.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", bumpMessagePopup);
  popup.removeClass('closed');
}
function hideMessagePopup(){
  var popup = $('#message-popup');
  popup.css('z-index', 0);
  popup.addClass('closed');
  popup.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", showPopupLauncher);
}
function showPopupLauncher(){
  var popup = $('#message-popup');
  var popup_launcher = $('#popup-opener');
  popup.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
  popup_launcher.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", bumpPopupLauncher);
  popup_launcher.removeClass('closed');
}
function hidePopupLauncher(){
  var popup_launcher = $('#popup-opener');
  popup_launcher.css('z-index', 0);
  popup_launcher.addClass('closed');
  popup_launcher.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", showMessagePopup);
}
function dismissPopup(event){
  $('#content aside').removeClass('opened');
  $('header nav ul li').removeClass('active');
  // if(!$('#message-popup').hasClass('closed')){
  //   hideMessagePopup();
  // }
}
function checkImage(image){
  if (image.height() > 50){
    image.parents('li').addClass('loaded');
  }else{
    image.parents('li').addClass('fail');
  }
}
function recentImageLoaded(event){
  loadedImages++;
  checkImage($(this));
}
function hideNotFoundImages(){
  // console.log("hideNotFoundImages");
  $('.recentes img').each(function(){
    checkImage($(this));
  });
  var diferenca = expectedImages - loadedImages;
  if (diferenca == ultima_diferenca){
    turnosIguais++;
  }else{
    turnosIguais = 0;
  }
  // console.log("expected:"+expectedImages+" loaded:"+loadedImages+
  //             " diferenca:"+diferenca+" ultima_diferenca:"+
  //             ultima_diferenca+" turnos:"+turnosIguais);
  ultima_diferenca = diferenca;
  if((loadedImages == expectedImages)||(turnosIguais == 10)){
    // console.log("para o intervalo");
    clearInterval(brokenImagesCheckInterval);
    loadedImages = expectedImages;
  }
}
function startCheckingForBrokenImages(){
  clearInterval(brokenImagesCheckInterval);
  brokenImagesCheckInterval = setInterval(hideNotFoundImages, 1000);
}
function paginationLoaded(){
  $('.recentes li a').mouseover(recentPhotoHover);
  $('.recentes li img').bind('load', 'error', recentImageLoaded);
  $('#mouse-over').mouseout(closeHover);
}
function loadNextPhotoPage(url){
  if (!url){
    var url = "ajax_more_photos.php?nocache="+(new Date().getTime())+"&page="+next_page;
  }
  expectedImages +=28;
  $.get(url,function(data) {
      var posts = $(data).find('li');
      $('.recentes').append(posts);
      next_page++;
      if (expectedImages == $('.recentes li').length){
        $('#main').css('min-height', $('#main').height() + 4*143)
      }
      paginationLoaded();
      ajax_currently_loading = false;
      startCheckingForBrokenImages();
  });
  return false;
}
function checkScrollEnd(event){
  if (ajax_currently_loading) {return false;}
  if (($(this).scrollTop() + $(this).height() - 130) > $('#main').height()){ //171
    ajax_currently_loading = true;
    // console.log('next')
    loadNextPhotoPage();
  }
}
function recentPhotoHover(event){
  var li = $(this).parent('li');
  var li_offset = li.offset();
  var ol_offset = $(this).parents('ol').offset();
  var over = $('#mouse-over');
  over.css('display', 'block');
  over.html(li.html());
  over.css('top', li_offset['top'] - 252);
  over.css('left', li_offset['left'] - ol_offset['left'] - 87);
  // event.stopPropagation();
}
function closeHover(event){
  $('#mouse-over').css('display', 'none');
}

$(document).ready(function() {
  $('header nav a').click(tabClicked);
  $('#message-popup .close-button').click(hideMessagePopup);
  $('#popup-opener a').click(hidePopupLauncher);
  $('body').click(dismissPopup);
  paginationLoaded();
  if (typeof(miliseconds_left)==="undefined"){
    miliseconds_left = false;
  }
  updateCounter(miliseconds_left);
  showMessagePopup();
  $(window).scroll(checkScrollEnd);
  $(window).load(startCheckingForBrokenImages);
});
