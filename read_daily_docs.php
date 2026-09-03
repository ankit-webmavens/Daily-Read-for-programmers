<?php
// 2026-09-03 06:24:33

/* PHP
Topic: Using Prepared Statements with PDO to Prevent SQL Injection

Explanation:  
1. PDO (PHP Data Objects) provides a uniform interface for accessing many different databases.  
2. Prepared statements separate the SQL code from the data, which eliminates the risk of malicious input altering the query structure.  
3. The database parses and compiles the query once, then you can bind values for each execution, improving performance for repeated queries.  
4. Binding parameters also ensures the correct data type is used, reducing type‑related bugs.  
5. If an error occurs, PDO can throw exceptions, making troubleshooting easier.

Code example with comments:  
<?php  
// Create a new PDO instance with exception mode enabled  
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8', 'username', 'password', [  
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION  
]);  

// Prepare an INSERT statement with named placeholders  
$stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:user, :mail, :pass)');  

// Bind values to the placeholders; the third argument enforces the data type  
$stmt->bindValue(':user', $username, PDO::PARAM_STR);  
$stmt->bindValue(':mail', $email,    PDO::PARAM_STR);  
$stmt->bindValue(':pass', password_hash($plainPassword, PASSWORD_BCRYPT), PDO::PARAM_STR);  

// Execute the statement; if any placeholder is missing, an exception is thrown  
$stmt->execute();  

// Optional: get the ID of the newly inserted record  
$newUserId = $pdo->lastInsertId();  
echo "New user created with ID: $newUserId";  
?>
*/

/* Laravel
Laravel Topic: Service Container & Dependency Injection  

Explanation:  
- The Laravel service container is a powerful tool that manages class dependencies and performs automatic injection.  
- It resolves objects, injecting any required dependencies defined in the constructor without manual instantiation.  
- By binding abstractions (interfaces) to concrete implementations, you can swap implementations easily, which aids testing and decoupling.  
- The container is accessed via the app() helper, the resolve() method, or type‑hinting in controller constructors.  
- Understanding the container allows you to write clean, maintainable code that follows the SOLID principles.  

Code Example (PHP):  

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;          // Interface abstraction
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Laravel will automatically inject the concrete class bound to PaymentGateway
class CheckoutController extends Controller
{
    protected $gateway;

    // Constructor injection: the container resolves PaymentGateway implementation
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway; // Assigned for later use
    }

    public function process(Request $request)
    {
        $orderData = $request->only(['amount', 'currency', 'customer_id']);

        // Use the injected gateway to charge the order
        $result = $this->gateway->charge($orderData['amount'], $orderData['currency'], $orderData['customer_id']);

        if ($result->successful()) {
            return response()->json(['status' => 'Payment successful']);
        }

        return response()->json(['status' => 'Payment failed'], 422);
    }
}

// In a service provider (e.g., App\Providers\AppServiceProvider) you bind the interface:
public function register()
{
    // Bind the PaymentGateway interface to a concrete Stripe implementation
    $this->app->bind(
        \App\Contracts\PaymentGateway::class,
        \App\Services\StripePaymentGateway::class
    );
}

// Example concrete implementation
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    public function charge($amount, $currency, $customerId)
    {
        // Here you would call Stripe's SDK; this is a stub for illustration
        return (object)[ 'successful' => true ];
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by separating complex logic from the main query.  
When the WITH keyword is followed by RECURSIVE, the CTE can call itself to process hierarchical or tree‑structured data.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE name.  
The recursion stops when the recursive member returns no rows, preventing infinite loops.  

Code example with comments:  
-- Create a simple hierarchy table for demonstration  
CREATE TABLE employees (  
    emp_id INT PRIMARY KEY,  
    name VARCHAR(50),  
    manager_id INT NULL   -- references emp_id of the manager  
);  

INSERT INTO employees (emp_id, name, manager_id) VALUES  
(1, 'Alice', NULL),   -- top‑level manager  
(2, 'Bob', 1),  
(3, 'Carol', 1),  
(4, 'Dave', 2),  
(5, 'Eve', 2);  

-- Recursive CTE to list each employee together with the chain of managers up to the top  
WITH RECURSIVE manager_path AS (  
    -- Anchor member: start with each employee and its direct manager  
    SELECT emp_id, name, manager_id, CAST(name AS CHAR(200)) AS path  
    FROM employees  
    WHERE manager_id IS NOT NULL  
    UNION ALL  
    -- Recursive member: join the current level to its manager and extend the path  
    SELECT e.emp_id, e.name, e.manager_id, CONCAT(m.path, ' -> ', e.name)  
    FROM employees e  
    JOIN manager_path m ON e.emp_id = m.manager_id  
)  
SELECT emp_id, name, manager_id, path  
FROM manager_path  
ORDER BY emp_id;  

-- The result shows each employee with the full manager chain, e.g. "Bob -> Alice".  
*/

/* JavaScript
Topic: Async/Await with Proper Error Handling

Explanation:  
Async functions let you write asynchronous code that looks synchronous, improving readability.  
The await keyword pauses the function execution until a Promise settles, without blocking the event loop.  
Wrapping await calls in try/catch blocks allows you to handle rejections locally instead of using .catch everywhere.  
You can also combine multiple await calls sequentially or run them in parallel with Promise.all for better performance.  
Proper error handling ensures resources are cleaned up and unexpected failures don’t crash the entire application.

Code example with comments:  
function fetchData(url) {  
    return fetch(url).then(response => {  
        if (!response.ok) {  
            throw new Error('Network response was not ok');  
        }  
        return response.json();  
    });  
}  

async function loadUserData(userId) {  
    const endpoint = `https://api.example.com/users/${userId}`;  
    try {  
        // Wait for the fetchData promise to resolve or reject  
        const user = await fetchData(endpoint);  
        console.log('User data:', user);  
        return user;  
    } catch (error) {  
        // Handle any errors that occurred during the fetch or parsing  
        console.error('Failed to load user data:', error.message);  
        // Optionally rethrow or return a fallback value  
        throw error;  
    } finally {  
        // This block runs regardless of success or failure; useful for cleanup  
        console.log('loadUserData execution completed');  
    }  
}  

// Example usage:  
loadUserData(42)  
    .then(data => { /* further processing */ })  
    .catch(err => { /* global error handling */ });
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
- Few‑shot prompting provides the model with example interactions that illustrate the desired behavior, improving consistency without fine‑tuning.  
- By embedding a short conversation history in the system or user messages, you can guide the model to follow a specific format or style.  
- This technique works well for tasks such as data extraction, code generation, or role‑playing assistants.  
- The key is to keep the examples concise and representative, balancing context length with token limits.  
- Adjust temperature and max_tokens to control creativity and output length for the target use case.  

Code example (Python, using openai library):  

import os  
import openai  

# Load your API key from environment variable or other secure store  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that shows the desired input‑output pattern  
few_shot_prompt = [  
    {  
        "role": "system",  
        "content": "You are a helpful assistant that extracts the title and author from a book description and returns a JSON object."  
    },  
    {  
        "role": "user",  
        "content": "Description: 'A thrilling adventure set in a dystopian future where robots have taken over.'\nExtract the title and author."  
    },  
    {  
        "role": "assistant",  
        "content": '{ "title": "Robotic Dawn", "author": "J. K. Morgan" }'  
    },  
    {  
        "role": "user",  
        "content": "Description: 'An introspective memoir about growing up in the mountains, blending nature and personal growth.'\nExtract the title and author."  
    }  
]  

# New user query that will be answered using the few‑shot context  
new_query = {  
    "role": "assistant",  
    "content": ""  # placeholder, model will fill in  
}  

# Append the new query to the prompt chain  
messages = few_shot_prompt + [new_query]  

response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",          # choose appropriate model  
    messages=messages,  
    temperature=0.0,              # deterministic output for extraction tasks  
    max_tokens=100,               # limit response length  
)  

# Print the model's JSON result  
print(response.choices[0].message.content.strip())  
*/

