<?php
// 2026-08-13 10:07:28

/* PHP
**Lambdas in PHP**

Lambdas in PHP are anonymous functions that can be used to pass functions as arguments to other functions, return functions as values from other functions, or store functions in variables. They are similar to closures in other languages. Lambdas can be used with any context where a function is required. They are more memory efficient as compared to named functions in some cases.

```php
// Lambda function to square a number
$numbers = array(1, 2, 3, 4, 5);
$mapFunc = function($num) { 
    // This is the implementation of the square function
    return $num * $num; 
};
$squared = array_map($mapFunc, $numbers);

// Print the squared numbers
print_r($squared);
```

This will output: `Array ( [0] => 1 [1] => 4 [2] => 9 [3] => 16 [4] => 25 )`
*/

/* Laravel
**Middleware in Laravel**

Middleware is a great way to perform operations before or after an application request is handled by a route. Laravel's middleware system allows you to check for user authentication, rate limiting, and more.

Middleware are classes that contain a handle method. They can be attached to routes individually by specifying middleware, or globally by appending them to a specific group of routes.

Here is an example of a middleware class:
 
```php
// app/Http/Middleware/ExampleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;

class ExampleMiddleware
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle($request, Closure $next)
    {
        // You can do any processing here, like checking user data
        // In this case, we check if the user is authenticated
        if ($this->session->has('user_id')) {
            // User is authenticated
        } else {
            // Return an HTTP response
            return redirect("/login");
        }

        // Pass the request to the next middleware (if any)
        return $next($request);
    }
}
```
Then in the Kernel.php, we register the middleware at the bottom of the kernel class like so:

```php
// app/Http/Kernel.php

protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'example.middleware' => \App\Http\Middleware\ExampleMiddleware::class,
];
```
*/

/* MySQL
**Indexing MySQL Columns**

Indexing MySQL columns improves query performance by allowing the database to quickly locate data. An index is a data structure that speeds up data retrieval operations. When a query is executed, MySQL can use the index to quickly locate the required data. This reduces the time it takes to retrieve data. Indexing can also improve data insertion and update operations. However, indexing can slow down write operations.

```sql
CREATE TABLE employees (
  employee_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255)
);

-- Create a simple index on the email column
CREATE INDEX idx_email ON employees(email);

-- Drop the index
DROP INDEX idx_email ON employees;

-- Create a composite index on multiple columns
CREATE INDEX idx_email_name ON employees(email, name);

-- Create a unique index on a column
CREATE UNIQUE INDEX idx_email ON employees(email);
```
*/

/* JavaScript
Closures in JavaScript

A closure is a function that has access to its own local scope and the scope of its outer functions. This allows a function to remember variables from its surrounding scope even when it is invoked outside of that scope. A classic example of this is a function returned by the previous function that uses the outer function's variables.

```javascript
function outerFunction() {
  var name = 'John Doe';
  function innerFunction() {
    console.log(name);  // accessing outer function's variable
  }
  return innerFunction;
}

var sayName = outerFunction();
sayName();  // outputs: John Doe
```

In this code, `innerFunction` is returned from `outerFunction` and `sayName` takes its place. So even though `outerFunction` is called once and goes out of scope, `sayName` continues to have access to the `name` variable in the outer function's scope. This demonstrates the concept of a closure.
*/

/* AI
Topic: Building a Simple Neural Network for Image Classification using Keras

Building a simple neural network for image classification involves training a model to recognize patterns in images and assign labels to them. This can be achieved using Keras, a popular deep learning library in Python. In this example, we'll create a simple neural network that can classify handwritten digits using the MNIST dataset. We'll use the Sequential API to build the model and compile it with a suitable loss function, optimizer, and evaluation metrics.

```
# Import necessary libraries
from keras.models import Sequential
from keras.layers import Dense, Dropout, Flatten
from keras.layers import Conv2D, MaxPooling2D
from keras.datasets import mnist
from keras.utils import to_categorical

# Load MNIST dataset
(X_train, y_train), (X_test, y_test) = mnist.load_data()

# Preprocess images
X_train = X_train.reshape(-1, 28, 28, 1)
X_test = X_test.reshape(-1, 28, 28, 1)
X_train = X_train.astype('float32') / 255
X_test = X_test.astype('float32') / 255

# One-hot encode labels
y_train = to_categorical(y_train, 10)
y_test = to_categorical(y_test, 10)

# Build the model
model = Sequential()
model.add(Conv2D(32, (3, 3), activation='relu', input_shape=(28, 28, 1)))
model.add(MaxPooling2D(pool_size=(2, 2)))
model.add(Flatten())
model.add(Dense(128, activation='relu'))
model.add(Dropout(0.2))
model.add(Dense(10, activation='softmax'))

# Compile the model
model.compile(loss='categorical_crossentropy', optimizer='adam', metrics=['accuracy'])

# Train the model
model.fit(X_train, y_train, batch_size=64, epochs=10, verbose=1)
```
*/
