<?php

namespace Opera\CoreBundle\Routing;

use Symfony\Component\Routing\Route;

class RoutingUtils
{
    private static function getCompiledRoute(string $regexpStr, array $requirements = [])
    {
        dump($requirements);
        $route = new Route($regexpStr.'/', [], $requirements ?? []);

        return $route->compile();
    }

    public static function convertPathToRegexp(string $regexpStr, array $requirements = []) : string
    {
        $regex = self::getCompiledRoute($regexpStr, $requirements ?? [])->getRegex();

        // Support trailing slash
        if ($pos = strpos($regex, '/$')) {
            $regex = substr($regex, 0, $pos).'/?$'.substr($regex, $pos + 2);
        }
        
        return $regex;
    }

    public static function getRouteVariables(string $regexpStr, string $pathInfo, array $requirements = []) : array
    {
        $route = self::getCompiledRoute($regexpStr, $requirements ?? []);
        $variables = [];

        preg_match(self::convertPathToRegexp($regexpStr, $requirements ?? []), $pathInfo, $matches);

        foreach ($route->getVariables() as $var) {
            $variables[$var] = $matches[$var];
        }

        return $variables;
    }
}