<?php
// 2026-11-11 08:17:09

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:  
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
PDO (PHP Data Objects) provides a consistent interface for accessing different databases.  
You prepare the SQL query once, then bind values to placeholders before execution.  
This approach also improves performance when the same statement is run multiple times.  
Error handling can be managed with exceptions, making debugging easier.

Code example with comments:  

<?php
// Create a new PDO instance for a MySQL database
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exceptions for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare(
        "INSERT INTO users (username, email, created_at) 
         VALUES (:username, :email, NOW())"
    );

    // Bind values to the placeholders
    $stmt->bindParam(':username', $usernameInput);
    $stmt->bindParam(':email', $emailInput);

    // Example data to insert
    $usernameInput = 'johndoe';
    $emailInput = 'john@example.com';

    // Execute the prepared statement
    $stmt->execute();

    echo "User successfully added.";
} catch (PDOException $e) {
    // Handle any errors that occur during the process
    echo "Database error: " . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic injection. It resolves objects by reading type hints in constructors or method signatures, allowing you to keep your code loosely coupled. By binding interfaces to concrete classes, you can swap implementations without changing dependent code. The container also supports contextual bindings for more complex scenarios. Using dependency injection promotes testability and cleaner architecture throughout the application.  

Code example with comments:  

<?php  
namespace App\Http\Controllers;  

use App\Services\ReportService;  
use Illuminate\Http\Request;  

class ReportController extends Controller  
{  
    // The ReportService instance is automatically injected by the container  
    protected $reportService;  

    public function __construct(ReportService $reportService)  
    {  
        $this->reportService = $reportService;  
    }  

    // Action that utilizes the injected service to generate a report  
    public function generate(Request $request)  
    {  
        $type = $request->input('type', 'summary');  
        // Business logic is delegated to the service layer  
        $result = $this->reportService->create($type);  
        return response()->json($result);  
    }  
}  

// Service class that contains the report generation logic  
namespace App\Services;  

class ReportService  
{  
    // Simple method illustrating business logic based on a report type  
    public function create(string $type)  
    {  
        // In a real scenario this could involve complex data processing  
        return [  
            'status' => 'success',  
            'type'   => $type,  
            'data'   => [] // placeholder for generated report data  
        ];  
    }  
}  

// Optional: binding an interface to the concrete service in a service provider  
// This allows you to type‑hint the interface elsewhere and still receive ReportService  
namespace App\Providers;  

use Illuminate\Support\ServiceProvider;  
use App\Contracts\ReportGenerator;  
use App\Services\ReportService;  

class AppServiceProvider extends ServiceProvider  
{  
    public function register()  
    {  
        $this->app->bind(  
            ReportGenerator::class,   // Interface or abstract class  
            ReportService::class      // Concrete implementation  
        );  
    }  
}  
*/

/* MySQL
Topic: MySQL Common Table Expressions (CTE) and Recursive Queries

Explanation:
- A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.
- CTEs improve readability by allowing you to break complex queries into logical sub‑queries.
- They are defined using the WITH clause and can be either non‑recursive (single use) or recursive (self‑referencing).
- Recursive CTEs are useful for hierarchical data such as organizational charts, category trees, or date ranges.
- MySQL supports CTEs from version 8.0 onward, and they are scoped to the statement in which they appear.

Code example (non‑recursive and recursive CTEs with comments):

-- Example 1: Simple (non‑recursive) CTE to calculate total sales per region
WITH regional_sales AS (
    SELECT region,
           SUM(amount) AS total_amount
    FROM sales
    GROUP BY region
)
SELECT region,
       total_amount,
       CASE
           WHEN total_amount > 100000 THEN 'High'
           ELSE 'Normal'
       END AS performance_category
FROM regional_sales
ORDER BY total_amount DESC;

-- Example 2: Recursive CTE to generate an employee hierarchy
WITH RECURSIVE emp_hierarchy AS (
    -- Anchor member: start with top‑level managers (no manager_id)
    SELECT employee_id,
           name,
           manager_id,
           1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join employees to their managers
    SELECT e.employee_id,
           e.name,
           e.manager_id,
           eh.level + 1 AS level
    FROM employees e
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.employee_id
)
SELECT employee_id,
       name,
       manager_id,
       level
FROM emp_hierarchy
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to the variables of its lexical scope even after that outer function has finished executing.  
Closures enable data privacy by allowing inner functions to manipulate variables that are not directly accessible from the outside.  
They are created every time a function is defined, capturing the surrounding environment at that moment.  
Closures are heavily used for callbacks, event handlers, and functional programming patterns.  
Understanding closures helps prevent common bugs related to variable scope and asynchronous code.

Code example with comments:
function makeCounter() {
    let count = 0; // private variable, not reachable from outside
    return function() {
        count++; // modify the private count
        return count; // return the current value
    };
}
const counter = makeCounter(); // counter now holds a closure over count
console.log(counter()); // prints 1
console.log(counter()); // prints 2
console.log(counter()); // prints 3

The inner function keeps a reference to the 'count' variable, demonstrating how closures preserve access to their original scope.
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models  

Explanation:  
Few‑shot prompting supplies a small number of example input‑output pairs within the same request, guiding the model toward the desired format and behavior without fine‑tuning.  
By carefully selecting diverse yet representative examples, you can steer the model to generalize to new, unseen inputs that follow the same pattern.  
The technique works well for tasks like data extraction, code generation, or style transfer where a strict schema is needed.  
Prompt length is limited, so balance the number of examples with the complexity of the task; usually 2‑4 examples are sufficient.  
When combined with system messages that set the overall role, few‑shot prompts become a powerful, zero‑cost way to customize model outputs.  

Code example (Python, using OpenAI’s API):  

import os  
import openai  

# Load your API key from an environment variable for safety  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def extract_contacts(text: str) -> list:  
    """Extract name‑email pairs from a free‑form paragraph using a few‑shot prompt."""  
    # Define the system message that establishes the assistant’s role  
    system_msg = {  
        "role": "system",  
        "content": "You are a helpful assistant that extracts contact information and returns a JSON array of objects with 'name' and 'email' fields."  
    }  

    # Provide a few examples of the desired input‑output behavior  
    examples = [  
        {  
            "role": "user",  
            "content": "Alice Johnson, alice@example.com; Bob Smith bob.smith@work.org"  
        },  
        {  
            "role": "assistant",  
            "content": '[{"name": "Alice Johnson", "email": "alice@example.com"}, {"name": "Bob Smith", "email": "bob.smith@work.org"}]'  
        },  
        {  
            "role": "user",  
            "content": "Contact: Dr. Emily Chen <e.chen@university.edu>."  
        },  
        {  
            "role": "assistant",  
            "content": '[{"name": "Dr. Emily Chen", "email": "e.chen@university.edu"}]'  
        }  
    ]  

    # Append the actual user query after the examples  
    user_msg = {  
        "role": "user",  
        "content": text  
    }  

    # Build the message list in order: system, examples, then the new query  
    messages = [system_msg] + examples + [user_msg]  

    # Call the chat completion endpoint  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",          # choose a model that supports chat completion  
        messages=messages,  
        temperature=0,               # deterministic output for extraction tasks  
        max_tokens=200               # enough to hold the JSON result  
    )  

    # The model returns the JSON string in the 'content' field of the assistant message  
    json_result = response["choices"][0]["message"]["content"]  
    return json_result  

# Example usage  
sample_text = "Please reach out to Carlos Ruiz (c.ruiz@startup.io) and Maya Patel – maya.patel@consulting.com for the meeting."  
print(extract_contacts(sample_text))   # -> [{"name":"Carlos Ruiz","email":"c.ruiz@startup.io"},{"name":"Maya Patel","email":"maya.patel@consulting.com"}]
*/

