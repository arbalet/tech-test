<?php

namespace Journey;

class View
{

    private $template;

    private $variables;

    private $config;



    /**
     * Create a new view object with the appropriate template
     * @param   $template [description]
     * @return [type]           [description]
     */
    public function __construct($template, $variables)
    {
        $this->template = $template;
        $this->variables = $variables;
    }



    /**
     * Render this view into a string
     * @return none
     */
    public function render()
    {
        $config = $this->config();
        $location = $config['templates'] . "/" . $this->template . $config['extension'];

        if (file_exists($location) || $config['string_template']) {
            $prior = ob_start();
            extract($this->variables, EXTR_OVERWRITE, $config['string_template']);
            if (!$config['string_template']) {
                include $location;
            } else {
                echo $string;
            }
            $template = ob_get_clean();
        } else {
            throw new ViewException('Unable to locate the required template: ' . $location);
        }

        return $template;
    }



    /**
     * Get/Set the configuration options for this discrete view
     * @param Array  $options   optional set of elements to override this
     * @return none [description]
     */
    public function config($options = array())
    {
        $this->config = ($this->config) ?:self::defaults();

        if (!empty($options)) {
            $this->config = array_replace_recursive(self::defaults(), $options);
        }
        return $this->config;
    }



    /**
     * Factory constructor used just for shorthand instantiations
     * @param  String $template template name to load
     * @return Journey\View
     */
    public static function make($template, $variables = [])
    {
        return new self($template, $variables);
    }



    /**
     * Set universal configuration variables for all view objects
     * @param Array $options configuration options to extend the defaults with
     * @return Array         configuration options
     */
    public static function defaults($options = array())
    {
        static $config;

        if ($options || !$config) {

            $defaults = array(
                'templates' => getcwd() . "/templates",
                'extension' => '.php',
                'variable_prefix' => null,
                'string_template' => false
            );

            $config = array_replace_recursive($defaults, $options);
        }
        
        return $config;
    }



    /**
     * Allow direct echo of this object
     * @return string  should output the rendered view
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (ViewException $e) {
            return $e->getMessage();
        }
    }
}
