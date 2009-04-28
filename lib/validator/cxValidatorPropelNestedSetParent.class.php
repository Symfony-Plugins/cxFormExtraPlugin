<?php
/**
 * cxValidatorPropelNestedSetParent
 *
 * @package    cxFormExtraPlugin
 * @subpackage validator
 * @author     Jean-Philippe BELASSAMI <cdx@nerim.net>
 */
class cxValidatorPropelNestedSetParent extends sfValidatorPropelChoice
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * model:      The model class (required)
   *  * criteria:   A criteria to use when retrieving objects
   *  * column:     The column name (null by default which means we use the primary key)
   *                must be in field name format
   *  * connection: The Propel connection to use (null by default)
   *  * multiple:   true if the select tag must allow multiple selections
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('model');
    $this->addRequiredOption('node');
    $this->addOption('equal', false);
    $this->addOption('criteria', null);
    $this->addOption('column', null);
    $this->addOption('connection', null);

    $this->addMessage('equal', 'Selected parent is the same as current node');
    $this->addMessage('child', 'Parent is a current node\'s child');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {

    $criteria = is_null($this->getOption('criteria')) ? new Criteria() : clone $this->getOption('criteria');
    
    $node = $this->getOption('node');
    
    $criteria->add($this->getColumn(), $value);

    $parent = call_user_func(array(constant($this->getOption('model').'::PEER'), 'doSelectOne'), $criteria, $this->getOption('connection'));

    if (is_null($parent))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    if(!$node->isNew()) 
    {
      if (($this->getOption('equal') === false) && $parent->equals($node))
      {
        throw new sfValidatorError($this, 'equal', array('value' => $value));
      } 
      
      if (SpecNodePeer::isChildOf($parent, $node))
      {
        throw new sfValidatorError($this, 'child', array('value' => $value));
      } 
    }
    
    return $value;
  }

  /**
   * Returns the column to use for comparison.
   *
   * The primary key is used by default.
   *
   * @return string The column name
   */
  protected function getColumn()
  {
    if ($this->getOption('column'))
    {
      $columnName = $this->getOption('column');
    }
    else
    {
      $map = call_user_func(array(constant($this->getOption('model').'::PEER'), 'getTableMap'));
      foreach ($map->getColumns() as $column)
      {
        if ($column->isPrimaryKey())
        {
          $columnName = strtolower($column->getColumnName());
          break;
        }
      }
    }

    return call_user_func(array(constant($this->getOption('model').'::PEER'), 'translateFieldName'), $columnName, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
  }
}
