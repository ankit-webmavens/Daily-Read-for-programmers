<?php
// 2026-10-14 06:38:24

/* PHP
Topic: Using PHP PDO (PHP Data Objects) for Secure Database Access

Explanation:
- PDO provides a consistent interface for accessing many different databases (MySQL, PostgreSQL, SQLite, etc.) from PHP.
- It supports prepared statements, which protect against SQL injection by separating query structure from data.
- PDO throws exceptions on errors, allowing you to handle failures cleanly with try‑catch blocks.
- You can fetch results as associative arrays, objects, or even bind them directly to variables.
- Connection parameters are supplied as a DSN string, making it easy to switch databases without rewriting code.

Code example (MySQL connection, inserting a row, and retrieving data):

<?php
// Enable error reporting for PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES => false,           // Use native prepared statements
];

$host = 'localhost';
$db   = 'sample_db';
$user = 'db_user';
$pass = 'db_password';
$charset = 'utf8mb4';

// Build DSN string
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    // Create PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);

    // INSERT using a prepared statement
    $insertSql = "INSERT INTO users (username, email) VALUES (:username, :email)";
    $stmt = $pdo->prepare($insertSql);
    $stmt->execute([
        ':username' => 'alice',
        ':email'    => 'alice@example.com'
    ]);

    // SELECT using a prepared statement
    $selectSql = "SELECT id, username, email FROM users WHERE username = :username";
    $stmt = $pdo->prepare($selectSql);
    $stmt->execute([':username' => 'alice']);

    // Fetch the result
    $user = $stmt->fetch();

    if ($user) {
        echo "User ID: {$user['id']}\n";
        echo "Username: {$user['username']}\n";
        echo "Email: {$user['email']}\n";
    } else {
        echo "No user found.\n";
    }
} catch (PDOException $e) {
    // Handle connection or query errors
    echo "Database error: " . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection

Explanation:
The Laravel Service Container is a powerful tool that manages class dependencies and performs automatic injection. It resolves objects by reading type hints in constructors or method signatures, allowing you to decouple code and facilitate testing. You can bind interfaces to concrete classes, define singleton instances, or use contextual bindings for different implementations in various parts of the app. By leveraging the container, you keep controllers thin and push business logic into dedicated services. This pattern also makes it easy to swap implementations without changing the consuming code.

Code example (app/Providers/AppServiceProvider.php):
public function register()
{
    // Bind an interface to a concrete class
    $this->app->bind(
        App\Contracts\PaymentGateway::class,
        App\Services\StripePaymentGateway::class
    );

    // Register a singleton instance (same object throughout the request lifecycle)
    $this->app->singleton('logger', function ($app) {
        return new Monolog\Logger('app', [
            new Monolog\Handler\StreamHandler(storage_path('logs/app.log'))
        ]);
    });
}

// Usage in a controller (app/Http/Controllers/OrderController.php):
use App\Contracts\PaymentGateway;

class OrderController extends Controller
{
    protected $gateway;

    // The container automatically injects the concrete StripePaymentGateway
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function store(Request $request)
    {
        // Use the injected payment gateway
        $this->gateway->charge($request->input('amount'));

        // Access the singleton logger via the container helper
        $logger = app('logger');
        $logger->info('Order placed', $request->all());

        return response()->json(['status' => 'success']);
    }
}

// Interface definition (app/Contracts/PaymentGateway.php):
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount);
}

// Concrete implementation (app/Services/StripePaymentGateway.php):
namespace App\Services;

use App\Contracts\PaymentGateway;
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGateway
{
    protected $stripe;

    public function __construct()
    {
        // Initialize Stripe client with API key from config
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function charge(float $amount)
    {
        // Example charge request to Stripe
        $this->stripe->charges->create([
            'amount' => $amount * 100, // amount in cents
            'currency' => 'usd',
            'source' => 'tok_visa', // placeholder token
            'description' => 'Laravel Charge',
        ]);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) in MySQL  

Explanation:  
A Common Table Expression (CTE) is a temporary named result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0, enabling more readable queries and hierarchical data processing.  
CTEs are defined using the WITH clause and exist only for the duration of the statement that follows it.  
They can simplify complex subqueries, improve maintainability, and allow you to reference the same derived set multiple times without repeating the logic.  
Recursive CTEs are particularly useful for traversing tree structures such as organizational charts or category hierarchies.  

Code example (with inline comments):  
WITH RECURSIVE employee_hierarchy AS (  
    -- Anchor member: select the top‑level manager(s)  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL  

    UNION ALL  

    -- Recursive member: find subordinates of the previous level  
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1  
    FROM employees e  
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id  
)  
-- Final query: retrieve the hierarchy ordered by level and employee name  
SELECT employee_id, manager_id, employee_name, level  
FROM employee_hierarchy  
ORDER BY level, employee_name;
*/

/* JavaScript
Topic: Event Delegation in the DOM

Explanation:
Event delegation is a technique where a single event listener is attached to a parent element to handle events fired by its child elements. This reduces the number of listeners, improves performance, and works for dynamically added elements. The listener uses the event object's target property to determine which child triggered the event. It is especially useful for lists, tables, or any container with many interactive children. By leveraging bubbling, you can centralize logic and keep your code cleaner.

Code example with comments:
// HTML structure (for reference)
// <ul id="menu">
//   <li data-action="new">New File</li>
//   <li data-action="open">Open File</li>
//   <li data-action="save">Save File</li>
// </ul>

// JavaScript
const menu = document.getElementById('menu');

// Attach one click listener to the parent <ul>
menu.addEventListener('click', function(event) {
  // Determine the actual element that was clicked
  const clickedItem = event.target;
  
  // Ensure the click originated from a <li> (ignore clicks on the <ul> itself)
  if (clickedItem.tagName.toLowerCase() === 'li') {
    const action = clickedItem.getAttribute('data-action');
    
    // Perform the appropriate action based on the data-action value
    switch (action) {
      case 'new':
        console.log('Creating a new file...');
        break;
      case 'open':
        console.log('Opening an existing file...');
        break;
      case 'save':
        console.log('Saving the current file...');
        break;
      default:
        console.log('Unknown action:', action);
    }
  }
});
*/

/* AI
Topic: Chain‑of‑Thought Prompting for Large Language Models  

Explanation:  
Chain‑of‑Thought (CoT) prompting encourages a language model to generate step‑by‑step reasoning before giving a final answer. This technique improves performance on complex tasks such as math word problems, logical puzzles, and multi‑hop reasoning. By explicitly asking the model to “think aloud,” you can extract intermediate computations that guide the final output. CoT works best when the prompt provides a clear template that the model can follow consistently. It can be combined with temperature settings to balance creativity and deterministic reasoning.

Code example (Python, using OpenAI’s API):

import os                                 # Access environment variables
import openai                              # OpenAI Python client library

# Set your API key (ensure OPENAI_API_KEY is defined in your environment)
openai.api_key = os.getenv("OPENAI_API_KEY")

def solve_with_cot(question: str) -> str:
    """
    Sends a Chain‑of‑Thought prompt to the model and returns the final answer.
    """
    # Define the CoT prompt template
    prompt = (
        "You are a helpful AI that solves problems step by step.\n"
        "Problem: " + question + "\n"
        "Step‑by‑step reasoning:\n"
    )
    
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",                # Choose a suitable model
        messages=[{"role": "user", "content": prompt}],
        temperature=0.2,                    # Low temperature for more deterministic reasoning
        max_tokens=300,                     # Allow enough space for the reasoning chain
        stop=None
    )
    
    # The model returns the full reasoning; extract the final answer line
    full_text = response.choices[0].message.content.strip()
    # Assume the last line contains "Answer: <value>"
    answer_line = [line for line in full_text.splitlines() if line.lower().startswith("answer:")]
    return answer_line[0] if answer_line else "No answer found"

# Example usage
question = "If a train travels 150 km in 2.5 hours, what is its average speed in km/h?"
print(solve_with_cot(question))   # Expected output: "Answer: 60 km/h"
*/

