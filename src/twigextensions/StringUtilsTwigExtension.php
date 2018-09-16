<?php
/**
 * StringUtils plugin for Craft CMS 3.x
 *
 * This twig plugin for the Craft CMS brings helpful string utils to your Twig templates.
 *
 * @link      https://www.twitter.com/moonty
 * @copyright Copyright (c) 2018 Josh Moont
 */

namespace jmoont\stringutils\twigextensions;

use jmoont\stringutils\StringUtils;

use Craft;

/**
 * @author    Josh Moont
 * @package   StringUtils
 * @since     1.0.0
 */
class StringUtilsTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'StringUtils';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('extract', [$this, 'getExtract']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('extract', [$this, 'getExtract']),
        ];
    }

    public function getExtract($text = null, $keyword = null, $wordsBefore = 5, $wordsAfter = 15, $boldTerm = true)
    {

        $result = $text;

        if(preg_match("/(((?:\S+\s+){0," . $wordsBefore . "}\s*\S*)(" . $keyword . ")(\b\s*(?:\S+\s+){0," . $wordsAfter . "}))/mui", $text, $matches)){
            if($boldTerm){
                $result = $matches[2] . "<strong>" . $matches[3] . "</strong>";
                if(isset($matches[4])){
                    $result .= $matches[4];
                }
            } else {
                $result = implode(' ', array_slice(explode(' ', $matches[1]), 0, $wordsBefore + $wordsAfter));
            }
        }

        return $result;
    }
}