<?php
//use App\Http\Controllers\Blog\PostController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', 'WelcomeController@index')->name('welcome');

/*Route::get('blog/posts/{post}', [PostController::class, 'show'])->name('blog.show');
Route::get('blog/categories/{category}', [PostController::class, 'category'])->name('blog.category');
Route::get('blog/tags/{tag}', [PostController::class, 'tag'])->name('blog.tag');
*/
Route::get('blog/posts/{post}', 'Blog\PostController@show')->name('blog.show');
Route::get('blog/categories/{category}', 'Blog\PostController@category')->name('blog.category');
Route::get('blog/tags/{tag}', 'Blog\PostController@tag')->name('blog.tag');
Auth::routes();
Route::middleware('auth')->group(function(){
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('categories','CategoriesController');
Route::resource('posts','PostsController');
Route::get('trashed-posts', 'PostsController@trashed')->name('trashed-posts.index');
Route::put('restore-posts{post}', 'PostsController@restore')->name('restore-posts');
Route::resource('tags','TagsController');
});

Route::middleware('auth','verifyAdmin')->group(function() {
    Route::get('users/profile', 'UsersController@edit')->name('users.edit-profile');
  Route::put('users/profile', 'UsersController@update')->name('users.update-profile');
  Route::get('users', 'UsersController@index')->name('users.index');
  Route::post('users/{user}/make-admin', 'UsersController@makeAdmin')->name('users.make-admin');
});
