<?php

namespace App\Core;

/**
 * Contains several helper methods
 */
class Helper
{

    /**
     * Parse a url and return its url path
     *
     * @param  string $url
     * @return string
     */
    public static function getUrlPath(string $url): string
    {
        if (!$url) {
            throw new \Exception('Invalid url');
        }
        $parsedUrl = parse_url($url);

        return $parsedUrl['path'];
    }
 
    /**
     * Parse a url and return an arry of useful info
     *
     * @param  string $url
     * @return array
     */
    public static function getUrlInfo(string $url): array
    {
        $urlPathElements = self::getUrlPathElements($url);

        if (!$urlPathElements) {
            return [
                'base' => '/',
                'length' => 0,
                'last' => null,
            ];
        }

        return [
            'base' => $urlPathElements[0],
            'length' => count($urlPathElements),
            'last' => $urlPathElements[count($urlPathElements) - 1],
        ];
    }

    /**
     * Split url path and return result as array
     *
     * @param  string $url
     * @return array
     */
    public static function getUrlPathElements(string $url): array
    {
        $parsedUrl = parse_url($url);
        
        return array_values(array_filter(explode('/', $parsedUrl['path'])));
    }

    /**
     * Determine if route contains wildcard value
     *
     * @param  array $routes
     * @return string|bool
     */
    public static function routeContainsWildCard(array $routes): string | bool
    {
        $matches = [];

        foreach ($routes as $key => $route) {
            $isWildCard = preg_match_all('/{(.*?)}/', $route, $matched);
            
            if ($isWildCard) {
                $matches[] = $matched;
            }
        }

        if (count($matches) > 0) {
            return $matches[0][1][0];
        }

        return false;
    }

    /**
     * Parse a url and return its url path
     *
     * @param  string $url
     * @return array|null
     */
    public static function parseUrlQueryString($url): ?array
    {
        $parsedUrl = parse_url($url);
        
        if (!isset($parsedUrl['query'])) {
            return null;
        }

        $items = explode(',', $parsedUrl['query']);
        $queryData = [];

        foreach ($items as $key => $item) {
            $split = explode('=', $item);
            $queryData[$split[0]] = $split[1];
        }

        return $queryData;
    }
}
