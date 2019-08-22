<?php

namespace App\Http\Classes;

use Browser;
use Evergreen\Generic\App\Helpers\EGForm;

class VTForm extends EGForm
{
    public static function color($key, $aInp)
    {
        self::colour($key, $aInp);
    }

    public static function colour($key, $aInp)
    {
        $aInp['inp_type'] = 'color';
        EGForm::input($key, $aInp);
    }

    public static function multiSelect($key, $aInp)
    {
        global $type;
        $aInp['classes'][] = 'selectize-multiple'; //Added
        $aInp['attributes']['multiple'] = 'multiple'; //Added

        $aInp['inp_type'] = "select";
        $aInp = self::setDefaults($key, $aInp, $type);
        //get the value from the array if it exists
        if (array_search($aInp['value'], (array) $aInp['list'])) {
            $aInp['value'] = array_search($aInp['value'], (array) $aInp['list']);
        }

        if ($aInp['type'] == 'view' || !empty($aInp['disabled'])) {
            if (isset($aInp['list'][$aInp['value']])) {
                $aInp['value'] = $aInp['list'][$aInp['value']];
            } elseif (isset($aInp['other']) && $aInp['other'] == true) {
                $aInp['value'] = $aInp['value'];
            } else {
                $aInp['value'] = '';
            }

            //display as text field when viewing
            self::text($key, $aInp);
        } else {
            //otherwise display as select drop down
            if (!empty($aInp['list'])) {
                $sAttr = self::buildInputAttr($key, $aInp, array(
                    "name",
                    "class",
                    "id",
                    "disabled",
                    "attributes", //Added
                ));
                $divClasses = ['control'];
                if (isset($aInp['prefix'])) {
                    $divClasses[] = "has-icons-left";
                }
                if (isset($aInp['suffix'])) {
                    $divClasses[] = "has-icons-right";
                }
                $sInput = '<div class="'.implode(' ', $divClasses).'">';
                $sInput .= '<div class="select" style="width:100%">';
                $sInput .= '<select '.$sAttr;
                if (isset($aInp['other']) && $aInp['other'] == true) {
                    $sInput.= " onchange='".$key."changed();'";
                }
                $sInput.=' />';

                if (isset($aInp['selector']) && $aInp['selector']) {
                    if (isset($aInp['selector_message']) && !empty($aInp['selector_message'])) {
                        $message = $aInp['selector_message'];
                    } else {
                        $message = "Please choose...";
                    }
                    $sInput .= '<option value="">'.$message.'</option>';
                }
                    
                $aInp['otherValue'] = '';
                if (isset($aInp['other']) && $aInp['other'] == true && !isset($aInp['list'][$aInp['value']]) && isset($aInp['value']) && $aInp['value'] != '') {
                    $aInp['otherValue'] = $aInp['value'];
                    $aInp['value'] = "OTHER";
                }

                if (!is_array($aInp['value'])) {
                    $aInp['value'] = [$aInp['value']];
                }
                foreach ($aInp['list'] as $keys => $value) {
                    $selected = "";
                    if (in_array($keys, array_keys($aInp['value']))) {
                        $selected = "selected";
                    }
                    $sInput .= '<option value="'.$keys.'" '.$selected.'>'.htmlentities($value).'</option>';
                }

                $sInput .= '</select>';
                $sInput .= '</div>';
                if (isset($aInp['prefix'])) {
                    $sInput.= self::addIcons($aInp, 'prefix', 'left');
                }
                if (isset($aInp['suffix'])) {
                    $sInput.= self::addIcons($aInp, 'suffix', 'right');
                }
                $sInput .= '</div>';

                self::drawInputHTML($key, $aInp, $sInput);

                if (isset($aInp['other']) && $aInp['other'] == true) {
                    $otherAInp = $aInp;
                    $otherAInp['label'] = null;
                    if (isset($aInp['list'][$aInp['value']]) || $aInp['value'] == '') {
                        $otherAInp['attributes']['style'] = "display:none";
                    }
                    if (isset($otherAInp['other_placeholder'])) {
                        $otherAInp['attributes']['placeholder'] = $otherAInp['other_placeholder'];
                    } else {
                        $otherAInp['attributes']['placeholder'] = "Please specify";
                    }

                    $otherAInp['value'] = $aInp['otherValue'];

                    if (isset($otherAInp['other_type'])) {
                        switch ($otherAInp['other_type']) {
                            case 'textArea':
                                self::textArea($key."_other", $otherAInp);
                                break;
                            case 'number':
                                self::number($key."_other", $otherAInp);
                                break;
                            case 'date':
                                self::date($key."_other", $otherAInp);
                                break;
                            case 'time':
                                self::time($key."_other", $otherAInp);
                                break;
                            case 'ckeditor':
                                self::ckeditor($key."_other", $otherAInp);
                                break;
                            case 'file':
                                self::file($key."_other", $otherAInp);
                                break;
                            case 'text':
                            default:
                                self::text($key."_other", $otherAInp);
                                break;
                        }
                    } else {
                        self::text($key."_other", $otherAInp);
                    }
                    echo "
                        <script>
                            $(document).ready(function(){
                                ".$key."changed()
                            });
                            function ".$key."changed(){
                                if ($(\"#".$key."\").val() == \"OTHER\"){
                                    $(\"#".$key."_other\").show();
                                } else {
                                    $(\"#".$key."_other\").hide();
                                }
                            }
                        </script>
                    ";
                }
            }
        }
    }
}
