<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// Create a shortcut for params.
$params = $displayData->params;
$canEdit = $displayData->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.framework');
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=495758307205124";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="share">
<div class="social-button">
    <div class="fb-like" data-href="http://localhost:8888<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($displayData->slug, $displayData->catid)); ?>" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>
    <div class="twitter">
        <a href="https://twitter.com/share" data-text="<?php echo $this->escape($displayData->title); ?>" data-url="http://localhost:8888<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($displayData->slug, $displayData->catid)); ?>" class="twitter-share-button" data-hashtags="ellysilveira">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
    <div class="google-plus">
        <!-- Place this tag where you want the +1 button to render. -->
        <div class="g-plusone" data-size="medium" data-href="http://localhost:8888<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($displayData->slug, $displayData->catid)); ?>"></div>

        <!-- Place this tag after the last +1 button tag. -->
        <script type="text/javascript">
          window.___gcfg = {lang: 'pt-BR'};

          (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = 'https://apis.google.com/js/plusone.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                  })();
        </script>
    </div>
    <div class="pinterest">
        <a href="//pinterest.com/pin/create/button/?" data-pin-do="buttonPin"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="" /></a>
        <script type="text/javascript">// <![CDATA[
        (function (document){
            (function(d){
              var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
              p.type = 'text/javascript';
              p.async = true;
              p.src = '//assets.pinterest.com/js/pinit.js';
              f.parentNode.insertBefore(p, f);
            }(document));

            document.observe('dom:loaded', function() {

              // get the pinterest anchor element, the pinterest sdk changes the anchor
              // during rendering so look for either one
              var pinEl = $$("a[data-pin-do]")[0];
              if (pinEl === undefined) {
                $$("a[data-pin-log]")[0];
              }

              var imageUrl = "";
              if ($("image") !== null) {
                imageUrl = encodeURIComponent($("image").src);
              }

              var curHref = pinEl.getAttribute("href");
              curHref += "&media=" + imageUrl;
              curHref += "&url=" + encodeURIComponent(location.href);
              curHref += "&description=" + encodeURIComponent(document.title);
              pinEl.setAttribute("href", curHref);
            });
        })(document);
        // ]]></script>
    </div>
</div>
</div>