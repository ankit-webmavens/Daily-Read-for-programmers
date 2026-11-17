<?php
// 2026-11-17 09:34:51

/* PHP
Topic: Using PDO Prepared Statements for Secure Database Queries  

Explanation:  
Prepared statements separate SQL code from user‑supplied data, preventing SQL injection attacks.  
The PDO (PHP Data Objects) extension provides a uniform interface for many database systems.  
You first prepare the SQL with placeholders, then bind values and execute the statement.  
If the query fails, PDO can throw exceptions that you can catch for error handling.  
This approach also improves performance when the same statement is executed repeatedly with different data.  

Code example (with comments):  

<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO instance (replace DSN, username, password as needed)
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$pdo = new PDO($dsn, $username, $password, $options);

// SQL with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data (normally obtained from user input)
$userData = [
    ':username' => 'alice',
    ':email'    => 'alice@example.com',
];

// Bind values and execute the prepared statement
$stmt->execute($userData);

// Optionally retrieve the ID of the inserted row
$insertedId = $pdo->lastInsertId();
echo "New user inserted with ID: $insertedId";
?>
*/

/* Laravel
Topic: Laravel Service Container & Automatic Dependency Injection

Explanation:
The Service Container is Laravel’s powerful IoC (Inversion of Control) system that manages class dependencies and performs dependency injection automatically. By binding interfaces to concrete classes, you can swap implementations without changing the consuming code. When a class is resolved, Laravel inspects its constructor and injects the required dependencies, simplifying testing and promoting loose coupling. You can bind singletons for shared instances or use contextual bindings for specific scenarios. The container is also accessible via the app() helper, allowing manual resolution when needed.

Code example (app/Providers/AppServiceProvider.php):
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;
use App\Services\PaypalPaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGateway interface to Stripe implementation by default
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);

        // Example of a singleton binding (shared instance)
        $this->app->singleton('logger', function ($app) {
            return new \Monolog\Logger('app');
        });
    }

    public function boot()
    {
        // No boot logic needed for this example
    }
}

Code example (app/Http/Controllers/OrderController.php):
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel automatically injects the concrete class bound to PaymentGateway
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $orderData = $request->all();

        // Use the injected payment gateway to process the payment
        $this->paymentGateway->charge($orderData['amount'], $orderData['currency']);

        // Continue with order creation logic...
        return response()->json(['status' => 'order placed']);
    }
}

Code example (app/Contracts/PaymentGateway.php):
<?php
namespace App\Contracts;

interface PaymentGateway
{
    // Define a contract for charging payments
    public function charge(float $amount, string $currency): void;
}

Code example (app/Services/StripePaymentGateway.php):
<?php
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    public function charge(float $amount, string $currency): void
    {
        // Here you would integrate with Stripe’s SDK
        // For demonstration, we just log the action
        logger()->info("Charging \${$amount} {$currency} via Stripe.");
    }
}

Code example (app/Services/PaypalPaymentGateway.php):
<?php
namespace App\Services;

use App\Contracts\PaymentGateway;

class PaypalPaymentGateway implements PaymentGateway
{
    public function charge(float $amount, string $currency): void
    {
        // Integration with PayPal’s API would go here
        logger()->info("Charging \${$amount} {$currency} via PayPal.");
    }
}

Explanation of usage:
If you later decide to switch to PayPal, change the binding in AppServiceProvider to:
$this->app->bind(PaymentGateway::class, PaypalPaymentGateway::class);
All controllers that depend on PaymentGateway will automatically receive the new implementation without any further code changes. This demonstrates the flexibility and power of Laravel’s Service Container and automatic dependency injection.
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by separating complex subqueries from the main query logic.  
Recursive CTEs allow you to query hierarchical data such as organization charts, bill‑of‑materials, or folder structures.  
The recursion is controlled by an anchor member (the starting point) and a recursive member that repeatedly references the CTE itself.  
MySQL 8.0+ fully supports both non‑recursive and recursive CTEs, making it easier to write self‑joins and traversals without deep nesting.

Code Example (MySQL syntax with comments):

WITH RECURSIVE OrgChart AS (                           -- define a recursive CTE named OrgChart
    SELECT EmployeeID, ManagerID, Name, 1 AS Level      -- anchor member: top‑level employees
    FROM Employees
    WHERE ManagerID IS NULL                            -- no manager means top of hierarchy
    UNION ALL
    SELECT e.EmployeeID, e.ManagerID, e.Name, oc.Level + 1   -- recursive member: join back to CTE
    FROM Employees e
    INNER JOIN OrgChart oc ON e.ManagerID = oc.EmployeeID   -- walk down the hierarchy
)
SELECT EmployeeID, ManagerID, Name, Level               -- final query using the CTE result
FROM OrgChart
ORDER BY Level, ManagerID;                              -- order for easier viewing.
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures allow inner functions to remember and use variables defined in an outer function after the outer function has finished running.  
They are created each time a function is created, capturing the surrounding state at that moment.  
Closures are essential for data encapsulation, creating private variables, and implementing function factories.  
Understanding closures helps avoid common pitfalls such as unintended variable sharing in loops or memory leaks.

Code example with comments:
function makeCounter() {                     // outer function creates a private variable
    let count = 0;                          // this variable is captured by the inner function

    return function() {                     // the inner function forms a closure
        count += 1;                         // it can modify the captured variable
        console.log('Current count:', count);
    };
}

const counterA = makeCounter();              // each call creates a new independent closure
const counterB = makeCounter();

counterA(); // Output: Current count: 1
counterA(); // Output: Current count: 2
counterB(); // Output: Current count: 1   (independent of counterA)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s ChatCompletion API  

Explanation:  
Few‑shot prompting lets you guide a large language model by providing a handful of example input‑output pairs inside the same request. By framing the task explicitly and showing the desired format, the model can generalize to new queries with higher fidelity. This technique works well for classification, extraction, and transformation tasks without any model fine‑tuning. The key is to keep examples concise, maintain consistent structure, and clearly separate the user’s new request from the demo pairs. Using the ChatCompletion endpoint, you can embed these examples in a system or user message and let the model continue the pattern.

Code example (Python, requires `openai` library and a valid API key):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that teaches the model to turn a short description into a polite email reply  
few_shot_examples = """\
User: I need to cancel my subscription because I'm moving abroad.  
Assistant: Dear Customer, we’re sorry to hear about your move. Your subscription has been canceled as requested. If you decide to return, we’ll be happy to welcome you back.  

User: My package arrived damaged and I want a refund.  
Assistant: Dear Customer, we apologize for the damaged delivery. A full refund has been processed and should appear on your statement within 3‑5 business days.  

User: I forgot my password and cannot log in.  
Assistant: Dear Customer, you can reset your password using the “Forgot Password” link on the login page. A reset link will be sent to your registered email.  
"""  

# New query that should follow the same pattern  
new_query = "User: I was double‑charged for my last purchase and need it corrected."  

# Build the messages payload for ChatCompletion  
messages = [  
    {"role": "system", "content": "You are a helpful assistant that writes concise, polite email replies to customer support requests."},  
    {"role": "user", "content": few_shot_examples + "\n" + new_query}  
]  

# Call the API  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  # or any Chat model you have access to  
    messages=messages,  
    temperature=0.2,      # low temperature for deterministic replies  
    max_tokens=150        # enough for a short email  
)  

# Extract and print the assistant’s reply  
assistant_reply = response["choices"][0]["message"]["content"]  
print(assistant_reply)  
*/

