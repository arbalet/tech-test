<?php

/**
 * Vardump
 * zero dependency replacement of php dump functions var_dump and print_r with beautiful styling and support for cli-mode
 * @author Jari Berg <jariberg@gmail.com>
 */
class VardumpOptions
{
  public $colors;
  public $returnMode;
  public $stylingMode;
  public $htmlMode;
  public $flattenMode;
  public $editorLink;
}

class Vardump
{
  /**
   * @var VardumpOptions
   */
  private $options;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->options = new VardumpOptions();

    // Set defaults
    $this->setColors(array());
    $this->setReturnMode(false);
    $this->setHtmlMode(false);
    $this->setFlattenMode(false);
    $this->setStylingModeDetect();
    $this->setEditorLink();
  }

  /**
   * Singleton instance
   * @return Vardump
   */
  public static function singleton()
  {
    static $instance;
    if (!isset($instance)) {
      $instance = new Vardump();
    }
    return $instance;
  }

  /**
   * Get arguments
   *
   * @param integer $num
   * @param array $args
   *
   * @return mixed or null if no arguments
   */
  public static function getArgs($num,$args)
  {
    if ($num == 0) {
      return null;
    } else {
      if (is_array($args) && count($args) == 1 && $num = 1) {
        $args = current($args);
      }
      return $args;
    }
  }

  /**
   * Set colors from array
   *
   * @param array $colors
   */
  public function setColors(array $colors)
  {
    $defaultColors = array(
      'default' => '#4488bb',
      'seperator' => '#92a2b0',
      'key' => '#bb5588',
      'size' => '#92a2b0',
      'type' => '#4488bb',
      'string' => '#339922',
      'array' => '#4488dd',
      'number' => '#dd8844',
      'null' => '#8866ee',
      'bool' => '#8866ee',
      'resource' => '#8866ee',
      'object' => '#8866ee',
    );
    $this->options->colors = array_replace($defaultColors, is_array($colors) ? $colors : array());
  }

  /**
   * Set html mode on or off to allow html output or escape html output
   *
   * @param bool $bool
   */
  public function setHtmlMode($bool = true)
  {
    $this->options->htmlMode = (bool)$bool;
  }

  /**
   * Set flatten mode where arrays are flattened to a string
   *
   * @param bool $bool
   */
  public function setFlattenMode($bool = true)
  {
    $this->options->flattenMode = (bool)$bool;
  }

  /**
   * Set editor link format (editor://open/?file=%s&line=%s, txmt://open/?url=file://%s&line=%s)
   *
   * @param string $link
   */
  public function setEditorLink($link = 'editor://open/?file=%s&line=%s')
  {
    $this->options->editorLink = $link;
  }

  /**
   * Set return mode on or off to return result or default to display result
   *
   * @param bool $bool
   */
  public function setReturnMode($bool)
  {
    $this->options->returnMode = (bool)$bool;
  }

  /**
   * Set styling mode to be non-styled CLI mode (forced)
   */
  public function setStylingModeCli()
  {
    $this->options->stylingMode = false;
  }

  /**
   * Set styling mode automatically based on detected environment
   */
  public function setStylingModeDetect()
  {
    $this->options->stylingMode = false === (PHP_SAPI == 'cli');
  }

  /**
   * Display data dump with dump styling
   *
   * @return mixed
   */
  public function dump()
  {
    return $this->render('dump', func_get_args(), func_num_args());
  }

  /**
   * Display data dump with error styling
   *
   * @return mixed
   */
  public function error()
  {
    return $this->render('error', func_get_args(), func_num_args());
  }

  /**
   * Display data dump with info styling
   *
   * @return mixed
   */
  public function info()
  {
    return $this->render('info', func_get_args(), func_num_args());
  }

  /**
   * Display php error with error styling
   *
   * @param integer $type
   * @param string $message
   * @param string $file
   * @param integer $line
   *
   * @return mixed
   */
  public function dumpPhpError($type, $message, $file, $line)
  {
    $options = clone($this->options);
    $typeString = !is_numeric($type) ? $type : str_replace('E_', '', array_search($type, get_defined_constants(), true));
    if (false == $this->options->stylingMode) {
      $message = sprintf('%s: %s in %s on line %s', $typeString, $message, $file, $line);
    } else {
      $this->setHtmlMode(true);
      $message = sprintf("<strong>%s</strong>: <span style=\"font-style:italic;\">%s</span> in <strong>{{%s}}</strong> on line <strong>%s</strong>", $typeString, $message, $file, $line);
      $message = $this->replaceEditorLink($message, $file, $line);
    }
    $this->setFlattenMode(true);
    $result = $this->render('error', $message, 1);
    $this->options = $options;
    return $result;
  }

  /**
   * Display exception using error styling
   *
   * @param \Exception $e
   *
   * @return mixed
   */
  public function dumpPhpException(\Exception $e)
  {
    $options = clone($this->options);
    if (false == $this->options->stylingMode) {
      $message = sprintf("%s with code %s in %s on line %s \n%s", get_class($e), $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
    } else {
      $this->setHtmlMode(true);
      $message = sprintf("<strong>%s</strong> with code <strong>%s</strong> in <strong>{{%s}}</strong> on line <strong>%s</strong> \n\n<span style=\"font-style:italic;\">%s</span>", get_class($e), $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
      $message = $this->replaceEditorLink($message, $e->getFile(), $e->getLine());
    }
    $this->setFlattenMode(true);
    $result = $this->render('error', $message);
    $this->options = $options;
    return $result;
  }

  private function replaceEditorLink($message, $file, $line)
  {
    if ($this->options->editorLink) {
      $format = "<a style=\"" . $this->getStyle('a') . "\" href=\"" . sprintf($this->options->editorLink, $file, $line) . "\">%s</a>";
      return str_replace('{{' . $file . '}}', sprintf($format, $file), $message);
    } else {
      return $message;
    }
  }

  /**
   * Get style by name and prepare for inlining
   *
   * @param string $name
   *
   * @return string
   */
  private function getStyle($name)
  {
    $styles = $this->getStyles();
    if (isset($styles[$name])) {
      return preg_replace('/\r\n|\r|\n|\t| {2,}/', '', $styles[$name]);
    } else {
      return '';
    }
  }

  /**
   * Render
   *
   * @param string $classname
   * @param mixed $args
   * @param int $numargs
   *
   * @return string
   */
  protected function render($classname, $args, $numargs = 1)
  {
    if ($numargs == 1 && is_array($args)) {
      $args = array_shift($args);
    }

    // flatten array to string
    if (is_array($args) && $this->options->flattenMode) {
      $args = implode("\n", $args);
    }

    /*
    if (is_string($args)) {
      $s = $args;
    } else {
      ob_start();
      @debug_zval_dump($args);
      $s = ob_get_contents();
      ob_end_clean();
    }
    */

    ob_start();
    @debug_zval_dump($args);
    $s = ob_get_contents();
    ob_end_clean();

    $s = htmlentities($s, ENT_COMPAT);
    //$s = mb_convert_encoding($s, 'HTML-ENTITIES', 'UTF-8');

    $s = str_replace(array("\r\n", "\r"), "\n", $s);
    $lines = explode("\n", trim($s));
    $s = '';
    $eol = "\n";

    foreach ($lines as $line) {

      if ($eol == '') {
        $line = ' ' . ltrim($line);
      }

      $eol = "\n";

      if (substr($line, -5, 5) == '=&gt;') {
        $eol = '';
        $line = substr($line, 0, mb_strlen($line) - 5);
        $line .= ' =>';
      }

      $line = $this->colorize($line);
      $line = rtrim($line);

      $line = preg_replace('/ refcount\(\d+\)(\{)?$/', ' $1', $line);

      // skip empty lines
      if ($line == '') {
        continue;
      }

      $s .= $line;
      $s .= $eol;
    }

    // replace class="c_" with colors in options
    foreach ($this->options->colors as $id => $color) {
      if ($this->options->stylingMode) {
        $s = str_replace('<span class="c_' . $id . '">', '<span style="color:' . $color . '">', $s);
        $s = str_replace('</span_c>', '</span>', $s);
      } else {
        // cli mode
        $s = str_replace('<span class="c_' . $id . '">', '', $s);
        $s = str_replace('</span_c>', '', $s);
      }
    }

    if ($this->options->htmlMode) {
      $s = html_entity_decode($s, ENT_COMPAT);
    }

    if ($this->options->stylingMode) {
      if (mb_substr(trim($s), 0, 1) != '<') {
        $s = '<span style="' . $this->getStyle('string-container') . '">' . $s . '</span>';
      }
    }

    $html = '<div style="' . $this->getStyle('clearfix') . '"></div>';
    $html .= '<div style="' . $this->getStyle('wrapper') . '">';
    $html .= '<div style="' . $this->getStyle('container') . '">';
    $html .= '<pre style="' . $this->getStyle('pre') . $this->getStyle('pre-' . $classname) . '">' . $s . '</pre>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div style="' . $this->getStyle('clearfix') . '"></div>';

    $header = "<!-- Vardump::" . $classname . " -->";

    if ($this->options->stylingMode) {
      $return = $header . $html . $header . "\n";
    } else {
      // cli mode
      $s = html_entity_decode($s, ENT_COMPAT);
      $return = $s . "";
    }

    if (false == $this->options->returnMode) {
      echo $return;
    }

    return $return;
  }

  protected function colorize($line)
  {
    // null
    $line = str_replace('NULL refcount(', '<span class="c_null">null</span_c> refcount(', $line);

    // bool(true|false)
    $line = preg_replace('/bool\((true|false)\) refcount\(/', '<span class="c_type">bool</span_c> <span class="c_bool">($1)</span_c> refcount(', $line);

    // key
    $line = preg_replace_callback(
      '/^([\s]+)\[(.*)\] \=\>/',
      function ($match) {
        $result = $match[1];
        $parts = explode(':', $match[2]);
        $parts[0] = preg_replace('/^\&quot\;/', '', $parts[0]);
        $parts[0] = preg_replace('/\&quot\;$/', '', $parts[0]);

        $result .= '<span class="c_key">' . $parts[0] . '</span_c> ';
        $result .= '<span class="c_size">=&gt;</span_c> ';
        if (isset($parts[2])) { // access
          $result .= '<span class="c_size">(' . $parts[2] . ')</span_c>';
        }
        return $result;
      },
      $line
    );

    // string
    $line = preg_replace('/string\((\d+)\) /', '<span class="c_type">string</span_c> <span class="c_size">($1)</span_c> <span class="c_string">', $line);

    // number
    $line = preg_replace('/(long|double)\(([\d\.]+)\) /', '<span class="c_type">$1</span_c> <span class="c_number">$2</span_c> ', $line);

    // array|resource
    $line = preg_replace('/(array|resource)\((\d+)\) /', '<span class="c_type">$1</span_c> <span class="c_size">($2)</span_c> ', $line);

    // of type (?)
    $line = preg_replace('/ of type \(([a-z]+)\)/i', '<span class="c_type"> of type</span_c> <span class="c_resource">($1)</span_c> ', $line);

    // object(Framework\DependencyInjection\Container)#3 (5) refcount(3){
    $line = preg_replace(
      '/object\((.*)\)\#\d+ \((\d+)\) refcount\(/i',
      '<span class="c_type">object</span_c> <span class="c_object">($1)</span_c> <span class="c_size">($2)</span_c> refcount(',
      $line
    );

    // recursion
    if (trim($line) == '*RECURSION*') {
      $line = '<span class="c_size"> *RECURSION*</span_c>';
    }

    // string end
    $line = str_replace('&quot; refcount(', '&quot;</span_c> refcount(', $line);

    return $line;
  }

  /**
   * Get styles
   * @return string
   */
  private function getStyles()
  {
    $fontstyle = "
            font-size:10pt;
            font-family:
                'Liberation Mono',
                'Literation Mono Powerline',
                'DejaVu Sans Mono',
                'DejaVu Sans Mono for Powerline',
                'Droid Sans Mono',
                'Droid Sans Mono for Powerline',
                'Bitstream Vera Sans Mono',
                menlo,
                consolas,
                monospace;
        ";
    return array(
      'clearfix' => '
                clear: both;
                float: none;
                height: 1px;
                width: 1px;
            ',
      'wrapper' => '
                border-radius: 7px;
                padding: 2px;
                float: left;
                clear: both;
                background-color: rgba(190,190,190,0.1);
            ',
      'container' => "
                border-radius: 5px;
                color: #666666;
                margin: 0;
                padding: 1px;
                background-color: #d0d0d0;
                background: linear-gradient(to bottom,  rgba(210,210,210,0.8) 0%,rgba(90,90,90,0.3) 100%);
                width: auto;
                float: left;
                clear: none;
                overflow: hidden;
            ",
      'a' => "
                $fontstyle
                color: #336677;
                text-decoration: none;
            ",
      'string-container' => 'color: #666666;',
      'pre' => "
                $fontstyle
                border-radius: 5px;
                color: #aaaaaa;
                white-space: pre-wrap;
                word-wrap: normal;
                word-break: break-none;
                display: block;
                width: auto;
                float: left;
                overflow: visible;
                background-color: #eeeeee;
                margin: 0;
                padding: 8px;
                padding-left: 9px;
                padding-right: 9px;
            ",
      'pre-dump' => '
                background-color: rgb(240,240,240) !important;
                background: linear-gradient(to bottom, rgba(220,220,220,0.5) 0%,rgba(120,120,120,0.2) 100%);
            ',
      'pre-info' => '
                background-color: rgb(240,240,200) !important;
                background: linear-gradient(to bottom,  rgba(230,230,230,0.6) 0%,rgba(160,90,30,0.2) 100%);
            ',
      'pre-error' => '
                background-color: rgb(240,200,200) !important;
                background: linear-gradient(to bottom, rgba(230,230,230,0.5) 0%,rgba(90,120,90,0.2) 100%);
            '
    );
  }
}