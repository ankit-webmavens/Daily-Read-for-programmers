<?php
// 2026-09-09 06:36:33

/* PHP
Topic: Using PDO (PHP Data Objects) for Secure Database Access  

Explanation:  
PDO provides a uniform interface for accessing many different databases from PHP, making code portable across MySQL, PostgreSQL, SQLite, and more. It supports prepared statements, which separate SQL code from data and protect against SQL injection attacks. PDO also offers flexible error handling modes, allowing developers to throw exceptions for easier debugging. Connection parameters are supplied via a DSN (Data Source Name) string, and options can be set to control attributes like fetch mode and character encoding. By using named or positional placeholders, you can bind values safely and execute the same statement multiple times with different data.

Code example with comments:
<?php
// Define connection parameters
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

// Set PDO attributes: throw exceptions on error and fetch associative arrays by default
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Create a new PDO instance (establishes the database connection)
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Handle connection errors gracefully
    die('Connection failed: ' . $e->getMessage());
}

// Prepare a SELECT statement with a named placeholder to filter active users
$stmt = $pdo->prepare('SELECT id, name FROM users WHERE status = :status');

// Execute the statement, binding the placeholder to the value 'active'
$stmt->execute(['status' => 'active']);

// Loop through the result set and output each user's ID and name
while ($row = $stmt->fetch()) {
    echo $row['id'] . ': ' . $row['name'] . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic injection. It resolves objects automatically, allowing you to type‑hint classes in constructors or controller methods without manually instantiating them. By binding interfaces to concrete implementations, you can swap out functionality without changing the consuming code. This promotes loose coupling and makes testing easier through mocking. Understanding how to register and resolve services is essential for building maintainable Laravel applications.  

Code Example:  

<?php
// Define an interface for a payment gateway
namespace App\Contracts;
interface PaymentGateway
{
    public function charge(float $amount);
}

// Implement the interface with a Stripe gateway
namespace App\Services;
use App\Contracts\PaymentGateway;
class StripeGateway implements PaymentGateway
{
    public function charge(float $amount)
    {
        // Logic to charge via Stripe API
        return "Charged $$amount with Stripe.";
    }
}

// Bind the interface to the concrete class in a service provider
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // When PaymentGateway is requested, resolve StripeGateway
        $this->app->bind(PaymentGateway::class, StripeGateway::class);
    }
}

// Use dependency injection in a controller
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
?>
*/

/* MySQL
Topic: Stored Procedures and Parameters in MySQL

Explanation:  
Stored procedures are pre‑compiled SQL routines stored on the server that can encapsulate complex logic, reduce network traffic, and improve security by limiting direct table access.  
They accept input (IN), output (OUT), and input‑output (INOUT) parameters, allowing you to pass values into the routine and retrieve results without returning a full result set.  
Procedures can contain multiple SQL statements, control‑flow constructs (IF, WHILE, CASE), and error handling with DECLARE ... HANDLER.  
Using procedures helps enforce business rules centrally and makes application code simpler and more maintainable.  
When a procedure finishes, any changes made inside it are committed or rolled back according to the session’s transaction mode.

Code example with comments:

CREATE PROCEDURE GetCustomerOrders
    (IN p_customer_id INT, OUT p_order_count INT)
BEGIN
    -- Count the number of orders for the given customer
    SELECT COUNT(*) INTO p_order_count
    FROM orders
    WHERE customer_id = p_customer_id;

    -- If the customer has no orders, raise a custom warning
    IF p_order_count = 0 THEN
        SIGNAL SQLSTATE '01000' SET MESSAGE_TEXT = 'Customer has no orders';
    END IF;
END;

-- Call the procedure and retrieve the output parameter
SET @cnt = 0;
CALL GetCustomerOrders(12345, @cnt);
SELECT @cnt AS order_count;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation: A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This happens because the inner function forms a lexical environment that includes the outer function’s scope. Closures enable data encapsulation, allowing private state that cannot be accessed directly from the outside. They are commonly used for creating factory functions, partial application, and maintaining state in asynchronous callbacks. Understanding closures is essential for mastering scope and memory management in JavaScript.

Code example:
// outerFunction creates a private counter variable
function outerFunction() {
    let counter = 0;                     // private variable, not accessible directly

    // innerFunction forms a closure over counter
    return function innerFunction() {
        counter += 1;                     // modifies the closed-over variable
        console.log('Current count:', counter);
    };
}

// create an instance of the closure
const increment = outerFunction();

increment();  // Output: Current count: 1
increment();  // Output: Current count: 2
increment();  // Output: Current count: 3

// The counter variable remains hidden; only the inner function can modify it.
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs directly in the prompt, guiding it toward the desired behavior without fine‑tuning.  
It works well for tasks where labeled data is scarce but the model already has broad knowledge, such as classification, translation, or code generation.  
The key is to format examples consistently and to keep the overall prompt length within the model’s token limits.  
By varying the number and quality of examples, you can trade off between precision and computational cost.  
Few‑shot prompting is a practical bridge between zero‑shot usage and full model fine‑tuning.

Code example (Python, using OpenAI’s API):

import os
import openai

# Load your API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define the task description and a few examples
prompt = (
    "Task: Classify the sentiment of a movie review as Positive, Negative, or Neutral.\n\n"
    "Example 1:\n"
    "Review: \"I loved the cinematography and the story was gripping.\"\n"
    "Sentiment: Positive\n\n"
    "Example 2:\n"
    "Review: \"The plot was predictable and the acting was mediocre.\"\n"
    "Sentiment: Negative\n\n"
    "Now classify the following review:\n"
    "Review: \"The movie had some good moments but overall felt flat.\"\n"
    "Sentiment:"
)

# Call the completion endpoint
response = openai.Completion.create(
    model="text-davinci-003",   # Choose a suitable GPT model
    prompt=prompt,
    max_tokens=10,              # Small number since we only need the label
    temperature=0.0,            # Deterministic output for classification
    stop=["\n"]                 # Stop at the end of the label
)

# Extract and print the model's answer
sentiment = response.choices[0].text.strip()
print("Predicted Sentiment:", sentiment)
*/

