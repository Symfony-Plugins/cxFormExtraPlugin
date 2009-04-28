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
class cxWidgetFormTinyMCEWrapperComment extends sfWidgetFormTextareaTinyMCE
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
    $this->addOption('width', 485);
    $this->addOption('height', 350);
    $this->addOption('config', '
      doctype : \'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\',
      plugins: "spellchecker,inlinepopups", 
      language : "fr",
      spellchecker_languages : "+French=fr",
      theme_advanced_toolbar_location: "top",
      theme_advanced_toolbar_align: "left",
      theme_advanced_path_location: "bottom",
      theme_advanced_buttons1: "justifyleft,justifycenter,justifyright,justifyfull,separator,bold,italic,strikethrough,separator,sub,sup,separator,charmap,spellchecker",
      theme_advanced_buttons2: "bullist,numlist,separator,outdent,indent,separator,undo,redo,separator,link,unlink,separator,cleanup,removeformat,separator,code",
      theme_advanced_buttons3: "",
      extended_valid_elements: "",
      content_css: "/css/main.css",
      theme_advanced_resizing : false,
      relative_urls: false,
      debug: false'
    );
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
    sfContext::getInstance()->getResponse()->addJavascript('tiny_mce/tiny_mce.js');
    return array();
  }  
  
}
