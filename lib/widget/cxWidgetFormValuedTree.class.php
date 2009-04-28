<?php
class cxWidgetFormValuedTree extends sfWidgetForm
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
    $this->addRequiredOption('nodes');
    $this->addOption('class', 'valued_tree');
    $this->addOption('node_id_getter', 'getId');
    $this->addOption('node_name_getter', 'getName');
    $this->addOption('node_hasvalue_getter', 'getHasValue');
    $this->addOption('node_level_getter', 'getLevel');
    
    $this->addOption('template', <<<EOF
<div class="%class%"">
    %content%
</div>
<hr class="spacer" />
EOF
);   


    $this->addOption('row_template', <<<EOF
<div class="%class%">
  <label for="%rowid%" style="margin-left: %level%ex">%label%</label>
  %content%
</div>
EOF
);

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
    $nodes = $this->getOption('nodes');
    
    $html = '';

    $length = sizeof($nodes);
    if ($length > 0)
    {
      
      $rootNode = $nodes[0];
      $this->setLabel($this->getNodeName($rootNode));
      
      $rootLevel = $rootNode->getLevel() + 1;
      
      for($i=1;$i<$length;$i++)
      {
        
        $node = $nodes[$i];
        
        $nodeId = $this->getNodeId($node);
        $nodeLabel = $this->getNodeName($node);
        $rowName = $name.'['.$nodeId.']';
        $rowId = $this->generateId($rowName);
        $isEmpty = !$this->getNodeHasValue($node);

        if(!$isEmpty)
        {
          isset($value[$nodeId])?$specValue=$value[$nodeId]:$specValue='';
          
          $content = $this->renderTag('input', array_merge(
                                    array(
                                      'id' => $rowId,
                                      'name' => $rowName,
                                      'value' => $specValue,
                                    ),
                                    $attributes
                                  ));
        }
        else
        {
          $content = '&nbsp;';
        }
        
        $rowClass = $isEmpty?'empty':(($i % 2)?'even':'odd');
        
        $html .= strtr($this->getOption('row_template'),
                 array(
                   '%level%' => $this->getNodeLevel($node) - $rootLevel,
                   '%rowid%' => $rowId,
                   '%class%' => $rowClass,
                   '%label%' => $nodeLabel,
                   '%content%' => $content,
                 ));
        
        //$this->renderContentTag()
      }

      $html = strtr($this->getOption('template'),
               array(
                 '%class%' => $this->getOption('class'),
                 '%content%' => $html,
               ));      
    }

    /*
    foreach($nodes as $node)
    {
      $html .= '';
    }
    */
    return $html;
    return var_dump($this->getOption('nodes'));
  }
/*  
  private function getElementNode($element)
  {
    $method = $this->getOption('element_node_getter');
    return $element->$method();
  }

  private function getElementValue($element)
  {
    $method = $this->getOption('element_value_getter');
    return $element->$method();
  }
  */
  private function getNodeId($node)
  {
    $method = $this->getOption('node_id_getter');
    return $node->$method();
  }  
  
  private function getNodeName($node)
  {
    $method = $this->getOption('node_name_getter');
    return $node->$method();
  }
  
  private function getNodeHasValue($node)
  {
    $method = $this->getOption('node_hasvalue_getter');
    return $node->$method();
  }  

  private function getNodeLevel($node)
  {
    $method = $this->getOption('node_level_getter');
    return $node->$method();
  }  
  
}
