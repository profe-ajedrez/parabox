<?php declare(strict_types=1);
namespace parabox\views;

use Exception;

/**
 * Undocumented class
 */
class View
{
    const VERSION = "0.1.1";

    private $viewLoader;
    private $fragments = [];
    private $config    = [];

    /**
     * View constructor.
     * @param ViewLoader $viewLoader
     * @throws Exception
     */
    public function __construct(ViewLoader $viewLoader, array $config)
    {
        if (is_null($viewLoader)) {
            throw new Exception("ViewLoader parameter expected. Received null.");
        }

        if (!is_array($config) || empty($config)) {
            throw new Exception("Array expected in parameter \$config. Received non array, null or empty.");
        }

        $this->viewLoader = $viewLoader;
        $this->config     = $config;
    }


    /**
     * @param string $viewName
     * @throws Exception
     */
    public function addFragment(string $viewName = "")
    {
        if (empty($viewName)) {
            throw new Exception("String viewName parameter expected. Received null or empty.");
        }
        $this->fragments[] = $viewName;
    }


    /**
     * @param string $viewName
     * @param string $theme
     * @return string
     * @throws Exception
     */
    public function render(string $viewName = "", string $theme = "") : string
    {
        $theme = (
            empty($theme) ? "" : /** @lang text */
                <<<'THEMELINK'
<link rel="stylesheet" href="[CSS]/themes/$theme.css?v=1">
THEMELINK
);
        if (empty($viewName)) {
            if (empty($this->fragments)) {
                throw new Exception('There is no fragments to build a view, and no view was passed as parameter on render');
            }

            $view = '';
            foreach ($this->fragments as $fragment) {
                $view .= $this->viewLoader->load($fragment) . "\n";
            }
        } else {
            $view = $this->viewLoader->load($viewName);
        }

        //add the theme link just before </head>
        $view = str_replace('</head>', $theme . "\n</head>", $view);
        $view = str_replace($this->config["css-tag"], $this->config["css-url"], $view);
        $view = str_replace($this->config["img-tag"], $this->config["img-url"], $view);
        $view = str_replace($this->config["js-tag"],  $this->config["js-url"], $view);

        return $view;
    }


    /**
     *
     */
    public function setCustomTag(string $tag = "", $value = "", array $config)
    {
        if (empty($tag)) {
            throw new Exception("String parameter expected in \$tag. Null or empty received.");
        }

        if (empty($config)) {
            throw new Exception("Array parameter expected. Null or empty received.");
        }


        $config["custom-tags"][$tag]["value"] = $value;
    }


    public function getCustomTag(string $tag = "", array $config)
    {
        if (empty($tag)) {
            throw new Exception("String parameter expected in \$tag. Null or empty received.");
        }

        if (!array_key_exists($tag, $config["custom-tags"])) {
            throw new Exception("Unrecognized \$tag $tag in configurations.");
        }

        return $config["custom-tags"][$tag]["value"];
    }


    public function replaceCustomTags(string $str, array $config)
    {

        foreach($config["custom-tags"] as $tag => $container) {
            $value = $container["value"];
            if (is_array($container["value"]) || is_object($container["value"])) {
                $value = json_encode($container["value"]);
            }

            $str = str_replace($container["tag"], $value, $str);
        }

        return $str;
    }
}
