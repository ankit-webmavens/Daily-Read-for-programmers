<?php
// 2026-09-26 06:34:50

/* PHP
Topic: Prepared Statements with PDO  

Explanation:  
Prepared statements separate the SQL query structure from the actual data, which prevents SQL injection attacks by ensuring that user input is never directly concatenated into the query string. PDO (PHP Data Objects) provides a uniform interface for accessing multiple database types, and its prepared statement API works the same across MySQL, PostgreSQL, SQLite, and others. When a statement is prepared, the database parses and compiles the query once, allowing it to be executed repeatedly with different parameters efficiently. Binding values to placeholders can be done by name or by position, giving flexibility in how data is supplied to the query. After execution, the result set can be fetched using PDO’s fetch methods, which return data as associative arrays, objects, or numeric arrays.  

Code example:  
<?php  
// Create a new PDO instance (replace DSN, username, password as needed)  
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass');  

// Enable exceptions for error handling  
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

// Prepare the SQL statement with named placeholders  
$stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE status = :status AND created_at > :date');  

// Bind values to the placeholders (type is optional, PDO will infer)  
$stmt->bindValue(':status', 'active');  
$stmt->bindValue(':date', '2023-01-01');  

// Execute the prepared statement  
$stmt->execute();  

// Fetch all matching rows as an associative array  
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);  

// Iterate and display results  
foreach ($users as $user) {  
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}\n";  
}  
?>
*/

/* Laravel
Topic: Route Model Binding in Laravel

Explanation:
Route model binding lets Laravel automatically inject model instances into your routes or controller methods based on the route parameters. When a parameter name matches a model's primary key, Laravel queries the database and returns the corresponding model object. If the model is not found, a 404 response is generated automatically. This feature reduces boilerplate code and improves readability of controllers. You can use implicit binding for standard primary keys or define explicit bindings for custom logic.

Code example (app/Models/Post.php):
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // The table associated with the model.
    protected $table = 'posts';

    // Mass assignable attributes.
    protected $fillable = ['title', 'content'];
}
?>

Code example (routes/web.php):
<?php
use App\Models\Post;
use Illuminate\Support\Facades\Route;

// Implicit route model binding: {post} will be resolved to a Post model instance.
Route::get('/posts/{post}', function (Post $post) {
    // $post is already a fully hydrated Post model.
    return view('posts.show', ['post' => $post]);
});
?>

Code example (explicit binding in a service provider, e.g., App\Providers\RouteServiceProvider.php):
<?php
namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Define an explicit binding for a route parameter named "admin".
        Route::bind('admin', function ($value) {
            // Custom query logic: only retrieve users with an admin role.
            return User::where('id', $value)->where('role', 'admin')->firstOrFail();
        });
    }
}
?>

Code example (using the explicit binding in a route, routes/web.php):
<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;

// The {admin} parameter will be resolved using the custom binding above.
Route::get('/admin/dashboard/{admin}', function (User $admin) {
    // $admin is guaranteed to be an admin user.
    return view('admin.dashboard', ['admin' => $admin]);
});
?>
*/

/* MySQL
MySQL Topic: Common Table Expressions (CTE)

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
It is defined using the WITH clause and can improve readability by breaking complex queries into logical building blocks.  
CTEs support recursion, allowing you to traverse hierarchical data such as organization charts or category trees.  
They exist only for the duration of the statement that defines them, so they do not persist in the database.  
You can define multiple CTEs in a single WITH clause, separating each with a comma.

Code Example:
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: select the top‑level manager
    SELECT employee_id, manager_id, employee_name, 1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: select subordinates of the current level
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1
    FROM employees e
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id
)
SELECT employee_id,
       manager_id,
       employee_name,
       level
FROM employee_hierarchy
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: Debouncing Functions in JavaScript

Explanation: Debouncing limits how often a function can be invoked by postponing its execution until a certain amount of idle time has passed. It is useful for performance‑critical events such as window resizing, scrolling, or keypress handling where rapid firing can cause lag. The technique works by resetting a timer each time the event occurs, ensuring the original function runs only after the user stops triggering the event for the specified delay. Debouncing can be implemented as a reusable utility that returns a wrapped version of any callback. This helps keep UI responsive and reduces unnecessary computations.

Code example:
// Utility that creates a debounced version of a given function
function debounce(func, wait) {
    let timeoutId;                       // holds the timer identifier
    return function (...args) {          // returned wrapper receives any arguments
        const later = () => {            // function to run after the wait period
            timeoutId = null;
            func.apply(this, args);      // preserve context and forward arguments
        };
        clearTimeout(timeoutId);         // cancel any previously scheduled call
        timeoutId = setTimeout(later, wait); // schedule a new call after 'wait' ms
    };
}

// Example usage: log the window width after the user stops resizing for 300 ms
const logWidth = () => console.log('Window width:', window.innerWidth);
const debouncedLogWidth = debounce(logWidth, 300);

window.addEventListener('resize', debouncedLogWidth);
*/

/* AI
Few-Shot Prompt Engineering with OpenAI’s Chat Completion API  
This technique embeds a small number of example interactions (the “few‑shots”) directly in the prompt to guide the model’s behavior. By showing the desired input‑output pattern, the model can generalize to new queries that follow the same format. It is especially useful when you need consistent style, structure, or domain‑specific knowledge without fine‑tuning. The prompt typically consists of a system message, several user‑assistant turn pairs as examples, and then the new user query. Adjust the number and quality of examples to balance performance and token cost.

import os
import json
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define the system instruction that sets the overall behavior
system_msg = {"role": "system", "content": "You are a helpful assistant that formats travel itineraries in JSON."}

# Few-shot examples: user asks for a trip, assistant returns structured JSON
example1 = {"role": "user", "content": "Plan a 2‑day trip to Paris for a food lover."}
response1 = {"role": "assistant", "content": json.dumps({
    "destination": "Paris",
    "duration_days": 2,
    "highlights": ["Le Marais food tour", "Michelin‑starred dinner at L’Arpège"],
    "daily_plan": [
        {"day": 1, "activities": ["Breakfast at a local boulangerie", "Lunch at Marché des Enfants‑Rouges", "Evening wine tasting"]},
        {"day": 2, "activities": ["Visit a pâtisserie", "Explore the Latin Quarter", "Dinner at L’Arpège"]}
    ]
}, indent=2)}

# New user query we want the model to answer in the same format
new_query = {"role": "user", "content": "Create a 3‑day itinerary for a nature photographer in Iceland."}

# Assemble the messages list: system, few-shot pairs, then the new query
messages = [system_msg, example1, response1, new_query]

# Call the Chat Completion API
completion = openai.ChatCompletion.create(
    model="gpt-4o-mini",
    messages=messages,
    temperature=0.2  # low temperature for more deterministic JSON output
)

# Print the assistant’s formatted JSON response
print(completion.choices[0].message.content)
*/

