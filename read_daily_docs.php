<?php
// 2026-11-27 09:57:49

/* PHP
PHP Topic: Generators (yield)

Explanation:
Generators allow you to create iterators without the overhead of building an entire array in memory.  
By using the yield keyword, a function can pause its execution and return a value each time it is iterated.  
This is especially useful for processing large data sets, streaming files, or implementing lazy evaluation.  
Generators maintain their own internal state, so the next call resumes right after the previous yield.  
They provide a simple syntax compared to implementing the Iterator interface manually.

Code Example with Comments:
<?php
function getNumbers($max) {
    // Generator function that yields numbers from 1 up to $max
    for ($i = 1; $i <= $max; $i++) {
        // Pause execution and return the current number
        yield $i;
    }
}

// Using the generator in a foreach loop
foreach (getNumbers(5) as $number) {
    // Each iteration receives the next yielded value
    echo $number . PHP_EOL; // Outputs 1 2 3 4 5 on separate lines
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection

Explanation:  
The Laravel service container is a powerful tool for managing class dependencies and performing automatic injection. It resolves classes by reading type‑hints in constructors, method parameters, or controller actions, allowing you to decouple implementation from usage. By binding abstractions (interfaces) to concrete classes, you can swap implementations without changing the consuming code. The container also supports singleton bindings, contextual bindings, and automatic resolution of nested dependencies. Understanding the container is essential for writing clean, testable Laravel applications.

Code example (app/Providers/AppServiceProvider.php):
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;          // Interface
use App\Services\StripePaymentGateway;    // Concrete implementation

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to a concrete class so the container can resolve it
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could read configuration values here
            $apiKey = config('services.stripe.secret');
            return new StripePaymentGateway($apiKey);
        });

        // Example of a singleton binding (same instance used throughout request lifecycle)
        $this->app->singleton('logger', function ($app) {
            return new \Monolog\Logger('app');
        });
    }

    public function boot()
    {
        //
    }
}

// Usage in a controller (app/Http/Controllers/OrderController.php):
<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;   // Type‑hint the interface

class OrderController extends Controller
{
    protected $paymentGateway;

    // The service container automatically injects the bound implementation
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store()
    {
        // Use the injected payment gateway without caring about its concrete class
        $this->paymentGateway->charge(1000, 'usd');
        // ...
    }
}
*/

/* MySQL
MySQL Topic: Common Table Expressions (CTE) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve query readability by allowing you to break complex logic into named subqueries.  
MySQL supports both non‑recursive CTEs (single‑use subqueries) and recursive CTEs, which are useful for hierarchical data such as organization charts or tree structures.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself, terminating when the result set stops growing.  
Using CTEs can also enable set‑based operations that would otherwise require procedural loops, leading to better performance and maintainability.  

Code Example (recursive CTE to list an employee hierarchy):  

WITH RECURSIVE employee_hierarchy AS (  
    -- Anchor member: start with the top‑level manager (e.g., CEO with employee_id = 1)  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE employee_id = 1  

    UNION ALL  

    -- Recursive member: find direct reports of the current level  
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1  
    FROM employees e  
    JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM employee_hierarchy  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is created when a function retains access to variables from its outer (enclosing) lexical scope, even after that outer function has finished executing. This allows inner functions to remember the environment in which they were created, enabling data encapsulation and private state. Closures are fundamental for patterns like function factories, module pattern, and handling asynchronous callbacks. They can also lead to memory leaks if references to large objects are unintentionally kept alive. Understanding closures helps write more predictable and maintainable code.

Code example with comments:
function makeCounter() {
    // `count` is a private variable, accessible only inside the returned function
    let count = 0;
    // The inner function forms a closure over `count`
    return function() {
        // Increment and return the current count each time the function is called
        count++;
        return count;
    };
}

// Create a new counter instance
const counterA = makeCounter();

console.log(counterA()); // 1
console.log(counterA()); // 2
console.log(counterA()); // 3

// A separate counter has its own independent `count` variable
const counterB = makeCounter();
console.log(counterB()); // 1
console.log(counterA()); // 4   (counterA continues where it left off)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets a language model learn a new task from only a handful of examples embedded in the prompt. By carefully structuring the prompt—defining the role, providing clear instructions, and presenting input‑output pairs—you can guide the model to generate consistent, high‑quality responses without any fine‑tuning. This technique works well for classification, transformation, or extraction tasks where labeled data is scarce. The prompt should separate examples with delimiters and end with the new input, leaving the model to infer the pattern. Adjusting temperature, max_tokens, and stop sequences further refines the output behavior.

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text: str) -> str:  
    # Construct a few‑shot prompt with two labeled examples  
    prompt = """You are a helpful assistant that classifies the sentiment of a sentence as Positive, Negative, or Neutral.  

Example 1:  
Sentence: I love the new design of the app!  
Sentiment: Positive  

Example 2:  
Sentence: The update crashed my phone and ruined my day.  
Sentiment: Negative  

Now classify the following sentence:  
Sentence: """ + text + """  
Sentiment:"""  

    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",                 # lightweight model suitable for prompts  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,                     # deterministic output for classification  
        max_tokens=10,                       # we only need the short label  
        stop=["\n"]                          # stop at line break to avoid extra text  
    )  

    # Extract the model’s answer and strip whitespace  
    sentiment = response.choices[0].message.content.strip()  
    return sentiment  

# Example usage  
if __name__ == "__main__":  
    test_sentence = "The customer service was okay, not great but not bad either."  
    print(f"Sentence: {test_sentence}")  
    print(f"Predicted Sentiment: {classify_sentiment(test_sentence)}")  
*/

