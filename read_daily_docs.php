<?php
// 2026-10-20 06:30:21

/* PHP
Topic: PDO Prepared Statements for Secure Database Access

Explanation:
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
The PDO extension provides a consistent interface for many database systems.  
You prepare the query once, then bind values to placeholders each time you execute.  
This improves performance when the same statement runs multiple times with different data.  
Using exceptions for error handling makes debugging easier and keeps code clean.

Code example with comments:
<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO connection (adjust DSN, username, password as needed)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass', $options);

// Prepare an INSERT statement with named placeholders
$stmt = $pdo->prepare('INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())');

// Data to be inserted
$users = [
    ['username' => 'alice', 'email' => 'alice@example.com'],
    ['username' => 'bob',   'email' => 'bob@example.com'],
];

// Loop through data and execute the prepared statement for each row
foreach ($users as $user) {
    // Bind values to the named placeholders and execute
    $stmt->execute([
        ':username' => $user['username'],
        ':email'    => $user['email'],
    ]);
}

// Fetch and display all users to verify insertion
foreach ($pdo->query('SELECT id, username, email, created_at FROM users') as $row) {
    echo "ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}, Created: {$row['created_at']}\n";
}
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:  
Form Request classes encapsulate validation logic, keeping controllers clean and focused on handling requests.  
They are generated via Artisan and automatically resolve through Laravel’s service container.  
Define rules, custom messages, and authorize logic inside the request class.  
When a request fails validation, Laravel redirects back with error messages and old input.  
Using Form Requests promotes reusability and testability of validation rules across the application.  

Code Example (UserStoreRequest.php):  
<?php  
namespace App\Http\Requests;  

use Illuminate\Foundation\Http\FormRequest;  

class UserStoreRequest extends FormRequest  
{  
    // Determine if the user is authorized to make this request  
    public function authorize()  
    {  
        return true; // set to false to block unauthorized attempts  
    }  

    // Define the validation rules for the incoming request  
    public function rules()  
    {  
        return [  
            'name' => 'required|string|max:255',  
            'email' => 'required|email|unique:users,email',  
            'password' => 'required|string|min:8|confirmed',  
        ];  
    }  

    // Optional: customize validation error messages  
    public function messages()  
    {  
        return [  
            'email.unique' => 'The email address is already registered.',  
        ];  
    }  
}  

Usage in a controller (UserController.php):  
<?php  
namespace App\Http\Controllers;  

use App\Http\Requests\UserStoreRequest;  
use App\Models\User;  

class UserController extends Controller  
{  
    // Store a newly created user using the Form Request for validation  
    public function store(UserStoreRequest $request)  
    {  
        // Validation has already been performed; retrieve validated data  
        $validated = $request->validated();  

        // Create the user record  
        $user = User::create([  
            'name' => $validated['name'],  
            'email' => $validated['email'],  
            'password' => bcrypt($validated['password']),  
        ]);  

        // Return a response or redirect  
        return redirect()->route('users.show', $user)->with('status', 'User created successfully!');  
    }  
}  
*/

/* MySQL
Topic: Using Common Table Expressions (CTEs) for Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
Recursive CTEs allow you to query hierarchical data such as organization charts or folder structures by repeatedly applying a query to its own output.  
The CTE is defined with the WITH clause, followed by the CTE name and column list, then the anchor query and UNION ALL with the recursive part.  
The recursion stops automatically when the recursive query returns no rows, preventing infinite loops.  
Recursive CTEs are supported in MySQL 8.0 and later, offering a powerful alternative to stored procedures for hierarchical processing.

Code example (comments included):
-- Sample table for a simple employee hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50) NOT NULL,
    manager_id INT NULL,            -- NULL indicates top‑level manager
    FOREIGN KEY (manager_id) REFERENCES employees(emp_id)
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),      -- CEO
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'David', 2),
(5, 'Eve', 2),
(6, 'Frank', 3);

-- Recursive CTE to list all subordinates of a given manager (e.g., manager_id = 1)
WITH RECURSIVE subordinates AS (
    -- Anchor member: start with the manager whose subordinates we want
    SELECT emp_id, emp_name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id = 1

    UNION ALL

    -- Recursive member: find employees whose manager is in the previous result set
    SELECT e.emp_id, e.emp_name, e.manager_id, s.level + 1
    FROM employees e
    INNER JOIN subordinates s ON e.manager_id = s.emp_id
)
SELECT emp_id, emp_name, manager_id, level
FROM subordinates
ORDER BY level, emp_id;   -- shows hierarchy depth first

-- The query returns:
-- emp_id | emp_name | manager_id | level
--   2    | Bob      | 1          | 1
--   3    | Carol    | 1          | 1
--   4    | David    | 2          | 2
--   5    | Eve      | 2          | 2
--   6    | Frank    | 3          | 2   (and so on for deeper levels)
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical scope even when the function is executed outside that scope.  
It allows inner functions to "remember" the variables of their outer functions, enabling data privacy and partial application.  
Closures are created every time a function is defined, and the retained variables stay alive as long as the closure can be reached.  
They are commonly used for creating function factories, encapsulating state, and implementing the module pattern.  
Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state across calls.

Code Example with comments:
function makeCounter(initialValue) {               // outer function receives an initial count
    let count = initialValue;                     // this variable is captured by the inner function
    return function() {                          // the returned function forms a closure over 'count'
        count += 1;                               // each call updates the private 'count' variable
        return count;                            // the current count is returned
    };
}
const counterA = makeCounter(0);                  // create a new counter starting at 0
console.log(counterA()); // 1                     // first call increments to 1
console.log(counterA()); // 2                     // second call increments to 2
const counterB = makeCounter(10);                 // a separate counter with its own private state
console.log(counterB()); // 11                    // independent of counterA’s state
console.log(counterA()); // 3                     // counterA continues from its own last value  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI GPT‑4 API

Explanation:
Few‑shot prompting guides a large language model by providing a handful of example input‑output pairs within the same request. This technique helps the model infer the desired pattern without needing fine‑tuning. By carefully selecting diverse and representative examples, you can shape the model’s behavior for tasks such as text classification, data extraction, or code generation. The prompt typically consists of a short instruction, several examples, and finally the new user query. Adjusting the temperature and max_tokens parameters further controls creativity and length of the response.

Code example (Python, using the openai library):

import os
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define the few‑shot prompt with instruction, examples, and the new input
prompt = (
    "You are an assistant that extracts the product name and price from a short description.\n\n"
    "Example 1:\n"
    "Input: \"A sleek stainless steel kettle, 1.7 L, priced at $29.99.\"\n"
    "Output: {\"product\": \"stainless steel kettle\", \"price\": 29.99}\n\n"
    "Example 2:\n"
    "Input: \"Wireless earbuds with noise cancellation, cost $149.\"\n"
    "Output: {\"product\": \"wireless earbuds\", \"price\": 149}\n\n"
    "Now process the following description:\n"
    "Input: \"Compact portable charger, 10000 mAh, available for $39.50.\"\n"
    "Output:"
)

# Call the GPT‑4 completion endpoint
response = openai.Completion.create(
    model="gpt-4o-mini",          # or "gpt-4" depending on your access
    prompt=prompt,
    max_tokens=60,                # limit the length of the JSON output
    temperature=0.0,              # deterministic extraction
    stop=["\n"]                   # stop after the first line of output
)

# Print the model's extracted JSON
print(response.choices[0].text.strip())
*/

