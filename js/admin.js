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
function submitForm(){
  $('form')[0].submit();
}
$(document).ready(function() {
  $('#submit_link').click(submitForm);
});
