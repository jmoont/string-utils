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
            new \Twig_SimpleFilter('protectEmails', [$this, 'getProtectedEmails']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('extract', [$this, 'getExtract']),
            new \Twig_SimpleFunction('protectEmails', [$this, 'getProtectedEmails']),
        ];
    }

    public function getProtectedEmails($text = null)
    {
        $result = preg_replace_callback("(<a(.+)?href=\"(mailto:.*)\"(.+)?>.*</a>)", "processMatches", $text);

        return $result;
    }

    private function processMatches($matches)
    {
        return $this->encodeRot13($matches[0]);
    }

    /**
     * @var int
     */
    private $count = 1;

    /**
     * Returns a rot13 encrypted string as well as a JavaScript decoder function.
     * http://snipplr.com/view/6037/
     *
     * @param  string $string Value to be encoded
     *
     * @return mixed An encoded string and javascript decoder function
     */
    private function encodeRot13($string)
    {
        $rot13encryptedString = str_replace('"', '\"', str_rot13($string));
        $uniqueId = uniqid('sproutencodeemail-', true);
        $countId = $this->count++;
        $ajaxId = Craft::$app->getRequest()->isAjax ? '-ajax' : '';
        $encodeId = $uniqueId.'-'.$countId.$ajaxId;
        $encodedString = '
        <span id="'.$encodeId.'"></span>
        <script type="text/javascript">
            var sproutencodeemailRot13String = "'.$rot13encryptedString.'";
            var sproutencodeemailRot13 = sproutencodeemailRot13String.replace(/[a-zA-Z]/g, function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);});
            document.getElementById("'.$encodeId.'").innerHTML =
            sproutencodeemailRot13;
        </script>';
        return $encodedString;
    }

    public function getExtract($text = null, $keyword = null, $wordsBefore = 5, $wordsAfter = 15, $boldTerm = true)
    {

        $result = trim(implode(' ', array_slice(explode(' ', $text), 0, $wordsBefore + $wordsAfter + 1))). "...";

        if(preg_match("/(((?:\S+\s+){0," . $wordsBefore . "}\s*\S*)(" . $keyword . ")(\b\s*(?:\S+\s+){0," . $wordsAfter . "}))/mui", $text, $matches)){
            if($boldTerm){
                $result = $matches[2] . "<strong>" . $matches[3] . "</strong>";
                if(isset($matches[4])){
                    $result .= $matches[4];
                }
            } else {
                $result = $matches[1];
            }
            $result = "..." . trim($result) . "...";
        }

        if($result == "..."){
            $result = "";
        }

        return $result;
    }
}