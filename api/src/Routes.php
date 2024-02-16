<?php

use App\Core\Router;

Router::get('subscribers', 'handleListSubscribers');
Router::get('subscribers/{email}', 'handleGetSubscriber');
Router::post('subscribers', 'handlePostSubscribers');
