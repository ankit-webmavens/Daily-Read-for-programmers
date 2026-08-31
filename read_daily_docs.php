<?php
// 2026-08-31 07:33:38

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:
Prepared statements let you separate SQL logic from the data that will be bound to it, which prevents SQL injection attacks and can improve performance for repeated queries. PDO provides a uniform API for different databases, so the same code works with MySQL, PostgreSQL, SQLite, etc. You first prepare the SQL with placeholders, then bind values and execute the statement. Errors can be caught with exceptions, making debugging easier. Using named placeholders makes the code more readable than positional ones.

Code example (MySQL connection and a SELECT using named placeholders):

<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO instance (replace credentials as needed)
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$pdo = new PDO($dsn, $username, $password, $options);

// The SQL query with named placeholders
$sql = "
    SELECT id, name, email
    FROM users
    WHERE status = :status AND created_at > :date
    ORDER BY created_at DESC
    LIMIT :limit
";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$status = 'active';
$date   = '2023-01-01';
$limit  = 10;

// bindParam is used for the LIMIT because it expects an integer
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':date',   $date,   PDO::PARAM_STR);
$stmt->bindParam(':limit',  $limit,  PDO::PARAM_INT);

// Execute the prepared statement
$stmt->execute();

// Fetch all matching rows
$users = $stmt->fetchAll();

// Output the result (for demonstration)
foreach ($users as $user) {
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Laravel Service Container & Dependency Injection

The Service Container is Laravel’s powerful tool for managing class dependencies and performing dependency injection. It resolves objects automatically, allowing you to type‑hint dependencies in constructors or controller methods. By binding interfaces to concrete implementations, you can swap out classes without changing the consuming code. The container also supports contextual bindings, singleton bindings, and automatic injection of primitive values. Mastering the container leads to more testable, decoupled, and maintainable applications.

<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;
use App\Services\PaypalPaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGateway contract to a concrete class.
        // Here we use Stripe as the default implementation.
        $this->app->bind(PaymentGateway::class, function ($app) {
            return new StripePaymentGateway(config('services.stripe.secret'));
        });

        // Example of a contextual binding: use PayPal when the controller
        // explicitly asks for it.
        $this->app->when(\App\Http\Controllers\PayPalController::class)
                  ->needs(PaymentGateway::class)
                  ->give(function ($app) {
                      return new PaypalPaymentGateway(config('services.paypal.client_id'));
                  });
    }

    public function boot()
    {
        //
    }
}

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $gateway;

    // The container injects the bound implementation automatically.
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function store(Request $request)
    {
        // Use the injected payment gateway to process a charge.
        $amount = $request->input('amount');
        $this->gateway->charge($amount, $request->user());

        return response()->json(['status' => 'payment processed']);
    }
}

// app/Contracts/PaymentGateway.php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount, $user);
}

// app/Services/StripePaymentGateway.php
namespace App\Services;

use App\Contracts\PaymentGateway;
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGateway
{
    protected $stripe;

    public function __construct(string $secretKey)
    {
        $this->stripe = new StripeClient($secretKey);
    }

    public function charge(float $amount, $user)
    {
        // Simplified charge example
        $this->stripe->charges->create([
            'amount' => $amount * 100, // amount in cents
            'currency' => 'usd',
            'customer' => $user->stripe_customer_id,
            'description' => 'Order payment',
        ]);
    }
}

// app/Services/PaypalPaymentGateway.php
namespace App\Services;

use App\Contracts\PaymentGateway;

class PaypalPaymentGateway implements PaymentGateway
{
    protected $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }

    public function charge(float $amount, $user)
    {
        // Placeholder for PayPal charge logic
        // ...
    }
}
*/

/* MySQL
Topic: MySQL Stored Procedures with Input and Output Parameters

Explanation:  
A stored procedure is a pre‑compiled set of SQL statements stored in the database that can be invoked repeatedly.  
It can accept input parameters, modify data, and return results through output parameters or result sets.  
Using parameters makes the procedure reusable and helps avoid SQL injection by separating code from data.  
Procedures improve performance because the execution plan is cached on the server.  
They also encapsulate business logic, allowing changes without altering application code.

Example code (with inline comments):

CREATE DATABASE IF NOT EXISTS demo_db;
USE demo_db;

-- Create a sample table
CREATE TABLE IF NOT EXISTS employees (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    salary DECIMAL(10,2)
);

-- Insert some test data
INSERT INTO employees (first_name, last_name, salary) VALUES
('John', 'Doe', 55000),
('Jane', 'Smith', 72000),
('Alice', 'Brown', 48000);

-- Drop the procedure if it already exists
DROP PROCEDURE IF EXISTS GetEmployeeInfo;

-- Create a stored procedure that takes an employee ID as input
-- and returns the employee's full name and salary as output parameters
CREATE PROCEDURE GetEmployeeInfo (
    IN p_emp_id INT,
    OUT p_full_name VARCHAR(101),
    OUT p_salary DECIMAL(10,2)
)
BEGIN
    DECLARE v_first VARCHAR(50);
    DECLARE v_last VARCHAR(50);

    -- Retrieve first and last name and salary for the given ID
    SELECT first_name, last_name, salary
    INTO v_first, v_last, p_salary
    FROM employees
    WHERE emp_id = p_emp_id;

    -- Concatenate first and last name into the output variable
    SET p_full_name = CONCAT(v_first, ' ', v_last);
END;

-- Call the procedure for employee ID 2
CALL GetEmployeeInfo(2, @name, @salary);

-- Show the output values
SELECT @name AS employee_name, @salary AS employee_salary;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to its lexical environment even after the outer function has finished executing.  
It allows inner functions to "remember" variables from the scope in which they were created.  
Closures are useful for data privacy, encapsulation, and creating function factories.  
They enable patterns such as currying, partial application, and module-like structures.  
Understanding closures helps avoid common pitfalls like unintended shared state in loops.

Code example with comments:  

function makeCounter() {                     // outer function creates a private variable
    let count = 0;                           // this variable is local to makeCounter
    return function() {                     // returned inner function forms a closure
        count++;                             // it can access and modify 'count' each call
        console.log(count);                 // output the current count
    };
}
const counter = makeCounter();                // counter now holds the inner function
counter(); // 1                               // first call increments count to 1
counter(); // 2                               // second call increments count to 2
counter(); // 3                               // subsequent calls continue the sequence  
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models  

Explanation:  
Few‑shot prompting supplies a language model with a handful of example input‑output pairs before the actual query. This technique helps the model infer the desired format, style, or reasoning pattern without fine‑tuning. By carefully selecting diverse yet concise examples, you can guide the model to produce more reliable and task‑specific responses. The approach works well for classification, transformation, and reasoning tasks across many domains. It is lightweight, requires only prompt construction, and can be dynamically adjusted at runtime.

Code Example (Python, using OpenAI’s ChatCompletion API):
import os
import openai

# Load your API key from an environment variable or secret manager
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt with two examples for sentiment classification
few_shot_prompt = [
    {"role": "system", "content": "You are a helpful assistant that classifies the sentiment of short sentences as Positive, Negative, or Neutral."},
    {"role": "user", "content": "I love the new design of the app!"},
    {"role": "assistant", "content": "Positive"},
    {"role": "user", "content": "The update caused many bugs and crashes."},
    {"role": "assistant", "content": "Negative"},
    # The actual user query follows
    {"role": "user", "content": "The documentation is okay, but could be clearer."}
]

# Call the ChatCompletion endpoint
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # Choose a suitable model
    messages=few_shot_prompt,
    temperature=0.0               # Deterministic output for classification
)

# Extract and print the model’s classification
classification = response.choices[0].message.content.strip()
print("Sentiment:", classification)   # Expected output: "Neutral"  
*/

