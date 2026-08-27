<?php
// 2026-08-27 11:58:25

/* PHP
PHP Generators (Yield)

Explanation:
Generators allow a function to return values one at a time, pausing its execution between each yield. This reduces memory usage because the entire dataset does not need to be stored in an array. They are useful for processing large data streams, such as reading big files or database result sets. The generator function returns an object that implements the Traversable interface, which can be looped with foreach. Using yield makes code more readable compared to manual iterator implementations.

Code example:
// Define a generator that yields numbers from 1 to $max
function numberSequence(int $max) : Generator {
    for ($i = 1; $i <= $max; $i++) {
        // Yield the current number and pause execution
        yield $i;
    }
}

// Use the generator in a foreach loop
foreach (numberSequence(5) as $num) {
    // $num receives each yielded value sequentially
    echo "Number: $num\n";
}

// Output:
// Number: 1
// Number: 2
// Number: 3
// Number: 4
// Number: 5

// The generator does not create an array of 5 elements; it produces each value on demand.
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection  

Explanation:  
The Laravel service container is the core of the framework’s inversion of control (IoC) system. It resolves class dependencies automatically, allowing you to type‑hint dependencies in constructors or controller methods without manually instantiating them. When a class is requested, the container checks its bindings, builds the object, and injects any required dependencies recursively. This promotes clean, testable code and decouples concrete implementations from the classes that use them. You can also bind interfaces to concrete classes, letting the container swap implementations effortlessly.  

Code example:  

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;          // Interface for a payment service
use App\Services\StripeGateway;            // Concrete implementation
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $payment;

    // The container will inject the concrete class bound to PaymentGateway
    public function __construct(PaymentGateway $payment)
    {
        $this->payment = $payment;          // Assigned to a property for later use
    }

    public function store(Request $request)
    {
        // Validate and create order logic here...

        // Use the injected payment service
        $this->payment->charge($request->user(), $order->total);
        // Continue with order confirmation...
    }
}

// In a service provider (e.g., AppServiceProvider) you bind the interface to the concrete class
public function register()
{
    $this->app->bind(
        PaymentGateway::class,
        StripeGateway::class               // Whenever PaymentGateway is requested, give StripeGateway
    );
}

// Example of the StripeGateway implementation
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    public function charge($user, $amount)
    {
        // Call Stripe API to charge the user
        // This method satisfies the contract defined by PaymentGateway
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries in MySQL

Explanation:  
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve query readability by allowing you to define subqueries up front rather than nesting them.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.  
Recursive CTEs are useful for traversing hierarchical data such as organization charts or folder structures.  
The syntax uses the WITH clause followed by the CTE definition, and a final query that consumes the CTE.

Code example (finding all ancestors of a given employee in an employee hierarchy):

-- Define the CTE named employee_hierarchy
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: start with the employee whose ancestors we need
    SELECT emp_id, manager_id, emp_name, 1 AS level
    FROM employees
    WHERE emp_id = 7                     -- replace 7 with the target employee ID
    UNION ALL
    -- Recursive member: repeatedly join to find the manager of the current row
    SELECT e.emp_id, e.manager_id, e.emp_name, eh.level + 1
    FROM employees e
    INNER JOIN employee_hierarchy eh
        ON e.emp_id = eh.manager_id
)
-- Final query: list the chain of managers from the employee up to the top‑level boss
SELECT emp_id, emp_name, manager_id, level
FROM employee_hierarchy
ORDER BY level;   -- level 1 = original employee, higher numbers = higher‑level managers  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is created when an inner function retains access to variables from its outer (enclosing) function after that outer function has finished executing. This allows the inner function to “remember” the environment in which it was created, enabling data privacy and persistent state across multiple calls. Closures are fundamental for patterns such as function factories, module design, and handling asynchronous callbacks. Because the referenced variables live on the heap rather than the stack, they are not garbage‑collected until all closures that reference them are gone. Mastering closures helps you write more modular and expressive code.

Code Example:
// Outer function defines a private variable 'count'
function createCounter() {
    let count = 0;                     // This variable is captured by the inner function

    // Inner function forms a closure over 'count'
    return function increment() {
        count++;                       // Modify the captured variable
        console.log('Current count:', count);
    };
}

// Obtain a closure that can manipulate its own private 'count'
const counterA = createCounter();
counterA(); // Current count: 1
counterA(); // Current count: 2

// Each call to createCounter produces an independent closure
const counterB = createCounter();
counterB(); // Current count: 1   (separate from counterA)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with the OpenAI API  

Explanation:  
- Few‑shot prompting lets a language model learn a new task from just a handful of examples included in the prompt.  
- The key is to format the prompt so the model can clearly see the pattern of inputs and desired outputs.  
- Use a clear delimiter (e.g., “---”) between examples and the new query to avoid confusion.  
- Include a concise instruction line at the top to set the model’s role.  
- Test variations of example ordering and wording to maximize performance before scaling.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your API key from an environment variable for security  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Build a few‑shot prompt with two labeled examples and the new query  
    prompt = (  
        "You are a sentiment analysis assistant. Classify the sentiment of each sentence as Positive, Negative, or Neutral.\n\n"  
        "Example 1:\n"  
        "Sentence: I love the new design of the app.\n"  
        "Sentiment: Positive\n\n"  
        "Example 2:\n"  
        "Sentence: The update caused many bugs and crashes.\n"  
        "Sentiment: Negative\n\n"  
        "Now classify the following sentence:\n"  
        f"Sentence: {text}\n"  
        "Sentiment:"  
    )  

    response = openai.Completion.create(  
        model="text-davinci-003",  
        prompt=prompt,  
        max_tokens=10,  
        temperature=0.0,  # deterministic output for classification  
        stop=["\n"]       # stop at the end of the label  
    )  

    # Strip whitespace and return the label  
    return response.choices[0].text.strip()  

# Example usage  
if __name__ == "__main__":  
    test_sentence = "The customer service was okay, nothing special."  
    result = classify_sentiment(test_sentence)  
    print(f"Sentiment: {result}")  
*/

