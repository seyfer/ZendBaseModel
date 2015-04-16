<?php

namespace ZendBaseModel;

use Doctrine\Common\Util\Debug as DoctrineDebug;

/**
 * Description of Debug
 *
 * @author seyfer
 */
class Debug
{

    public static $stripTags = FALSE;

    /**
     * @return boolean
     */
    public static function isStripTags()
    {
        return self::$stripTags;
    }

    /**
     * @param boolean $stripTags
     * @return $this
     */
    public static function setStripTags($stripTags)
    {
        self::$stripTags = $stripTags;
    }

    /**
     * wrapper for smarter debug
     *
     * @param      $var
     * @param int  $maxDepth
     * @param bool $stripTags
     */
    public static function dump($var, $maxDepth = 3, $stripTags = null)
    {
        if (!self::checkDevMode()) {
            return;
        }

        if ($stripTags === null) {
            $stripTags = self::isStripTags();
        }

        ob_start();
        DoctrineDebug::dump($var, $maxDepth, $stripTags);
        $dump = ob_get_clean();

        if (php_sapi_name() === 'cli') {
            echo $dump;
        } else {
            echo '<pre>';
            echo $dump;
            echo '</pre>';
        }
    }

    /**
     * many
     */
    public static function vars()
    {
        if (!self::checkDevMode()) {
            return;
        }

        $vars = func_get_args();

        foreach ($vars as $var) {
            self::dump($var);
        }

    }

    /**
     * Constant for safety use in production
     *
     * @return bool
     */
    private static function checkDevMode()
    {
        if (defined('DEVELOPMENT_MODE')) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Returns an HTML string, highlighting a specific line of a file, with some
     * number of lines padded above and below.
     *
     *     // Highlights the current line of the current file
     *     echo Debug::source(__FILE__, __LINE__);
     *
     * @param   string  $file        file to open
     * @param   integer $line_number line number to highlight
     * @param   integer $padding     number of padding lines
     * @return  string   source of file
     * @return  FALSE    file is unreadable
     */
    public static function source($file, $line_number, $padding = 5)
    {
        if (!$file OR !is_readable($file)) {
            // Continuing will cause errors
            return FALSE;
        }

        // Open the file and set the line position
        $file = fopen($file, 'r');
        $line = 0;

        // Set the reading range
        $range = ['start' => $line_number - $padding, 'end' => $line_number + $padding];

        // Set the zero-padding amount for line numbers
        $format = '% ' . strlen($range['end']) . 'd';

        $source = '';
        while (($row = fgets($file)) !== FALSE) {
            // Increment the line number
            if (++$line > $range['end'])
                break;

            if ($line >= $range['start']) {
                // Make the row safe for output
                $row = htmlspecialchars($row, ENT_NOQUOTES, 'UTF-8');

                // Trim whitespace and sanitize the row
                $row = '<span class="number">' . sprintf($format, $line) . '</span> ' . $row;

                if ($line === $line_number) {
                    // Apply highlighting to this row
                    $row = '<span class="line highlight">' . $row . '</span>';
                } else {
                    $row = '<span class="line">' . $row . '</span>';
                }

                // Add to the captured source
                $source .= $row;
            }
        }

        // Close the file
        fclose($file);

        return '<pre class="source"><code>' . $source . '</code></pre>';
    }

    /**
     * Returns an array of HTML strings that represent each step in the backtrace.
     *
     *     // Displays the entire current backtrace
     *     echo implode('<br/>', Debug::trace());
     *
     * @param   array $trace
     * @return  string
     */
    public static function trace(array $trace = NULL)
    {
        if ($trace === NULL) {
            // Start a new trace
            $trace = debug_backtrace();
        }

        // Non-standard function calls
        $statements = ['include', 'include_once', 'require', 'require_once'];

        $output = [];
        foreach ($trace as $step) {
            if (!isset($step['function'])) {
                // Invalid trace step
                continue;
            }

            if (isset($step['file']) AND isset($step['line'])) {
                // Include the source of this step
                $source = Debug::source($step['file'], $step['line']);
            }

            if (isset($step['file'])) {
                $file = $step['file'];

                if (isset($step['line'])) {
                    $line = $step['line'];
                }
            }

            // function()
            $function = $step['function'];

            if (in_array($step['function'], $statements)) {
                if (empty($step['args'])) {
                    // No arguments
                    $args = [];
                } else {
                    // Sanitize the file path
                    $args = [$step['args'][0]];
                }
            } elseif (isset($step['args'])) {
                if (!function_exists($step['function']) OR strpos($step['function'], '{closure}') !== FALSE) {
                    // Introspection on closures or language constructs in a stack trace is impossible
                    $params = NULL;
                } else {
                    if (isset($step['class'])) {
                        if (method_exists($step['class'], $step['function'])) {
                            $reflection = new \ReflectionMethod($step['class'], $step['function']);
                        } else {
                            $reflection = new \ReflectionMethod($step['class'], '__call');
                        }
                    } else {
                        $reflection = new \ReflectionFunction($step['function']);
                    }

                    // Get the function parameters
                    $params = $reflection->getParameters();
                }

                $args = [];

                foreach ($step['args'] as $i => $arg) {
                    if (isset($params[$i])) {
                        // Assign the argument by the parameter name
                        $args[$params[$i]->name] = $arg;
                    } else {
                        // Assign the argument by number
                        $args[$i] = $arg;
                    }
                }
            }

            if (isset($step['class'])) {
                // Class->method() or Class::method()
                $function = $step['class'] . $step['type'] . $step['function'];
            }

            $output[] = [
                'function' => $function,
                'args'     => isset($args) ? $args : NULL,
                'file'     => isset($file) ? $file : NULL,
                'line'     => isset($line) ? $line : NULL,
                'source'   => isset($source) ? $source : NULL,
            ];

            unset($function, $args, $file, $line, $source);
        }

        return $output;
    }


}
