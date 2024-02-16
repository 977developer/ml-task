<?php

namespace App\Core;

use Redis;

/**
 * Class for caching database queries
 */
class Cache
{

    /**
     * Redis host
     *
     * @var string
     */
    private $host;
        
    /**
     * Redis port
     *
     * @var string
     */
    private $port;

    /**
     * Redis instance
     *
     * @var Redis
     */
    public $redis;

    /**
     * Instance of Cache
     *
     * @var Cache
     */
    private static $instance = null;
    
    /**
     * Set redis host, port and proceed to setup
     *
     * @return void
     */
    public function __construct()
    {

        $this->host = getenv('REDIS_HOST');
        $this->port = getenv('REDIS_PORT');
        
        $this->setup();
    }
    
    /**
     * Connect with Redis
     *
     * @return Cache
     */
    private function setup(): Cache
    {
        $this->redis = new Redis();
        $this->redis->connect($this->host, $this->port);

        return $this;
    }
    
    /**
     * Generate key for cache by email
     *
     * @param  string $email
     * @return string
     */
    public function getKey(string $email): string
    {
        return 'subscriber:' . md5($email);
    }
    
    /**
     * Get hash for redis cache, if it exists
     *
     * @param  string $key
     * @param  array $data
     * @return array|bool
     */
    public function getHashFromCache(string $key, array $data): array | bool
    {
        if ($this->redis->exists($key)) {
            return $this->redis->hMGet($key, $data);
        }
                
        return false;
    }
    
    /**
     * Set hash to cache
     *
     * @param  mixed $key
     * @param  mixed $data
     * @return void
     */
    public function setHashToCache(string $key, array $data): void
    {
        $this->redis->hMSet($key, $data);
    }

    /**
     * Get an instance of Cache
     *
     * @return Cache
     */
    public static function getInstance(): Cache
    {
        if (self::$instance == null) {
            self::$instance = new Cache();
        }
 
        return self::$instance->setup();
    }
}
