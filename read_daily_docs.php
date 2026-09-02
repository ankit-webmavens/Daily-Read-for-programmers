<?php
// 2026-09-02 06:21:58

/* PHP
Topic: PHP Namespaces

Explanation:  
Namespaces allow you to group related classes, interfaces, functions, and constants under a single name, preventing name collisions in larger projects. They are defined with the namespace keyword at the top of a PHP file. When using a class from another namespace, you either import it with the use statement or reference it with its fully qualified name. Namespaces can be nested, creating a hierarchy that mirrors directory structure. They are especially useful when integrating third‑party libraries that might use the same class names as your own code.

Code example with comments:

<?php
// Define a namespace for the library
namespace MyApp\Utilities;

// A simple class inside the namespace
class StringHelper
{
    // Returns the string reversed
    public static function reverse(string $input): string
    {
        return strrev($input);
    }
}

// ---------------------------------------------------
// In a different file, we use the class defined above
// ---------------------------------------------------
namespace MyApp\Controllers;

use MyApp\Utilities\StringHelper; // Import the class for easier access

// Example controller method
function showReversed()
{
    $original = "Hello, World!";
    // Call the static method from the imported class
    $reversed = StringHelper::reverse($original);
    echo "Original: $original\n";
    echo "Reversed: $reversed\n";
}

// Execute the function
showReversed();
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection

Explanation:
- The service container is the core of Laravel's inversion of control (IoC) system.  
- It resolves class dependencies automatically, allowing you to type‑hint classes in constructors.  
- Bindings can be defined in service providers to tell the container how to build an object.  
- When a class is requested, the container examines its constructor parameters and injects the appropriate instances.  
- This mechanism simplifies testing, promotes loose coupling, and reduces boilerplate code.  
- You can also bind interfaces to concrete implementations for flexible swapping.  

Code Example with Comments:

<?php
namespace App\Services;

class PaymentGateway
{
    // Simple method that would interact with an external API
    public function charge($amount)
    {
        // Imagine sending request to a payment provider
        return "Charged {$amount} dollars.";
    }
}

<?php
namespace App\Contracts;

interface PaymentGatewayInterface
{
    // Contract that any payment gateway must implement
    public function charge($amount);
}

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGatewayInterface;
use App\Services\PaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the concrete class so the container knows what to resolve
        $this->app->bind(PaymentGatewayInterface::class, PaymentGateway::class);
    }
}

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayInterface;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel automatically injects the concrete class bound to the interface
    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store()
    {
        $amount = 100;
        // Use the injected service to perform the charge
        $result = $this->paymentGateway->charge($amount);
        return response()->json(['message' => $result]);
    }
}
*/

/* MySQL
Topic: MySQL Stored Procedures with IN, OUT, and INOUT Parameters  

Explanation:  
- A stored procedure is a reusable set of SQL statements that runs on the server.  
- Parameters can be defined as IN (input only), OUT (output only), or INOUT (both input and output).  
- IN parameters let you pass values into the procedure, while OUT parameters return results to the caller.  
- INOUT parameters start with a value provided by the caller and can be modified inside the procedure to return a new value.  
- Using stored procedures improves performance by reducing network round‑trips and encapsulating business logic.  

Code example (with comments):  

DELIMITER $$  
CREATE PROCEDURE GetEmployeeStats(  
    IN dept_id INT,                -- input: department identifier  
    OUT emp_count INT,            -- output: number of employees in the department  
    INOUT total_salary DECIMAL(10,2)  -- input/output: total salary, will be set inside the proc  
)  
BEGIN  
    -- Count employees in the given department and store the result in emp_count  
    SELECT COUNT(*) INTO emp_count  
    FROM employees  
    WHERE department_id = dept_id;  

    -- Sum salaries for the department and store the result in total_salary (overwrites input value)  
    SELECT SUM(salary) INTO total_salary  
    FROM employees  
    WHERE department_id = dept_id;  
END$$  
DELIMITER ;  

-- Example of calling the procedure  
SET @dept = 3;          -- department to query  
SET @cnt = 0;           -- variable to receive employee count (OUT)  
SET @sal = 0.00;        -- variable to receive total salary (INOUT)  
CALL GetEmployeeStats(@dept, @cnt, @sal);  
SELECT @cnt AS employee_count, @sal AS total_salary;   -- display the results.
*/

/* JavaScript
Topic Name: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
This allows the inner function to remember variables from the outer function after the outer function has finished.  
Closures are created each time a function is created, not when it is called.  
They are useful for data encapsulation, creating private variables, and for patterns like partial application or memoization.  
Understanding closures is essential for mastering asynchronous patterns and callbacks in JavaScript.  

Code Example:  
function makeCounter() {  
    let count = 0;                     // count is private to makeCounter  
    return function() {               // this inner function forms a closure  
        count += 1;                    // accesses and updates the private count  
        console.log('Current count:', count);  
    };  
}  
const counterA = makeCounter();        // creates a new closure with its own count  
counterA();                            // Current count: 1  
counterA();                            // Current count: 2  
const counterB = makeCounter();        // new independent closure  
counterB();                            // Current count: 1  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
- Few‑shot prompting supplies the model with a handful of example input‑output pairs to steer its behavior without fine‑tuning.  
- By framing the task as a conversation, you can embed demonstrations directly in the system or user messages.  
- This technique works well for classification, transformation, or structured data extraction tasks.  
- It is lightweight, requires only API calls, and can be adapted on the fly for new domains.  
- Properly crafted examples reduce hallucinations and improve consistency across responses.  

Code example (Python, using the official openai library):  

import os  
import openai  

# Load your API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that teaches the model to extract city names from sentences  
few_shot_prompt = [  
    {"role": "system", "content": "You are an assistant that extracts the name of the city mentioned in a user sentence. Return only the city name or 'None' if no city is present."},  
    {"role": "user", "content": "I will travel to Paris next summer."},  
    {"role": "assistant", "content": "Paris"},  
    {"role": "user", "content": "The conference was held in Tokyo last year."},  
    {"role": "assistant", "content": "Tokyo"},  
    {"role": "user", "content": "She loves reading books."},  
    {"role": "assistant", "content": "None"},  
]  

# New query we want the model to answer using the learned pattern  
new_query = {"role": "user", "content": "Our next meeting is scheduled in Berlin on Friday."}  

# Combine the few‑shot examples with the new query  
messages = few_shot_prompt + [new_query]  

# Call the Chat Completion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",            # choose a suitable model  
    messages=messages,  
    temperature=0.0,                # deterministic output for extraction tasks  
)  

# Extract and print the assistant’s answer (the city name)  
city = response.choices[0].message.content.strip()  
print("Extracted city:", city)   # Expected output: "Berlin"  
*/

