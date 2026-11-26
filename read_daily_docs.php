<?php
// 2026-11-26 09:53:31

/* PHP
Topic: PDO Prepared Statements for Secure Database Access  

Explanation:  
Prepared statements separate SQL code from data, preventing malicious input from altering query structure.  
They are compiled once by the database server, then executed multiple times with different parameters, improving performance.  
Using named or positional placeholders makes the code more readable and easier to maintain.  
PDO (PHP Data Objects) provides a uniform API for many database systems, with built‑in error handling.  
When combined with password hashing, prepared statements form a strong foundation for safe user authentication.

Code example with comments:  

<?php  
// Data Source Name: specify driver, host, database, and charset  
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';  
$username = 'dbuser';  
$password = 'dbpass';  

try {  
    // Create a PDO instance and enable exceptions for errors  
    $pdo = new PDO($dsn, $username, $password);  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

    // Prepare an INSERT statement using named placeholders  
    $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');  

    // Bind PHP variables to the placeholders  
    $stmt->bindParam(':email', $email);  
    $stmt->bindParam(':password', $hashedPassword);  

    // Example data to insert  
    $email = 'alice@example.com';  
    $hashedPassword = password_hash('secret123', PASSWORD_BCRYPT);  

    // Execute the prepared statement with the bound values  
    $stmt->execute();  

    echo 'User inserted successfully.';  
} catch (PDOException $e) {  
    // Handle any database errors gracefully  
    echo 'Database error: ' . $e->getMessage();  
}  
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection

Explanation:  
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection automatically. It resolves classes, interfaces, and their required dependencies at runtime, allowing you to type‑hint any class in a controller or other class constructor. By binding abstractions to concrete implementations, you can swap out implementations without changing the consuming code. The container also supports contextual bindings, singletons, and automatic resolution of primitive values via the config or environment. Using the container makes your application more modular, testable, and adheres to the inversion of control principle.

Code example (a controller that receives a repository via automatic injection):

<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;   // The abstraction we will inject

class UserController extends Controller
{
    protected $users;   // Property to hold the repository instance

    // Laravel will automatically resolve the concrete class bound to this interface
    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;   // Assign the injected repository
    }

    // Example action that uses the repository
    public function index()
    {
        $allUsers = $this->users->all();   // Calls the repository method
        return view('users.index', compact('allUsers'));
    }
}

// In a service provider you bind the interface to a concrete implementation
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\EloquentUserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the Eloquent implementation as a singleton
        $this->app->singleton(UserRepositoryInterface::class, function ($app) {
            return new EloquentUserRepository();   // The concrete class
        });
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs improve readability by allowing you to break complex queries into logical building blocks. When a CTE is defined with the RECURSIVE keyword, it can reference itself to produce hierarchical or iterative results, such as traversing a tree structure. Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until a termination condition is met. This feature is useful for generating series, processing organization charts, or handling bill‑of‑materials data without needing stored procedures.

Code example (MySQL 8.0+):
-- Define a recursive CTE to generate a sequence of numbers from 1 to 10
WITH RECURSIVE numbers AS (
    SELECT 1 AS n                      -- Anchor member: start with 1
    UNION ALL
    SELECT n + 1 FROM numbers WHERE n < 10   -- Recursive member: add 1 while n < 10
)
SELECT n FROM numbers ORDER BY n;      -- Final query returns the generated series

-- Example using a hierarchical employees table (id, name, manager_id)
WITH RECURSIVE employee_hierarchy AS (
    SELECT id, name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id IS NULL               -- Anchor: top‑level managers
    UNION ALL
    SELECT e.id, e.name, e.manager_id, eh.level + 1
    FROM employees e
    JOIN employee_hierarchy eh ON e.manager_id = eh.id   -- Recursive join
)
SELECT id, name, manager_id, level
FROM employee_hierarchy
ORDER BY level, manager_id;            -- Result shows each employee with their depth in the hierarchy.
*/

/* JavaScript
JavaScript Closures  

A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
It allows inner functions to remember and interact with variables defined in outer functions after the outer function has finished.  
Closures are useful for data privacy, function factories, and maintaining state across multiple calls.  
Because the inner function holds references to the outer variables, those variables are not garbage‑collected until the closure itself is no longer reachable.  
Understanding closures is essential for mastering asynchronous code, callbacks, and module patterns in JavaScript.  

// Example: function factory that creates a counter using a closure
function createCounter(initialValue) {          // outer function with a private variable
    let count = initialValue;                  // this variable is captured by the inner function
    return function increment(step = 1) {      // inner function forms a closure over `count`
        count += step;                         // modifies the captured variable
        return count;                          // returns the updated count
    };
}

// Using the closure
const counterA = createCounter(0);  // counterA has its own private `count`
console.log(counterA());            // 1
console.log(counterA(5));           // 6

const counterB = createCounter(10); // separate closure with its own `count`
console.log(counterB());            // 11
console.log(counterA());            // 7  (counterA retains its own state)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets you guide a language model by providing a handful of example interactions before the actual user query.  
By placing the examples in the system or user messages, the model can infer the desired format, tone, or reasoning pattern.  
This technique works well for tasks like data extraction, style conversion, or custom Q&A without fine‑tuning.  
When using the Chat Completion endpoint, you construct a message list where the first few pairs are demonstrations, followed by the real request.  
The model then treats the examples as a template and generates output consistent with them.  

Code example (Python, uses the openai library):  

import os  
import openai  

# Load your API key from an environment variable or a config file  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few demonstration exchanges that show the desired behavior  
few_shot_messages = [  
    {"role": "system", "content": "You are an assistant that extracts product names and prices from short descriptions."},  
    {"role": "user", "content": "The new Acme smartwatch costs $199 and has a 48‑hour battery life."},  
    {"role": "assistant", "content": "Product: Acme smartwatch\nPrice: $199"},  
    {"role": "user", "content": "Our latest headphones are priced at $89 and feature noise cancellation."},  
    {"role": "assistant", "content": "Product: headphones\nPrice: $89"}  
]  

# The actual query we want the model to handle using the same pattern  
user_query = {"role": "user", "content": "Check out the sleek XPhone Pro for $999 with a 6.7‑inch display."}  

# Combine the demonstrations with the new request  
messages = few_shot_messages + [user_query]  

# Call the Chat Completion API  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",          # choose a model that supports chat completions  
    messages=messages,            # the full prompt including few‑shot examples  
    temperature=0.0               # deterministic output for extraction tasks  
)  

# Print the assistant’s reply, which follows the demonstrated format  
print(response.choices[0].message.content)   # Expected: Product: XPhone Pro\nPrice: $999  
*/

