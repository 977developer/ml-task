<?php

namespace App\Core;

use Exception;
use App\Core\Helper;

/**
 * Request Class For Parsing HTTP request body
 */
class Request
{

    /**
     * Parsed Input
     *
     * @var mixed
     */
    private $input;

    /**
     * Request Url
     *
     * @var string
     */
    private $url;
        
    /**
     * Request method
     *
     * @var string
     */
    private $method;
    
    /**
     * Parse the incoming request and fill class properties
     *
     * @return void
     */
    public function __construct()
    {
        $this->input  = json_decode(file_get_contents("php://input"));
        $this->url    = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
    }
    
    /**
     * Get value from request
     *
     * @param  mixed $data
     * @return string|array
     */
    public function get($data = null): string|array
    {
        if ($data) {
            return $this->input->$data;
        }

        return (array)$this->input;
    }

    /**
     * Set input from request
     *
     * @param  string $key
     * @param  string $value
     * @return void
     */
    public function set($key, $value): void
    {
        if (!$this->input) {
            $this->input = new \stdClass();
        }
        
        $this->input->$key = $value;
    }

    /**
     * Only return specific fields from Request
     *
     * @param  array $fields
     * @return array
     */
    public function only(array $fields): array
    {
        $filteredArray = [];

        foreach ($fields as $field) {
            if (!isset($this->input->$field)) {
                throw new Exception(sprintf(
                    'Error! field [%s] is required in your post body.',
                    $field
                ));
            }

            $filteredArray[$field] = $this->input->$field;
        }

        return $filteredArray;
    }

    /**
     * Get query param
     *
     * @param  string $field
     * @return string|null
     */
    public function query(string $field): ?string
    {
        $queryData = Helper::parseUrlQueryString($this->url);

        if (!isset($queryData[$field])) {
            return null;
        }
        return $queryData[$field];
    }
    
    /**
     * Returns request url
     *
     * @return string
     */
    public function url(): ?string
    {
        return $this->url;
    }

    /**
     * Returns request method
     *
     * @return string
     */
    public function method(): ?string
    {
        return $this->method;
    }
}
