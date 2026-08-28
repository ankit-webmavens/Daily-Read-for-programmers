<?php
// 2026-08-28 13:19:41

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:
Prepared statements separate SQL code from data, which prevents SQL injection attacks. PDO provides a consistent interface for many database systems, making the code portable. You first prepare the SQL with placeholders, then bind values and execute the statement. The database parses the query only once, improving performance for repeated executions. Errors can be handled via exceptions, giving clear feedback during development.

Code example with comments:
<?php
// Create a new PDO instance for a MySQL database
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// SQL with named placeholders
$sql = 'INSERT INTO users (email, password_hash, created_at) VALUES (:email, :hash, NOW())';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data to insert
$email = 'alice@example.com';
$plainPassword = 'Secret123!';
$hash = password_hash($plainPassword, PASSWORD_BCRYPT);

// Bind values to the placeholders and execute
$stmt->bindParam(':email', $email);
$stmt->bindParam(':hash', $hash);
$stmt->execute();

// If you need the ID of the inserted row
$insertedId = $pdo->lastInsertId();
echo "New user ID: " . $insertedId . PHP_EOL;
?>
*/

/* Laravel
Topic: Laravel Service Container Binding (Interface to Implementation)

Explanation:
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection. By binding an interface to a concrete implementation, you can type‑hint the interface in your controllers or services and let the container resolve the appropriate class automatically. This promotes loose coupling and makes testing easier, as you can swap the implementation with a mock. Bindings are typically defined in a service provider's register method. When the container resolves a class, it reads the binding and injects the bound implementation.

Code example:
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;          // Interface
use App\Services\StripePaymentGateway;    // Concrete class

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGateway interface to the Stripe implementation
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could pull configuration values here if needed
            $apiKey = config('services.stripe.secret');
            return new StripePaymentGateway($apiKey);
        });
    }
}

// In a controller or any class resolved by the container
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;

class CheckoutController extends Controller
{
    protected $gateway;

    // Laravel will automatically inject the StripePaymentGateway instance
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function store()
    {
        // Use the injected gateway to process a payment
        $this->gateway->charge(1000, 'usd', 'tok_visa');
        // ...
    }
}

// Interface definition
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(int $amount, string $currency, string $source);
}

// Concrete implementation
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    protected $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
        // Initialize Stripe SDK with the secret key
    }

    public function charge(int $amount, string $currency, string $source)
    {
        // Call Stripe's API to create a charge
        // Example: \Stripe\Charge::create([...]);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries  

Explanation:  
A CTE allows you to define a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
It improves readability by separating complex subqueries from the main query logic.  
Recursive CTEs enable hierarchical data traversal, such as organizational charts or tree structures.  
The WITH clause introduces the CTE and can be chained; the recursive part must reference the CTE name.  
MySQL 8.0+ supports both non‑recursive and recursive CTEs, replacing older workarounds with temporary tables.  

Code example with comments:  
WITH RECURSIVE OrgChart AS (  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL          -- root of the hierarchy  
    UNION ALL  
    SELECT e.employee_id, e.manager_id, e.employee_name, oc.level + 1  
    FROM employees e  
    JOIN OrgChart oc ON e.manager_id = oc.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM OrgChart  
ORDER BY level, employee_name;   -- show hierarchy ordered by depth and name
*/

/* JavaScript
Topic: Async/Await with Structured Error Handling

Explanation:  
Async/Await lets you write asynchronous code that looks synchronous, improving readability.  
When combined with try/catch blocks you can handle promise rejections in a clear, linear flow.  
Await pauses the function until the promise settles, and any thrown error bubbles to the nearest catch.  
Using finally allows you to run cleanup code regardless of success or failure.  
This pattern replaces nested .then/.catch chains and makes error propagation easier to reason about.

Code Example:
// Simulated asynchronous operation that may fail
function fetchUser(id) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            if (id <= 0) {
                reject(new Error('Invalid user ID'));
            } else {
                resolve({ id, name: 'User' + id });
            }
        }, 500);
    });
}

// Async function using await and structured error handling
async function loadUser(id) {
    let user = null;
    try {
        // Await the promise; if it rejects, control jumps to catch
        user = await fetchUser(id);
        console.log('User data:', user);
    } catch (err) {
        // Handle any errors from fetchUser here
        console.error('Failed to load user:', err.message);
    } finally {
        // This runs whether the try succeeded or an error was caught
        console.log('loadUser completed for ID:', id);
    }
    return user;
}

// Example calls
loadUser(3);   // Successful fetch
loadUser(-1);  // Triggers error handling
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets a language model learn a task from a handful of examples embedded in the prompt, without any fine‑tuning. By carefully formatting the examples and clearly separating the instruction, input, and expected output, you guide the model toward the desired behavior. This technique works well for classification, transformation, or extraction tasks where labeled data is scarce. The prompt should include a short description of the task, 2‑3 representative examples, and a placeholder for the new input. Consistent formatting (e.g., using “Input:” and “Output:”) improves reliability across different queries.

Code example (Python, using the OpenAI API):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Define a few‑shot prompt that teaches the model how to label sentiment  
    prompt = """You are a sentiment analysis assistant.  
Classify the sentiment of each sentence as Positive, Negative, or Neutral.  

Input: I love the new design of the app!  
Output: Positive  

Input: The update crashed my phone twice.  
Output: Negative  

Input: The app loads quickly.  
Output: Neutral  

Input: {user_input}  
Output:"""  

    # Insert the user's text into the placeholder  
    filled_prompt = prompt.format(user_input=text)  

    # Call the Chat Completion endpoint with a single system message containing the prompt  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[{"role": "user", "content": filled_prompt}],  
        temperature=0.0,          # deterministic output for classification  
        max_tokens=10,            # we only need the short label  
    )  

    # Extract the model's answer and strip whitespace  
    sentiment = response.choices[0].message.content.strip()  
    return sentiment  

# Example usage  
if __name__ == "__main__":  
    test_sentence = "The recent bug fix made the app much smoother."  
    print(f"Sentiment: {classify_sentiment(test_sentence)}")  
*/

