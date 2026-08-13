<?php
// 2026-08-13 06:46:17
/*
**Topic Name: Understanding Laravel Eloquent Subqueries**

**Explanation:**
Laravel Eloquent provides an elegant way to perform database operations in PHP. Sometimes, we need to use subqueries to achieve complex database logic. A subquery is a query nested inside another query. In this topic, we will explore how to use subqueries in Eloquent to fetch data from a database table.

**Code Example:**
```php
// Create a model called User
class User extends Model
{
    // Establish a relationship with the Post model
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

class Post extends Model
{
    // Establish a relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// Use a subquery to fetch users who have posts
$usersWithPosts = User::has('posts')->get();

// Use a subquery to fetch users who have posts with a title containing 'Hello'
$usersWithHelloPosts = User::has('posts', function ($query) {
    $query->where('title', 'like', '%Hello%');
})->get();

// Use a subquery to fetch the count of posts for each user
$userPostCounts = User::withCount('posts')->get();

// Use a subquery to fetch the average rating of posts for each user
$userAverageRatings = User::withAverage('posts', 'rating')->get();
```
In this example, we are using the `has` method to fetch users who have posts, the `whereHas` method to fetch users who have posts with a title containing 'Hello', the `withCount` method to fetch the count of posts for each user, and the `withAverage` method to fetch the average rating of posts for each user.
*/
