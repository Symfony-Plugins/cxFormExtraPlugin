<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormTextareaTinyMCE represents a Tiny MCE widget.
 *
 * You must include the Tiny MCE JavaScript file by yourself.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormTextareaTinyMCE.class.php 11894 2008-10-01 16:36:53Z fabien $
 */
class cxWidgetFormTinyMCEWrapperBackend extends sfWidgetFormTextareaTinyMCE
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * theme:  The Tiny MCE theme
   *  * width:  Width
   *  * height: Height
   *  * config: The javascript configuration
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('theme', 'advanced');
    $this->addOption('width', 510);
    $this->addOption('height', 400);
    $this->addOption('config', '
      doctype : \'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\',
      plugins: "table,layer,advimage,advlink,cdxmedia,preview,contextmenu,visualchars,inlinepopups,advhr,spellchecker,style", 
      file_browser_callback:"sfAssetsLibrary.fileBrowserCallBack",
      language : "fr",
      spellchecker_languages : "+French=fr",
      media_strict: true,
      media_flv_player:"/media/newmediaplayer.swf",
      theme_advanced_toolbar_location: "top",
      theme_advanced_toolbar_align: "left",
      theme_advanced_path_location: "bottom",
      theme_advanced_blockformats : "p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp",
      theme_advanced_buttons1: "preview,|,spellchecker,|,undo,redo,|,charmap,|,link,unlink,|,image,cdxmedia,advhr,|,visualaid,visualchars,|,removeformat,cleanup,code",
      theme_advanced_buttons2: "tablecontrols,|,insertlayer,moveforward,movebackward,absolute",
      theme_advanced_buttons3: "justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,forecolor,backcolor,|,styleprops",
      theme_advanced_buttons4: "formatselect,fontselect,fontsizeselect,|,bold,italic,underline,strikethrough,sub,sup",
      //theme_advanced_buttons3: "forecolor,backcolor,|,link,unlink,|,image,cdxmedia,advhr,charmap",
      //theme_advanced_buttons4: "undo,redo,|,visualaid,visualchars,removeformat,styleprops,|,cleanup,code,|,spellchecker,preview",      
    extended_valid_elements: "p[style|class]|div[style|class]|span[style|class]|img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style|class],'
                              .'object[class|style|classid|codebase|data|type|width|height|align],'
                              .'param[name|value],'
                              .'embed[quality|type|pluginspage|width|height|src|align|allowscriptaccess|allowfullscreen|flashvars]",
      content_css: "/css/main.css",
      relative_urls: false,
      debug: false'
    );
  }
        //extended_valid_elements: "img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],'
  
  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return parent::render($name, $value, $attributes, $errors)
          .javascript_tag('sfAssetsLibrary.setTinyBrowserUrl(\''.url_for('sfAsset/list?popup=tinymce').'\')');;
  }
  
  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    sfContext::getInstance()->getResponse()->addJavascript('tiny_mce/tiny_mce.css');
    return array();
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */

  public function getJavascripts()
  {
    sfContext::getInstance()->getResponse()->addJavascript('/sfAssetsLibraryPlugin/js/main.js', 'last'); 
    sfContext::getInstance()->getResponse()->addJavascript('tiny_mce/tiny_mce.js');
    return array();
  }  
  
}
