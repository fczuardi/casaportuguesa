// For IE8 and earlier version.
if (!Date.now) {
  Date.now = function() {
    return new Date().valueOf();
  }
}

function tabClicked(event){
  var aside_id = $(this).attr('href');
  if ( (aside_id.indexOf('#') == -1) ||
      (aside_id.length == 1) ){return};
  var linked_aside = $(aside_id);
  event.preventDefault();
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
    var final_date = new Date(2012, 8, 19 , 0, 0, 0, 0).getTime(); //19 de setembro de 2012
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
$(document).ready(function() {
  $('header nav a').click(tabClicked);
  updateCounter();
});
