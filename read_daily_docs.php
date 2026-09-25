<?php
// 2026-09-25 06:33:19

/* PHP
PHP Topic: Generators (Yield)

Explanation:
- Generators provide a simple way to implement iterators without the overhead of building a full iterator class.
- They allow a function to return values one at a time, pausing execution after each yield and resuming on the next request.
- This reduces memory consumption, especially when dealing with large data sets or streams.
- Generators are created using the `yield` keyword inside a function.
- They can also receive values sent back into the generator via `send()` and handle cleanup with `return` or `finally`.

Code Example (with comments):

<?php
// A generator function that yields numbers from 1 up to $max
function numberSequence(int $max): Generator
{
    for ($i = 1; $i <= $max; $i++) {
        // Yield the current number and pause execution
        yield $i;
    }
}

// Use the generator
$maxValue = 5;
$gen = numberSequence($maxValue);

foreach ($gen as $number) {
    // Each iteration receives the next yielded value
    echo "Number: $number\n";
}

// Demonstrating sending a value back into the generator
function keyValueGenerator(array $data): Generator
{
    foreach ($data as $key => $value) {
        // Yield the value and wait for a possible new value to replace it
        $newValue = yield $key => $value;
        if ($newValue !== null) {
            $data[$key] = $newValue; // Update the array with the new value
        }
    }
    return $data; // Return the possibly modified array when finished
}

$sample = ['a' => 1, 'b' => 2, 'c' => 3];
$kvGen = keyValueGenerator($sample);

// Advance to the first yield and get the key/value
$kvGen->rewind(); // optional, starts the generator
list($key, $value) = $kvGen->current(); // $key = 'a', $value = 1
echo "Key: $key, Value: $value\n";

// Send a new value for the current key and move to the next
$kvGen->send(10); // replaces value of 'a' with 10
$kvGen->next();   // advance to 'b'

// Continue iteration normally
while ($kvGen->valid()) {
    $k = $kvGen->key();
    $v = $kvGen->current();
    echo "Key: $k, Value: $v\n";
    $kvGen->next();
}

// Retrieve the final array after generator completes
$finalArray = $kvGen->getReturn();
print_r($finalArray);
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection  

Explanation:  
The service container is the heart of Laravel’s inversion of control (IoC) system. It manages class dependencies and performs automatic resolution, allowing you to type‑hint classes in constructors or controller methods without manually creating them. By binding abstractions to concrete implementations, you can swap out classes easily, which is useful for testing and for adhering to the SOLID principles. The container also supports contextual bindings, singleton bindings, and automatic injection of primitive values via the service provider. Understanding the container enables clean, decoupled code and makes your application more maintainable.

Code example (app/Providers/AppServiceProvider.php):  

<?php  

namespace App\Providers;  

use Illuminate\Support\ServiceProvider;  
use App\Contracts\PaymentGateway;  
use App\Services\StripePaymentGateway;  

class AppServiceProvider extends ServiceProvider  
{  
    /**  
     * Register services.  
     */  
    public function register()  
    {  
        // Bind the PaymentGateway contract to a concrete Stripe implementation  
        $this->app->bind(PaymentGateway::class, function ($app) {  
            // You could pull configuration values here if needed  
            return new StripePaymentGateway(config('services.stripe.secret'));  
        });  

        // Example of a singleton binding – the same instance will be returned each time  
        $this->app->singleton('logger', function ($app) {  
            return new \Monolog\Logger('app');  
        });  
    }  

    /**  
     * Bootstrap services.  
     */  
    public function boot()  
    {  
        // No boot logic needed for this example  
    }  
}  

// Using the container in a controller (app/Http/Controllers/OrderController.php)  

<?php  

namespace App\Http\Controllers;  

use App\Contracts\PaymentGateway;  
use Illuminate\Http\Request;  

class OrderController extends Controller  
{  
    protected $paymentGateway;  

    // Laravel automatically injects the bound implementation  
    public function __construct(PaymentGateway $paymentGateway)  
    {  
        $this->paymentGateway = $paymentGateway;  
    }  

    public function store(Request $request)  
    {  
        // Use the injected payment gateway to process a payment  
        $this->paymentGateway->charge($request->input('amount'), $request->input('token'));  

        return response()->json(['status' => 'payment processed']);  
    }  
}  

// The contract (app/Contracts/PaymentGateway.php)  

<?php  

namespace App\Contracts;  

interface PaymentGateway  
{  
    public function charge(float $amount, string $token);  
}  

// Concrete implementation (app/Services/StripePaymentGateway.php)  

<?php  

namespace App\Services;  

use App\Contracts\PaymentGateway;  
use Stripe\StripeClient;  

class StripePaymentGateway implements PaymentGateway  
{  
    protected $stripe;  

    public function __construct(string $secretKey)  
    {  
        $this->stripe = new StripeClient($secretKey);  
    }  

    public function charge(float $amount, string $token)  
    {  
        // Call Stripe's API to create a charge  
        $this->stripe->charges->create([  
            'amount' => $amount * 100, // amount in cents  
            'currency' => 'usd',  
            'source' => $token,  
            'description' => 'Order payment',  
        ]);  
    }  
}  
*/

/* MySQL
Topic: MySQL Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
A CTE is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
It is defined using the WITH clause and improves readability by separating complex subqueries from the main query.  
MySQL supports both non‑recursive and recursive CTEs; recursive CTEs are useful for traversing hierarchical data such as organization charts or tree structures.  
The recursive part consists of an anchor query that provides the initial rows and a recursive query that references the CTE itself to generate subsequent rows.  
Recursive CTEs must include a termination condition, otherwise they will cause an infinite loop and the server will stop execution after reaching the max recursion depth.  

Code example with comments:  
WITH RECURSIVE emp_path (emp_id, emp_name, manager_id, level) AS (  
    -- Anchor query: select top‑level employees (those without a manager)  
    SELECT emp_id, emp_name, manager_id, 1  
    FROM employees  
    WHERE manager_id IS NULL  

    UNION ALL  

    -- Recursive query: find direct reports of employees already in the path  
    SELECT e.emp_id, e.emp_name, e.manager_id, ep.level + 1  
    FROM employees e  
    JOIN emp_path ep ON e.manager_id = ep.emp_id  
)  
SELECT emp_id, emp_name, manager_id, level  
FROM emp_path  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: JavaScript Closures and Lexical Scoping

Explanation:
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This works because JavaScript uses lexical scoping: a function’s scope is determined by its location in the source code, not by where it is called. Closures enable data encapsulation, allowing private variables that cannot be accessed directly from the outside. They are commonly used for creating function factories, memoization, and maintaining state in asynchronous callbacks. Understanding closures is essential for writing efficient, modular, and secure JavaScript code.

Code Example:
// Outer function creates a private counter variable
function createCounter(initialValue) {
    let count = initialValue;                     // this variable is private to createCounter

    // Inner function forms a closure over the 'count' variable
    return function increment(step = 1) {
        count += step;                           // can modify the private variable
        console.log('Current count:', count);   // side effect: output current value
        return count;                            // return the updated count
    };
}

// Use the factory to create independent counters
const counterA = createCounter(0);
const counterB = createCounter(10);

counterA();        // Current count: 1
counterA(5);       // Current count: 6
counterB();        // Current count: 11
counterB(2);       // Current count: 13

// The 'count' variable of each counter is isolated and persists across calls because of the closure.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s ChatCompletion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs inside the prompt, guiding it to produce the desired format for new queries. This technique is useful when you cannot fine‑tune a model but need consistent, structured responses. By carefully crafting the examples, you can control tone, level of detail, and even enforce JSON output for downstream processing. The approach works across many tasks such as data extraction, code generation, or summarization. It is lightweight, requires only API calls, and can be iteratively refined based on model feedback.  

Code example (Python, using the openai library):  
import os  
import openai  

# Load your API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt with two examples and a new user request  
messages = [  
    {"role": "system", "content": "You are an assistant that extracts book information and returns JSON."},  
    {"role": "user", "content": "Title: The Great Gatsby\nAuthor: F. Scott Fitzgerald\nYear: 1925"},  
    {"role": "assistant", "content": "{\"title\": \"The Great Gatsby\", \"author\": \"F. Scott Fitzgerald\", \"year\": 1925}"},  
    {"role": "user", "content": "Title: 1984\nAuthor: George Orwell\nYear: 1949"},  
    {"role": "assistant", "content": "{\"title\": \"1984\", \"author\": \"George Orwell\", \"year\": 1949}"},  
    {"role": "user", "content": "Title: To Kill a Mockingbird\nAuthor: Harper Lee\nYear: 1960"}  
]  

# Call the ChatCompletion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0.0,          # deterministic output for structured data  
    max_tokens=150            # enough for the JSON payload  
)  

# Extract and print the assistant's JSON response  
assistant_message = response["choices"][0]["message"]["content"]  
print(assistant_message)   # Expected output: {"title": "To Kill a Mockingbird", "author": "Harper Lee", "year": 1960}  
*/

