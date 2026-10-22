<?php
// 2026-10-22 06:31:49

/* PHP
PHP Generators (Yield)

Generators allow you to create iterators without the overhead of building an entire array in memory.  
By using the yield keyword, a function can return a value and pause its execution, resuming later.  
This is especially useful for processing large data sets, streaming files, or handling infinite sequences.  
Generators improve performance and reduce memory consumption compared to traditional loops that collect all results.  
They can also be combined with foreach, making iteration syntax seamless and readable.

<?php
// Define a generator that yields the first n square numbers
function squareNumbers(int $n) {
    for ($i = 1; $i <= $n; $i++) {
        // Yield the current square and pause execution
        yield $i => $i * $i;
    }
}

// Use the generator in a foreach loop
foreach (squareNumbers(5) as $index => $square) {
    // Output each index and its corresponding square
    echo "Number $index squared is $square\n";
}
?>
*/

/* Laravel
Topic: Laravel Service Container – Binding and Automatic Resolution  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. By binding an interface to a concrete class, you tell the container which implementation to provide when the interface is requested. When a class type‑hinted in a controller or another class is resolved, Laravel will automatically inject the appropriate instance. This decouples your code, making it easier to swap implementations or mock dependencies during testing. The container also supports contextual bindings, allowing different implementations for the same interface in different situations.

Code example (PHP):

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;
use App\Services\PayPalGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Simple binding: whenever PaymentGateway is requested, give StripeGateway
        $this->app->bind(PaymentGateway::class, StripeGateway::class);

        // Contextual binding: for OrderController use PayPalGateway instead
        $this->app->when(\App\Http\Controllers\OrderController::class)
                  ->needs(PaymentGateway::class)
                  ->give(PayPalGateway::class);
    }
}

// Example interface
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount);
}

// Stripe implementation
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    public function charge(float $amount)
    {
        // Logic to charge via Stripe API
        return "Charged $$amount with Stripe.";
    }
}

// PayPal implementation
namespace App\Services;

use App\Contracts\PaymentGateway;

class PayPalGateway implements PaymentGateway
{
    public function charge(float $amount)
    {
        // Logic to charge via PayPal API
        return "Charged $$amount with PayPal.";
    }
}

// Controller demonstrating automatic injection
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;

class OrderController extends Controller
{
    protected $gateway;

    // Laravel injects the appropriate PaymentGateway implementation
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function store()
    {
        $result = $this->gateway->charge(99.99);
        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: MySQL Stored Procedures with IN, OUT, and INOUT Parameters  

Explanation:  
Stored procedures let you group multiple SQL statements so they can be executed as a single unit, improving performance and maintainability.  
Parameters can be defined as IN (input only), OUT (output only), or INOUT (both input and output), allowing data to be passed to and returned from the procedure.  
Using parameters makes procedures reusable for different inputs without rewriting the logic each time.  
Procedures can contain control‑flow statements such as IF, LOOP, and CASE to implement complex business rules.  
When a procedure finishes, any OUT or INOUT parameters are sent back to the caller, enabling result communication without result sets.  

Code example (with comments):  

-- Drop the procedure if it already exists to avoid errors  
DROP PROCEDURE IF EXISTS GetEmployeeStats;  

-- Create a new procedure that receives a department ID (IN) and returns the total salary (OUT) and average age (INOUT)  
CREATE PROCEDURE GetEmployeeStats(  
    IN dept_id INT,                -- input: department to analyze  
    OUT total_salary DECIMAL(10,2),-- output: sum of salaries in the department  
    INOUT avg_age DECIMAL(5,2)    -- input/output: initial average age (can be used as a starting value)  
)  
BEGIN  
    -- Compute total salary for the given department and store it in the OUT parameter  
    SELECT SUM(salary) INTO total_salary  
    FROM employees  
    WHERE department_id = dept_id;  

    -- Compute average age; if the caller supplied a non‑NULL value, use it as a weighted contribution  
    IF avg_age IS NULL THEN  
        SELECT AVG(age) INTO avg_age  
        FROM employees  
        WHERE department_id = dept_id;  
    ELSE  
        SELECT ( (avg_age * 1) + AVG(age) ) / 2 INTO avg_age   -- simple averaging with caller's value  
        FROM employees  
        WHERE department_id = dept_id;  
    END IF;  
END;  

-- Example call: retrieve stats for department 3, capture output values in session variables  
SET @total = 0;  
SET @average = NULL;  
CALL GetEmployeeStats(3, @total, @average);  

-- Display the results returned via OUT/INOUT parameters  
SELECT @total AS TotalSalary, @average AS AverageAge;  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical environment even after the outer function has finished executing.  
It allows inner functions to remember and use variables defined in an outer scope.  
Closures are created every time a function is created, enabling data privacy and function factories.  
They are essential for patterns such as module pattern, currying, and event handlers.  
Understanding closures helps avoid common pitfalls with variable scope and asynchronous code.

Code Example:
// Outer function creates a private variable and returns an inner function
function makeCounter() {
    let count = 0;                     // count is not accessible from outside
    return function() {
        count += 1;                    // inner function accesses and updates count
        console.log('Current count:', count);
    };
}

// Create two independent counters using the closure
const counterA = makeCounter();        // counterA has its own count variable
const counterB = makeCounter();        // counterB has a separate count variable

counterA(); // Output: Current count: 1
counterA(); // Output: Current count: 2
counterB(); // Output: Current count: 1   (independent from counterA)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with GPT‑4 for Text Classification  

Explanation:  
Few‑shot prompting lets a large language model learn a task from only a handful of examples supplied in the prompt, eliminating the need for fine‑tuning. By carefully formatting the prompt – including clear instructions, labeled demonstration pairs, and a delimiter before the new input – the model can infer the desired label mapping. This approach works well for classification tasks where data is scarce or rapidly changing. It also makes it easy to iterate on prompt wording without retraining a model. Using the OpenAI API, you can send the crafted prompt and retrieve the model’s prediction in a single request.

Code example (Python, OpenAI API):  
import os  
import openai  

# Load your OpenAI API key from the environment  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt for sentiment classification  
prompt = """You are a helpful assistant that classifies customer feedback as Positive, Negative, or Neutral.  
Example 1:  
Feedback: "I love the new features, they are exactly what I needed!"  
Label: Positive  

Example 2:  
Feedback: "The app crashes whenever I try to upload a photo."  
Label: Negative  

Example 3:  
Feedback: "It works fine, nothing special."  
Label: Neutral  

Now classify the following feedback:  
Feedback: "The latest update made the interface smoother and faster."  
Label:"""  

# Call the GPT‑4 model with the prompt  
response = openai.ChatCompletion.create(  
    model="gpt-4",  
    messages=[{"role": "user", "content": prompt}],  
    temperature=0.0,        # deterministic output  
    max_tokens=10           # we only need the label  
)  

# Extract and print the label from the model's reply  
label = response.choices[0].message.content.strip()  
print("Predicted label:", label)  
*/

