<?php
// 2026-09-21 07:00:32

/* PHP
PHP Topic: Traits in PHP

Explanation:
Traits are a mechanism for code reuse in single inheritance languages such as PHP.  
They allow you to group methods that can be included in multiple classes without using inheritance.  
A trait can contain methods, properties, and even abstract methods that the using class must implement.  
Traits help avoid duplication when different classes need similar functionality but do not share a parent.  
They are especially useful for mixing in common behaviors like logging, caching, or utility functions.

Code Example (with comments):
<?php
// Define a reusable trait with common methods
trait LoggerTrait {
    // Log a message with a timestamp
    public function log(string $message): void {
        $time = date('Y-m-d H:i:s');
        echo "[$time] $message\n";
    }

    // Helper method to format messages
    protected function formatMessage(string $level, string $msg): string {
        return strtoupper($level) . ': ' . $msg;
    }
}

// First class using the trait
class User {
    use LoggerTrait;   // Include the LoggerTrait in this class

    public function create(string $username): void {
        // Some user creation logic...
        $this->log($this->formatMessage('info', "User '$username' created"));
    }
}

// Second class also using the same trait
class Order {
    use LoggerTrait;   // Reuse the same logging functionality

    public function place(int $orderId): void {
        // Some order processing logic...
        $this->log($this->formatMessage('notice', "Order #$orderId placed"));
    }
}

// Demonstration
$user = new User();
$user->create('alice');

$order = new Order();
$order->place(12345);
?>
*/

/* Laravel
Laravel Service Container & Dependency Injection  

The service container is Laravel’s powerful tool for managing class dependencies and performing inversion of control. It resolves objects automatically, allowing you to type‑hint classes in constructors or methods without manually instantiating them. By binding interfaces to concrete implementations, you decouple your code and make it easier to test. The container can also resolve primitive parameters and contextual bindings for more complex scenarios. Using dependency injection keeps controllers thin and encourages a clean, maintainable architecture.

Example (plain PHP file, e.g., app/Providers/AppServiceProvider.php and a controller):

// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGateway interface to the StripeGateway implementation
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could pull API keys from config here
            return new StripeGateway(config('services.stripe.secret'));
        });
    }
}

// app/Contracts/PaymentGateway.php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount, string $currency, array $metadata = []): bool;
}

// app/Services/StripeGateway.php
namespace App\Services;

use App\Contracts\PaymentGateway;
use Stripe\StripeClient;

class StripeGateway implements PaymentGateway
{
    protected $stripe;

    public function __construct(string $secretKey)
    {
        $this->stripe = new StripeClient($secretKey);
    }

    public function charge(float $amount, string $currency, array $metadata = []): bool
    {
        $this->stripe->charges->create([
            'amount' => (int)($amount * 100), // amount in cents
            'currency' => $currency,
            'source' => $metadata['source'] ?? 'tok_visa',
            'description' => $metadata['description'] ?? '',
        ]);

        return true;
    }
}

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\PaymentGateway;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The container automatically injects the bound implementation
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        // Validate order data...

        // Process payment through the injected gateway
        $this->paymentGateway->charge(
            $request->input('total'), 
            'usd', 
            ['source' => $request->input('stripe_token'), 'description' => 'Order #' . $request->order_id]
        );

        // Continue with order creation...
        return response()->json(['status' => 'success']);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries in MySQL

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs improve readability by allowing you to break complex queries into logical building blocks. MySQL supports both non‑recursive and recursive CTEs (since version 8.0). Recursive CTEs are useful for hierarchical data such as organizational charts or tree structures. They consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until no new rows are produced.

Code example (recursive CTE to list an employee hierarchy):
-- Define the CTE named employee_hierarchy
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: start with the top‑level manager (e.g., employee_id = 1)
    SELECT
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL            -- top of the hierarchy

    UNION ALL

    -- Recursive member: join each manager to their direct reports
    SELECT
        e.employee_id,
        e.manager_id,
        e.employee_name,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN employee_hierarchy eh
        ON e.manager_id = eh.employee_id
)
-- Final query: retrieve the hierarchy ordered by level and employee name
SELECT
    employee_id,
    manager_id,
    employee_name,
    level
FROM employee_hierarchy
ORDER BY level, employee_name;
*/

/* JavaScript
Topic: JavaScript Closures  

Explanation:  
A closure is a function that retains access to the variables from its outer (enclosing) scope even after that outer function has finished executing. This happens because functions in JavaScript form a lexical environment that captures the surrounding scope at the time they are created. Closures enable powerful patterns such as data encapsulation, function factories, and maintaining private state. They are created automatically whenever an inner function references a variable from an outer function. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state across multiple calls.  

Code example:  
function makeCounter() {                     // outer function creates a private variable  
    let count = 0;                           // this variable is captured by the inner function  
    return function() {                     // the returned inner function forms a closure  
        count += 1;                          // it can read and modify 'count' each call  
        return count;                       // expose the updated value  
    };                                       // end of inner function  
}                                            // end of outer function  

const counterA = makeCounter(); // each call to makeCounter gets its own 'count'  
console.log(counterA()); // 1  
console.log(counterA()); // 2  

const counterB = makeCounter(); // a separate closure with its own private 'count'  
console.log(counterB()); // 1  
console.log(counterA()); // 3   (counterA retains its own state)
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models  

Explanation:  
Few‑shot prompting supplies a language model with a small number of example input‑output pairs to steer its behavior without fine‑tuning. By carefully selecting representative demonstrations, the model can infer the desired pattern and apply it to new queries. This technique is especially useful when the target task has limited labeled data or when rapid prototyping is needed. The prompt must maintain consistent formatting, avoid ambiguous wording, and stay within the model’s token limits. Iteratively testing and refining the examples often yields the most reliable performance.

Code example (Python, using OpenAI’s ChatCompletion API):

import os
import json
import openai

# Load your API key from an environment variable or configuration file
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt with two examples of sentiment analysis
system_prompt = {"role": "system", "content": "You are an assistant that classifies the sentiment of short English sentences as Positive, Negative, or Neutral."}

example_1 = {"role": "user", "content": "I love the new design of the website!"}
response_1 = {"role": "assistant", "content": "Positive"}

example_2 = {"role": "user", "content": "The delivery was late and the package was damaged."}
response_2 = {"role": "assistant", "content": "Negative"}

# New user query to classify
new_query = {"role": "user", "content": "The coffee was okay, nothing special."}

# Assemble the message list in the order: system, examples, new query
messages = [system_prompt,
            example_1, response_1,
            example_2, response_2,
            new_query]

# Call the ChatCompletion endpoint
completion = openai.ChatCompletion.create(
    model="gpt-4o-mini",
    messages=messages,
    temperature=0.0   # deterministic output for classification tasks
)

# Print the model’s classification
print("Sentiment:", completion.choices[0].message.content.strip())
*/

