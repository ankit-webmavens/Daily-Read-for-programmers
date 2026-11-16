<?php
// 2026-11-16 05:40:44

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:
Prepared statements separate SQL code from data values, preventing SQL injection attacks. PDO (PHP Data Objects) provides a uniform interface for interacting with many database systems. By preparing a statement once and executing it multiple times with different parameters, you improve performance and security. Placeholders can be named (e.g., :name) or positional (?). After binding values, the statement is executed and results are fetched. This approach also handles proper escaping and type conversion automatically.

Code example (PHP):
<?php
// Create a new PDO connection (replace DSN, username, password with real values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Define an INSERT query with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data to insert
$users = [
    ['username' => 'alice', 'email' => 'alice@example.com'],
    ['username' => 'bob',   'email' => 'bob@example.com'],
    ['username' => 'carol', 'email' => 'carol@example.com']
];

// Loop through data and execute the prepared statement for each row
foreach ($users as $user) {
    // Bind values to the placeholders and execute
    $stmt->execute([
        ':username'   => $user['username'],
        ':email'      => $user['email'],
        ':created_at' => date('Y-m-d H:i:s') // current timestamp
    ]);
}

// Retrieve the inserted rows using a prepared SELECT statement
$selectSql = "SELECT id, username, email FROM users WHERE email LIKE :domain";
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':domain' => '%@example.com']);
$rows = $selectStmt->fetchAll();

foreach ($rows as $row) {
    echo "ID: {$row['id']}, User: {$row['username']}, Email: {$row['email']}\n";
}
?>
*/

/* Laravel
Topic: Laravel Service Container & Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic resolution. By binding abstractions to concrete implementations, you can decouple your code and make it more testable. When a class is type‑hinted in a controller or another class, the container injects the appropriate instance automatically. This mechanism underpins many Laravel features, such as middleware, event listeners, and job dispatching. Understanding how to register bindings and resolve them manually gives you fine‑grained control over the application's architecture.  

Code example (PHP):  

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;          // abstraction
use App\Services\StripeGateway;            // concrete implementation
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $gateway;

    // The service container injects the concrete class bound to PaymentGateway
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;          // $gateway is now an instance of StripeGateway
    }

    public function process(Request $request)
    {
        $amount = $request->input('amount');
        // Use the injected gateway to charge the customer
        $result = $this->gateway->charge($amount);

        return response()->json(['status' => $result ? 'success' : 'failed']);
    }
}

// In a service provider (e.g., App\Providers\AppServiceProvider)
public function register()
{
    // Bind the interface to a concrete class
    $this->app->bind(
        \App\Contracts\PaymentGateway::class,
        \App\Services\StripeGateway::class
    );
}

// Example of the interface
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount): bool;
}

// Example of the concrete implementation
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    public function charge(float $amount): bool
    {
        // Here you would call Stripe's SDK; returning true for demo purposes
        return true;
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) in MySQL  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and can improve readability by breaking complex queries into logical building blocks.  
MySQL 8.0+ supports both non‑recursive and recursive CTEs, the latter being useful for hierarchical data such as organization charts or bill‑of‑materials.  
CTEs exist only for the duration of the statement in which they are defined and do not create permanent database objects.  
Multiple CTEs can be chained together by separating them with commas in the WITH clause.  

Code example (non‑recursive CTE with comments):  

WITH top_sales AS (  
    -- Select the top 5 products by total sales amount  
    SELECT p.product_id, p.product_name, SUM(o.quantity * o.unit_price) AS total_sales  
    FROM products p  
    JOIN orders o ON p.product_id = o.product_id  
    GROUP BY p.product_id, p.product_name  
    ORDER BY total_sales DESC  
    LIMIT 5  
)  
SELECT ts.product_id, ts.product_name, ts.total_sales, c.category_name  
FROM top_sales ts  
JOIN categories c ON ts.product_id = c.product_id  
ORDER BY ts.total_sales DESC;  
*/

/* JavaScript
Topic: Async/Await for handling asynchronous operations  

Explanation:  
Async/await is syntactic sugar built on top of Promises that lets you write asynchronous code that looks synchronous.  
Mark a function with the async keyword to enable the use of await inside it.  
Await pauses the function execution until the Promise on its right side settles, then returns the resolved value.  
If the awaited Promise rejects, the error can be caught with a regular try‑catch block.  
This pattern makes complex chaining of asynchronous calls easier to read and maintain.  

Code example:  
async function fetchUserData(userId) {  
    try {  
        // Await the response of the fetch call, which returns a Promise  
        const response = await fetch(`https://api.example.com/users/${userId}`);  
        // Await the parsing of JSON, also a Promise  
        const data = await response.json();  
        console.log('User data:', data);  
        return data;  
    } catch (error) {  
        // Handle network errors or JSON parsing errors here  
        console.error('Failed to fetch user data:', error);  
        throw error; // Re‑throw if the caller needs to handle it further  
    }  
}  

// Call the async function; it returns a Promise, so you can chain .then/.catch or use await in another async context  
fetchUserData(42).then(user => {  
    // Do something with the user object  
}).catch(err => {  
    // Additional error handling if needed  
});
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models  

Explanation:  
Few‑shot prompting supplies a language model with a small number of example input‑output pairs within the same request, guiding it to perform a desired task without fine‑tuning. By carefully selecting representative examples and clearly separating them from the new query, the model can infer the pattern and generate accurate responses. This technique is especially useful for classification, data extraction, or transformation tasks where labeled data is scarce. The prompt should include concise instructions, the examples, and a placeholder for the target input. Proper formatting (e.g., using delimiters like "---") helps the model distinguish between context, examples, and the new query.

Code example (Python, using OpenAI’s ChatCompletion API):

import os
import openai

# Set your API key – ensure it is stored securely, e.g., in an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    # Construct a few‑shot prompt with two labeled examples
    prompt = (
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n"
        "Example 1:\n"
        "Sentence: I love the new design of this app!\n"
        "Sentiment: Positive\n"
        "---\n"
        "Example 2:\n"
        "Sentence: The battery life is disappointing.\n"
        "Sentiment: Negative\n"
        "---\n"
        "Now classify this sentence:\n"
        f"Sentence: {text}\n"
        "Sentiment:"
    )

    # Call the ChatCompletion endpoint with the constructed prompt
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",            # choose a suitable model
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,                # deterministic output for classification
        max_tokens=10
    )
    # Extract the model’s answer and strip any extra whitespace
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
print(classify_sentiment("The update made the app slower than before."))   # Expected: Negative  
*/

