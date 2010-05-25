<?php
/**
 * cxWidgetFormJQuerySelectMany
 *
 * @package    cxFormExtraPlugin
 * @subpackage widget
 * @author     Jean-Philippe BELASSAMI <cdx@nerim.net>
 */
class cxWidgetFormJQuerySelectMany extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * url:            The URL to call to get the choices to use (required)
   *  * config:         A JavaScript array that configures the JQuery autocompleter widget
   *  * value_callback: A callback that converts the value before it is displayed
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    sfProjectConfiguration::getActive()->loadHelpers('Tag');
    sfProjectConfiguration::getActive()->loadHelpers('Asset');
    sfProjectConfiguration::getActive()->loadHelpers('I18N');
    $this->addRequiredOption('ajax_url');
    $this->addOption('value_formatter', null);
    $this->addOption('config', '{ }');
    $this->addOption('class', 'open_list');
    $this->addOption('option_factory', 'CxOpenListOptionFactory.make');
    $this->addOption('label_source', __('Unassociated'));
    $this->addOption('label_selected', __('Associated'));
    $this->addOption('add', image_tag('/sfFormExtraPlugin/images/next.png',array('absolute' => true, 'alt' => __('add'))));
    $this->addOption('remove', image_tag('/sfFormExtraPlugin/images/previous.png',array('absolute' => true, 'alt' => __('del'))));
    $this->addOption('template', <<<EOF
<div class="%class%">
  <div style="float: left">
    <div class="%class%_label">%label_source%</div>
    %source%
  </div>
  <div style="float: left; margin-top: 2em">
    <a class="%class%_add">%add%</a>
    <br />
    <a class="%class%_remove">%remove%</a>
  </div>
  <div style="float: left">
    <div class="%class%_label">%label_selected%</div>
    %selected%
  </div>
  <br style="clear: both" />
  <script type="text/javascript">
    var %js_instance_name% = new CxOpenSelect('%class%', '%src_id%', '%dest_id%', '%ajax_url%', %config%, %option_factory%);
  </script>
</div>
EOF
);

  parent::configure($options, $attributes);
  }
  
  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $srcId = $this->generateId('source_'.$name);
    $destId = $this->generateId($name);
    
    $jsInstanceName = sfInflector::camelize($destId.'_Handler');

    $size = isset($attributes['size']) ? $attributes['size'] : (isset($this->attributes['size']) ? $this->attributes['size'] : null);

    $sourceWidget = new sfWidgetFormInput(
      array(),
      array(
        'size' => $size, 
        'class' => $this->getOption('class').'_source'
      ));
    
      $valueFormatter = $this->getOption('value_formatter');
      if ($valueFormatter instanceof sfCallable)
      {
        $value = $valueFormatter->call($value);
      }
      
      $selectedWidget = new sfWidgetFormSelect(
      array(
        'multiple' => true,
        'choices' => $value
      ),
      array(
        'size' => $size,
        'class' => $this->getOption('class').'_selected'
      ));

    $sourceHtml = $sourceWidget->render('source_'.$name, '');
    $selectedHtml = $selectedWidget->render($name, array());
    
    $html = strtr(
      $this->getOption('template'), 
      array(
        '%src_id%'            => $srcId,
        '%dest_id%'           => $destId,
        '%js_instance_name%'  => $jsInstanceName,
        '%ajax_url%'          => $this->getOption('ajax_url'),
        '%config%'            => $this->getOption('config'),
        '%option_factory%'    => $this->getOption('option_factory'),
        '%class%'             => $this->getOption('class'),
        '%label_source%'      => $this->getOption('label_source'),
        '%label_selected%'    => $this->getOption('label_selected'),
        '%add%'               => $this->getOption('add'),
        '%remove%'            => $this->getOption('remove'),
        '%source%'            => $sourceHtml,
        '%selected%'          => $selectedHtml,
      ));
    return $html;
  }
  
  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    sfContext::getInstance()->getResponse()->addStylesheet('/sfFormExtraPlugin/css/jquery.autocompleter.css', '', array('media' => 'screen'));
    sfContext::getInstance()->getResponse()->addJavascript('/cxFormExtraPlugin/css/cx_open_list.css');
    return array();
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */

  public function getJavascripts()
  {
    sfContext::getInstance()->getResponse()->addJavascript('/sfFormExtraPlugin/js/jquery.autocompleter.js');
    sfContext::getInstance()->getResponse()->addJavascript('/cxFormExtraPlugin/js/cx_open_list.js');
    return array();
  }
  
  /**
   * 
   * used for unkeyed values. replaces keys by values.
   * 
   * @param $value
   * @return unknown_type
   */
  public static function formatValuesAsKeys(array $value)
  {
    if(count($value))
      return array_combine($value, $value);
    else
      return array();
  }
}
