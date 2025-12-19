<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Mvc;

use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Mvc\Url\Exception;
use Phalcon\Mvc\Url\UrlInterface;

use function http_build_query;
use function is_array;
use function is_string;
use function preg_match;
use function preg_replace;
use function strlen;

/**
 * This component helps in the generation of: URIs, URLs and Paths
 *
 *```php
 * // Generate a URL appending the URI to the base URI
 * echo $url->get("products/edit/1");
 *
 * // Generate a URL for a predefined route
 * echo $url->get(
 *     [
 *         "for"   => "blog-post",
 *         "title" => "some-cool-stuff",
 *         "year"  => "2012",
 *     ]
 * );
 *```
 */
class Url extends AbstractInjectionAware implements UrlInterface
{
    /**
     * @var string|null
     */
    protected string | null $basePath = null;
    /**
     * @var string|null
     */
    protected string | null $baseUri = null;
    /**
     * @var string|null
     */
    protected string | null $staticBaseUri = null;

    public function __construct(
        protected RouterInterface | null $router = null
    ) {
    }

    /**
     * Generates a URL
     *
     *```php
     * // Generate a URL appending the URI to the base URI
     * echo $url->get("products/edit/1");
     *
     * // Generate a URL for a predefined route
     * echo $url->get(
     *     [
     *         "for"   => "blog-post",
     *         "title" => "some-cool-stuff",
     *         "year"  => "2015",
     *     ]
     * );
     *
     * // Generate a URL with GET arguments (/show/products?id=1&name=Carrots)
     * echo $url->get(
     *     "show/products",
     *     [
     *         "id"   => 1,
     *         "name" => "Carrots",
     *     ]
     * );
     *
     * // Generate an absolute URL by setting the third parameter as false.
     * echo $url->get(
     *     "https://phalcon.io/",
     *     null,
     *     false
     * );
     *```
     *
     * @param array|string|null $uri = [
     *                               'for' => '',
     *                               ]
     * @param mixed|null        $arguments
     * @param bool|null         $local
     * @param mixed|null        $baseUri
     *
     * @return string
     * @throws Exception
     */
    public function get(
        array | string | null $uri = null,
        mixed $arguments = null,
        ?bool $local = null,
        mixed $baseUri = null
    ): string {
        if (null === $local) {
            if (
                is_string($uri) &&
                (str_contains($uri, "//") || str_contains($uri, ":"))
            ) {
                if (preg_match("#^((//)|([a-z0-9]+://)|([a-z0-9]+:))#i", $uri)) {
                    $local = false;
                } else {
                    $local = true;
                }
            } else {
                $local = true;
            }
        }

        if (!is_string($baseUri)) {
            $baseUri = $this->getBaseUri();
        }

        if (is_array($uri)) {
            if (!isset($uri["for"])) {
                throw new Exception(
                    "It's necessary to define the route name with the parameter 'for'"
                );
            }

            $routeName = $uri["for"];

            /**
             * Check if the router has not previously set
             */
            if (null === $this->router) {
                if (null === $this->container) {
                    throw new Exception(
                        "A dependency injection container is "
                        . "required to access the 'router' service"
                    );
                }

                if (true !== $this->container->has("router")) {
                    throw new Exception(
                        "A dependency injection container is "
                        . "required to access the 'router' service"
                    );
                }

                $this->router = $this->container->getShared("router");
            }

            /**
             * Every route is uniquely identified by a name
             */
            $route = $this->router->getRouteByName($routeName);

            if (false === $route) {
                throw new Exception(
                    "Cannot obtain a route using the name '" . $routeName . "'"
                );
            }

            /**
             * Replace the patterns by its variables
             */
            $uri = $this->replacePaths(
                $route->getPattern(),
                $route->getReversedPaths(),
                $uri
            );
        }

        if (true === $local) {
            $strUri = (string)$uri;
            $uri    = preg_replace(
                "#(?<!:)//+#",
                "/",
                $baseUri . $strUri
            );
        }

        if ($arguments) {
            $queryString = http_build_query($arguments);

            if (strlen($queryString)) {
                if (str_contains($uri, "?")) {
                    $uri .= "&" . $queryString;
                } else {
                    $uri .= "?" . $queryString;
                }
            }
        }

        return $uri;
    }

    /**
     * Returns the base path
     *
     * @return string|null
     */
    public function getBasePath(): string | null
    {
        return $this->basePath;
    }

    /**
     * Returns the prefix for all the generated urls. By default, /
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        if (null === $this->baseUri) {
            if (isset($_SERVER["PHP_SELF"])) {
                $uri = $this->getUri($_SERVER["PHP_SELF"]);
            } else {
                $uri = null;
            }

            if (null !== $uri) {
                $baseUri = "/";
            } else {
                $baseUri = "/" . $uri . "/";
            }

            $this->baseUri = $baseUri;
        }

        return $this->baseUri;
    }

    /**
     * Generates a URL for a static resource
     *
     *```php
     * // Generate a URL for a static resource
     * echo $url->getStatic("img/logo.png");
     *
     * // Generate a URL for a static predefined route
     * echo $url->getStatic(
     *     [
     *         "for" => "logo-cdn",
     *     ]
     * );
     *```
     *
     * @param array|string|null $uri = [
     *                               'for' => ''
     *                               ]
     *
     * @return string
     * @throws Exception
     */
    public function getStatic(array | string | null $uri = null): string
    {
        return $this->get(
            $uri,
            null,
            null,
            $this->getStaticBaseUri()
        );
    }

    /**
     * Returns the prefix for all the generated static urls. By default, /
     *
     * @return string
     */
    public function getStaticBaseUri(): string
    {
        if (null !== $this->staticBaseUri) {
            return $this->staticBaseUri;
        }

        return $this->getBaseUri();
    }

    /**
     * Generates a local path
     *
     * @param string|null $path
     *
     * @return string
     */
    public function path(string | null $path = null): string
    {
        return $this->basePath . $path;
    }

    /**
     * Sets a base path for all the generated paths
     *
     *```php
     * $url->setBasePath("/var/www/htdocs/");
     *```
     *
     * @param string $basePath
     *
     * @return UrlInterface
     */
    public function setBasePath(string $basePath): UrlInterface
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * Sets a prefix for all the URIs to be generated
     *
     *```php
     * $url->setBaseUri("/invo/");
     *
     * $url->setBaseUri("/invo/index.php/");
     *```
     *
     * @param string $baseUri
     *
     * @return UrlInterface
     */
    public function setBaseUri(string $baseUri): UrlInterface
    {
        $this->baseUri = $baseUri;

        if (null === $this->staticBaseUri) {
            $this->staticBaseUri = $baseUri;
        }

        return $this;
    }

    /**
     * Sets a prefix for all static URLs generated
     *
     *```php
     * $url->setStaticBaseUri("/invo/");
     *```
     *
     * @param string $staticBaseUri
     *
     * @return UrlInterface
     */
    public function setStaticBaseUri(string $staticBaseUri): UrlInterface
    {
        $this->staticBaseUri = $staticBaseUri;

        return $this;
    }

    /**
     * Extract the part of a path that lies between the last two
     * directory separators.
     *
     * Walk the string backwards. Find the last separator ("/" or "\\"), then
     * the one before that and return the string between them.
     *
     * @param string $path
     *
     * @return string
     */
    private function getUri(string $path): string
    {
        if (true === empty($path)) {
            return '';
        }

        $length     = strlen($path);
        $separators = 0;
        $position   = 0;

        /**
         * Walk backwards
         */
        for ($counter = $length - 1; $counter >= 0; --$counter) {
            $character = $path[$counter];
            /**
             * Find a separator
             */
            if ($character === '/' || $character === '\\') {
                ++$separators;
                /**
                 * Found, store it
                 */
                if ($separators === 1) {
                    $position = $counter;
                } else {
                    /**
                     * Found the second one, return the string in between
                     */
                    $start  = $counter + 1;
                    $length = $position - $counter - 1;

                    return substr($path, $start, $length);
                }
            }
        }

        /**
         * Default
         */
        return '';
    }


    /**
     * Resolve a placeholder found in a pattern.
     *
     * @param bool    $named
     * @param array   $paths
     * @param array   $replacements
     * @param int    &$position
     * @param string  $cursor
     * @param int     $markerPosition
     *
     * @return string|null
     */
    private function replaceMarker(
        bool $named,
        array $paths,
        array $replacements,
        int &$position,
        string $cursor,
        int $markerPosition
    ): ?string {
        /**
         * Named placeholder
         */
        if (true === $named) {
            /**
             * The placeholder runs from $markerPosition (first char after
             * "{” or “(”) up to the character just before the closing
             * brace/parenthesis.
             *
             * The length is computed as:
             *     length = cursor - marker - 1
             *
             * $cursor is the full pattern, $markerPosition is the start offset,
             * and we already know the current parsing index ($cursorIdx) from
             * the caller – but the caller passes us the *current* cursor
             * pointer, so we can compute the length by scanning forward until
             * we hit a non‑valid char or a ':' that separates a variable name.
             *
             * For simplicity we rely on the fact that the driver already knows
             * the exact substring that belongs to the placeholder (it stops
             * when the closing brace/parenthesis is encountered). Therefore we
             * can extract the raw text between the braces directly from the
             * original pattern.
             *
             * The driver supplies $markerPosition as the offset of the
             * opening brace/parenthesis.  The closing position is the current
             * parsing index ($cursorIdx) which the driver does not expose;
             * however, the driver only calls this function *after* it has
             * verified that the placeholder is syntactically correct, so we
             * can safely take the substring from $markerPosition + 1 up to the
             * next non‑identifier character.
             */
            $raw = substr($cursor, $markerPosition + 1);

            /**
             * Find the first character that terminates the identifier:
             *     - end of string
             *     - a character that is not a‑z, A‑Z, 0‑9, '-', '_' or ':'
             */
            if (preg_match('/^([a-zA-Z][a-zA-Z0-9\-\_:]*)/', $raw, $matches)) {
                $identifier = $matches[1];
            } else {
                /**
                 * Invalid identifier. Advance the position
                 */
                ++$position;

                return null;
            }

            /**
             * If the identifier contains a colon, split it (e.g. "var:sub")
             */
            if (str_contains($identifier, ':')) {
                [$identifier] = explode(':', $identifier, 2);
            }

            /**
             * Look up the identifier
             */
            if (array_key_exists($identifier, $replacements)) {
                ++$position;

                return (string)$replacements[$identifier];
            }

            /**
             * Not found - advance position
             */
            ++$position;

            return null;
        }

        /**
         * Positional placeholder. Position is 1 based, PHP arrays are 0 based
         */
        $idx = $position - 1;

        if (array_key_exists($idx, $paths)) {
            $value = $paths[$idx];

            /**
             * Replace when the value is a string and it exists in the
             * $replacements array.
             */
            if (is_string($value) && array_key_exists($value, $replacements)) {
                ++$position;

                return (string)$replacements[$value];
            }
        }

        /**
         * No match – advance position
         */
        ++$position;

        return null;
    }

    /**
     * Convert a routing pattern by replacing placeholders with values from
     * $paths and $replacements.
     *
     * @param string $pattern
     * @param array  $paths
     * @param array  $replacements
     *
     * @return string|false|null
     */
    private function replacePaths(string $pattern, array $paths, array $replacements)
    {
        if (true === empty($pattern)) {
            return false;
        }

        $counter            = 0;          // index inside $pattern
        $position           = 1;          // 1‑based placeholder position
        $bracketCount       = 0;          // curly‑brace nesting level
        $parenCount         = 0;          // parentheses nesting level
        $intermediate       = 0;          // length of current placeholder content
        $lookingPlaceholder = false;      // true when we are inside a ":name" placeholder
        $markerPosition     = null;       // start offset of the current placeholder
        $result             = '';         // builds the final string

        // Skip leading slash
        if ($pattern[0] === '/') {
            $counter = 1;
        }

        /**
         * Empty array - return without leading slash
         */
        if (empty($paths)) {
            return substr($pattern, $counter);
        }

        $length = strlen($pattern);

        for (; $counter < $length; ++$counter) {
            $character = $pattern[$counter];

            /**
             * Curly braces
             */
            if ($parenCount === 0 && !$lookingPlaceholder) {
                if ($character === '{') {
                    if ($bracketCount === 0) {
                        $markerPosition = $counter;
                        $intermediate   = 0;
                    }
                    ++$bracketCount;
                } elseif ($character === '}') {
                    --$bracketCount;
                    if ($intermediate > 0 && $bracketCount === 0) {
                        /**
                         * Replace the named placeholder
                         */
                        $replacement = $this->replaceMarker(
                            true,
                            $paths,
                            $replacements,
                            $position,
                            $pattern,
                            $markerPosition
                        );
                        if ($replacement !== null) {
                            $result .= $replacement;
                        }

                        continue;
                    }
                }
            }

            /**
             * Parentheses
             */
            if ($bracketCount === 0 && !$lookingPlaceholder) {
                if ($character === '(') {
                    if ($parenCount === 0) {
                        $markerPosition = $counter;
                        $intermediate   = 0;
                    }
                    ++$parenCount;
                } elseif ($character === ')') {
                    --$parenCount;
                    if ($intermediate > 0 && $parenCount === 0) {
                        /**
                         * Replace the unnamed placeholder
                         */
                        $replacement = $this->replaceMarker(
                            false,
                            $paths,
                            $replacements,
                            $position,
                            $pattern,
                            $markerPosition
                        );
                        if ($replacement !== null) {
                            $result .= $replacement;
                        }

                        continue;
                    }
                }
            }

            /**
             * Colon
             */
            if ($bracketCount === 0 && $parenCount === 0) {
                if ($lookingPlaceholder) {
                    if ($intermediate > 0) {
                        // End of placeholder when we encounter a non‑letter or EOS
                        $isEnd = ($character < 'a' || $character > 'z' || $counter === $length - 1);
                        if ($isEnd) {
                            /**
                             * Replace the unnamed placeholder
                             */
                            $replacement = $this->replaceMarker(
                                false,
                                $paths,
                                $replacements,
                                $position,
                                $pattern,
                                $markerPosition
                            );
                            if ($replacement !== null) {
                                $result .= $replacement;
                            }
                            $lookingPlaceholder = false;

                            continue;
                        }
                    }
                } else {
                    if ($character === ':') {
                        $lookingPlaceholder = true;
                        $markerPosition     = $counter;
                        $intermediate       = 0;

                        continue;
                    }
                }
            }

            /**
             * Character count of the placeholder
             */
            if ($bracketCount > 0 || $parenCount > 0 || $lookingPlaceholder) {
                ++$intermediate;
            } else {
                $result .= $character;
            }
        }

        return $result;
    }
}
