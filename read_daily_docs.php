<?php
// 2026-08-13 09:29:24

/* PHP
Closures in PHP

Closures are anonymous functions that have access to their own scope, the scope in which they were created, and can be used later in the program. They can also capture variables from the outer scope and use them even when the outer function has finished executing. Closures can be used to create higher-order functions and implement functional programming concepts in PHP. They can also be used as objects and have methods.

```php
function outer_function($name) {
    $greeting = "Hello, ";
    $closure = function() use ($greeting, $name) {
        // using variables from outer scope
        echo $greeting . $name . "\n";
    };
    return $closure;
}

// create a closure
$closure_hello_john = outer_function("John");
$closure_hello_john(); // outputs "Hello, John"

$closure_hello_mark = outer_function("Mark");
$closure_hello_mark(); // outputs "Hello, Mark"
```
*/

/* Laravel
**Eager Loading**

Eager loading is a feature in Laravel that allows you to load related models and their relationships with a single database query. This improves performance by reducing the number of database queries required to load data.

To enable eager loading, we can use the with() method on a query builder instance and pass in the related models. For example:

```php
$posts = App\Post::with('comments', 'author')->get();
```

In this example, we're loading two relationships: comments and author.

If these relationships are defined on the Post model like so:

```php
// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
```

We can then access the related models using the getRelations() method:

```php
$firstPost = $posts[0];

// Load all comments for the first post
$comments = $firstPost->comments;

// Load the author of the first post
$user = $firstPost->author;
```

This approach makes it easier to manage complex relationships between models, as well as improving performance by reducing the number of database queries.
*/

/* MySQL
**Triggers in MySQL**

Triggers in MySQL are database level stored procedures that are automatically executed in response to certain events such as insert, update or delete operations on a table. They can be used to enforce data consistency, perform complex operations, and increase data integrity. Triggers are especially useful when you need to perform some operation before or after an alteration on a table. They are useful in cases where direct database manipulation is involved such as updating tables in an online application. Triggers can also be used to track changes made to the data in a database.

**Example of MySQL Trigger**

```sql
CREATE TABLE customers (
  id INT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255)
);

-- Before update trigger to send email notifications
DELIMITER //
CREATE TRIGGER send_email_notification
BEFORE UPDATE ON customers
FOR EACH ROW
BEGIN
  -- Send email to the customer and administrator
  DECLARE exitHandler INT DEFAULT 0;
  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Email sent';
END;//
DELIMITER ;

-- Update a customer
UPDATE customers SET name = 'John Doe' WHERE id = 1;

-- Show the new value in customers table after update
SELECT * FROM customers WHERE id = 1;

-- Drop the trigger
DROP TRIGGER send_email_notification;

-- Update again to see the new value
UPDATE customers SET name = 'Jane Doe' WHERE id = 1;
```
*/

/* JavaScript
**Closures**

Closures are a fundamental concept in JavaScript that involves the creation of a function in which some of the variables of the outer function's scope are accessible. This can be useful for creating private variables, returning functions from functions, and creating callbacks. A closure is formed when a function has access to its own scope and the scope of the outer function in which it was created. Closures are often used in real-world applications, such as event handlers and modules.

```javascript
// An example of a closure in JavaScript
function outerFunction() {
    let privateVariable = "private";  // a private variable
    return function innerFunction() {
        console.log(privateVariable);  // accessing the private variable
    };
}

let innerFunc = outerFunction();
innerFunc();  // prints "private"
```
*/

/* AI
**Transfer Learning in Machine Learning**

Transfer learning is a machine learning technique where a pre-trained model is adapted for a new task, leveraging the knowledge and representation learned from the original task. This approach can be beneficial when dealing with limited data or computational resources. By reusing pre-trained models, transfer learning reduces the time and effort required to train a model for a specific task. It's widely used in NLP, image classification, and object detection tasks.

```python
from tensorflow import keras
from tensorflow.keras import layers

# Load the pre-trained VGG16 model
base_model = keras.applications.VGG16(weights='imagenet', include_top=False, input_shape=(224, 224, 3))

# Freeze the base model layers
for layer in base_model.layers:
    layer.trainable = False

# Add custom layers to the model
x = base_model.output
x = layers.Flatten()(x)
x = layers.Dense(64, activation='relu')(x)
x = layers.Dense(10, activation='softmax')(x)

# Define the new model
model = keras.Model(inputs=base_model.input, outputs=x)

# Compile the model
model.compile(optimizer='adam', loss='categorical_crossentropy', metrics=['accuracy'])
```
*/
