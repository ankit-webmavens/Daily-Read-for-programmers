<?php
// 2026-09-28 07:30:47

/* PHP
PHP Generators (Yield)

Generators allow functions to produce values lazily, one at a time, without building an entire array in memory.  
They are created using the `yield` keyword, turning the function into an iterator object.  
Each `yield` pauses the function, preserving its local state until the next value is requested.  
Generators are ideal for processing large data sets, reading files line‑by‑line, or streaming results.  
They reduce memory consumption and can improve performance in I/O‑bound tasks.  
Use them with `foreach` or by manually advancing the iterator with `next()`.

<?php
// Define a generator that yields numbers from 1 to $n
function rangeGenerator(int $n): Generator {
    for ($i = 1; $i <= $n; $i++) {
        // Yield the current number and pause execution
        yield $i;
    }
}

// Consume the generator
foreach (rangeGenerator(5) as $value) {
    // Each iteration receives the next yielded value
    echo "Value: $value\n";
}

// Manual iteration example
$gen = rangeGenerator(3);
echo $gen->current() . "\n"; // Outputs 1
$gen->next();                // Move to next value
echo $gen->current() . "\n"; // Outputs 2
?>
*/

/* Laravel
Topic: Laravel Eloquent Polymorphic Relationships  

Explanation:  
Polymorphic relationships allow a single model to belong to more than one other model on a single association. This is useful when you have different types of models that can share a common relationship, such as comments that can belong to posts, videos, or products. Laravel handles the underlying foreign key and type columns automatically, simplifying queries and data insertion. You define the relationship on the child model using morphTo, and on each parent model using morphMany or morphOne. The database schema requires an ID column and a type column to identify the owning model.

Code example (PHP):

<?php
// Comment model – the polymorphic side
class Comment extends Model
{
    // Define the inverse polymorphic relationship
    public function commentable()
    {
        // morphTo will look for commentable_id and commentable_type columns
        return $this->morphTo();
    }
}

// Post model – one possible parent
class Post extends Model
{
    // A post can have many comments
    public function comments()
    {
        // morphMany tells Laravel this model can have many related comments
        return $this->morphMany(Comment::class, 'commentable');
    }
}

// Video model – another possible parent
class Video extends Model
{
    // A video can have many comments as well
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

// Storing a comment for a post
$post = Post::find(1);
$post->comments()->create([
    'body' => 'Great article!',
    'user_id' => auth()->id(),
]);

// Storing a comment for a video
$video = Video::find(5);
$video->comments()->create([
    'body' => 'Nice tutorial!',
    'user_id' => auth()->id(),
]);

// Retrieving comments for a post
$postComments = $post->comments; // Collection of Comment objects

// Accessing the owning model from a comment
$comment = Comment::find(10);
$owner = $comment->commentable; // Returns either a Post or Video instance depending on the type
?>
*/

/* MySQL
Topic: MySQL Stored Procedures

Explanation:
- A stored procedure is a named set of SQL statements that can be stored in the database and executed repeatedly.
- It allows you to encapsulate complex logic, loops, and conditional processing on the server side.
- Parameters can be defined as IN, OUT, or INOUT to pass values into and retrieve results from the procedure.
- Using stored procedures can improve performance by reducing network round‑trips and centralizing business rules.
- They also help with security, as you can grant execution rights without exposing underlying tables.

Code example (with comments):
CREATE PROCEDURE GetCustomerOrders(IN cust_id INT, OUT order_count INT)
BEGIN
    -- Count the number of orders for the given customer
    SELECT COUNT(*) INTO order_count
    FROM orders
    WHERE customer_id = cust_id;

    -- Optionally, you could return the list of orders as a result set
    SELECT order_id, order_date, total_amount
    FROM orders
    WHERE customer_id = cust_id
    ORDER BY order_date DESC;
END;
-- To call the procedure and retrieve the OUT parameter:
CALL GetCustomerOrders(42, @totalOrders);
SELECT @totalOrders AS TotalOrdersForCustomer42;
*/

/* JavaScript
Topic: Closures and the Module Pattern

Explanation:
Closures allow a function to retain access to its lexical scope even after the outer function has finished executing. By leveraging closures, developers can create private variables and encapsulate functionality, mimicking the behavior of classes or modules. The module pattern uses an immediately‑invoked function expression (IIFE) to expose a public API while keeping internal details hidden. This approach helps prevent global namespace pollution and protects internal state from unintended modification. Understanding closures is essential for writing maintainable, testable JavaScript code.

Code example:
// Define a module using an IIFE and closures
var CounterModule = (function () {
    // Private variable, not accessible from outside
    var count = 0;

    // Private helper function
    function log(message) {
        console.log('[Counter] ' + message);
    }

    // Expose public methods
    return {
        // Increments the count and logs the new value
        increment: function () {
            count++;
            log('incremented to ' + count);
            return count;
        },
        // Decrements the count and logs the new value
        decrement: function () {
            count--;
            log('decremented to ' + count);
            return count;
        },
        // Returns the current count without allowing direct modification
        getValue: function () {
            return count;
        }
    };
})(); // The IIFE executes immediately, creating the module

// Using the module
CounterModule.increment(); // logs: [Counter] incremented to 1
CounterModule.increment(); // logs: [Counter] incremented to 2
CounterModule.decrement(); // logs: [Counter] decremented to 1
console.log('Current count:', CounterModule.getValue()); // prints: Current count: 1

// Trying to access the private variable directly will fail
console.log(CounterModule.count); // undefined, as count is hidden inside the closure.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API

Explanation:  
Few‑shot prompting supplies the model with a handful of example input‑output pairs inside the same request, guiding it toward the desired behavior without fine‑tuning. By carefully constructing the system message and a few user‑assistant exchanges, you can steer the model to follow custom formats, adhere to business rules, or emulate a specific writing style. This technique works well for tasks like data extraction, code generation, or domain‑specific Q&A where a full dataset is unavailable. The approach is inexpensive, flexible, and can be altered on the fly by modifying the example set. It also demonstrates how prompt design complements traditional model training in practical applications.

Code example (Node.js, using the official openai npm package):
```javascript
// Load the OpenAI client library
const { Configuration, OpenAIApi } = require("openai");

// Initialize with your API key (store securely, e.g., in environment variables)
const configuration = new Configuration({
    apiKey: process.env.OPENAI_API_KEY,
});
const openai = new OpenAIApi(configuration);

// Define a few‑shot prompt that teaches the model to translate
// informal English sentences into formal business language
const messages = [
    { role: "system", content: "You are a professional writer who rewrites casual sentences into formal business language." },
    // Example 1
    { role: "user", content: "Hey, can you send me the report?" },
    { role: "assistant", content: "Could you please forward the report to me at your earliest convenience?" },
    // Example 2
    { role: "user", content: "I need that data ASAP." },
    { role: "assistant", content: "I would appreciate receiving the data as soon as possible." },
    // New query
    { role: "user", content: "Let’s meet tomorrow to discuss the project." }
];

// Call the Chat Completion endpoint
async function rewrite() {
    try {
        const response = await openai.createChatCompletion({
            model: "gpt-4o-mini",
            messages: messages,
            temperature: 0.2   // low temperature for deterministic output
        });
        const rewritten = response.data.choices[0].message.content;
        console.log("Rewritten sentence:", rewritten);
    } catch (error) {
        console.error("API request failed:", error);
    }
}

// Execute the function
rewrite();
```
*/

