function recentPhotoHover(event){
}
function checkScrollEnd(event){
  if (ajax_currently_loading) {return false;}
  if (($(this).scrollTop() + $(this).height() - 130) > $('#main').height()){ //171
    ajax_currently_loading = true;
    console.log('next')
    loadNextPhotoPage("ajax_more_photos.php?admin=yes&page="+next_page);
  }
}
function itemUpdated(event){
  $(this).parents('li').addClass('updated');
}
function submitForm(){
  var photo_ids = [];
  $('.recentes li.updated').each(function(){
    photo_ids.push($(this).data('photo_id'));
  });
  $('#recent_photo_ids').attr('value', photo_ids.join(','));
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
