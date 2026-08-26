<?php
// 2026-08-26 02:50:54

/* PHP
Topic: Prepared Statements with PDO  

Explanation:  
Prepared statements separate the SQL query from its data values, which prevents SQL injection attacks. PDO (PHP Data Objects) provides a consistent API for interacting with many database systems, making it easier to write portable code. When a statement is prepared, the database parses the query once and can reuse it multiple times with different parameters, improving performance. Binding values to placeholders ensures that the data is correctly escaped and typed. This approach also simplifies handling of complex queries and bulk inserts.  

Code example:  

<?php
// Create a new PDO connection (replace DSN, username, and password with your own values)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass');

// Enable exceptions for error handling
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare an INSERT statement with named placeholders
$stmt = $pdo->prepare('INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)');

// Bind values to the placeholders
$username   = 'johndoe';
$email      = 'john@example.com';
$created_at = date('Y-m-d H:i:s');

// Execute the statement with the bound parameters
$stmt->execute([
    ':username'   => $username,
    ':email'      => $email,
    ':created_at' => $created_at
]);

// Fetch the ID of the newly inserted row
$lastId = $pdo->lastInsertId();
echo "New user ID: " . $lastId;
?>
*/

/* Laravel
Laravel Service Container & Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic injection. It resolves objects, injects them where needed, and allows you to bind abstractions to concrete implementations. By type‑hinting dependencies in a controller or any class, the container will automatically instantiate and inject the required objects. This promotes loose coupling, easier testing, and cleaner code. You can also bind singleton instances or contextual bindings for more control over resolution.

Code example (app/Providers/AppServiceProvider.php):

public function register()
{
    // Bind an interface to a concrete class so the container knows how to resolve it
    $this->app->bind(
        App\Contracts\PaymentGateway::class,
        App\Services\StripePaymentGateway::class
    );

    // Register a singleton that should be shared across the entire request lifecycle
    $this->app->singleton('logger', function ($app) {
        return new Monolog\Logger('app', [
            new Monolog\Handler\StreamHandler(storage_path('logs/app.log'))
        ]);
    });
}

// Example controller using automatic dependency injection (app/Http/Controllers/OrderController.php)

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $gateway;

    // The service container injects the concrete implementation of PaymentGateway
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway; // now $gateway is an instance of StripePaymentGateway
    }

    public function store(Request $request)
    {
        // Use the injected gateway to process payment
        $result = $this->gateway->charge($request->amount, $request->payment_method);

        // Retrieve the shared logger singleton
        $logger = app('logger');
        $logger->info('Payment processed', ['result' => $result]);

        return response()->json(['status' => 'success', 'data' => $result]);
    }
}

// Interface definition (app/Contracts/PaymentGateway.php)

namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount, string $paymentMethod);
}

// Concrete implementation (app/Services/StripePaymentGateway.php)

namespace App\Services;

use App\Contracts\PaymentGateway;
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGateway
{
    protected $stripe;

    public function __construct()
    {
        // Initialize Stripe client with secret key from config
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function charge(float $amount, string $paymentMethod)
    {
        // Perform charge using Stripe API
        return $this->stripe->charges->create([
            'amount' => $amount * 100, // convert to cents
            'currency' => 'usd',
            'source' => $paymentMethod,
            'description' => 'Order payment',
        ]);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and improves readability by allowing you to break complex queries into logical building blocks. CTEs can be recursive, meaning the CTE can refer to itself to process hierarchical or graph‑structured data such as organizational charts or tree traversals. Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until no new rows are produced. They are evaluated before the main query, and their scope is limited to that single statement.  

Code example (recursive CTE to list an employee hierarchy):  
-- Define a recursive CTE named employee_hierarchy  
WITH RECURSIVE employee_hierarchy AS (  
    -- Anchor member: start with the top‑level manager (e.g., CEO with id = 1)  
    SELECT id, name, manager_id, 1 AS level  
    FROM employees  
    WHERE id = 1  
    UNION ALL  
    -- Recursive member: join each employee to their direct reports  
    SELECT e.id, e.name, e.manager_id, eh.level + 1 AS level  
    FROM employees e  
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.id  
)  
-- Use the CTE to retrieve the full hierarchy ordered by level  
SELECT id, name, manager_id, level  
FROM employee_hierarchy  
ORDER BY level, name;  
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is created when an inner function accesses variables from an outer function that has already finished execution. The inner function retains a reference to the outer scope’s variables, allowing them to persist beyond the outer function’s lifetime. Closures are useful for data encapsulation, creating private state, and implementing function factories. They enable patterns such as memoization, currying, and module-like structures without native classes. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state across calls.

Code example:  
function makeCounter(start) {          // outer function with a private variable  
    let count = start;                 // this variable is captured by the inner function  

    return function() {               // the inner function forms a closure  
        count += 1;                    // modifies the captured variable  
        return count;                  // returns the updated count  
    };                                 // end of inner function  
}                                      // end of outer function  

const counterA = makeCounter(0);        // each call creates a separate closure  
const counterB = makeCounter(10);  

console.log(counterA()); // 1  
console.log(counterA()); // 2  
console.log(counterB()); // 11  
console.log(counterB()); // 12   // counterA and counterB maintain independent private state.
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Text Classification with GPT‑4  

Explanation:  
Few‑shot prompting lets you teach a language model a new task by providing a handful of input‑output examples directly in the prompt, eliminating the need for fine‑tuning. By carefully formatting the examples and using clear separators, the model can infer the desired pattern and apply it to unseen inputs. This technique works especially well for classification tasks where the label set is small and the domain is well‑defined. Adjusting temperature, max tokens, and the “stop” sequence helps keep responses concise and consistent. Prompt engineering also includes experimenting with role‑based messages (system, user, assistant) to steer the model’s behavior.

Code example (Python, OpenAI API) – builds a few‑shot prompt for sentiment analysis and calls GPT‑4:

import os
import openai

# Load your API key from environment variable or other secure store
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    """
    Sends a few‑shot prompt to GPT‑4 to classify the sentiment of `text`.
    Returns one of: Positive, Negative, Neutral.
    """
    # Construct the prompt with three labeled examples and the new input
    prompt = """Classify the sentiment of the following sentences as Positive, Negative, or Neutral.

Example 1:
Sentence: I love the new design of this app!
Sentiment: Positive

Example 2:
Sentence: The update caused several crashes and bugs.
Sentiment: Negative

Example 3:
Sentence: The battery life is okay, not great but acceptable.
Sentiment: Neutral

Now classify this sentence:
Sentence: """ + text + """
Sentiment:"""

    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",               # lightweight GPT‑4 variant suitable for prompts
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,                    # deterministic output for classification
        max_tokens=10,                      # we only need the short label
        stop=["\n"]                         # stop at line break to avoid extra text
    )
    # Extract the model's answer, strip whitespace
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
if __name__ == "__main__":
    test_sentence = "The customer service was helpful but the wait time was long."
    print(f"Input: {test_sentence}")
    print(f"Predicted sentiment: {classify_sentiment(test_sentence)}")
*/

