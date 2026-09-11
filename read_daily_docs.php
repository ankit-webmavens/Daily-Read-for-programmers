<?php
// 2026-09-11 06:35:34

/* PHP
Topic: PHP Generators

Explanation:
Generators allow you to create iterators without the overhead of building an entire array in memory.  
They use the yield keyword to return values one at a time, pausing execution until the next value is requested.  
This is especially useful for processing large data sets, reading files line‑by‑line, or streaming results from a database.  
Each call to next() or a foreach loop resumes the function right after the last yield, preserving local variable state.  
Generators improve performance and reduce memory consumption while keeping code readable and maintainable.  

Code example (PHP 7+):

function readLargeFile(string $filePath): Generator
{
    // Open the file for reading
    $handle = fopen($filePath, 'r');
    if ($handle === false) {
        throw new RuntimeException("Cannot open file: $filePath");
    }

    try {
        // Read each line and yield it to the caller
        while (($line = fgets($handle)) !== false) {
            // Trim the line and yield it
            yield trim($line);
        }
    } finally {
        // Ensure the file handle is always closed
        fclose($handle);
    }
}

// Usage example
foreach (readLargeFile('bigdata.txt') as $row) {
    // Process each line without loading the whole file into memory
    echo $row . PHP_EOL;
}
*/

/* Laravel
Topic: Laravel Service Container & Automatic Dependency Injection

Explanation:
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. It resolves classes by reading their constructor type‑hints, instantiating required services, and injecting them where needed. By binding interfaces to concrete implementations, you can swap out implementations without changing the consuming code. This promotes loose coupling and makes testing easier, as you can replace real services with mocks. The container works behind the scenes for controllers, event listeners, jobs, and even route closures.

Code Example (app/Providers/AppServiceProvider.php):
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    // Register bindings in the container
    public function register()
    {
        // Whenever the PaymentGateway interface is requested,
        // resolve it to an instance of StripePaymentGateway.
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You can pull configuration values from the config system.
            $apiKey = config('services.stripe.secret');
            return new StripePaymentGateway($apiKey);
        });
    }

    public function boot()
    {
        // No boot logic needed for this example.
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

    // The container automatically injects the concrete implementation
    // bound to PaymentGateway when the controller is instantiated.
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $orderData = $request->all();

        // Use the injected payment gateway to process payment.
        $this->paymentGateway->charge($orderData['amount'], $orderData['currency']);

        // Continue with order creation logic...
        return response()->json(['status' => 'order placed']);
    }
}
?>
*/

/* MySQL
Topic: Prepared Statements (Parameterized Queries) in MySQL  

Explanation:  
Prepared statements let you separate SQL code from data values, which improves security by preventing SQL injection. The server parses and optimizes the statement once, then you can execute it many times with different parameters, reducing overhead. Placeholders (question marks) mark where values will be supplied later. Values are sent to the server separately, so they are never interpreted as part of the SQL text. This technique is especially useful in applications that run the same query repeatedly with different input values.  

Code example (MySQL client syntax with comments):  

-- Define the SQL with a placeholder for the employee ID  
PREPARE emp_stmt FROM 'SELECT first_name, last_name, salary FROM employees WHERE employee_id = ?';  

-- Supply a value for the placeholder (e.g., employee_id = 7)  
SET @emp_id = 7;  

-- Execute the prepared statement using the supplied value  
EXECUTE emp_stmt USING @emp_id;  

-- Clean up by deallocating the prepared statement  
DEALLOCATE PREPARE emp_stmt;  
*/

/* JavaScript
Topic: Closures and Lexical Scoping in JavaScript

Explanation:
- A closure is a function that retains access to its lexical environment even after the outer function has finished executing.  
- JavaScript creates a new scope for each function, and inner functions can reference variables defined in outer scopes.  
- Closures enable data encapsulation, private state, and function factories.  
- They are formed automatically whenever a function accesses variables from its parent scope.  
- Understanding closures is essential for writing efficient asynchronous code and managing memory correctly.  

Code Example (with comments):
function createCounter(initialValue) {               // Outer function that sets up the counter
    let count = initialValue;                       // Private variable, not accessible from outside
    return function increment(step = 1) {           // Inner function forms a closure over `count`
        count += step;                              // Modifies the private `count` variable
        console.log(`Current count: ${count}`);    // Shows the updated count
        return count;                               // Returns the current value
    };
}

// Using the closure
const counterA = createCounter(0);   // counterA has its own private `count`
counterA();                          // Output: Current count: 1
counterA(5);                         // Output: Current count: 6

const counterB = createCounter(10);  // counterB has a separate `count`
counterB();                          // Output: Current count: 11
counterB(2);                         // Output: Current count: 13

// The two counters operate independently because each closure captures its own `count` variable.
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with the OpenAI API  

Explanation:  
Few‑shot prompting supplies the model with a handful of example input‑output pairs, guiding it to perform a new task without fine‑tuning. By carefully selecting diverse examples and explicitly stating the desired format, you can improve consistency and reduce hallucinations. Use a clear instruction line, followed by a separator, then the examples, and finally the new query. Adjust temperature low (e.g., 0.2) to favor deterministic answers for structured outputs. This technique works well for data extraction, code generation, and classification tasks.

Code example (Python, using openai library):

import openai

# Set your OpenAI API key
openai.api_key = "YOUR_API_KEY"

def classify_sentiment(text):
    # Define the system instruction and few‑shot examples
    prompt = (
        "You are an assistant that classifies the sentiment of a sentence as Positive, Negative, or Neutral.\n"
        "Examples:\n"
        "Sentence: I love the new design! Sentiment: Positive\n"
        "Sentence: The product arrived late and broken. Sentiment: Negative\n"
        "Sentence: The meeting was okay, nothing special. Sentiment: Neutral\n"
        "\n"
        f"Sentence: {text} Sentiment:"
    )

    # Call the OpenAI chat completion endpoint
    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=[{"role": "user", "content": prompt}],
        temperature=0.2,          # low temperature for consistent output
        max_tokens=10,            # we only need a short label
        n=1,
        stop=None
    )
    # Extract the model's answer and strip whitespace
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
print(classify_sentiment("The food was bland but the service was friendly."))   # Expected: Neutral or Positive depending on wording.
*/

