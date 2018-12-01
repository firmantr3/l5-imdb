<?php 

/**
 * IMDB library configurations
 */

return [
    'library' => [
        /**
         * API URL
         */
        'imdbsite' => 'www.imdb.com',
    
        /**
         * Languages e.g 'de-DE,de,en'
         */
        'language' => 'en',
    
        /**
         * Cache Settings
         */
    
        'usecache' => 1,
    
        'storecache' => 1,
    
        'usezip' => 1,
    
        'converttozip' => 0,
        
        'cacheexpire' => 604800,
    
        /**
         * Image settings
         */
    
        'photodir' => storage_path('app/public/images'),
    
        'photoroot' => '/public/images',
    
        'imdb_img_url' => './imgs/',
    
        /**
         * Misc Settings
         */
    
        'debug' => env('IMDB_DEBUG', false),
    
        'throwHttpExceptions' => 1,
    
        /**
         * Proxy Settings
         */
    
        'use_proxy' => 0,
    
        'ip_address' => '',
    
        'proxy_host' => '',
    
        'proxy_port' => '',
    
        'proxy_user' => '',
        
        'proxy_pw' => '',
    
        /**
         * Tweaks
         */
    
        'default_agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0',
    
        'force_agent' => '',
    ],

    /**
     * Automatic data getter configurations. 
     * Automatically get all available data when all() method is called
     */
    'auto_get' => [
        /**
         * Get detailed data that cost the library to do multiple request (SLOW!)
         */
        'detail' => env('IMDB_GET_DETAIL', false),
        /**
         * Get some data that using unstable library API
         */
        'unstable' => env('IMDB_GET_UNSTABLE', false),
        /**
         * Get sound, photo, video, misc sites. (WARNING! EXTREMELY SLOW!!!)
         */
        'sites' => env('IMDB_GET_SITES', false),
    ],
];
