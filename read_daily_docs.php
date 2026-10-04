<?php
// 2026-10-04 07:21:43

/* PHP
Topic: Anonymous Functions and Closures in PHP  

Explanation:  
Anonymous functions, also called closures, allow you to create functions without naming them.  
They can capture variables from the surrounding scope, enabling powerful functional patterns.  
Closures are useful for callbacks, array manipulation, and creating lightweight, one‑off logic.  
PHP automatically binds the `$this` context inside a closure when used within a class.  
You can also manually bind a different object or `null` using the `bindTo` method.  

Code example:  

<?php  
// Define an array of numbers  
$numbers = [1, 2, 3, 4, 5];  

// Use an anonymous function as a callback for array_map  
$squared = array_map(function($n) {  
    // This function squares each element and returns the result  
    return $n * $n;  
}, $numbers);  

// Output the squared numbers  
print_r($squared);  

// Example of a closure capturing an external variable  
$multiplier = 3;  
$multiply = function($value) use ($multiplier) {  
    // $multiplier is captured from the outer scope  
    return $value * $multiplier;  
};  

echo $multiply(10); // prints 30  

// Binding a closure to a different object (optional)  
class Greeter {  
    private $greeting = 'Hello';  
    public function getGreetingFunction() {  
        return function($name) {  
            // $this refers to the Greeter instance when bound  
            return $this->greeting . ', ' . $name . '!';  
        };  
    }  
}  

$greeter = new Greeter();  
$greetFn = $greeter->getGreetingFunction();  
echo $greetFn('Alice'); // prints "Hello, Alice!"  
?>
*/

/* Laravel
Topic: Laravel Service Container & Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic resolution. It enables you to bind abstractions to concrete implementations, making your code more modular and testable. When a class is type‑hinted in a controller or another class, the container automatically injects the required instance. This process is called dependency injection and reduces the need for manual object creation. By leveraging the container, you can swap implementations without changing the dependent code, supporting the SOLID principles.

Code example (with comments):
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
        // Bind the interface to a concrete class
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could read config values here if needed
            return new StripePaymentGateway(config('services.stripe.secret'));
        });
    }
}

// ------------------------------------------------------------

namespace App\Contracts;

interface PaymentGateway
{
    // Define a contract for processing payments
    public function charge(float $amount, string $currency);
}

// ------------------------------------------------------------

namespace App\Services;

use App\Contracts\PaymentGateway;
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGateway
{
    protected $stripe;

    public function __construct(string $secretKey)
    {
        // Initialise the Stripe client with the secret key
        $this->stripe = new StripeClient($secretKey);
    }

    public function charge(float $amount, string $currency)
    {
        // Perform the actual charge using Stripe's API
        return $this->stripe->charges->create([
            'amount'   => $amount * 100, // amount in cents
            'currency' => $currency,
            'source'   => 'tok_visa',    // test token
        ]);
    }
}

// ------------------------------------------------------------

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $paymentGateway;

    // Laravel automatically injects the bound implementation
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function charge(Request $request)
    {
        $amount   = $request->input('amount');
        $currency = $request->input('currency', 'usd');

        // Use the injected payment gateway to process the charge
        $result = $this->paymentGateway->charge($amount, $currency);

        return response()->json($result);
    }
}
?>
*/

/* MySQL
Topic: MySQL Stored Procedures

Explanation:  
A stored procedure is a named set of SQL statements stored on the MySQL server that can be invoked repeatedly.  
It allows you to encapsulate complex logic, loop constructs, and conditional processing inside the database.  
Parameters can be passed in (IN), out (OUT), or both (INOUT), enabling flexible data exchange with the caller.  
Procedures improve performance by reducing network round‑trips because the logic runs server‑side.  
They also help enforce business rules consistently across different applications that share the same database.

Code Example (with inline comments):
CREATE PROCEDURE GetCustomerOrders(IN p_customer_id INT, OUT p_total_orders INT)
BEGIN
    -- Initialize the output variable
    SET p_total_orders = 0;

    -- Count the number of orders for the given customer
    SELECT COUNT(*) INTO p_total_orders
    FROM orders
    WHERE customer_id = p_customer_id;

    -- If the customer has no orders, raise a notice (optional)
    IF p_total_orders = 0 THEN
        SELECT CONCAT('Customer ', p_customer_id, ' has no orders.') AS message;
    END IF;
END;
-- To call the procedure and retrieve the result:
-- CALL GetCustomerOrders(123, @orderCount);
-- SELECT @orderCount AS total_orders;
*/

/* JavaScript
Topic: Debouncing in JavaScript

Explanation:
Debouncing is a technique that limits how often a function can be invoked. It is especially useful for performance‑critical events such as window resizing, scrolling, or keypresses, where the handler might be called many times per second. The debounced function postpones its execution until after a specified wait time has elapsed since the last call. If the event fires again before the wait period ends, the timer resets, ensuring the original function runs only once after the rapid activity stops. This helps reduce unnecessary calculations, network requests, or DOM updates.

Code example (with comments):

function debounce(func, wait) {
    // Holds the timeout identifier between calls
    let timeoutId = null;

    // Return a new function that wraps the original
    return function(...args) {
        // If a timer is already running, clear it
        if (timeoutId !== null) {
            clearTimeout(timeoutId);
        }

        // Set a new timer to invoke the original function after 'wait' ms
        timeoutId = setTimeout(() => {
            // Call the original function with the correct context and arguments
            func.apply(this, args);
        }, wait);
    };
}

// Usage example: log the window width only after the user stops resizing for 300ms
const logWidth = debounce(() => {
    console.log('Window width:', window.innerWidth);
}, 300);

window.addEventListener('resize', logWidth);
*/

/* AI
Topic: Function Calling with OpenAI’s Chat Completion API  

Explanation:  
1. Function calling lets a language model decide when to invoke a predefined function, turning natural‑language requests into structured data.  
2. The model receives a list of function specifications (name, description, JSON schema) and can return a “function_call” object instead of a normal text response.  
3. This approach improves reliability for tasks like data extraction, calendar scheduling, or code generation because the output follows a strict schema.  
4. The developer receives the function name and arguments, executes the real code, and can feed the result back to the model for follow‑up conversation.  
5. Using function calling reduces post‑processing effort and mitigates hallucinations when exact data formats are required.  

Code Example (Python, using openai library):  
import os  
import json  
import openai  

# Set your API key – in practice load from environment or secret manager  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define the function the model may call  
functions = [  
    {  
        "name": "get_weather",  
        "description": "Retrieve current weather for a given city",  
        "parameters": {  
            "type": "object",  
            "properties": {  
                "city": {"type": "string", "description": "Name of the city"},  
                "unit": {"type": "string", "enum": ["celsius", "fahrenheit"], "default": "celsius"}  
            },  
            "required": ["city"]  
        }  
    }  
]  

# User prompt asking for weather information  
user_message = {"role": "user", "content": "What's the weather like in Tokyo right now?"}  

# First call – let the model decide whether to call the function  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=[user_message],  
    functions=functions,  
    function_call="auto"   # model can choose to call or respond normally  
)  

msg = response.choices[0].message  

if msg.get("function_call"):  
    # Model decided to call get_weather – extract arguments  
    function_name = msg["function_call"]["name"]  
    arguments = json.loads(msg["function_call"]["arguments"])  

    # Simulated function implementation (replace with real API call)  
    def get_weather(city, unit="celsius"):  
        # Placeholder data – in production query a weather service  
        dummy_data = {"Tokyo": {"celsius": 22, "fahrenheit": 71}}  
        temp = dummy_data.get(city, {}).get(unit, "unknown")  
        return {"city": city, "unit": unit, "temperature": temp}  

    function_response = get_weather(**arguments)  

    # Send the function result back to the model for a final answer  
    follow_up = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[user_message, msg, {"role": "function", "name": function_name, "content": json.dumps(function_response)}]  
    )  

    final_reply = follow_up.choices[0].message["content"]  
    print(final_reply)  
else:  
    # Model answered directly without needing a function call  
    print(msg["content"])
*/

