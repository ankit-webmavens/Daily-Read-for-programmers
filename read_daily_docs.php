<?php
// 2026-09-14 06:55:36

/* PHP
PHP PDO (PHP Data Objects) – Secure Database Interaction  
The PDO extension provides a consistent interface for accessing multiple database types (MySQL, PostgreSQL, SQLite, etc.) using the same functions.  
It supports prepared statements, which separate SQL logic from data values and protect against SQL injection attacks.  
Connection parameters (host, database name, username, password) are passed to the PDO constructor, which returns a PDO object.  
Error handling can be configured to throw exceptions, making debugging and error tracking easier.  
Fetching results can be done in various formats (associative array, numeric array, objects) using fetch modes.

<?php
// Database connection parameters
$host = 'localhost';
$db   = 'sample_db';
$user = 'db_user';
$pass = 'secret_password';
$charset = 'utf8mb4';

// Data Source Name (DSN) string tells PDO which driver to use and how to connect
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options – enable exceptions and set default fetch mode to associative array
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch rows as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    // Create a new PDO instance (establishes the database connection)
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // If connection fails, display the error message and stop execution
    die('Connection failed: ' . $e->getMessage());
}

// Example of a prepared SELECT statement with a placeholder
$sql = "SELECT id, name, email FROM users WHERE status = :status";
$stmt = $pdo->prepare($sql);                // Prepare the SQL statement
$stmt->execute(['status' => 'active']);     // Bind the value and execute

// Loop through the result set
while ($row = $stmt->fetch()) {
    // $row is an associative array: ['id'=>..., 'name'=>..., 'email'=>...]
    echo "ID: {$row['id']} - Name: {$row['name']} - Email: {$row['email']}\n";
}

// Example of an INSERT using named placeholders
$insertSql = "INSERT INTO users (name, email, status) VALUES (:name, :email, :status)";
$insertStmt = $pdo->prepare($insertSql);
$insertStmt->execute([
    'name'   => 'Jane Doe',
    'email'  => 'jane@example.com',
    'status' => 'active'
]);

echo "New user inserted with ID: " . $pdo->lastInsertId() . "\n";
?>
*/

/* Laravel
Laravel Service Container and Binding  

The service container is Laravel’s powerful inversion of control (IoC) system that resolves class dependencies automatically.  
You register bindings in a service provider, telling the container which concrete class to instantiate for an abstract type.  
When a class is type‑hinted in a controller or another class, the container injects the appropriate implementation.  
Bindings can be singleton (one instance shared) or transient (new instance each time).  
This mechanism enables clean, testable code by decoupling implementations from their contracts.  

Example – binding an interface to an implementation and using it in a controller  

<?php  
namespace App\Providers;  

use Illuminate\Support\ServiceProvider;  
use App\Contracts\PaymentGateway;  
use App\Services\StripePaymentGateway;  

class AppServiceProvider extends ServiceProvider  
{  
    public function register()  
    {  
        // Bind the interface to a concrete class  
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);  

        // If you want a single shared instance, use singleton instead  
        // $this->app->singleton(PaymentGateway::class, StripePaymentGateway::class);  
    }  
}  

---  

<?php  
namespace App\Contracts;  

interface PaymentGateway  
{  
    public function charge(float $amount);  
}  

---  

<?php  
namespace App\Services;  

use App\Contracts\PaymentGateway;  

class StripePaymentGateway implements PaymentGateway  
{  
    public function charge(float $amount)  
    {  
        // Logic to charge via Stripe API  
        return "Charged $$amount using Stripe.";  
    }  
}  

---  

<?php  
namespace App\Http\Controllers;  

use App\Contracts\PaymentGateway;  

class OrderController extends Controller  
{  
    protected $paymentGateway;  

    // Laravel automatically injects the bound implementation  
    public function __construct(PaymentGateway $paymentGateway)  
    {  
        $this->paymentGateway = $paymentGateway;  
    }  

    public function store()  
    {  
        $result = $this->paymentGateway->charge(99.99);  
        return response()->json(['message' => $result]);  
    }  
}  
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by allowing you to break complex queries into logical building blocks.  
When the WITH clause includes the RECURSIVE keyword, the CTE can refer to itself, enabling hierarchical or tree‑structured data retrieval.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly joins the previous level until a termination condition is met.  
They are useful for traversing parent‑child relationships such as organizational charts, file systems, or bill‑of‑materials structures.  

Code example:  
-- Create a sample table representing an employee hierarchy  
CREATE TABLE employees (  
    emp_id INT PRIMARY KEY,  
    emp_name VARCHAR(50),  
    manager_id INT NULL   -- NULL indicates top‑level manager  
);  

-- Insert example data  
INSERT INTO employees VALUES (1,'Alice',NULL),(2,'Bob',1),(3,'Carol',1),(4,'Dave',2),(5,'Eve',2);  

-- Recursive CTE to list each employee with their reporting chain depth  
WITH RECURSIVE emp_hierarchy AS (  
    -- Anchor member: start with top‑level managers (manager_id IS NULL)  
    SELECT emp_id, emp_name, manager_id, 0 AS depth  
    FROM employees  
    WHERE manager_id IS NULL  

    UNION ALL  

    -- Recursive member: join employees to their managers from the previous level  
    SELECT e.emp_id, e.emp_name, e.manager_id, eh.depth + 1  
    FROM employees e  
    JOIN emp_hierarchy eh ON e.manager_id = eh.emp_id  
)  
SELECT emp_id, emp_name, manager_id, depth  
FROM emp_hierarchy  
ORDER BY depth, manager_id, emp_id;  



-- The result shows each employee, their manager, and the depth of their position in the hierarchy.  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to the variables of its lexical scope even after that outer function has finished executing.  
It allows private data encapsulation, enabling patterns like module design and function factories.  
Closures are created every time a function is defined, capturing the surrounding environment at that moment.  
They are essential for asynchronous code, callbacks, and maintaining state across invocations.  
Understanding closures helps avoid common pitfalls such as unintended variable sharing and memory leaks.

Code Example:
function makeCounter() {
    // Private variable that will be captured by the inner function
    let count = 0;
    // The inner function forms a closure over 'count'
    return function increment() {
        count += 1;            // Modify the captured variable
        console.log('Current count:', count);
    };
}

// Create a new counter instance
const counterA = makeCounter();
counterA(); // Current count: 1
counterA(); // Current count: 2

// Create another independent counter
const counterB = makeCounter();
counterB(); // Current count: 1
counterB(); // Current count: 2

// The two counters maintain separate 'count' variables because each closure captures its own lexical environment.
*/

/* AI
Topic: Chain‑of‑Thought Prompting for Complex Reasoning  

Explanation:  
Chain‑of‑Thought (CoT) prompting guides large language models to produce intermediate reasoning steps before delivering a final answer, improving accuracy on multi‑step problems. By explicitly asking the model to “think step‑by‑step,” it generates a logical trace that can be inspected or used for debugging. CoT works well for arithmetic, logic puzzles, and code synthesis where hidden dependencies exist. The technique is model‑agnostic and can be combined with few‑shot examples to reinforce the desired reasoning pattern. Proper prompt design balances brevity with enough context to trigger the stepwise thinking behavior.

Code example (Python, OpenAI API) with comments:  

import os  
import openai  

# Load your OpenAI API key from environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def chain_of_thought(question: str) -> str:  
    # Construct a prompt that explicitly asks for step‑by‑step reasoning  
    prompt = (  
        "You are a helpful AI assistant. Solve the following problem by reasoning step by step, then give the final answer.\n\n"  
        f"Problem: {question}\n\n"  
        "Answer:"  
    )  

    response = openai.Completion.create(  
        engine="text-davinci-003",        # Choose a model that supports CoT  
        prompt=prompt,  
        max_tokens=300,  
        temperature=0.2,                  # Low temperature for deterministic reasoning  
        top_p=1,  
        stop=None,  
        n=1,  
    )  

    # The model returns both the reasoning trace and the final answer  
    return response.choices[0].text.strip()  

# Example usage  
question = "If a train travels 150 km in 2 hours and then 90 km in 1.5 hours, what is the average speed of the whole journey?"  
print(chain_of_thought(question))  
*/

