<?php

function social_media($products_id, $products_name) {

  $media_icons = '';

if (FACEBOOK_STATUS == 1) {
  $fb_image = zen_image(DIR_WS_IMAGES . 'facebook.png', '', '24', '24', 'class="socialImage"');
  $media_icons .= '<a class="facebook tooltips" href="http://www.facebook.com/sharer.php?u='.urlencode(zen_href_link(zen_get_info_page($products_id),'cPath='.(int)$_GET['cPath'].'&products_id=' . $products_id)).'&t='.$products_name.'" rel="external nofollow" target="_blank" title="Share this on Facebook"/>'.$fb_image.'</a>'.'&nbsp';
}

if (TWITTER_STATUS == 1) {
  $tw_image = zen_image(DIR_WS_IMAGES . 'twitter.png', '', '24', '24', 'class="socialImage"');
  $media_icons .= '<a class="twitter tooltips" href="http://twitthis.com/twit?url='.urlencode(zen_href_link(zen_get_info_page($products_id),'cPath='.(int)$_GET['cPath'].'&products_id=' . $products_id)).'&title='.$products_name.'" rel="external nofollow" target="_blank" title="Tweet this"/>'.$tw_image.'</a>'.'&nbsp';
}

if (DELICIOUS_STATUS == 1) {
  $dl_image = zen_image(DIR_WS_IMAGES . 'delicious.png', '', '24', '24', 'class="socialImage"');
  $media_icons .= '<a class="delicious tooltips" href="http://del.icio.us/post?url='.urlencode(zen_href_link(zen_get_info_page($products_id),'cPath='.(int)$_GET['cPath'].'&products_id=' . $products_id)).'&title='.$products_name.'" rel="external nofollow" target="_blank" title="Add this to Delicious"/>'.$dl_image.'</a>'.'&nbsp';
}

if (DIGG_STATUS == 1) {
  $dg_image = zen_image(DIR_WS_IMAGES . 'digg.png', '', '24', '24', 'class="socialImage"');
  $media_icons .= '<a class="digg tooltips" href="http://digg.com/submit?phase=2&url='.urlencode(zen_href_link(zen_get_info_page($products_id),'cPath='.(int)$_GET['cPath'].'&products_id=' . $products_id)).'" rel="external nofollow" target="_blank" title="Digg this"/>'.$dg_image.'</a>'.'&nbsp';
}

if (STUMBLE_STATUS == 1) {
  $st_image = zen_image(DIR_WS_IMAGES . 'stumbleupon.png', '', '24', '24', 'class="socialImage"');
  $media_icons .= '<a class="stumbleupon tooltips" href="http://www.stumbleupon.com/submit?url='.urlencode(zen_href_link(zen_get_info_page($products_id),'cPath='.(int)$_GET['cPath'].'&products_id=' . $products_id)).'&title='.$products_name.'" rel="external nofollow" target="_blank" title="Stumble this"/>'.$st_image.'</a>'.'&nbsp';
}

if (TECHNORATI_STATUS == 1) {
  $th_image = zen_image(DIR_WS_IMAGES . 'technorati.png', '', '24', '24', 'class="socialImage"');
  $media_icons .= '<a class="technorati tooltips" href="http://technorati.com/faves?add='.urlencode(zen_href_link(zen_get_info_page($products_id),'cPath='.(int)$_GET['cPath'].'&products_id=' . $products_id)).'" rel="external nofollow" target="_blank" title="Fave this"/>'.$th_image.'</a>'.'&nbsp';
}

if (REDDIT_STATUS == 1) {
  $ri_image = zen_image(DIR_WS_IMAGES . 'reddit.png', '', '24', '24', 'class="socialImage"');
  $media_icons .= '<a class="reddit tooltips" href="http://reddit.com/submit?url='.urlencode(zen_href_link(zen_get_info_page($products_id),'cPath='.(int)$_GET['cPath'].'&products_id=' . $products_id)).'&title='.$products_name.'" rel="external nofollow" target="_blank" title="Bookmark and tag this"/>'.$ri_image.'</a>'.'&nbsp';
}
if (GOOGLE_STATUS == 1) {
  echo '<div class="googleplus fleft"><g:plusone></g:plusone></div>'; 
}

  return $media_icons;
}
?>