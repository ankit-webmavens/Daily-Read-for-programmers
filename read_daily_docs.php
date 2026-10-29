<?php
// 2026-10-29 07:38:10

/* PHP
PHP PDO Prepared Statements  

Prepared statements are a secure way to execute SQL queries with user‑supplied data.  
They separate the query structure from the actual values, preventing SQL injection.  
The statement is prepared once by the database server, then executed multiple times with different parameters.  
PDO automatically handles data type conversion and quoting when binding values.  
Using prepared statements also improves performance for repeated queries.  

<?php
// Create a new PDO instance (replace DSN, username, password as needed)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass');
// Enable exceptions for error handling
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare an INSERT statement with named placeholders
$stmt = $pdo->prepare('INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())');

// Bind values to the placeholders
$username = 'alice';
$email = 'alice@example.com';
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// The same statement can be reused with different data
$username = 'bob';
$email = 'bob@example.com';
$stmt->execute(); // No need to re‑prepare, just execute with new bound values
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:
Form Request Validation is a dedicated class that encapsulates validation logic for incoming HTTP requests, keeping controllers clean and reusable. It allows you to define validation rules, custom messages, and authorization logic in a single place. When a Form Request is type‑hinted in a controller method, Laravel automatically validates the request before the method runs. If validation fails, a redirect with error messages is generated automatically. This approach promotes single‑responsibility principles and makes testing validation rules straightforward.

Code Example (app/Http/Requests/StorePostRequest.php):
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // For example, only allow authenticated users.
        return auth()->check();
    }

    // Define the validation rules that apply to the request.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'image'   => 'nullable|image|max:2048',
            'tags'    => 'array',
            'tags.*'  => 'exists:tags,id',
        ];
    }

    // Optional: customize error messages.
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'body.required'  => 'Please provide the post content.',
        ];
    }
}

Usage in a controller (app/Http/Controllers/PostController.php):
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        // Validation has already been performed at this point.
        $validated = $request->validated();

        // Create a new post with the validated data.
        $post = Post::create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'user_id' => auth()->id(),
        ]);

        // Handle optional image upload.
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('post_images', 'public');
            $post->update(['image_path' => $path]);
        }

        // Attach tags if provided.
        if (!empty($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return redirect()->route('posts.show', $post)->with('status', 'Post created successfully!');
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. 
CTEs improve readability by allowing you to break complex queries into logical building blocks. 
When the CTE is defined with the RECURSIVE keyword, it can reference itself to produce hierarchical or sequential data. 
Recursive CTEs consist of an anchor member (the starting point) and a recursive member that repeatedly adds rows until a termination condition is met. 
They are especially useful for traversing trees, generating series, or performing cumulative calculations without procedural code.

Code example (generating an employee hierarchy using a recursive CTE):

-- Define the recursive CTE named org_chart
WITH RECURSIVE org_chart AS (
    -- Anchor member: select the top‑level manager(s)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL
    
    UNION ALL
    
    -- Recursive member: join employees to their managers
    SELECT 
        e.employee_id,
        e.manager_id,
        e.employee_name,
        oc.level + 1 AS level
    FROM employees e
    INNER JOIN org_chart oc ON e.manager_id = oc.employee_id
)
-- Final query: retrieve the full hierarchy ordered by level
SELECT 
    employee_id,
    manager_id,
    employee_name,
    level
FROM org_chart
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures allow inner functions to remember and interact with variables defined in outer functions.  
They are created every time a function is defined, capturing the surrounding state at that moment.  
Common uses include data encapsulation, function factories, and maintaining private variables.  
Understanding closures is essential for mastering asynchronous patterns and module design in JavaScript.  

Code Example:
// Outer function defines a private variable and returns an inner function
function createCounter(initialValue) {
    let count = initialValue;                 // count is private to createCounter
    return function() {
        count += 1;                            // inner function accesses and modifies count
        return count;                         // returns the updated count
    };
}

// Use the closure
const counterA = createCounter(0);             // counterA has its own private count
console.log(counterA()); // 1
console.log(counterA()); // 2

const counterB = createCounter(10);            // counterB has a separate private count
console.log(counterB()); // 11
console.log(counterA()); // 3   // counterA’s count continues independently of counterB  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies a small number of example inputs and desired outputs within the prompt, guiding the language model toward the required behavior without fine‑tuning. By carefully selecting representative demonstrations, you can shape the model’s style, enforce formatting rules, or constrain it to a particular domain. This technique works well for tasks like data extraction, code generation, or classification where labeled data are scarce. The prompt is constructed as a list of messages: a system message sets the overall role, then alternating user‑assistant pairs provide the examples, followed by the new user query. Adjusting the number and quality of examples directly influences the model’s accuracy and consistency.  

Code example (Python, using the openai library):  

import os  
import openai  

# Set your OpenAI API key (ensure it is stored securely)  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define the few‑shot prompt as a list of message dictionaries  
messages = [  
    {"role": "system", "content": "You are a helpful assistant that extracts product names and prices from e‑commerce descriptions."},  
    # Example 1 – user provides a description, assistant extracts fields  
    {"role": "user", "content": "The sleek XYZ smartwatch features a 1.5‑inch display and costs $199."},  
    {"role": "assistant", "content": "Product: XYZ smartwatch\nPrice: $199"},  
    # Example 2 – second demonstration  
    {"role": "user", "content": "Our new ergonomic office chair is priced at $349 and comes in black or gray."},  
    {"role": "assistant", "content": "Product: ergonomic office chair\nPrice: $349"},  
    # New query – model must follow the pattern above  
    {"role": "user", "content": "Introducing the portable Bluetooth speaker, battery life 12 hours, price $79."}  
]  

# Call the Chat Completion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",          # choose a model that supports chat format  
    messages=messages,  
    temperature=0.0,              # deterministic output for extraction tasks  
)  

# Print the assistant’s extraction result  
print(response.choices[0].message.content.strip())   # Expected: Product: portable Bluetooth speaker\nPrice: $79  
*/

