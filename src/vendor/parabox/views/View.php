<?php declare(strict_types=1);
namespace parabox\views;


class View
{
    const VERSION = "0.1.0";

    private $viewLoader;


    public function __construct(ViewLoader $viewLoader)
    {
        if (is_null($viewLoader)) {
            throw new Exception("ViewLoader parameter expected. Received null.");
        }

        $this->viewLoader = $viewLoader;
    }


    /**
     * @param string $viewName
     * @return string
     */
    public function render(string $viewName) : void
    {
        echo $this->viewLoader->load($viewName);
    }

}