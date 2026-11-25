<?php
// 2026-11-25 10:11:11

/* PHP
Topic: Prepared Statements with PDO

Explanation:  
Prepared statements separate the SQL query structure from the actual data, which prevents SQL injection attacks.  
The PDO (PHP Data Objects) extension provides a unified API for accessing many different databases.  
You first prepare the statement with placeholders, then bind values or pass an array of parameters when executing.  
PDO can reuse the same prepared statement multiple times, improving performance for repeated queries.  
Error handling with exceptions allows you to catch database errors cleanly without exposing details to users.

Code example with comments:

<?php
// Create a new PDO instance (replace DSN, username, and password with your own values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays by default
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders and execute the statement
$data = [
    ':username' => 'alice',
    ':email'    => 'alice@example.com',
];
$stmt->execute($data);

// Prepare a SELECT statement with a positional placeholder
$selectSql = "SELECT id, username, email FROM users WHERE email = ?";
$selectStmt = $pdo->prepare($selectSql);

// Execute with the email as a parameter
$selectStmt->execute(['alice@example.com']);

// Fetch the result
$user = $selectStmt->fetch();

if ($user) {
    echo "User ID: " . $user['id'] . PHP_EOL;
    echo "Username: " . $user['username'] . PHP_EOL;
    echo "Email: " . $user['email'] . PHP_EOL;
} else {
    echo "No user found." . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Route Model Binding in Laravel

Explanation:
Route Model Binding allows Laravel to automatically inject model instances into your routes or controller methods based on the route parameters. When a route contains a parameter that matches a model's primary key, Laravel resolves the parameter to an instance of that model. This eliminates the need to manually query the database inside the controller. Implicit binding works out of the box if the parameter name matches the model variable name, while explicit binding lets you customize the key or use a different column. Using model binding makes the code cleaner, more readable, and leverages Laravel’s built‑in error handling for missing records.

Code Example:
// In routes/web.php
Route::get('posts/{post}', [App\Http\Controllers\PostController::class, 'show']);

// In App/Models/Post.php (the Eloquent model)
class Post extends Model
{
    // The table, fillable fields, etc.
}

// In App/Http/Controllers/PostController.php
class PostController extends Controller
{
    // Laravel automatically injects the Post model instance
    public function show(Post $post)
    {
        // $post is already retrieved from the database.
        // If the ID does not exist, Laravel throws a 404 error.
        return view('posts.show', ['post' => $post]);
    }
}

// Explicit binding (optional) in a service provider, e.g., App\Providers\RouteServiceProvider
public function boot()
{
    parent::boot();

    // Bind the {post} parameter to a Post model using the slug column instead of ID
    Route::bind('post', function ($value) {
        return App\Models\Post::where('slug', $value)->firstOrFail();
    });
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve query readability by allowing you to break complex logic into named sub‑queries that appear before the main query.  
When a CTE references itself, it becomes a recursive CTE, which is useful for traversing hierarchical data such as organization charts or folder trees.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until a termination condition is met.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.  

Code example (finding all descendants of a manager in an employee hierarchy):

WITH RECURSIVE employee_hierarchy AS (  
    -- Anchor member: start with the manager whose ID is 3  
    SELECT employee_id, manager_id, employee_name, 0 AS level  
    FROM employees  
    WHERE employee_id = 3  

    UNION ALL  

    -- Recursive member: find employees whose manager is in the previous level  
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1 AS level  
    FROM employees e  
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM employee_hierarchy  
ORDER BY level, employee_id;  
*/

/* JavaScript
Topic: Asynchronous Iteration with for await…of

Explanation:  
The for await…of loop lets you iterate over any async‑iterable object, such as streams, async generators, or objects that implement Symbol.asyncIterator. Each iteration waits for the Promise returned by the iterator’s next() method to settle before proceeding, enabling sequential processing of asynchronous data. This pattern is especially useful for reading large files, handling paginated API responses, or processing real‑time event streams without loading everything into memory. By using for await…of you avoid manual Promise handling and keep the code readable and linear. It works only inside async functions or top‑level modules that support top‑level await.

Code example:
// Simulate an async data source that yields numbers with a delay
async function* asyncNumberGenerator(limit) {
    for (let i = 1; i <= limit; i++) {
        // Wait 200 ms before yielding each value
        await new Promise(resolve => setTimeout(resolve, 200));
        yield i;               // Yield the next number asynchronously
    }
}

// Consume the async iterator using for await…of
(async () => {
    console.log('Starting async iteration:');
    for await (const num of asyncNumberGenerator(5)) {
        // Each iteration runs after the previous promise resolves
        console.log('Received number:', num);
    }
    console.log('Iteration complete.');
})();
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Structured Data Extraction  

Explanation:  
Few‑shot prompting supplies the model with a handful of input‑output examples, guiding it to perform a specific task without fine‑tuning. By carefully crafting these demonstrations, you can teach the model to extract structured information such as JSON from free‑form text. The approach works well with large language models that understand pattern recognition across examples. It reduces the need for large labeled datasets and allows rapid iteration on prompt design. When combined with temperature control, the output becomes both accurate and deterministic for downstream processing.  

Code example (Python, using OpenAI’s chat completion API):  

import os  
import json  
import openai  

# Set your OpenAI API key (ensure it is stored securely)  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that teaches the model to extract a product’s name, price, and rating  
system_prompt = "You are an assistant that extracts product information and returns it as a JSON object with fields: name, price, rating."  

few_shot_examples = [  
    {  
        "role": "user",  
        "content": "I just bought the Echo Dot for $49.99 and it works great, 4.5 stars!"  
    },  
    {  
        "role": "assistant",  
        "content": json.dumps({"name": "Echo Dot", "price": 49.99, "rating": 4.5})  
    },  
    {  
        "role": "user",  
        "content": "The new Samsung Galaxy S23 costs $799 and I've given it a 5‑star rating."  
    },  
    {  
        "role": "assistant",  
        "content": json.dumps({"name": "Samsung Galaxy S23", "price": 799, "rating": 5})  
    }  
]  

# New input that we want to parse  
new_query = {  
    "role": "user",  
    "content": "Just ordered the Nintendo Switch for $299, looks promising, rating 4."  
}  

# Combine all messages in order: system, few‑shot examples, then the new query  
messages = [{"role": "system", "content": system_prompt}] + few_shot_examples + [new_query]  

# Call the chat model with low temperature for deterministic JSON output  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0.0,  
    max_tokens=150,  
    # Instruct the model to respond only with JSON (no extra text)  
    response_format={"type": "json_object"}  
)  

# Parse and print the structured result  
extracted = json.loads(response.choices[0].message.content)  
print(extracted)   # Expected: {'name': 'Nintendo Switch', 'price': 299, 'rating': 4}  
*/

