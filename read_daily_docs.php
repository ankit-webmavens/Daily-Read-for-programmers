<?php
// 2026-09-29 07:26:51

/* PHP
Topic: PHP PDO Prepared Statements

Explanation:
Prepared statements in PDO separate the SQL query from its parameters, improving security by preventing SQL injection. 
They allow the database engine to parse and compile the statement once, then execute it multiple times with different values. 
Binding parameters ensures correct data types are sent to the database. 
Errors are easier to handle because the statement preparation is distinct from execution. 
Using prepared statements also often yields better performance for repeated queries.

Code example:
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exceptions for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', 'alice', PDO::PARAM_STR);
$stmt->bindValue(':email', 'alice@example.com', PDO::PARAM_STR);

// Execute the statement
if ($stmt->execute()) {
    echo "New user inserted with ID: " . $pdo->lastInsertId();
} else {
    echo "Insert failed.";
}
*/

/* Laravel
Topic: Laravel Service Container & Automatic Dependency Injection

Explanation:
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection automatically. It resolves objects by inspecting their constructors, allowing you to type‑hint interfaces or classes and have the container provide the appropriate implementation. This promotes loose coupling, easier testing, and cleaner controller code. You can bind concrete classes, interfaces, or even closures to the container, and specify singleton or transient lifetimes. When a class is resolved, the container injects all required dependencies recursively, simplifying complex object graphs.

Code Example (app/Providers/AppServiceProvider.php):

public function register()
{
    // Bind an interface to a concrete implementation as a singleton
    $this->app->singleton(
        App\Contracts\PaymentGateway::class,
        App\Services\StripePaymentGateway::class
    );
}

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;   // Interface type‑hinted
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $gateway;

    // Laravel automatically injects the concrete class bound above
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;   // $gateway is an instance of StripePaymentGateway
    }

    public function store(Request $request)
    {
        $orderData = $request->only(['amount', 'currency']);
        // Use the injected payment gateway to process the payment
        $result = $this->gateway->charge($orderData['amount'], $orderData['currency']);

        if ($result->successful()) {
            // ...handle successful order
        }

        // ...handle failure
    }
}

// app/Contracts/PaymentGateway.php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount, string $currency);
}

// app/Services/StripePaymentGateway.php
namespace App\Services;

use App\Contracts\PaymentGateway;
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGateway
{
    protected $stripe;

    public function __construct()
    {
        // Initialize Stripe client (could also be injected)
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function charge(float $amount, string $currency)
    {
        // Perform the charge using Stripe's API
        return $this->stripe->charges->create([
            'amount' => $amount * 100,   // Stripe expects amount in cents
            'currency' => $currency,
            'source' => 'tok_visa',      // In real apps, use a token from the front‑end
        ]);
    }
}
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTEs) in MySQL

Explanation:
A recursive CTE lets you query hierarchical or tree‑structured data in a single SELECT statement.  
You define an anchor query that returns the first level of rows, then a recursive member that references the CTE itself to walk deeper levels.  
The recursion stops when the recursive member returns no rows, preventing infinite loops.  
MySQL 8.0+ supports recursive CTEs using the WITH RECURSIVE clause.  
Typical use cases include organizational charts, bill‑of‑materials, and graph traversals.

Code example with comments:
-- Sample table representing an employee hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL   -- NULL means top‑level manager
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),      -- CEO
(2, 'Bob', 1),           -- reports to Alice
(3, 'Carol', 1),         -- reports to Alice
(4, 'Dave', 2),          -- reports to Bob
(5, 'Eve', 2),           -- reports to Bob
(6, 'Frank', 3);         -- reports to Carol

-- Recursive CTE to list all subordinates of a given manager (e.g., manager_id = 1)
WITH RECURSIVE subordinates AS (
    -- Anchor member: start with the direct reports of the chosen manager
    SELECT emp_id, emp_name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id = 1

    UNION ALL

    -- Recursive member: find employees whose manager is in the previous level
    SELECT e.emp_id, e.emp_name, e.manager_id, s.level + 1
    FROM employees e
    INNER JOIN subordinates s ON e.manager_id = s.emp_id
)
SELECT emp_id, emp_name, manager_id, level
FROM subordinates
ORDER BY level, emp_id;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is created when an inner function accesses variables from an outer function after the outer function has finished executing.  
The inner function retains a reference to the outer scope's variables, allowing them to persist across multiple calls.  
Closures are useful for data encapsulation, creating private state, and implementing function factories.  
They enable patterns such as memoization, currying, and module-like structures without using classes.  
Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state between functions.

Code example:  
function makeCounter(initialValue) {  
    // The variable count is private to the outer function's scope  
    let count = initialValue;  

    // The returned inner function forms a closure over count  
    return function() {  
        // Each call increments and returns the private count  
        count += 1;  
        return count;  
    };  
}  

// Create a new counter starting at 10  
const counter = makeCounter(10);  

console.log(counter()); // 11  
console.log(counter()); // 12  
console.log(counter()); // 13  

// A second counter has its own independent private count  
const anotherCounter = makeCounter(100);  
console.log(anotherCounter()); // 101  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s GPT‑4 API  

Explanation:  
Few‑shot prompting lets you guide a large language model by providing a handful of example input‑output pairs inside the prompt. This technique reduces the need for fine‑tuning while achieving task‑specific behavior. By carefully selecting diverse, representative examples and clearly separating them with delimiters, the model can infer the pattern you expect it to follow. Adjusting temperature, max tokens, and stop sequences further refines the output quality. This approach is especially useful for rapid prototyping, custom data extraction, and on‑the‑fly text transformation without managing separate model versions.  

Code example (Python, using openai library):  

import os  
import openai  

# Load your API key from an environment variable for security  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def few_shot_completion(user_query):  
    # Define a prompt that includes two demonstration Q&A pairs  
    prompt = (  
        "Q: Translate the following English sentence to French: \"The cat sits on the mat.\"\n"  
        "A: Le chat s'assoit sur le tapis.\n\n"  
        "Q: Translate the following English sentence to French: \"She enjoys reading books.\"\n"  
        "A: Elle aime lire des livres.\n\n"  
        f"Q: Translate the following English sentence to French: \"{user_query}\"\n"  
        "A:"  
    )  

    response = openai.ChatCompletion.create(  
        model="gpt-4",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.2,          # low temperature for deterministic translation  
        max_tokens=60,            # enough for a short sentence  
        stop=["\n"]               # stop after the first line of the answer  
    )  

    # Extract the model's answer from the response payload  
    answer = response.choices[0].message.content.strip()  
    return answer  

# Example usage  
english_sentence = "The weather is pleasant today."  
french_translation = few_shot_completion(english_sentence)  
print(f"English: {english_sentence}")  
print(f"French: {french_translation}")  
*/

