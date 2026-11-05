<?php
// 2026-11-05 08:24:59

/* PHP
Topic: PHP PDO (PHP Data Objects) – Secure Database Access  

Explanation:  
PDO provides a uniform interface for accessing many different databases, allowing you to switch drivers without changing your code.  
It supports prepared statements, which separate SQL logic from data values and protect against SQL injection attacks.  
PDO returns results as objects, associative arrays, or both, giving flexibility in how you process query output.  
Error handling can be configured to throw exceptions, making debugging and logging more straightforward.  
Using PDO also enables transaction control, letting you commit or roll back multiple statements as a single unit.  

Code example (connecting to MySQL, inserting a record, and fetching data with prepared statements):  

<?php
// Data source name: includes driver, host, database name, and charset
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';

// Database credentials
$username = 'dbuser';
$password = 'dbpass';

try {
    // Create a new PDO instance with exception mode enabled
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Prepare an INSERT statement with placeholders
    $stmt = $pdo->prepare('INSERT INTO users (email, name) VALUES (:email, :name)');

    // Bind values to the placeholders and execute
    $stmt->execute([
        ':email' => 'jane.doe@example.com',
        ':name'  => 'Jane Doe'
    ]);

    // Prepare a SELECT statement to retrieve the inserted row
    $select = $pdo->prepare('SELECT id, email, name FROM users WHERE email = :email');
    $select->execute([':email' => 'jane.doe@example.com']);

    // Fetch the result as an associative array
    $user = $select->fetch();

    // Output the retrieved data
    echo 'User ID: ' . $user['id'] . PHP_EOL;
    echo 'Email: ' . $user['email'] . PHP_EOL;
    echo 'Name: ' . $user['name'] . PHP_EOL;

} catch (PDOException $e) {
    // Handle connection or query errors
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Implicit Route Model Binding

Explanation:  
Laravel can automatically inject model instances into your controller methods based on route parameters.  
When a route contains a parameter that matches a model's primary key, Laravel resolves the model behind the scenes.  
If the record is not found, a 404 response is returned without additional code.  
This feature reduces boilerplate and keeps controller actions clean and expressive.  
You just need to type‑hint the model class in the method signature and name the route parameter accordingly.

Code example (web.php):
Route::get('posts/{post}', [App\Http\Controllers\PostController::class, 'show']);

Controller (PostController.php):
<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Laravel will automatically resolve the Post model instance
    // based on the {post} parameter from the route.
    public function show(Post $post)
    {
        // $post is a fully hydrated model or a 404 is already sent.
        return view('posts.show', ['post' => $post]);
    }
}
?>
*/

/* MySQL
MySQL Topic: Stored Procedures with Input Parameters

Explanation:
Stored procedures are precompiled collections of SQL statements that reside on the MySQL server. They allow you to encapsulate complex logic, reduce client‑server round trips, and enforce consistent data processing. By accepting input parameters, a procedure can be reused for different values without rewriting the query each time. This improves performance because the execution plan is cached after the first call. Proper use of delimiters is required when defining a procedure that contains multiple statements.

Code Example (with comments):

DROP PROCEDURE IF EXISTS GetCustomerOrders;   -- Remove any existing version of the procedure
DELIMITER $$                                 -- Change delimiter to allow semicolons inside the procedure
CREATE PROCEDURE GetCustomerOrders(IN p_customer_id INT)   -- Define procedure with one input parameter
BEGIN
    SELECT o.order_id,                     -- Order identifier
           o.order_date,                   -- Date the order was placed
           o.total_amount                  -- Total value of the order
    FROM orders o
    WHERE o.customer_id = p_customer_id   -- Filter orders for the supplied customer ID
    ORDER BY o.order_date DESC;           -- Show newest orders first
END$$                                         -- End of procedure definition
DELIMITER ;                                   -- Restore default delimiter

-- Example call to the procedure:
CALL GetCustomerOrders(42);   -- Retrieves all orders for the customer whose ID is 42.
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This happens because the inner function forms a lexical binding to the environment where it was created. Closures are useful for data encapsulation, creating private state, and implementing function factories. They enable patterns such as memoization, partial application, and module-like structures without the need for classes. Understanding closures helps avoid common pitfalls like unintended shared references in loops.  

Code example (with comments):  
function makeCounter(start) {                     // outer function creates a private variable  
    let count = start;                            // this variable is captured by the inner function  

    return function() {                          // the inner function forms a closure  
        count += 1;                               // can read and modify the private count  
        return count;                             // expose the updated value to the caller  
    };                                            // end of inner function  
}                                                 // end of outer function  

const counterA = makeCounter(0);  // each call gets its own independent closure  
const counterB = makeCounter(10);  

console.log(counterA()); // 1 – count is now 1, private to counterA  
console.log(counterA()); // 2 – increments the same private count  
console.log(counterB()); // 11 – separate closure, starts from 10  
console.log(counterB()); // 12 – continues its own private count  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with GPT‑4  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs inside the same request, steering it toward a desired behavior.  
It is especially effective for tasks that require a consistent format, such as extracting fields from text, translating structured data, or generating boilerplate code.  
Choosing clear, representative examples helps the model generalize the pattern while keeping hallucinations low.  
The prompt typically combines a system instruction, several user‑assistant example messages, and finally the new user query.  
When using the OpenAI chat completion API, these messages are passed as a list of dictionaries, each with a role ("system", "user", "assistant") and content.

Code example (Python, using the openai library):  

import openai  

# Set your API key – replace with your actual key or configure via environment variable  
openai.api_key = "YOUR_API_KEY"  

# Define the system instruction that sets the overall behavior  
system_msg = {  
    "role": "system",  
    "content": "You are a helpful assistant that extracts contact information from informal emails."  
}  

# Example 1 – user provides an email, assistant extracts the data  
example_user_1 = {  
    "role": "user",  
    "content": "Hey team, you can reach me at jane.doe@example.com or call 555‑123‑4567. Thanks!"  
}  
example_assistant_1 = {  
    "role": "assistant",  
    "content": "Email: jane.doe@example.com\nPhone: 555‑123‑4567"  
}  

# Example 2 – another demonstration of the desired output format  
example_user_2 = {  
    "role": "user",  
    "content": "Hi, my new email is bob_smith@workmail.com and my number is (321) 654‑0987."  
}  
example_assistant_2 = {  
    "role": "assistant",  
    "content": "Email: bob_smith@workmail.com\nPhone: (321) 654‑0987"  
}  

# The actual query we want the model to process  
new_query = {  
    "role": "user",  
    "content": "Hello, please contact me at alice@example.org or at 777-888-9999."  
}  

# Assemble the message list in order: system, examples, then the new query  
messages = [system_msg, example_user_1, example_assistant_1, example_user_2, example_assistant_2, new_query]  

# Call the chat completion endpoint with the assembled messages  
response = openai.ChatCompletion.create(  
    model="gpt-4",  
    messages=messages,  
    temperature=0.0   # deterministic output for extraction tasks  
)  

# Print the assistant's extraction result  
print(response["choices"][0]["message"]["content"])  
*/

