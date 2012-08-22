function tabClicked(event){
  event.preventDefault();
  var aside_id = $(this).attr('href');
  var linked_aside = $(aside_id);
  if (linked_aside.hasClass('opened')){
    linked_aside.removeClass('opened');
  }else{
    $('#content aside').removeClass('opened');
    $(aside_id).addClass('opened');
  }
}
$(document).ready(function() {
  $('header nav a').click(tabClicked);
});
