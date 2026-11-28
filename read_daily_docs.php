<?php
// 2026-11-28 09:12:19

/* PHP
PHP PDO Prepared Statements  

Prepared statements separate SQL code from data, preventing SQL injection by sending the query structure to the database first and then binding parameters later.  
They improve performance when the same query is executed multiple times with different values.  
Using PDO you can work with many database types (MySQL, PostgreSQL, SQLite) through a consistent interface.  
Parameters are bound by name or position, and PDO handles proper quoting and escaping automatically.  
If an error occurs, PDO can throw exceptions, making debugging easier.  

<?php
// Create a new PDO instance (replace DSN, username, password with your own values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$stmt = $pdo->prepare('INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())');

// Bind values to the placeholders
$username = 'alice';
$email = 'alice@example.com';
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);

// Execute the statement
$stmt->execute();

// Retrieve the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: $newUserId\n";
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection

Explanation:
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection automatically. It resolves classes out of the container, injecting their required dependencies without manual instantiation. This promotes loose coupling and makes testing easier by allowing you to swap implementations. You can bind interfaces to concrete classes, define singletons, and even use contextual binding for specific scenarios. When a class is resolved, Laravel inspects its constructor and provides the needed objects from the container.

Code Example (app/Providers/AppServiceProvider.php):
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

        // Example of a singleton binding – the same instance will be reused
        $this->app->singleton('logger', function ($app) {
            return new \Monolog\Logger('app');
        });

        // Contextual binding: when OrderController needs PaymentGateway, use PayPal
        $this->app->when(\App\Http\Controllers\OrderController::class)
                  ->needs(PaymentGateway::class)
                  ->give(PaypalPaymentGateway::class);
    }

    public function boot()
    {
        //
    }
}
?>

Code Example (app/Http/Controllers/OrderController.php):
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel injects the appropriate PaymentGateway implementation automatically
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        // Use the injected payment gateway to process a payment
        $amount = $request->input('amount');
        $this->paymentGateway->charge($amount);

        return response()->json(['status' => 'payment processed']);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and can improve readability by breaking complex queries into logical parts. MySQL 8.0 and later support both non‑recursive and recursive CTEs. Recursive CTEs are useful for traversing hierarchical data such as organizational charts or tree structures. The recursion stops when the anchor query no longer produces rows, preventing infinite loops.

Code Example (with comments):
WITH RECURSIVE OrgChart AS (
    -- Anchor member: select the top‑level manager(s)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join each manager to their direct reports
    SELECT 
        e.employee_id,
        e.manager_id,
        e.employee_name,
        oc.level + 1 AS level
    FROM employees e
    INNER JOIN OrgChart oc ON e.manager_id = oc.employee_id
)
SELECT 
    employee_id,
    manager_id,
    employee_name,
    level
FROM OrgChart
ORDER BY level, manager_id, employee_id;
*/

/* JavaScript
Topic Name: Closures in JavaScript

Explanation:
A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing. This allows the inner function to retain a reference to the outer scope’s variables, enabling data privacy and function factories. Closures are fundamental for patterns like module encapsulation and partial application. Because the referenced variables live on the heap, they are not garbage‑collected until all closures that reference them are also discarded. Understanding closures helps avoid common pitfalls such as unintended sharing of mutable state.

Code Example:
// outerFunction returns an inner function that remembers `counter`
function outerFunction() {
    // `counter` is scoped to outerFunction
    let counter = 0;

    // inner function forms a closure over `counter`
    return function() {
        // each call increments the private `counter`
        counter++;
        console.log('Current count:', counter);
    };
}

// Create two independent counters using closures
const incrementA = outerFunction(); // first closure with its own `counter`
const incrementB = outerFunction(); // second closure with a separate `counter`

incrementA(); // Output: Current count: 1
incrementA(); // Output: Current count: 2
incrementB(); // Output: Current count: 1 (independent from incrementA)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with GPT‑4

Explanation:
Few‑shot prompting lets you teach a language model new tasks by providing a few example input‑output pairs directly in the prompt. The model infers the pattern and applies it to new inputs without any parameter updates. Careful selection of examples, clear separators, and explicit instructions improve reliability. This technique works well for classification, transformation, or reasoning tasks where labeled data is scarce. By adjusting the number and order of demos you can control the trade‑off between precision and speed.

Code example (Python, OpenAI API):

import os
import openai

# Load your API key from environment variable for security
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt for sentiment classification
prompt = """Classify the sentiment of the given sentence as Positive, Negative, or Neutral.

Example 1:
Sentence: "I love the new design of the app."
Sentiment: Positive

Example 2:
Sentence: "The update caused many crashes."
Sentiment: Negative

Example 3:
Sentence: "The tutorial was okay, nothing special."
Sentiment: Neutral

Now classify:
Sentence: "Your customer support was very helpful."
Sentiment:"""

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # lightweight GPT‑4 variant
    messages=[{"role": "user", "content": prompt}],
    temperature=0.0,               # deterministic output for classification
    max_tokens=10,                 # we only need the short label
)

# Extract and print the model's answer
sentiment = response.choices[0].message.content.strip()
print("Predicted sentiment:", sentiment)
*/

