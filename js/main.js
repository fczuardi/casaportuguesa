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
$(document).ready(function() {
  $('header nav a').click(tabClicked);
});
