<?php
// 2026-11-07 07:42:37

/* PHP
Topic: Using PDO for Secure Database Queries (Prepared Statements)

Explanation:
The PHP Data Objects (PDO) extension provides a consistent interface for accessing databases. By using prepared statements, you separate SQL code from data, which protects against SQL injection attacks. PDO supports named or positional placeholders, allowing you to bind values safely. It also offers error handling via exceptions, making debugging easier. Using PDO makes your code portable across different database systems with minimal changes.

Code example (MySQL connection and a SELECT query with prepared statement):
<?php
// Enable exceptions for PDO errors
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare a SELECT statement with a named placeholder
    $sql = 'SELECT id, name, email FROM users WHERE status = :status';
    $stmt = $pdo->prepare($sql);

    // Bind the placeholder to a value (e.g., active users)
    $status = 'active';
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch all matching rows as an associative array
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Iterate over the result set
    foreach ($users as $user) {
        echo 'ID: ' . $user['id'] . "\n";
        echo 'Name: ' . $user['name'] . "\n";
        echo 'Email: ' . $user['email'] . "\n\n";
    }
} catch (PDOException $e) {
    // Handle connection or query errors
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection  

Explanation:  
The service container is the core of Laravel’s inversion of control (IoC) system. It manages class dependencies and performs automatic resolution, allowing you to type‑hint classes in constructors or controller methods. By binding abstractions to concrete implementations, you can swap functionality without changing the consuming code. This promotes loose coupling and makes unit testing easier, as mock implementations can be injected. The container also supports contextual bindings, singleton bindings, and binding callbacks for advanced scenarios.  

Code example (binding an interface and resolving it via a controller):

<?php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount);
}

namespace App\Services;

use App\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    // Charge a customer using Stripe's API
    public function charge(float $amount)
    {
        // Imagine a real Stripe API call here
        return "Charged $$amount with Stripe.";
    }
}

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to a concrete class; use singleton if you want a single instance
        $this->app->bind(PaymentGateway::class, StripeGateway::class);
    }
}

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $gateway;

    // The service container automatically injects the concrete implementation
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function charge(Request $request)
    {
        $amount = $request->input('amount');
        // Use the injected gateway to process the payment
        $result = $this->gateway->charge($amount);
        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: MySQL Stored Procedures

Explanation:  
A stored procedure is a reusable set of SQL statements that can be stored in the database and executed with a single call. It allows you to encapsulate complex logic, reduce client‑server round trips, and improve performance by pre‑compiling the statements. Parameters can be passed in and out, enabling flexible data processing. Stored procedures support control‑flow constructs such as IF, CASE, LOOP, and CURSOR, making them suitable for business logic. Proper use can also enhance security by restricting direct table access and granting execute rights only.

Code Example:

CREATE DATABASE IF NOT EXISTS demo_db;
USE demo_db;

-- Create a sample table
CREATE TABLE IF NOT EXISTS employees (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    salary DECIMAL(10,2)
);

-- Insert some test data
INSERT INTO employees (first_name, last_name, salary) VALUES
('Alice', 'Smith', 60000),
('Bob',   'Johnson', 75000),
('Carol', 'Williams', 82000);

-- Define a stored procedure to give a raise
DELIMITER $$

CREATE PROCEDURE give_raise (
    IN p_emp_id INT,
    IN p_percent DECIMAL(5,2)
)
BEGIN
    -- Calculate the new salary
    UPDATE employees
    SET salary = salary + (salary * p_percent / 100)
    WHERE emp_id = p_emp_id;
END $$

DELIMITER ;

-- Call the procedure to give employee #2 a 10% raise
CALL give_raise(2, 10);

-- Verify the update
SELECT emp_id, first_name, last_name, salary FROM employees;
*/

/* JavaScript
Topic: Closures and Lexical Scoping

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) scope even after that outer function has finished executing. JavaScript creates closures automatically whenever a function is defined inside another function, allowing the inner function to “remember” the environment in which it was created. This mechanism enables patterns such as data encapsulation, function factories, and private state. Because closures capture variables by reference, changes to those variables are reflected across all closures that share them. Understanding closures is essential for writing efficient asynchronous code and avoiding common pitfalls like unintentionally retaining large objects in memory.

Code example with comments:  

function makeCounter(initialValue) {          // outer function creates a private variable
    let count = initialValue;                 // this variable is captured by the inner function
    return function increment(step = 1) {    // inner function forms a closure over `count`
        count += step;                        // modifies the captured variable
        console.log('Current count:', count);
        return count;                         // expose the new value if needed
    };
}

const counterA = makeCounter(0);              // each call gets its own independent closure
const counterB = makeCounter(10);

counterA();   // Current count: 1
counterA(5);  // Current count: 6
counterB();   // Current count: 11
counterB(2);  // Current count: 13

// Even after makeCounter has returned, the inner `increment` functions still have access
// to their respective `count` variables because of the closure.
*/

/* AI
Topic: Few‑Shot Prompt Engineering for GPT‑3.5  

Explanation:  
1. Few‑shot prompting supplies the model with example input‑output pairs before the actual query, guiding it toward the desired format.  
2. The technique reduces the need for extensive fine‑tuning while achieving higher consistency on specialized tasks.  
3. Selecting diverse yet representative examples is crucial; they should illustrate the pattern you expect the model to follow.  
4. The prompt is built as a single string where examples are concatenated, ending with the new user request.  
5. When using the OpenAI API, set temperature low (e.g., 0.2) to favor deterministic, example‑driven responses.  

Code example (Python, using OpenAI's API):  

import os  
import openai  

# Load your API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define few‑shot examples for a simple math word‑problem solver  
examples = [  
    "Problem: John has 3 apples and buys 2 more. How many apples does he have now?\nAnswer: 5",  
    "Problem: A train travels 120 km in 2 hours. What is its average speed in km/h?\nAnswer: 60",  
    "Problem: Sarah reads 15 pages each day. After 4 days, how many pages has she read?\nAnswer: 60"  
]  

# New problem we want the model to answer  
new_problem = "Problem: A bakery sells 8 loaves of bread each morning and 5 each afternoon. How many loaves are sold in a day?\nAnswer:"  

# Construct the full prompt by joining examples and the new problem  
prompt = "\n\n".join(examples) + "\n\n" + new_problem  

# Call the ChatCompletion endpoint with a low temperature for deterministic output  
response = openai.ChatCompletion.create(  
    model="gpt-3.5-turbo",  
    messages=[{"role": "user", "content": prompt}],  
    temperature=0.2,  
    max_tokens=20,  
    n=1,  
    stop=None  
)  

# Extract and print the answer part of the response  
answer = response.choices[0].message.content.strip()  
print("Model answer:", answer)  
*/

