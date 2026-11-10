<?php
// 2026-11-10 08:47:32

/* PHP
PHP Topic: Generators (Yield)

Explanation:  
Generators provide a simple way to create iterators without building a full class that implements the Iterator interface.  
When a function contains the `yield` keyword, PHP returns a Generator object that produces values one at a time.  
This approach reduces memory usage because values are generated on‑the‑fly rather than stored in an array.  
Generators can receive input via `send()` and can return a final value with `return`.  
They are especially useful for processing large data sets, reading files line by line, or streaming results from a database.

Code Example (with comments):
<?php
// A generator function that yields numbers from 1 to $max
function rangeGenerator(int $max): Generator
{
    for ($i = 1; $i <= $max; $i++) {
        // Yield the current number and pause execution
        yield $i;
    }
}

// Using the generator
$gen = rangeGenerator(5); // Creates a Generator object

foreach ($gen as $number) {
    // Each iteration resumes the function where it left off
    echo "Number: $number\n";
}

// Example of sending a value back into the generator
function counter(): Generator
{
    $count = 0;
    while (true) {
        // Yield the current count and wait for a value to be sent
        $increment = yield $count;
        // If a value is sent, add it; otherwise increment by 1
        $count += $increment ?? 1;
    }
}

$counter = counter();
echo $counter->current() . "\n"; // Outputs 0
$counter->next();                // Advances to next yield
echo $counter->current() . "\n"; // Outputs 1
$counter->send(5);               // Adds 5 to the count
echo $counter->current() . "\n"; // Outputs 6
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic resolution of objects. By binding interfaces to concrete implementations, you can swap out classes without changing the consuming code. Dependency injection allows you to type‑hint required services directly in controller constructors or method signatures, letting the container inject the appropriate instance. This promotes loose coupling, easier testing, and cleaner code organization. Understanding how to register bindings and resolve them is essential for building maintainable Laravel applications.  

Code example (app/Providers/AppServiceProvider.php):  
public function register()  
{  
    // Bind an interface to a concrete class so the container knows what to inject  
    $this->app->bind(  
        App\Contracts\PaymentGateway::class,  
        App\Services\StripePaymentGateway::class  
    );  
}  

Usage in a controller (app/Http/Controllers/OrderController.php):  
namespace App\Http\Controllers;  

use App\Contracts\PaymentGateway;  
use Illuminate\Http\Request;  

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
        // Use the injected service to process a payment  
        $this->paymentGateway->charge($request->user(), $request->amount);  

        // Continue with order creation logic...  
        return response()->json(['status' => 'order placed']);  
    }  
}  

Interface definition (app/Contracts/PaymentGateway.php):  
namespace App\Contracts;  

interface PaymentGateway  
{  
    public function charge($user, $amount);  
}  

Concrete implementation (app/Services/StripePaymentGateway.php):  
namespace App\Services;  

use App\Contracts\PaymentGateway;  
use Stripe\StripeClient;  

class StripePaymentGateway implements PaymentGateway  
{  
    protected $stripe;  

    public function __construct()  
    {  
        // Initialize the Stripe client with secret key  
        $this->stripe = new StripeClient(config('services.stripe.secret'));  
    }  

    public function charge($user, $amount)  
    {  
        // Perform the actual charge using Stripe's API  
        $this->stripe->charges->create([  
            'amount' => $amount * 100, // amount in cents  
            'currency' => 'usd',  
            'customer' => $user->stripe_customer_id,  
            'description' => 'Order payment',  
        ]);  
    }  
}  

With this setup, swapping to another gateway (e.g., PayPal) only requires creating a new class that implements PaymentGateway and changing the binding in the service provider. The controller code remains unchanged.
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by breaking complex queries into logical parts.  
Recursive CTEs allow you to query hierarchical or tree‑structured data, such as organizational charts or bill‑of‑materials.  
Each iteration of a recursive CTE builds upon the result of the previous iteration until a termination condition is met.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.

Code example (recursive CTE to list an employee hierarchy):
-- Define the anchor member: top‑level manager(s) with no supervisor
WITH RECURSIVE employee_hierarchy AS (
    SELECT
        emp_id,
        emp_name,
        manager_id,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join employees to their managers
    SELECT
        e.emp_id,
        e.emp_name,
        e.manager_id,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.emp_id
)
SELECT
    emp_id,
    emp_name,
    manager_id,
    level
FROM employee_hierarchy
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to its lexical scope even when the outer function has finished executing.  
It allows the inner function to remember variables from the outer function’s environment.  
Closures are useful for data encapsulation, creating private variables, and partial application.  
They are created automatically each time a function is defined inside another function.  
Understanding closures helps avoid common pitfalls with asynchronous code and loops.

Code example:
// outer function creates a private counter variable
function createCounter(initialValue) {
    let count = initialValue;               // this variable is private to the closure

    // inner function forms a closure over 'count'
    return function increment(step = 1) {
        count += step;                       // can read and modify 'count'
        console.log('Current count:', count);
        return count;
    };
}

// use the closure
const counterA = createCounter(0);           // start at 0
counterA();                                  // prints "Current count: 1"
counterA(5);                                 // prints "Current count: 6"

const counterB = createCounter(10);          // separate closure, starts at 10
counterB();                                  // prints "Current count: 11"
counterB(2);                                 // prints "Current count: 13"
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs to guide its behavior on new tasks.  
By carefully crafting the examples and using clear separators, you can shape the model’s style, format, and domain knowledge.  
This technique works well for classification, data extraction, and code generation without any model fine‑tuning.  
You can dynamically build the prompt in code, insert user input, and parse the structured response.  
Adjust temperature and max_tokens to control creativity and length of the generated answer.  

Code example (Python, using the openai package):  

import os, json, openai  

# Load your OpenAI API key from environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt with two examples for extracting product price from a description  
examples = [  
    {"role": "user", "content": "Product: Wireless Mouse\nDescription: A smooth, ergonomic mouse with 2.4 GHz connectivity.\nPrice: $25.99"},  
    {"role": "assistant", "content": "{\"price\": \"25.99\"}"},  
    {"role": "user", "content": "Product: Bluetooth Speaker\nDescription: Portable speaker with 10 h battery life.\nPrice: $45.00"},  
    {"role": "assistant", "content": "{\"price\": \"45.00\"}"},  
]  

def extract_price(product_description: str) -> dict:  
    # Build the conversation: few‑shot examples + new query  
    messages = examples + [{"role": "user", "content": product_description}]  

    # Call the Chat Completion endpoint  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",          # lightweight model suitable for prompting tasks  
        messages=messages,  
        temperature=0.0,              # deterministic output for extraction  
        max_tokens=50,                # enough to return a short JSON object  
        response_format={"type": "json_object"}  # ask model to output valid JSON  
    )  

    # Parse the JSON string returned by the model  
    result = json.loads(response.choices[0].message.content)  
    return result  

# Example usage  
query = "Product: Smart Thermostat\nDescription: Wi‑Fi enabled thermostat with voice control.\nPrice: $199.99"  
print(extract_price(query))   # Expected output: {'price': '199.99'}
*/

