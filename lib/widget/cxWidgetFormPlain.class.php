<?php
class cxWidgetFormPlain extends sfWidgetForm
{
  /**
   * Constructor.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default  HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('model', null);
    $this->addOption('method', '__toString');
    $this->addOption('connection', null);
    $this->addOption('multiple', false);
    $this->addOption('peer_method', 'retrieveByPK');

    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed  in this widget
   * @param  array  $attributes  An array of HTML  attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors  for the field
   *
   * @return string A string (value of the widget)
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if($this->getOption('model'))
    {
      $class = constant($this->getOption('model').'::PEER');

      if ($order = $this->getOption('order_by'))
      {
        $method = sprintf('add%sOrderByColumn', 0 === strpos(strtoupper($order[1]), 'ASC') ? 'Ascending' : 'Descending');
        $criteria->$method(call_user_func(array($class, 'translateFieldName'), $order[0], BasePeer::TYPE_PHPNAME, BasePeer::TYPE_COLNAME));
      }

      $object = call_user_func(array($class, $this->getOption('peer_method')), $value, $this->getOption('connection'));
  
      $methodValue = $this->getOption('method');
      if (!method_exists($this->getOption('model'), $methodValue))
      {
        throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('model'), $methodValue, __CLASS__));
      }
  
      return $object->$methodValue();
    }          
    else
    {
      return $value;
    }
  }
}