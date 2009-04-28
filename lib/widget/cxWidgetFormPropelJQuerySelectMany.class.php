<?php
/**
 * cxWidgetFormPropelJQuerySelectMany
 *
 * @package    cxFormExtraPlugin
 * @subpackage widget
 * @author     Jean-Philippe BELASSAMI <cdx@nerim.net>
 */
class cxWidgetFormPropelJQuerySelectMany extends cxWidgetFormJQuerySelectMany
{
  
  /**
   * @see sfWidget
   */
  public function __construct($options = array(), $attributes = array())
  {
    $options['value_formatter'] = new sfCallable(array($this, 'formatValues'));
    parent::__construct($options, $attributes);
  }
    
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * model:          The model where records will be picked up (required)
   *  * peer_method:    The method called for getting back records
   *  * key_method:     The method called for having a data representative of a record (eg. "getPrimaryKey")
   *  * method:         The method called for rendering records
   *  * order_by:	The field on which you want to order the results
   *  * connection:     The connection to use to retrieve the records
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('peer_method', 'retrieveByPKs');
    $this->addOption('key_method', 'getPrimaryKey');
    $this->addOption('method', '__toString');
    $this->addOption('order_by', null);
    $this->addOption('connection', null);
    
    parent::configure($options, $attributes);
  }
  
  /**
   * Returns the values associated to the model.
   *
   * @return array An array of id, values
   */
  public function formatValues($ids)
  {
    $class = constant($this->getOption('model').'::PEER');

    $methodKey = $this->getOption('key_method');
    if (!method_exists($this->getOption('model'), $methodKey))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodKey, __CLASS__));
    }

    $methodValue = $this->getOption('method');
    if (!method_exists($this->getOption('model'), $methodValue))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodValue, __CLASS__));
    }

    $values = array();
    $objects = call_user_func(array($class, $this->getOption('peer_method')), $ids, $this->getOption('connection'));
    
    foreach ($objects as $object)
    {
      $values[$object->$methodKey()] = $object->$methodValue();
    }

    return $values;
  }
}
