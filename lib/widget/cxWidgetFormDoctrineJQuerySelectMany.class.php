<?php
/**
 * cxWidgetFormSelectTags
 *
 * @package    cxFormExtraPlugin
 * @subpackage widget
 * @author     Jean-Philippe BELASSAMI <cdx@nerim.net>
 * @author     Baptiste SIMON <baptiste.simon AT libre-informatique.fr>
 */
class cxWidgetFormDoctrineJQuerySelectMany extends cxWidgetFormJQuerySelectMany
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
   *  * method:         The method called for rendering records
   *  * key:            The key which is representative of a record (eg. "id")
   *  * order_by:	The field on which you want to order the results
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('method', '__toString');
    $this->addOption('key', 'id');
    $this->addOption('order_by', null);
    
    parent::configure($options, $attributes);
  }
  
  /**
   * Returns the values associated to the model.
   *
   * @return array An array of id, values
   */
  public function formatValues($ids)
  {
    // if nothing selected
    if ( !$ids )
    	return array();
    
    $query = Doctrine::getTable($this->getOption('model'))
    		->createQuery()
    		->andWhereIn($this->getOption('key'),$ids);
    if ( $this->getOption('order_by') )
    	$query	->order_by($this->getOption('order_by'));
    $d = $query	->execute()->getData();
    
    $key   = $this->getOption('key');
    $method = $this->getOption('method');
    
    $values = array();
    foreach ($d as $object)
    {
      $values[$object->$key] = $object->$method();
    }
    return $values;
  }
}
