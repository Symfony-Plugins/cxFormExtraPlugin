<?php

/**
 * cxWidgetFormSelectTags
 *
 * @package    cxFormExtraPlugin
 * @subpackage widget
 * @author     Jean-Philippe BELASSAMI <cdx@nerim.net>
 */
class cxWidgetFormSchemaFormatterTree extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<tr>\n  <th>%label%</th>\n  <td>%error%%field%%help%%hidden_fields%</td>\n</tr>\n",
    $errorRowFormat  = "<tr><td colspan=\"2\">\n%errors%</td></tr>\n",
    $helpFormat      = '<br />%help%',
    $decoratorFormat = "<table>\n  %content%</table>";
}
