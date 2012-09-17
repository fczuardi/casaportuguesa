// For IE8 and earlier version.
if (!Date.now) {
  Date.now = function() {
    return new Date().valueOf();
  }
}

var next_page = 2;

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
function updateCounter(){
  if (!miliseconds_left){
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
}

function showMessagePopup(){
  var popup = $('#message-popup');
  var popup_launcher = $('#popup-opener');
  if (!popup_launcher.hasClass('closed')){ return; }
  popup_launcher.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
  popup.removeClass('closed');
}
function hideMessagePopup(){
  var popup = $('#message-popup');
  popup.addClass('closed');
  popup.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", showPopupLauncher);
  event.stopPropagation();
}
function showPopupLauncher(){
  var popup = $('#message-popup');
  var popup_launcher = $('#popup-opener');
  popup.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
  popup_launcher.removeClass('closed');
}
function hidePopupLauncher(){
  var popup_launcher = $('#popup-opener');
  popup_launcher.addClass('closed');
  popup_launcher.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", showMessagePopup);
  event.stopPropagation();
}
function dismissPopup(event){
  $('#content aside').removeClass('opened');
  $('header nav ul li').removeClass('active');
  // if(!$('#message-popup').hasClass('closed')){
  //   hideMessagePopup();
  // }
}
function hideNotFoundImages(){
  $('.recentes li a').each(function(){
    if ($(this).width() == 0){
      $(this).parent('li').css('display', 'none');
      // $(this).css('display', 'none');
    }
  });
}
function loadNextPhotoPage(event){
  // ajax_more_photos.php?page=2
  var url = "ajax_more_photos.php?page="+next_page;
  $.get(url,function(data) {
      var posts = $(data).find('li');
      $('.recentes').append(posts);
      hideNotFoundImages();
      next_page++;
  });
  return false;
}
$(document).ready(function() {
  $('header nav a').click(tabClicked);
  $('#message-popup .close-button').click(hideMessagePopup);
  $('#popup-opener a').click(hidePopupLauncher);
  $('body').click(dismissPopup);
  updateCounter();
  showMessagePopup();
  $(window).load(hideNotFoundImages);
});
