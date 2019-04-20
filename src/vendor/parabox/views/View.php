<?php declare(strict_types=1);
namespace parabox\views;


use Exception;

class View
{
    const VERSION = "0.1.0";

    private $viewLoader;
    private $fragments = [];

    /**
     * View constructor.
     * @param ViewLoader $viewLoader
     * @throws Exception
     */
    public function __construct(ViewLoader $viewLoader)
    {
        if (is_null($viewLoader)) {
            throw new Exception("ViewLoader parameter expected. Received null.");
        }

        $this->viewLoader = $viewLoader;
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
    public function render(string $viewName = "", string $theme = "")
    {
        if (empty($viewName)) {
            if (empty($this->fragments)) {
                throw new Exception('There is no fragments to build a view, and no view was passed as parameter on render');
            }

            $theme = (empty($theme) ? "" : /** @lang text */
                <<<'THEMELINK'
    <link rel="stylesheet" href="assets/css/themes/$theme.css?v=1">
THEMELINK
);
            $view = '';
            foreach ($this->fragments as $fragment) {
                $view .= $this->viewLoader->load($fragment) . "\n";
            }

            //add the theme link just before </head>
            str_replace('</head>', $theme . '\n</head>', $view);

            echo $view;
            return;
        }

        echo $this->viewLoader->load($viewName);
        return;
    }

}