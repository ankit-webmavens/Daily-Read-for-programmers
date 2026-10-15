<?php
// 2026-10-15 06:21:27

/* PHP
Topic: PHP PDO Prepared Statements for Secure Database Access

Explanation:
Prepared statements separate SQL logic from data, preventing SQL injection attacks by sending the query structure to the database before the actual values. PDO (PHP Data Objects) provides a uniform interface for interacting with different database systems using the same functions. With prepared statements, placeholders are used in the SQL query and later bound to variables. The database engine parses and compiles the query once, then can efficiently execute it multiple times with different parameters. Using PDO also allows handling errors through exceptions and supports transactions for atomic operations.

Code example with comments:
<?php
// Enable exception mode for PDO
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare(
        'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())'
    );

    // Sample data to insert
    $data = [
        ':username' => 'johndoe',
        ':email'    => 'johndoe@example.com'
    ];

    // Execute the prepared statement with the data array
    $stmt->execute($data);

    echo "New user added with ID: " . $pdo->lastInsertId();
} catch (PDOException $e) {
    // Handle any errors
    echo "Database error: " . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container & Automatic Dependency Resolution

Explanation:  
The Service Container is the core of Laravel’s inversion of control (IoC) system. It manages class dependencies and performs automatic injection when a class or method type‑hints a dependency. By binding abstractions (interfaces) to concrete implementations, you can easily swap implementations without changing the consuming code. The container resolves dependencies recursively, creating objects with all their required sub‑dependencies. This makes testing, decoupling, and code reuse much simpler and keeps controllers thin.

Code Example (app/Providers/AppServiceProvider.php):
public function register()
{
    // Bind an interface to a concrete class so the container knows what to inject
    $this->app->bind(
        App\Contracts\PaymentGateway::class,   // abstraction
        App\Services\StripePaymentGateway::class // concrete implementation
    );
}

Controller using automatic resolution (app/Http/Controllers/OrderController.php):
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // The PaymentGateway instance is automatically injected by the container
    public function store(Request $request, PaymentGateway $gateway)
    {
        // Validate and create order data (omitted for brevity)

        // Use the injected gateway to process the payment
        $result = $gateway->charge($request->user(), $request->amount);

        if ($result->successful()) {
            // Continue order fulfillment
        } else {
            // Handle payment failure
        }
    }
}

Concrete implementation (app/Services/StripePaymentGateway.php):
namespace App\Services;

use App\Contracts\PaymentGateway;
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGateway
{
    protected $stripe;

    public function __construct()
    {
        // Initialise the Stripe client with secret key from config
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    // Method required by the PaymentGateway contract
    public function charge($user, $amount)
    {
        // Example charge creation using Stripe's API
        return $this->stripe->charges->create([
            'amount' => $amount * 100, // amount in cents
            'currency' => 'usd',
            'customer' => $user->stripe_customer_id,
            'description' => 'Order payment',
        ]);
    }
}

Contract definition (app/Contracts/PaymentGateway.php):
namespace App\Contracts;

interface PaymentGateway
{
    // All payment gateways must implement a charge method
    public function charge($user, $amount);
}
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries in MySQL

Explanation:
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve query readability by separating complex logic from the main query.  
MySQL supports both non‑recursive and recursive CTEs, the latter allowing hierarchical or graph‑like data traversal.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself.  
They are useful for tasks such as building organizational charts, processing bill‑of‑materials, or generating series of numbers.  

Code example (comments start with --):
WITH RECURSIVE numbers AS (                                    -- Define a recursive CTE named numbers
    SELECT 1 AS n                                              -- Anchor member: start with 1
    UNION ALL
    SELECT n + 1 FROM numbers WHERE n < 10                    -- Recursive member: add 1 until 10
)
SELECT n, n * n AS square                                     -- Main query: list numbers and their squares
FROM numbers
ORDER BY n;                                                    -- Result: rows 1 through 10 with squares  
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context. This allows the inner function to remember variables from the outer function even after the outer function has finished executing. Closures are useful for data privacy, creating function factories, and maintaining state in asynchronous code. They are created every time a function is defined, not when it is called. Understanding closures helps avoid common pitfalls with variable scoping and memory leaks.

Code example with comments:
function createCounter(start) {
    // The variable count is private to the returned function
    let count = start;

    // The inner function forms a closure over count
    return function() {
        // Increment the private count each time the function is called
        count++;
        // Return the updated count value
        return count;
    };
}

// Create a new counter starting at 5
const counter = createCounter(5);

// Call the counter multiple times
console.log(counter()); // 6
console.log(counter()); // 7
console.log(counter()); // 8

// The original count variable is not directly accessible from the outside
// console.log(count); // ReferenceError: count is not defined

// Each call to createCounter produces an independent closure
const anotherCounter = createCounter(10);
console.log(anotherCounter()); // 11
console.log(counter()); // 9 (the first counter continues its own sequence)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s ChatCompletion API  

Explanation:  
1. Few‑shot prompting supplies the model with a handful of example input‑output pairs to steer its behavior without fine‑tuning.  
2. By embedding these demonstrations in the system or user messages, the model infers the pattern and applies it to new queries.  
3. This technique works well for tasks like text classification, format conversion, or generating structured data.  
4. The quality of the output depends on the clarity and relevance of the examples, as well as the temperature setting.  
5. Using the OpenAI Python client, you can construct a prompt list that alternates between “user” and “assistant” roles to implement few‑shot learning.  

Code example (Python, requires `openai` package and a valid API key):

import os
import openai

# Set your API key – replace with your own or use an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt: two examples of converting a bullet list into JSON
messages = [
    {"role": "system", "content": "You are a helpful assistant that converts bullet lists into JSON arrays."},
    {"role": "user", "content": "- Apple\n- Banana\n- Cherry"},
    {"role": "assistant", "content": '[ "Apple", "Banana", "Cherry" ]'},
    {"role": "user", "content": "- Red\n- Green\n- Blue"},
    {"role": "assistant", "content": '[ "Red", "Green", "Blue" ]'},
    # New request that follows the same pattern
    {"role": "user", "content": "- Monday\n- Tuesday\n- Wednesday"}
]

# Call the ChatCompletion endpoint with temperature=0 for deterministic output
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # choose an appropriate model
    messages=messages,
    temperature=0.0
)

# Extract and print the model’s answer
output = response.choices[0].message["content"]
print("Generated JSON:", output)
*/

