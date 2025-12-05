<?php

/**
 * Frontend Display Class (frontendDisplay.class.php) (c) by Jack Szwergold
 *
 * Frontend Display Class is licensed under a
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 *
 * You should have received a copy of the license along with this
 * work. If not, see <http://creativecommons.org/licenses/by-nc-sa/4.0/>.
 *
 * w: https://www.szwergold.com
 * e: jackszwergold@icloud.com
 *
 * Created: 2014-01-22, js
 * Version: 2014-01-22, js: creation
 *          2014-01-22, js: development & cleanup
 *          2014-01-23, js: refinements
 *          2014-02-17, js: setting a 'base'
 *          2014-02-27, js: adding a page URL
 *          2015-05-10, js: adding DIV wrapper class & id
 *          2015-05-11, js: setting dynamic DIV wrapper creation
 *          2016-06-09, js: major reshuffling to get footers, headers, content and ads working
 *
 */

//**************************************************************************************//
// The beginnings of a frontend display class.

class frontendDisplay {

  private $DEBUG_MODE = FALSE;
  private $JSON_MODE = FALSE;

  private $content;
  private $html_content;
  private $json_content;

  private $content_type = 'text/html';
  private $charset = 'utf-8';
  private $doctype = 'html5';
  private $favicons = FALSE;

  private $json_encode = FALSE;
  private $json_via_header = FALSE;

  private $javascripts = array();
  private $link_items = array();

  private $base = NULL;
  private $page_depth = 0;
  private $param_parts = array();

  private $view_mode = NULL;
  private $view_div = NULL;

  private $page_url = NULL;
  private $page_copyright = NULL;
  private $page_license = NULL;
  private $page_title = NULL;
  private $page_title_short = NULL;
  private $page_description = NULL;
  private $page_content = NULL;
  private $page_image = NULL;
  private $page_keyword = NULL;
  private $page_date = NULL;
  private $page_author = NULL;

  private $header_content = NULL;
  private $footer_content = NULL;

  private $page_div_wrapper_class = NULL;
  private $page_div_wrapper_id = NULL;
  private $page_div_wrappper_array = array();

  private $page_viewport = NULL;
  private $page_robots = NULL;

  private $social_media_info = array();

  private $ad_banner = NULL;

  //**************************************************************************************//
  // Set the constructor.
  public function __construct() {
    if (!defined('BASE_PATH')) {
      define('BASE_PATH', '/');
    } // if
    if (!defined('BASE_URL')) {
      define('BASE_URL', '');
    } // if
  } // __construct

  //**************************************************************************************//
  // Set the debug mode.
  function setDebugMode($DEBUG_MODE = null) {
    if (!empty($DEBUG_MODE)) {
      $this->DEBUG_MODE = $DEBUG_MODE;
    } // if
  } // setDebugMode

  //**************************************************************************************//
  // Set the JSON mode.
  function setJSONMode($JSON_MODE = null) {
    if (!empty($JSON_MODE)) {
      $this->JSON_MODE = $JSON_MODE;
    } // if
  } // setJSONMode

  //**************************************************************************************//
  // Set the character set.
  function setContentType($content_type = null) {
    global $VALID_CONTENT_TYPES;
    if (!empty($content_type) && in_array($content_type, $VALID_CONTENT_TYPES)) {
      $this->content_type = $content_type;
    } // if
  } // setContentType

  //**************************************************************************************//
  // Set the character set.
  function setCharset($charset = null) {
    global $VALID_CHARSETS;
    if (!empty($charset) && in_array(strtolower($charset), $VALID_CHARSETS)) {
      $this->charset = $charset;
    } // if
  } // setCharset

  //**************************************************************************************//
  // Set the page mode.
  function setViewMode($view_mode = null, $view_div = false) {
    $this->view_mode = $view_mode;
    $this->view_div = $view_div;
  } // setViewMode

  //**************************************************************************************//
  // Set the page URL.
  function setPageURL($page_url = null) {
    $this->page_url = $page_url;
  } // setPageURL

  //**************************************************************************************//
  // Set the page copyright.
  function setPageCopyright($page_copyright = null) {
    $this->page_copyright = $page_copyright;
  } // setPageCopyright

  //**************************************************************************************//
  // Set the page license.
  function setPageLicense($page_license = null) {
    $this->page_license = $page_license;
  } // setPageLicense

  //**************************************************************************************//
  // Set the page title.
  function setPageTitle($page_title = null) {
    $this->page_title = $page_title;
  } // setPageTitle

  //**************************************************************************************//
  // Set the page description.
  function setPageDescription($page_description = null) {
    $this->page_description = $page_description;
  } // setPageDescription

  //**************************************************************************************//
  // Set the page image.
  function setPageImage($page_image = null) {
    $this->page_image = $page_image;
  } // setPageImage

  //**************************************************************************************//
  // Set the page keyword.
  function setPageKeyword($page_keyword = null) {
    $this->page_keyword = $page_keyword;
  } // setPageKeyword

  //**************************************************************************************//
  // Set the page date.
  function setPageDate($page_date = null) {
    $this->page_date = $page_date;
  } // setPageDate

  //**************************************************************************************//
  // Set the page author.
  function setPageAuthor($page_author = null) {
    $this->page_author = $page_author;
  } // setPageAuthor

  //**************************************************************************************//
  // Set the Facebook admin stuff.
  function setPageFBAdmins($page_fb_admins = null) {
    $this->page_fb_admins = $page_fb_admins;
  } // setPageFBAdmins

  //**************************************************************************************//
  // Set the page content JSON content.
  function setPageJSONContent($json_content = null) {
    $this->json_content = $json_content;
  } // setPageJSONContent

  //**************************************************************************************//
  // Set the page html content.
  function setPageContent($html_content = null) {
    $this->html_content = $html_content;
  } // setPageContent

  //**************************************************************************************//
  // Set the page DIVs.
  function setPageDivs($page_div_wrappper_array = array()) {
    $this->page_div_wrappper_array = $page_div_wrappper_array;
  } // setPageDivs

  //**************************************************************************************//
  // Set the page DIV wrapper.
  function setPageDivWrapper($page_div_wrapper_class = null, $page_div_wrapper_id = null) {
    $this->page_div_wrapper_class = $page_div_wrapper_class;
    $this->page_div_wrapper_id = $page_div_wrapper_id;
  } // setPageDivWrapper

  //**************************************************************************************//
  // Set the page viewport.
  function setPageViewport($page_viewport = null) {
    $this->page_viewport = $page_viewport;
  } // setPageViewport

  //**************************************************************************************//
  // Set the page robots.
  function setPageRobots($page_robots = null) {
    $this->page_robots = $page_robots;
  } // setPageRobots

  //**************************************************************************************//
  // Set the JavaScript stuff.
  function setJavaScriptItems($javascripts = array()) {
    $this->javascripts = $javascripts;
  } // setJavaScriptItems

  //**************************************************************************************//
  // Set the link items stuff.
  function setLinkItems($link_items = array()) {
    $this->link_items = $link_items;
  } // setLinkItems

  //**************************************************************************************//
  // Set the Favicon stuff.
  function setFaviconItems($favicons = array()) {
    $this->favicons = $favicons;
  } // setFaviconItems

  //**************************************************************************************//
  // Set the HTML base.
  function setPageBase($page_base = null) {
    $this->base = $page_base;
  } // setPageBase

  //**************************************************************************************//
  // Set the page depth and param parts.
  function setPageURLParts($param_parts = array()) {
    $this->page_depth = count($param_parts);
    $this->param_parts = $param_parts;
  } // setPageURLParts

  //**************************************************************************************//
  // Set the social media info.
  function setSocialMediaInfo($social_media_info = null) {
    $this->social_media_info = $social_media_info;
  } // setSocialMediaInfo

  //**************************************************************************************//
  // Set the ad banner stuff.
  function setAdBanner($ad_banner = null) {
    $this->ad_banner = $ad_banner;
  } // setAdBanner

  //**************************************************************************************//
  // Set the body header.
  function setBodyHeader($header_content = null) {
    $this->header_content = $header_content;
  } // setBodyHeader

  //**************************************************************************************//
  // Set the body footer.
  function setBodyFooter($footer_content = null) {
    $this->footer_content = $footer_content;
  } // setBodyFooter

  //**************************************************************************************//
  // Init the core content.
  function initCoreContent($response_header = null) {
    if (!$this->JSON_MODE) {
      $this->buildCoreContent();
    } // if
  } // initCoreContent

  //**************************************************************************************//
  // Init the content.
  function initHTMLContent($response_header = null) {
    if (!$this->JSON_MODE) {
      $this->buildHTMLContent();
    } // if
    $this->renderContent($response_header);
  } // initHTMLContent

  //**************************************************************************************//
  // Build the core content.
  function buildCoreContent() {
    if (!empty($this->html_content)) {
      $this->html_content = $this->html_content;
    } // if
  } // buildCoreContent


  //**************************************************************************************//
  // Build the HTML content.
  function buildHTMLContent() {

    if (!empty($this->html_content)) {

      //**********************************************************************************//
      // Set the meta tags
      $meta_content_array = $this->setMetaTags($this->page_description, $this->page_viewport, $this->page_robots);

      //**********************************************************************************//
      // Set the favicons.
      $favicon_array = $this->setHeaderLinkArray($this->favicons);

      //**********************************************************************************//
      // Set the HTML/XHTML doctype.
      $doctype = $this->setDoctype();

      //**********************************************************************************//
      // Set the JavaScript.
      $javascript_array = $this->setJavaScriptArray();

      //**********************************************************************************//
      // Set the CSS.

      $css_array = $this->setHeaderLinkArray($this->link_items);

      //**********************************************************************************//
      // Set the body header.
      $header = '';
      if (!empty($this->header_content)) {
        $header =
            '<div class="Header">'
          . $this->header_content
          . '</div>'
          ;
      } // if

      //**********************************************************************************//
      // Set the body footer.
      $footer = '';
      if (!empty($this->footer_content)) {
        $footer =
            '<div class="Footer">'
          . $this->footer_content
          . '</div>'
          ;
      } // if

      //**********************************************************************************//
      // Set the view wrapper.
      if (!empty($this->view_mode) && $this->view_div) {
        $body =
            sprintf('<div class="%sView">', $this->view_mode)
          . $this->setWrapper($this->html_content)
          . sprintf('</div><!-- .%sView -->', $this->view_mode)
          ;
      } // if
      else {
        $body = $this->setWrapper($this->html_content);
      } // else

      //**********************************************************************************//
      // Set the final HTML content.
      $ret =
          $doctype
        . '<head>'
        . '<title>' . $this->page_title . '</title>'
        . join('', $meta_content_array)
        . join('', $css_array)
        . join('', $favicon_array)
        . join('', $javascript_array)
        . (!empty($this->base) ? '<base href="' . $this->base . '" />' : '')
        . '</head>'
        . '<body>'
        . $header
        . $body
        . $footer
        . '</body>'
        . '</html>'
        ;

      //**********************************************************************************//
      // Set the HTML content class.
      $this->html_content = $ret;

    } // if

  } // buildHTMLContent


  //**************************************************************************************//
  // Set the doctype.
  function setDoctype() {

    $ret = '';

    if ($this->doctype == 'xhtml') {
      $ret =
          '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
        . '<html xmlns="http://www.w3.org/1999/xhtml">'
        ;
    } // if
    else if ($this->doctype == 'html5') {
      $ret =
          '<!DOCTYPE html>'
        . '<html lang="en">'
        ;
    } // else if

    return $ret;

   } // setDoctype


  //**************************************************************************************//
  // Set the JavaScript stuff.
  function setJavaScriptArray() {
    $ret = array();
    foreach ($this->javascripts as $javascript) {
      $ret[] = sprintf('<script src="' . BASE_URL . '%s" defer="defer"></script>', $javascript);
    } // foreach
    return $ret;
  } // setJavaScriptArray


  //**************************************************************************************//
  // Set the header link stuff.
  function setHeaderLinkArray($array = array()) {
    $ret = array();
    foreach ($array as $array_type => $array_parts) {
      $parts = array();
      foreach ($array_parts as $key => $value) {
        if ($key == 'href') {
          if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $value = BASE_URL . $value;
          } // if
        } // if
        $parts[] = $key . '="' . $value . '"';
      } // foreach
      // $ret[$array_type] = sprintf('<!-- %s link_items -->', $type);
      $ret[$array_type] = sprintf('<link %s />', join(' ', $parts));
    } // foreach
    return $ret;
  } // setHeaderLinkArray

  //**************************************************************************************//
  // Set the meta content.
  function setMetaTags($description = null, $viewport = null, $robots = null) {

    // Set the meta property values.
    $meta_http_equivs = array();
    $meta_http_equivs['content-type'] = 'text/html; charset=utf-8';
    // $meta_http_equivs['imagetoolbar'] = 'no';

    // Set the meta property values.
    $meta_names = array();
    if (!empty($description)) {
      $meta_names['description'] = $description;
    }
    if (!empty($viewport)) {
      $meta_names['viewport'] = $viewport;
    }
    if (!empty($robots)) {
      $meta_names['robots'] = $robots;
    }
    if (!empty($this->page_date)) {
      $meta_names['date'] = $this->page_date;
      $meta_names['dc.date'] = $this->page_date;
    }
    if (!empty($this->page_author)) {
      $meta_names['author'] = $this->page_author;
      $meta_names['citation_author'] = $this->page_author;
      $meta_names['dc.creator'] = $this->page_author;
    }

    // The copyright changes between 'xhtml' & 'html5'
    $copyright_key = '';
    if ($this->doctype == 'xhtml') {
      $copyright_key = 'copyright';
    }
    else if ($this->doctype == 'html5') {
      $copyright_key = 'dcterms.rightsHolder';
    }
    if (!empty($copyright_key) && !empty($this->page_url)) {
      $meta_names[$copyright_key] = $this->page_copyright . '. ' . $this->page_license . '.';
    }
    $meta_names['apple-mobile-web-app-capable'] = 'yes';

    // Set the OpenGraph meta property values.
    $meta_properties = array();
    $meta_properties['og:title'] = $this->page_title;
    if (!empty($description)) {
      $meta_properties['og:description'] = $description;
    }
    $meta_properties['og:type'] = 'website';
    $meta_properties['og:locale'] = 'en_US';
    if (!empty($this->page_url)) {
      $meta_properties['og:url'] = $this->page_url;
    }
    if (!empty($this->page_title)) {
      $meta_properties['og:site_name'] = $this->page_title;
    }
    if (!empty($this->page_image)) {
      $meta_properties['og:image'] = $this->page_image;
    }

    // Set the Facebook specific meta property values.
    if (!empty($this->page_fb_admins)) {
      $meta_properties['fb:admins'] = $this->page_fb_admins;
    }

    // Set the Twitter meta property values.
    $meta_properties['twitter:card'] = 'summary';
    $meta_properties['twitter:title'] = $this->page_title;
    if (!empty($description)) {
      $meta_properties['twitter:description'] = $description;
    }
    if (!empty($this->page_url)) {
      $meta_properties['twitter:url'] = $this->page_url;
    }
    if (!empty($this->page_image)) {
      $meta_properties['twitter:image'] = $this->page_image;
    }


    $ret = array();

    // Roll through the '$meta_http_equivs'
    foreach ($meta_http_equivs as $http_equiv_key => $http_equiv_value) {
      $ret[] = sprintf('<meta http-equiv="%s" content="%s" />', $http_equiv_key, $http_equiv_value);
    }

    // Roll through the '$meta_names'
    foreach ($meta_names as $name_key => $name_value) {
      $ret[] = sprintf('<meta name="%s" content="%s" />', $name_key, $name_value);
    }

    // Roll through the '$meta_properties'
    foreach ($meta_properties as $property_key => $property_value) {
      $ret[] = sprintf('<meta property="%s" content="%s" />', $property_key, $property_value);
    }

    return $ret;

  } // setMetaTags

  //**************************************************************************************//
  // Set the navigation stuff.
  function setNavigation() {

    $li_items_l = array();
    if ($this->page_depth > 0) {
      $params_sliced = array_slice(array_values($this->param_parts), 0, -1);
      $back_url = BASE_PATH . join('/', $params_sliced);
      $li_items_l[] = '<li id="back">'
                    . sprintf('<a href="%s" title="back">«</a>', $back_url)
                    . '</li>'
                    ;
    }
    else {
      $li_items_l[] = '<li></li>';
    }

    $li_items_r = array();

    // Set the social media stuff.
    if (!empty($this->social_media_info)) {
      foreach ($this->social_media_info as $social_media_key => $social_media_value) {
        $li_items_r[] = sprintf('<li id="%s">', $social_media_key)
                      . sprintf('<a href="%s" title="%s">%s %s</a>', $social_media_value['url'], $social_media_value['description'], $social_media_value['short_name'], $social_media_value['emoji'])
                      . '</li>'
                      ;
      }
    }

    if (!empty($li_items_l)) {
      $content_l = sprintf('<ul>%s</ul>', implode('', $li_items_l));
    }

    if (!empty($li_items_r)) {
      $content_r = sprintf('<ul>%s</ul>', implode('', $li_items_r));
    }

    $div_l = '';
    if (!empty($content_l)) {
      $div_l = '<div class="Left">'
             . $content_l
             . '</div><!-- .Left -->'
             ;
    }

    $div_r = '';
    if (!empty($content_r)) {
      $div_r = '<div class="Right">'
             . $content_r
             . '</div><!-- .Right -->'
             ;
    }

    $ret = '';
    if (!empty($content_l) || !empty($content_r)) {
      $ret = '<div class="Navigation">'
           . $div_l
           . $div_r
           . '</div><!-- .Navigation -->'
           ;
    }

    return $ret;

  } // setNavigation

  //**************************************************************************************//
  // Set the ad banner stuff.
  function setAdBannerFinal() {
    return '<div class="Ad">'
         . sprintf($this->ad_banner, $this->page_keyword)
         . '</div>'
         ;
  } // setAdBannerFinal

  //**************************************************************************************//
  // Set the wrapper.
  function setWrapper($body = null) {

    $body_div_stuff = array();
    $body_div_close_stuff = array();

    if (!empty($this->page_div_wrapper_class)) {
      $body_div_stuff[] = sprintf('class="%s"', $this->page_div_wrapper_class);
      $body_div_close_stuff[] = sprintf('.%s', $this->page_div_wrapper_class);
    }

    if (!empty($this->page_div_wrapper_id)) {
      $body_div_stuff[] = sprintf('id="%s"', $this->page_div_wrapper_id);
    }

    if (!empty($this->page_div_wrapper_class) || (!empty($this->page_div_wrapper_class) && !empty($this->page_div_wrapper_id))) {
      $body = sprintf('<div %s>', implode(' ', $body_div_stuff))
            . $body
            . sprintf('</div><!-- %s -->', implode(' ', $body_div_close_stuff))
            ;
    }

    // Set the wrapper divs.
    $div_opening = $div_closing = '';
    if (!empty($this->page_div_wrappper_array)) {
      $div_opening = '<div class="' . implode('">' . "\n" . '<div class="', $this->page_div_wrappper_array) . '">';
      $div_closing = '</div><!-- .' . implode('-->' . "\n" . '</div><!-- .', array_reverse($this->page_div_wrappper_array)) . ' -->';
    }

    $ret = (!empty($nameplate) ? $nameplate : '')
         . $div_opening
         . $body
         . $div_closing
         ;

    return $ret;

  } // setWrapper

  //**************************************************************************************//
  // Function to send content to output.
  private function renderContent ($response_header = null) {
    global $VALID_CONTENT_TYPES, $VALID_CHARSETS, $DEBUG_OUTPUT_JSON;

    // If we are in debugging mode, just dump the content array & exit.
    if ($this->JSON_MODE && !empty($this->json_content)) {
      $json_content = $this->json_encode ? json_encode($this->json_content) : $this->json_content;
      header(sprintf('Content-Type: %s; charset=%s', $this->content_type, $this->charset));
      if ($this->json_via_header) {
        header('X-JSON:' . $json_content);
      }
      else {
        header($response_header);
        echo $json_content;
      }
      exit();
    }
    else if ($this->DEBUG_MODE && !empty($this->html_content)) {
      header('Content-Type: text/plain; charset=utf-8');
      echo $this->html_content;
      exit();
    }
    else {
      header(sprintf('Content-Type: %s; charset=%s', $this->content_type, $this->charset));
      echo $this->html_content;
      exit();
    }

  } // renderContent

} // frontendDisplay

?>
