<?php
// 2026-09-04 06:29:37

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:
Prepared statements separate SQL code from user‑supplied data, preventing SQL injection attacks. PDO (PHP Data Objects) provides a consistent API for many databases, making it easy to switch drivers. The query is first prepared, then bound with values and executed. This approach also improves performance when the same statement is run multiple times with different parameters. Errors can be handled via exceptions for robust error reporting.

Code example with comments:
<?php
// Create a PDO instance (replace DSN, username, and password with your own)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$inputUsername = $_POST['username'];   // Assume data comes from a form
$inputEmail    = $_POST['email'];

$stmt->bindParam(':username', $inputUsername, PDO::PARAM_STR);
$stmt->bindParam(':email',    $inputEmail,    PDO::PARAM_STR);

// Execute the prepared statement
try {
    $stmt->execute();
    echo 'User added successfully.';
} catch (PDOException $e) {
    echo 'Insert failed: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection

Explanation:
The Service Container is the core of Laravel’s inversion of control (IoC) system. It is responsible for managing class dependencies and performing automatic resolution of objects. By binding abstractions to concrete implementations, you can easily swap out components without changing the consuming code. Dependency injection allows you to type‑hint required classes in constructors or methods, and the container will automatically provide fully‑resolved instances. This promotes loose coupling, testability, and adherence to the single‑responsibility principle throughout your application.

Code Example (with inline comments):

<?php
namespace App\Http\Controllers;

use App\Services\PaymentGatewayInterface;   // The contract the controller depends on
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $paymentGateway;

    // The container injects an implementation of PaymentGatewayInterface automatically
    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;   // Store the concrete instance for later use
    }

    public function process(Request $request)
    {
        $order = $request->all();

        // Use the injected service to charge the customer
        $result = $this->paymentGateway->charge($order['amount'], $order['card_token']);

        if ($result->successful()) {
            // ... handle success
        } else {
            // ... handle failure
        }

        return response()->json($result);
    }
}

// In a service provider (e.g., App\Providers\AppServiceProvider)
public function register()
{
    // Bind the interface to a concrete class so the container knows what to resolve
    $this->app->bind(
        \App\Services\PaymentGatewayInterface::class,
        \App\Services\StripePaymentGateway::class
    );
}

// Example interface
namespace App\Services;

interface PaymentGatewayInterface
{
    public function charge(float $amount, string $token);
}

// Example concrete implementation
namespace App\Services;

use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGatewayInterface
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function charge(float $amount, string $token)
    {
        // Interact with Stripe's API to create a charge
        return $this->stripe->charges->create([
            'amount' => $amount * 100, // Stripe expects amount in cents
            'currency' => 'usd',
            'source' => $token,
            'description' => 'Order payment',
        ]);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by separating complex subqueries from the main query logic.  
Recursive CTEs allow you to iterate over hierarchical data, such as organization charts or bill‑of‑materials, without using stored procedures.  
The recursion terminates when the anchor query stops producing new rows, preventing infinite loops.  
CTEs exist only for the duration of the statement, so they do not affect the database schema or require cleanup.  

Code example (MySQL 8.0+):  

WITH RECURSIVE OrgChart AS (  
    -- Anchor member: start with the top‑level manager (e.g., employee_id = 1)  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE employee_id = 1  

    UNION ALL  

    -- Recursive member: find employees whose manager is in the previous level  
    SELECT e.employee_id, e.manager_id, e.employee_name, oc.level + 1 AS level  
    FROM employees e  
    INNER JOIN OrgChart oc ON e.manager_id = oc.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM OrgChart  
ORDER BY level, employee_id;  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures allow inner functions to reference variables declared in an outer function after the outer function has finished running.  
They are useful for data encapsulation, creating private variables, and implementing function factories.  
Because the inner function forms a closure over the outer variables, those variables persist in memory as long as the closure exists.  
Understanding closures helps avoid common pitfalls such as unintentionally sharing mutable state across multiple invocations.

Code example with comments:
function makeCounter() {                     // outer function creates a private count variable
    let count = 0;                           // this variable is scoped to makeCounter
    return function() {                     // inner function forms a closure over count
        count += 1;                          // modify the private count
        console.log('Current count:', count); // access the retained count value
    };
}
const counterA = makeCounter();               // each call to makeCounter creates a new closure
const counterB = makeCounter();
counterA(); // Current count: 1
counterA(); // Current count: 2
counterB(); // Current count: 1   (separate closure, independent count)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting lets you teach a language model new behavior by providing example input‑output pairs within the prompt.  
You include a brief instruction, several demonstration examples, and then the new query you want the model to answer.  
This technique works well for tasks like text classification, data extraction, or style transfer without fine‑tuning.  
When using the OpenAI API, you format the prompt as a single string and send it as the "system" or "user" message.  
Adjust the number of examples and temperature to balance consistency and creativity.  

Code example (Python, using openai package):  

import os  
import openai  

# Set your OpenAI API key (ensure it is stored securely)  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Define a few‑shot prompt with two labeled examples  
    prompt = (  
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"  
        "Sentence: I love the new design of the app!\n"  
        "Sentiment: Positive\n\n"  
        "Sentence: The update caused many crashes.\n"  
        "Sentiment: Negative\n\n"  
        f"Sentence: {text}\n"  
        "Sentiment:"  
    )  

    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",          # or any Chat model you have access to  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,               # deterministic output for classification  
        max_tokens=10,                 # we only need the short label  
    )  

    # Extract the model's answer, strip whitespace and line breaks  
    sentiment = response.choices[0].message.content.strip()  
    return sentiment  

# Example usage  
if __name__ == "__main__":  
    test_sentence = "The customer support was okay, not great but not terrible."  
    print(f"Input: {test_sentence}")  
    print("Predicted Sentiment:", classify_sentiment(test_sentence))  
*/

