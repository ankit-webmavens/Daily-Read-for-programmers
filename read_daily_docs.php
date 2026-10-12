<?php
// 2026-10-12 07:56:14

/* PHP
PHP PDO Prepared Statements

Explanation  
Prepared statements in PDO separate the SQL query from its data, which protects against SQL injection attacks. The query is parsed and compiled by the database server once, and then executed multiple times with different parameter values. This approach also improves performance when the same statement runs repeatedly. PDO supports named and positional placeholders, giving flexibility in how parameters are bound. Using prepared statements is considered a best practice for any database interaction in PHP.

Code Example
<?php
// Create a new PDO instance (replace DSN, username, and password with your own values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Return rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Define the SQL with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data to insert
$data = [
    ['username' => 'alice', 'email' => 'alice@example.com'],
    ['username' => 'bob',   'email' => 'bob@example.com'],
    ['username' => 'carol', 'email' => 'carol@example.com']
];

// Execute the prepared statement for each data row
foreach ($data as $row) {
    $stmt->execute([
        ':username'   => $row['username'],
        ':email'      => $row['email'],
        ':created_at' => date('Y-m-d H:i:s') // current timestamp
    ]);
    echo "Inserted user: " . $row['username'] . PHP_EOL;
}
?>
*/

/* Laravel
Laravel Service Container and Dependency Injection  

The service container is the core of Laravel’s inversion of control (IoC) system.  
It manages class dependencies and performs automatic resolution of objects.  
By binding interfaces to concrete implementations you can swap out services without changing the consuming code.  
Dependency injection allows you to type‑hint classes in constructors or controller methods, and the container will provide the required instances.  
Using the container promotes loose coupling, easier testing, and cleaner architecture.  

Example – binding an interface and injecting it into a controller:  

<?php  

namespace App\Providers;  

use Illuminate\Support\ServiceProvider;  
use App\Contracts\PaymentGateway;  
use App\Services\StripeGateway;  

class AppServiceProvider extends ServiceProvider  
{  
    public function register()  
    {  
        // Bind the PaymentGateway interface to the StripeGateway implementation  
        $this->app->bind(PaymentGateway::class, StripeGateway::class);  
    }  
}  

---  

<?php  

namespace App\Contracts;  

interface PaymentGateway  
{  
    public function charge(float $amount, string $currency);  
}  

---  

<?php  

namespace App\Services;  

use App\Contracts\PaymentGateway;  

class StripeGateway implements PaymentGateway  
{  
    public function charge(float $amount, string $currency)  
    {  
        // Here you would call Stripe's SDK to process the payment  
        // This is a simplified placeholder for demonstration purposes  
        return "Charged {$amount} {$currency} via Stripe.";  
    }  
}  

---  

<?php  

namespace App\Http\Controllers;  

use App\Contracts\PaymentGateway;  
use Illuminate\Http\Request;  

class OrderController extends Controller  
{  
    protected $paymentGateway;  

    // Laravel automatically injects the concrete class bound to PaymentGateway  
    public function __construct(PaymentGateway $paymentGateway)  
    {  
        $this->paymentGateway = $paymentGateway;  
    }  

    public function store(Request $request)  
    {  
        $amount = $request->input('amount');  
        $currency = $request->input('currency', 'USD');  

        // Use the injected payment service to process the charge  
        $result = $this->paymentGateway->charge($amount, $currency);  

        return response()->json(['message' => $result]);  
    }  
}  

---  

// In a test you can swap the implementation by re‑binding the interface  
// $this->app->instance(PaymentGateway::class, new FakePaymentGateway());  
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs improve readability by allowing you to break complex queries into logical building blocks. They are defined using the WITH clause and can be recursive, enabling you to traverse hierarchical data such as organization charts or category trees. Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself. MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.

Code Example (with comments):
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: select the top‑level manager(s)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL
    
    UNION ALL
    
    -- Recursive member: select employees reporting to the previous level
    SELECT 
        e.employee_id,
        e.manager_id,
        e.employee_name,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN employee_hierarchy eh
        ON e.manager_id = eh.employee_id
)
SELECT 
    employee_id,
    manager_id,
    employee_name,
    level
FROM employee_hierarchy
ORDER BY level, employee_id;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context. This means the inner function can reference variables declared in the outer (enclosing) function even after the outer function has finished executing. Closures are created every time a function is defined, allowing for data privacy, partial application, and the creation of function factories. They are essential for implementing patterns such as module encapsulation and callbacks that need persistent state. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable variables across invocations.

Code Example:  
function makeCounter(initialValue) {            // outer function creates a private variable  
    let count = initialValue;                  // this variable is captured by the inner function  

    return function increment(step = 1) {      // the inner function forms a closure over 'count'  
        count += step;                         // it can read and modify 'count' each call  
        console.log(`Current count: ${count}`);  
        return count;                          // returning the updated value (optional)  
    };                                          // end of inner function  

}                                               // end of outer function  

// Using the closure  
const counterA = makeCounter(0);   // each call to makeCounter returns a new independent closure  
counterA();        // Current count: 1  
counterA(5);       // Current count: 6  

const counterB = makeCounter(10);  // separate closure with its own private 'count'  
counterB();        // Current count: 11  
counterB(2);       // Current count: 13  

// The 'count' variable inside each closure is not accessible from the global scope, providing encapsulation.
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models  

Explanation:  
Few‑shot prompting provides the model with a small number of example input‑output pairs directly in the prompt, guiding it toward the desired behavior without any model weight changes. This technique is especially useful when you need consistent formatting, domain‑specific terminology, or a custom response style. By carefully selecting representative examples, the model can generalize to new queries that follow the same pattern. The approach works with any API that accepts a text prompt, such as OpenAI’s ChatCompletion or Azure OpenAI. It is quick to iterate on, requires no additional training data, and can be combined with system messages for even stronger control.  

Code example (Python, using OpenAI’s ChatCompletion API):  

import os
import openai

# Set your API key – replace with your own or use environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt with two example Q&A pairs
few_shot_prompt = """You are a helpful assistant that formats travel itineraries in bullet points.

User: I want a 3‑day trip to Kyoto, focusing on historic temples.
Assistant:
- Day 1: Kinkaku‑ji (Golden Pavilion), Ryoan‑ji Zen garden, Nishiki Market
- Day 2: Fushimi Inari Taisha, Tofuku‑ji, Gion district evening stroll
- Day 3: Kiyomizu‑dera, Higashiyama district, tea ceremony experience

User: Plan a weekend in Barcelona with a mix of architecture and food.
Assistant:
- Day 1: Sagrada Família, Park Güell, tapas crawl in El Born
- Day 2: Casa Batlló, La Rambla, paella dinner by the beach

User: {user_query}
Assistant:"""

def get_itinerary(user_query: str) -> str:
    # Insert the actual user query into the prompt template
    prompt = few_shot_prompt.format(user_query=user_query)

    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # choose a suitable model
        messages=[{"role": "user", "content": prompt}],
        temperature=0.2,              # low temperature for deterministic output
        max_tokens=300,
    )
    # Extract the assistant's reply text
    return response.choices[0].message.content.strip()

# Example usage
if __name__ == "__main__":
    query = "Create a 5‑day itinerary for a nature‑focused trip to Patagonia, including hiking and wildlife spotting."
    itinerary = get_itinerary(query)
    print(itinerary)
*/

