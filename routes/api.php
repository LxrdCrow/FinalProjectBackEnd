<?php

// Routes for API requests
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');
$router->get('/protected', 'AuthController@protectedRoute');
$router->put('/profile', 'AuthController@updateProfile');

// Routes for posts 
$router->post('/posts', 'PostController@createPost');
$router->get('/posts', 'PostController@getPosts');
$router->put('/posts', 'PostController@updatePost');
$router->delete('/posts', 'PostController@deletePost');

// Routes for friends
$router->post('/friends', 'FriendController@addFriend');
$router->delete('/friends', 'FriendController@removeFriend');
$router->get('/friends', 'FriendController@getFriends');

// Routes for comments
$router->post('/comments', 'CommentController@createComment');
$router->get('/comments', 'CommentController@getComments');
$router->put('/comments', 'CommentController@updateComment');
$router->delete('/comments', 'CommentController@deleteComment');



?>
