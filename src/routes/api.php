<?php

use Illuminate\Http\Resources\Json\JsonResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('api')->namespace('Api\Auth')->name('api.')->group(function () {
    Route::post('/login', 'AuthController@login')->name('login');
    Route::post('/register', 'AuthController@register')->name('register');
    Route::post('/refresh', 'AuthController@refresh')->name('refresh');
    Route::get('/routes', function () {
        $routes = [
            'api/login' => [
                'method' => 'POST',
                'description' => 'API login',
                'params' => [
                    'email',
                    'password'
                ]
            ],
            'api/register' => [
                'method' => 'POST',
                'description' => 'API registration',
                'params' => [
                    'name',
                    'email',
                    'password'
                ]
            ],
            'api/refresh' => [
                'method' => 'POST',
                'description' => 'Refresh jwt token'
            ],
            'api/links' => [
                'method' => 'GET',
                'description' => 'Get all links for authenticated user'
            ],
            'api/links/add' => [
                'method' => 'POST',
                'description' => 'Add link for user with at least on tag',
                'params' => [
                    'url',
                    'tags'
                ]
            ],
            'api/links/search' => [
                'method' => 'POST',
                'description' => 'Search links for user by tags',
                'params' => [
                    'tags'
                ]
            ],
            'api/tags/add' => [
                'method' => 'POST',
                'description' => 'Add tags to an existing link',
                'params' => [
                    'url',
                    'tags'
                ]
            ],
            'api/tags/suggested' => [
                'method' => 'POST',
                'description' => 'Get suggested tags for existing link by other users',
                'params' => [
                    'url'
                ]
            ],
            'api/tags/suggested/analyze' => [
                'method' => 'POST',
                'description' => 'Get suggested tags for existing link by content analysis',
                'params' => [
                    'url'
                ]
            ],
        ];

        return new JsonResource($routes);
    })->name('routes');
});

Route::middleware('auth:api')->namespace('Api')->name('api.tags.')->group(function () {
    Route::post('/tags/add', 'TagController@add')->name('add');
    Route::post('/tags/suggested/analyze', 'TagController@getSuggestedTagsByAnalysis')->name('analysis');
    Route::post('/tags/suggested', 'TagController@getSuggestedTags')->name('suggested');
});

Route::middleware('auth:api')->namespace('Api')->name('api.urls.')->group(function () {
    Route::post('/links/add', 'UrlController@add')->name('add');
    Route::post('/links/search', 'UrlController@search')->name('search');
    Route::get('/links', 'UrlController@view')->name('view');
});
