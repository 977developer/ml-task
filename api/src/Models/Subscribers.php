<?php

namespace App\Models;

use App\Core\Db;
use App\Core\Cache;
use PDO;

class Subscribers
{
    const TABLE  = 'subscribers';
    const FIELDS = ['email', 'firstName', 'lastName', 'status'];
    const ENTRIES_PER_PAGE = 10;
    
    /**
     * Find subscribers and paginate the result
     *
     * @param  int $currentPage
     * @return array $subscribers
     */
    public static function paginate(int $currentPage): array
    {
        if ($currentPage === 1) {
            $from = 0;
        } else {
            $from = ($currentPage * self::ENTRIES_PER_PAGE) - self::ENTRIES_PER_PAGE;
        }

        $query = Db::getInstance()
            ->prepare(
                'SELECT 
				    *,
				    (SELECT 
				            COUNT(*)
				        FROM
				            ' . self::TABLE . ' USE INDEX (PRIMARY)) AS total
				FROM
				    ' . self::TABLE . ' USE INDEX (PRIMARY)
                ORDER BY id DESC
				LIMIT :from, :to'
            );

        $query->bindValue(':from', (int) $from, PDO::PARAM_INT);
        $query->bindValue(':to', (int) self::ENTRIES_PER_PAGE, PDO::PARAM_INT);
        $query->execute();

        $subscribers = [
            'count'          => 0,
            'currentPage'    => 1,
            'totalPages'     => 0,
            'entriesPerPage' => self::ENTRIES_PER_PAGE,
            'data'           => []
        ];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $subscribers['count'] = $row['total'];
            unset($row['total']);
            $subscribers['data'][] = $row;
        }

        $subscribers['totalPages']  = ceil($subscribers['count'] / $subscribers['entriesPerPage']);
        $subscribers['currentPage'] = $currentPage;

        return $subscribers;
    }

    
    /**
     * Find subscriber by email
     *
     * This will first try to find the email from cache and return it
     * If it does not exist in Cache, it will query the db and set to cache
     * Finally return the result
     *
     * @param  string $email
     * @return array|bool
     */
    public static function findByEmail(string $email): array|bool
    {
        $cache = Cache::getInstance();
        $key   = $cache->getKey($email);
        $dataFromCache = $cache->getHashFromCache($key, self::FIELDS);

        if ($dataFromCache) {
            return $dataFromCache;
        }

        $query = Db::getInstance()
            ->prepare(
                'SELECT * FROM '. self::TABLE.' WHERE email = :email'
            );

        $query->bindValue(':email', $email);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $cache->setHashToCache($key, $result);
        }

        return $result;
    }
    
    /**
     * Insert the subscriber inside Redis Cache
     *
     * @param  array $data
     * @return void
     */
    public static function insertCache(array $data): void
    {
        $cache  = Cache::getInstance();
        $key    = $cache->getKey($data['email']);
        $exists = $cache->redis->exists($key);

        if ($exists) {
            throw new \Exception('Duplicate entry');
        }

        $cache->setHashToCache($key, $data);
    }
    
    /**
     * Insert data to Mysql
     *
     * @param  array $data
     * @return void
     */
    public static function insertDB($data, $kill = true): void
    {
        $insert = Db::getInstance()
            ->prepare(
                'INSERT INTO `'. self::TABLE.'` 
             (`firstName`, `lastName`, `email`, `status`) 
             VALUES (?, ?, ?, ?)'
            );
        
        $insert->execute([
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['status']
        ]);

        if ($kill) {
            die;
        }
    }
}
