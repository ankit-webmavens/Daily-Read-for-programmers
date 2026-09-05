<?php
// 2026-09-05 06:16:39

/* PHP
Topic: PHP PDO Prepared Statements

Explanation:
Prepared statements in PDO separate the SQL query from the data, preventing SQL injection attacks. They allow the database engine to parse and compile the query only once, improving performance for repeated executions. Placeholders (named or positional) are used in the SQL, and values are bound later. Binding can be done with bindParam, bindValue, or directly in execute. Errors are handled via exceptions, making debugging easier.

Code example:
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', 'johndoe', PDO::PARAM_STR);
$stmt->bindValue(':email', 'john@example.com', PDO::PARAM_STR);
$stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

// Execute the statement
$stmt->execute();

// Output the ID of the newly inserted row
echo "New user ID: " . $pdo->lastInsertId();
*/

/* Laravel
Topic: Form Request Validation

Explanation:
Form Request Validation is a dedicated class in Laravel that encapsulates validation logic for incoming HTTP requests. By moving validation rules out of controllers, the code becomes cleaner and more reusable. The class automatically handles redirection with error messages when validation fails. It also provides an authorize method to implement authorization checks before the request proceeds. Using Form Requests promotes a single‑responsibility principle, keeping controllers focused on handling business logic.

Code Example:
// app/Http/Requests/StorePostRequest.php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Return true to allow all users, or add custom logic.
        return true;
    }

    // Get the validation rules that apply to the request.
    public function rules()
    {
        return [
            // title is required, must be a string, and max 255 characters.
            'title' => 'required|string|max:255',
            // body is required and must be at least 10 characters.
            'body' => 'required|min:10',
            // optional image must be an uploaded file of type jpeg or png.
            'image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    // Customize the error messages (optional).
    public function messages()
    {
        return [
            'title.required' => 'Please provide a title for the post.',
            'body.min' => 'The post body must be at least 10 characters.',
        ];
    }
}

// app/Http/Controllers/PostController.php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    // Store a newly created post.
    public function store(StorePostRequest $request)
    {
        // Validation has already been performed at this point.
        // Retrieve validated data.
        $validated = $request->validated();

        // Create the post using mass assignment.
        $post = Post::create($validated);

        // If an image was uploaded, store it and update the post.
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $post->update(['image_path' => $path]);
        }

        // Redirect or return response.
        return redirect()->route('posts.show', $post)->with('status', 'Post created successfully!');
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
They are defined using the WITH clause and can be either non‑recursive or recursive.  
Recursive CTEs are useful for traversing hierarchical data such as organization charts or tree structures.  
The CTE exists only for the duration of the statement that defines it, so it does not persist in the database.

Code example (recursive CTE that lists an employee hierarchy):
-- Create a sample employees table
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),        -- top‑level manager
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'David', 2),
(5, 'Eve', 2),
(6, 'Frank', 3);

-- Recursive CTE to retrieve the full reporting chain for a given employee (e.g., employee 4)
WITH RECURSIVE reporting_chain AS (
    -- Anchor member: start with the selected employee
    SELECT emp_id, emp_name, manager_id, 0 AS level
    FROM employees
    WHERE emp_id = 4
    UNION ALL
    -- Recursive member: join to the manager of the current level
    SELECT e.emp_id, e.emp_name, e.manager_id, rc.level + 1
    FROM employees e
    INNER JOIN reporting_chain rc ON e.emp_id = rc.manager_id
)
SELECT emp_id, emp_name, manager_id, level
FROM reporting_chain
ORDER BY level DESC;   -- shows the chain from top manager down to the employee  
*/

/* JavaScript
Topic: Closures and the Module Pattern

Explanation:
A closure is a function that remembers the variables from the scope in which it was created, even after that outer function has finished executing. This feature lets you create private state that cannot be accessed directly from the outside. By returning an inner function (or a set of functions) from an outer function, you can expose a public API while keeping implementation details hidden. The module pattern uses this principle to bundle related functionality together, mimicking class‑like encapsulation without using the class syntax. It is especially useful for organizing code in environments where module loaders are not available.

Code Example:
// Define a module using an IIFE (Immediately Invoked Function Expression)
var CounterModule = (function () {
    // Private variable, not accessible from outside
    var count = 0;

    // Private helper function
    function logCurrent() {
        console.log('Current count is:', count);
    }

    // Expose public methods
    return {
        increment: function () {
            count++;           // modify private variable
            logCurrent();     // call private helper
        },
        decrement: function () {
            count--;
            logCurrent();
        },
        getValue: function () {
            return count;      // provide read‑only access
        }
    };
})(); // The IIFE runs immediately, returning the public API

// Using the module
CounterModule.increment();   // Output: Current count is: 1
CounterModule.increment();   // Output: Current count is: 2
CounterModule.decrement();   // Output: Current count is: 1
console.log(CounterModule.getValue()); // Prints: 1

// Trying to access the private variable directly will fail
console.log(CounterModule.count); // undefined (private)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
1. Few‑shot prompting supplies the model with a small number of example interactions, teaching it the desired pattern without fine‑tuning.  
2. By framing the examples as a conversation, the model better understands the role of each participant (system, user, assistant).  
3. This technique works well for tasks like classification, transformation, or generating structured output.  
4. The prompt can be dynamically assembled, allowing programmers to adapt examples on the fly for different domains.  
5. Using the Chat Completion endpoint keeps token usage efficient because only the necessary examples are sent each call.  

Code example (Python, requires `openai` library and a valid API key):  

import os  
import openai  

# Set your API key – best practice is to keep it in an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Define a few‑shot prompt with system instruction and two labeled examples  
    messages = [  
        {"role": "system", "content": "You are a helpful assistant that classifies the sentiment of a short user message as Positive, Negative, or Neutral."},  
        {"role": "user", "content": "I just got a promotion at work! 🎉"},  
        {"role": "assistant", "content": "Positive"},  
        {"role": "user", "content": "The coffee was cold and tasted terrible."},  
        {"role": "assistant", "content": "Negative"},  
        # The actual user query to classify  
        {"role": "user", "content": text}  
    ]  

    # Call the Chat Completion API with a low temperature for deterministic output  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=messages,  
        temperature=0.0,  
        max_tokens=5,  
        n=1,  
        stop=None  
    )  

    # Extract the assistant's reply, which should be the sentiment label  
    sentiment = response.choices[0].message.content.strip()  
    return sentiment  

# Example usage  
if __name__ == "__main__":  
    sample = "I'm not sure if I should buy this phone; the price seems high."  
    print(f"Input: {sample}")  
    print("Sentiment:", classify_sentiment(sample))  
*/

