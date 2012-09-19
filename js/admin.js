function recentPhotoHover(event){
}
function checkScrollEnd(event){
  if (ajax_currently_loading) {return false;}
  if (($(this).scrollTop() + $(this).height() - 130) > $('#main').height()){ //171
    ajax_currently_loading = true;
    var url = "ajax_more_photos.php?nocache="+(new Date().getTime())+"&page="+next_page;

    loadNextPhotoPage("ajax_more_photos.php?admin=yes&nocache="+(new Date().getTime())+"&page="+next_page);
  }
}
function itemUpdated(event){
  if ($(this).hasClass('block-user')){
    if (typeof($(this).attr('checked')) === "undefined"){
      $('.recentes li input[name="'+$(this).attr('name')+'"]').removeAttr('checked');
    }else{
      $('.recentes li input[name="'+$(this).attr('name')+'"]').attr('checked', 'checked');
    }
  }
  $(this).parents('li').addClass('updated');
}
function submitForm(){
  var photo_ids = [];
  var affected_users = [];
  $('.recentes li.updated').each(function(){
    photo_ids.push($(this).data('photo_id'));
    affected_users.push($(this).data('user_id'));
  });
  $('#recent_photo_ids').attr('value', photo_ids.join(','));
  $('#affected_users').attr('value', affected_users.join(','));
  $('form')[0].submit();
}
function paginationLoaded(){
  $('.recentes li a').mouseover(recentPhotoHover);
  $('.recentes li img').bind('load', 'error', recentImageLoaded);
  $('#mouse-over').mouseout(closeHover);
  $('.recentes li input').click(itemUpdated);
}
$(document).ready(function() {
  $('#submit_link').click(submitForm);
  paginationLoaded();
});
