<?php

namespace Opera\CoreBundle\Routing;

use Symfony\Component\Routing\Route;

class RoutingUtils
{
    private static function getCompiledRoute(string $regexpStr)
    {
        $route = new Route($regexpStr.'/');
        
        return $route->compile();
    }

    public static function convertPathToRegexp(string $regexpStr) : string
    {
        $regex = self::getCompiledRoute($regexpStr)->getRegex();

        // Support trailing slash
        if ($pos = strpos($regex, '/$')) {
            $regex = substr($regex, 0, $pos).'/?$'.substr($regex, $pos + 2);
        }
        
        return $regex;
    }

    public static function getRouteVariables(string $regexpStr, string $pathInfo) : array
    {
        $route = self::getCompiledRoute($regexpStr);
        $variables = [];

        preg_match(self::convertPathToRegexp($regexpStr), $pathInfo, $matches);

        foreach ($route->getVariables() as $var) {
            $variables[$var] = $matches[$var];
        }

        return $variables;
    }
}