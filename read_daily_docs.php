<?php
// 2026-08-21 02:48:17

/* PHP
Topic: Prepared Statements with PDO

Explanation:  
Prepared statements separate SQL code from data, which prevents SQL injection by ensuring that user‑supplied values are never executed as part of the query. PDO (PHP Data Objects) provides a consistent interface for prepared statements across many database drivers. You prepare the SQL once, then bind values or pass them directly when executing, allowing the database to reuse the execution plan for better performance. Errors can be handled with exceptions, making debugging easier. This approach also simplifies handling of different data types, such as dates and binary blobs.

Code example with comments:
<?php
// Enable PDO exceptions for error handling
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO instance (replace DSN, username, password with real values)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass', $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders and execute
$stmt->execute([
    ':username'   => 'alice',
    ':email'      => 'alice@example.com',
    ':created_at' => date('Y-m-d H:i:s')
]);

// Prepare a SELECT statement to fetch a user by email
$selectSql = "SELECT id, username, email FROM users WHERE email = :email";
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':email' => 'alice@example.com']);

// Fetch the result as an associative array
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
Topic: Laravel Service Container & Automatic Dependency Injection  

Explanation:  
The Service Container is the heart of Laravel’s inversion of control (IoC) system. It resolves class dependencies automatically, allowing you to type‑hint objects in constructors or controller methods without manually creating them. By binding abstractions to concrete implementations, you can swap implementations (e.g., for testing) without changing the consuming code. When the container builds an object, it inspects its constructor signature and injects the required dependencies recursively. This promotes clean, testable code and decouples components throughout the application.  

Code example (plain PHP with comments):  

<?php  
namespace App\Services;  

// Define an interface that describes the contract for a payment gateway.  
interface PaymentGatewayInterface {  
    public function charge(float $amount);  
}  

// Concrete implementation using Stripe.  
class StripeGateway implements PaymentGatewayInterface {  
    public function charge(float $amount) {  
        // Imagine calling Stripe's SDK here.  
        return "Charged \${$amount} via Stripe.";  
    }  
}  

// Concrete implementation using PayPal (useful for testing or alternative provider).  
class PayPalGateway implements PaymentGatewayInterface {  
    public function charge(float $amount) {  
        // Imagine calling PayPal's SDK here.  
        return "Charged \${$amount} via PayPal.";  
    }  
}  

// Service Provider where bindings are registered with the container.  
namespace App\Providers;  

use Illuminate\Support\ServiceProvider;  
use App\Services\PaymentGatewayInterface;  
use App\Services\StripeGateway;  

class PaymentServiceProvider extends ServiceProvider {  
    public function register() {  
        // Bind the interface to a concrete class.  
        // Swap StripeGateway for PayPalGateway to change implementation globally.  
        $this->app->bind(PaymentGatewayInterface::class, StripeGateway::class);  
    }  
}  

// A controller that receives the gateway via constructor injection.  
namespace App\Http\Controllers;  

use App\Services\PaymentGatewayInterface;  
use Illuminate\Http\Request;  

class CheckoutController extends Controller {  
    protected $gateway;  

    // The container automatically injects the concrete implementation.  
    public function __construct(PaymentGatewayInterface $gateway) {  
        $this->gateway = $gateway;  
    }  

    public function process(Request $request) {  
        $amount = $request->input('amount');  
        $result = $this->gateway->charge((float) $amount);  
        return response()->json(['message' => $result]);  
    }  
}  

// Usage in routes/web.php (or api.php)  
// Route::post('/checkout', [CheckoutController::class, 'process']);   (no markdown)  
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and can improve query readability, especially for complex subqueries. Recursive CTEs allow you to perform hierarchical or tree‑like queries by repeatedly applying a query to its own result set. They are useful for traversing organizational charts, category trees, or generating series of numbers. MySQL 8.0+ fully supports both non‑recursive and recursive CTEs.

Code example (creating an employee hierarchy and retrieving all subordinates of a manager):

-- Sample data: employee table with id, name, manager_id
CREATE TABLE employees (
    id INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    manager_id INT NULL,
    FOREIGN KEY (manager_id) REFERENCES employees(id)
);

INSERT INTO employees (id, name, manager_id) VALUES
(1, 'Alice', NULL),        -- top‑level manager
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'Dave', 2),
(5, 'Eve', 2),
(6, 'Frank', 3);

-- Recursive CTE to list all subordinates under manager with id = 1 (Alice)
WITH RECURSIVE subordinates AS (
    -- Anchor member: start with the direct reports of Alice
    SELECT id, name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id = 1
    UNION ALL
    -- Recursive member: find employees whose manager is in the previous level
    SELECT e.id, e.name, e.manager_id, s.level + 1
    FROM employees e
    INNER JOIN subordinates s ON e.manager_id = s.id
)
SELECT id, name, manager_id, level
FROM subordinates
ORDER BY level, name;

-- The result shows Bob, Carol (level 1) and then Dave, Eve, Frank (level 2) as subordinates of Alice.
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical scope even when executed outside that scope.  
It allows inner functions to remember variables from the outer function after the outer function has finished.  
Closures are useful for data privacy, creating function factories, and maintaining state between calls.  
They are created automatically whenever a function references variables from its outer environment.  
Understanding closures helps avoid common pitfalls like unintended shared references in loops.

Code example with comments:
// Outer function creates a private variable `count`
function createCounter() {
    let count = 0;                     // This variable is local to createCounter
    // Inner function forms a closure over `count`
    return function increment() {
        count++;                       // Modifies the closed-over variable
        console.log('Current count:', count);
    };
}

// Use the closure
const counterA = createCounter();      // counterA has its own `count`
counterA(); // Current count: 1
counterA(); // Current count: 2

const counterB = createCounter();      // counterB gets a fresh `count`
counterB(); // Current count: 1
counterA(); // Current count: 3   (counterA's count continues independently)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI GPT‑4 API  

Explanation:  
Few‑shot prompting supplies a small number of example input‑output pairs within the prompt to guide the model’s behavior on new queries. This technique is especially useful when you lack large training data but need the model to follow a specific format or style. By carefully selecting representative examples, you can steer GPT‑4 to perform tasks such as data extraction, classification, or code generation with high accuracy. The prompt must include clear delimiters and instructions to separate examples from the user request. Adjusting temperature to 0 and limiting max tokens ensures deterministic, concise responses.  

Code example (Python, using openai library):  

import os  
import openai  

# Set your OpenAI API key (ensure it is stored securely)  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def few_shot_completion(user_query):  
    # Construct the prompt with two examples of the desired behavior  
    prompt = (  
        "Extract the product name and price from a description.\n"  
        "Example 1:\n"  
        "Description: \"The Acme Super Blender costs $99.99 and comes with a 2‑year warranty.\"\n"  
        "Answer: {\"product\": \"Acme Super Blender\", \"price\": 99.99}\n\n"  
        "Example 2:\n"  
        "Description: \"Enjoy the fresh taste of Sunny Citrus Juice, now only $3.49 per bottle.\"\n"  
        "Answer: {\"product\": \"Sunny Citrus Juice\", \"price\": 3.49}\n\n"  
        "Now process the following description:\n"  
        f"Description: \"{user_query}\"\n"  
        "Answer:"  
    )  

    response = openai.ChatCompletion.create(  
        model="gpt-4",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0,          # deterministic output  
        max_tokens=100,         # limit token usage  
        top_p=1,                # standard nucleus sampling  
        frequency_penalty=0,  
        presence_penalty=0  
    )  

    # Return the model's raw answer text  
    return response.choices[0].message["content"].strip()  

# Example usage  
query = "The ZenBook Pro 14-inch laptop is now priced at $1,299.00 with free shipping."  
print(few_shot_completion(query))  
*/

