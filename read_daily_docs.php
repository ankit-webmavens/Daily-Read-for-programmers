<?php
// 2026-08-25 02:43:06

/* PHP
Topic: Prepared Statements using PDO in PHP  

Explanation:  
Prepared statements separate the SQL query structure from the data values, which prevents SQL injection attacks and can improve performance when the same query is executed multiple times. PDO (PHP Data Objects) provides a consistent interface for database access across many drivers, making it easy to switch databases without changing code. With PDO you first prepare the statement, then bind parameters (by value or by reference) and finally execute it. Errors can be handled through exceptions, allowing clean error management. This approach also enables the use of named or positional placeholders for clearer, more maintainable code.  

Code example:  
<?php  
// Create a PDO connection (replace DSN, username, password with real values)  
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';  
$user = 'dbuser';  
$pass = 'dbpass';  

$options = [  
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors  
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays  
];  

$pdo = new PDO($dsn, $user, $pass, $options);  

// Prepare an INSERT statement with named placeholders  
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';  
$stmt = $pdo->prepare($sql);  

// Bind values to the placeholders  
$username = 'alice';  
$email = 'alice@example.com';  
$stmt->bindParam(':username', $username, PDO::PARAM_STR);  
$stmt->bindParam(':email', $email, PDO::PARAM_STR);  

// Execute the prepared statement  
$stmt->execute();  

// Output the ID of the newly inserted row  
echo 'New user ID: ' . $pdo->lastInsertId();  
?>
*/

/* Laravel
Laravel Service Container (IoC Container)

The Laravel Service Container is a powerful tool for managing class dependencies and performing dependency injection. It resolves objects automatically, allowing you to type‑hint dependencies in constructors or controller methods without manually instantiating them. You can bind abstractions to concrete implementations, enabling flexible swapping of services (e.g., different payment gateways). The container also supports contextual binding, singleton bindings, and automatic resolution of nested dependencies. Understanding the Service Container is essential for writing clean, testable, and maintainable Laravel applications.

<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;          // abstraction
use App\Services\StripePaymentGateway;    // concrete implementation

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the contract to a concrete class so the container knows what to resolve
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could read config or environment variables here
            $apiKey = config('services.stripe.secret');
            return new StripePaymentGateway($apiKey);
        });

        // Example of a singleton binding (only one instance throughout the app)
        $this->app->singleton('logger', function ($app) {
            return new \Monolog\Logger('app');
        });
    }
}

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;   // type‑hint the abstraction
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel automatically injects the bound implementation
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $orderData = $request->all();

        // Use the injected payment gateway to process payment
        $this->paymentGateway->charge($orderData['amount'], $orderData['currency']);

        // Continue with order creation...
        return response()->json(['status' => 'order created']);
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

    public function __construct(string $apiKey)
    {
        $this->stripe = new StripeClient($apiKey);
    }

    public function charge(float $amount, string $currency)
    {
        // Perform the charge using Stripe's SDK
        $this->stripe->charges->create([
            'amount' => $amount * 100, // Stripe expects amount in cents
            'currency' => $currency,
            'source' => 'tok_visa',    // placeholder source token
        ]);
    }
}
?>
*/

/* MySQL
Topic: MySQL Stored Procedures

Explanation:  
A stored procedure is a named set of SQL statements that are stored in the database server and can be executed repeatedly.  
It allows you to encapsulate complex logic, reduce network traffic, and improve performance by executing on the server side.  
Parameters can be passed in and out, enabling flexible reuse of the same routine with different data.  
Procedures support control‑flow constructs such as IF, LOOP, and WHILE, making them suitable for procedural programming tasks.  
They are especially useful for enforcing business rules, data validation, and batch processing within MySQL.

Code example:
CREATE DATABASE IF NOT EXISTS demo_db;
USE demo_db;

-- Create a sample table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100),
    order_total DECIMAL(10,2),
    order_date DATE
);

-- Drop the procedure if it already exists
DROP PROCEDURE IF EXISTS AddOrder;

-- Define a stored procedure to insert a new order
DELIMITER $$

CREATE PROCEDURE AddOrder (
    IN p_customer_name VARCHAR(100),
    IN p_order_total DECIMAL(10,2),
    IN p_order_date DATE
)
BEGIN
    -- Insert the new row into the orders table
    INSERT INTO orders (customer_name, order_total, order_date)
    VALUES (p_customer_name, p_order_total, p_order_date);
END$$

DELIMITER ;

-- Call the procedure with sample data
CALL AddOrder('Alice Johnson', 250.75, '2024-09-15');
CALL AddOrder('Bob Smith', 120.00, '2024-09-16');

-- Verify the inserted rows
SELECT * FROM orders;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to its lexical environment even after the outer function has finished executing. This means the inner function can reference variables defined in the outer scope, preserving their values across calls. Closures enable data encapsulation, allowing private state without exposing it globally. They are commonly used for factory functions, event handlers, and module patterns. Understanding closures is essential for managing asynchronous code and avoiding common pitfalls like unintended shared references.

Code example with comments:
function createCounter(initialValue) {                     // outer function, creates a counter
    let count = initialValue;                             // private variable, not accessible outside

    return function increment(step = 1) {                 // inner function forms a closure
        count += step;                                    // accesses and updates the outer variable
        console.log('Current count:', count);            // side‑effect: logs the current value
        return count;                                    // returns the updated count
    };
}

const counterA = createCounter(0); // first independent counter
counterA();        // Current count: 1
counterA(5);       // Current count: 6

const counterB = createCounter(10); // second independent counter with its own private state
counterB();        // Current count: 11
counterB(2);       // Current count: 13

// The two counters do not interfere with each other's "count" variable because each closure
// captures its own lexical environment. This demonstrates how closures provide encapsulated
// state in JavaScript.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small set of example inputs and desired outputs within the same request, guiding it to produce consistent results on new queries. This technique works well for tasks like text classification, data extraction, or style transfer without fine‑tuning. By structuring the prompt as a sequence of user‑assistant message pairs, you can illustrate the pattern you expect the model to follow. The approach is lightweight, requires no additional training data, and can be adapted on the fly for different domains. It is especially useful for developers who need quick, controllable AI behavior in scripts or web services.

Code example (Python, using the openai library):

import os
import openai

# Load your API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    # Build a few‑shot prompt with two examples and the new input
    messages = [
        {"role": "system", "content": "You are a helpful assistant that classifies sentiment as Positive, Negative, or Neutral."},
        {"role": "user", "content": "I love the new design of the app!"},
        {"role": "assistant", "content": "Positive"},
        {"role": "user", "content": "The update caused many bugs and crashes."},
        {"role": "assistant", "content": "Negative"},
        {"role": "user", "content": text}  # New text to classify
    ]

    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # Choose a cost‑effective model
        messages=messages,
        temperature=0.0,               # Deterministic output for classification
        max_tokens=5                   # Only need a short label
    )

    # The assistant's reply contains the sentiment label
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
sample = "The service was okay, nothing exceptional."
print(f"Sentiment: {classify_sentiment(sample)}")   # Expected output: Neutral or similar.
*/

