<?php

/**
 * sfValidatorPass is an identity validator. It simply returns the value unmodified. 
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorPass.class.php 7902 2008-03-15 13:17:33Z fabien $
 */
class sfValidatorPass extends sfValidatorBase
{
  /**
   * @see sfValidatorBase
   */
  public function clean($value)
  {
    return $this->doClean($value);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {   
    return $value;
  }
}
