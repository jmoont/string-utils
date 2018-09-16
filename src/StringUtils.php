<?php
/**
 * StringUtils plugin for Craft CMS 3.x
 *
 * This twig plugin for the Craft CMS brings helpful string utils to your Twig templates.
 *
 * @link      https://www.twitter.com/moonty
 * @copyright Copyright (c) 2018 Josh Moont
 */

namespace jmoont\stringutils;

use jmoont\stringutils\twigextensions\StringUtilsTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Class StringUtils
 *
 * @author    Josh Moont
 * @package   StringUtils
 * @since     1.0.0
 *
 */
class StringUtils extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var StringUtils
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new StringUtilsTwigExtension());

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'string-utils',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
