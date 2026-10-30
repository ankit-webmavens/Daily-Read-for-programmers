<?php
// 2026-10-30 07:41:42

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:  
Prepared statements separate SQL code from data values, protecting against SQL injection and improving performance for repeated queries. With PDO you first prepare a statement containing placeholders, then bind actual values before execution. This approach lets the database engine compile the query once and reuse the execution plan for each set of parameters. PDO supports both named (e.g., :name) and positional (?) placeholders, making the syntax flexible. Using prepared statements also simplifies handling of different data types, as PDO automatically quotes and formats bound values.

Code example (with comments):
<?php
// Create a new PDO connection (replace DSN, username, password with real values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);

// Define the SQL with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Data to insert (could come from user input)
$userData = [
    'username' => 'jane_doe',
    'email'    => 'jane@example.com',
];

// Bind the values and execute the statement
$stmt->execute($userData);

// If you need to insert multiple rows, you can reuse the same prepared statement
$moreUsers = [
    ['username' => 'john_smith', 'email' => 'john@example.com'],
    ['username' => 'alice_wong', 'email' => 'alice@example.org'],
];

foreach ($moreUsers as $data) {
    $stmt->execute($data); // Reuse the prepared statement with new values
}

// Retrieve the ID of the last inserted row
$lastId = $pdo->lastInsertId();
echo "Last inserted user ID: $lastId\n";
?>
*/

/* Laravel
Laravel Service Container and Dependency Injection  

The service container is the heart of Laravel’s inversion of control (IoC) system.  
It resolves class dependencies automatically, allowing you to type‑hint objects in constructors or controller methods.  
Binding abstractions to concrete implementations lets you swap implementations without changing consuming code.  
Singleton bindings ensure a single shared instance across the request lifecycle.  
The container also supports contextual binding, useful when the same interface needs different implementations in different parts of the app.  

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;
use App\Services\PaypalPaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    // Register container bindings
    public function register()
    {
        // Bind the interface to a concrete class (default implementation)
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);

        // Bind a singleton – the same instance will be used each time it is resolved
        $this->app->singleton('cache.store', function ($app) {
            return new \Illuminate\Cache\RedisStore($app['redis']);
        });

        // Contextual binding: use Paypal when injecting into OrderController
        $this->app->when(\App\Http\Controllers\OrderController::class)
                  ->needs(PaymentGateway::class)
                  ->give(PaypalPaymentGateway::class);
    }
}

// Example of a controller using automatic dependency injection
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;

class CheckoutController extends Controller
{
    protected $paymentGateway;

    // The container injects the appropriate implementation automatically
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway; // StripePaymentGateway by default
    }

    public function process()
    {
        // Use the injected service
        $this->paymentGateway->charge(1000, 'USD');
        return response('Payment processed');
    }
}
?>
*/

/* MySQL
Topic: MySQL Transaction Isolation Levels

Explanation:  
MySQL supports several transaction isolation levels that control how concurrent transactions see data changes made by other sessions. The levels range from READ UNCOMMITTED (allows dirty reads) to SERIALIZABLE (prevents phantom reads by locking the entire range). Choosing the appropriate level balances consistency requirements against performance and concurrency. The default isolation level in InnoDB is REPEATABLE READ, which prevents non‑repeatable reads but allows phantom rows unless explicit locking is used. You can set the isolation level per session or globally, and it affects the behavior of SELECT, INSERT, UPDATE, and DELETE statements within a transaction.

Code example (with comments):
SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;  -- set isolation level for this session
START TRANSACTION;                                      -- begin a new transaction
SELECT * FROM orders WHERE order_date >= '2024-01-01' LOCK IN SHARE MODE; 
-- lock the selected rows in shared mode so other transactions can read but not modify them
UPDATE orders SET status = 'processed' WHERE order_id = 1024; 
-- this update will wait if another transaction holds an exclusive lock on the same row
COMMIT;                                                 -- finalize changes and release locks
-- After commit, other sessions will see the updated status according to their own isolation level.
*/

/* JavaScript
Topic: Closures and Lexical Scoping in JavaScript  

Explanation:  
A closure is a function that retains access to its surrounding lexical environment even after that outer function has finished executing. This means the inner function can read and modify variables defined in the outer scope. Closures enable data privacy, function factories, and partial application patterns. They are created automatically whenever a function references a variable from an outer scope. Understanding closures is essential for managing asynchronous code, event handlers, and module design.  

Code example:  

function makeCounter(initialValue) {               // outer function creates a private variable
  let count = initialValue;                        // this variable is captured by the inner function
  return function step(increment) {               // the inner function forms a closure
    count += increment;                           // it can read and modify 'count'
    return count;                                 // returns the updated value
  };
}

const counter = makeCounter(10); // counter is a closure with its own 'count' variable
console.log(counter(5));  // Output: 15  // adds 5 to the private count
console.log(counter(2));  // Output: 17  // adds 2, showing that 'count' persisted
// A second independent counter
const another = makeCounter(0);
console.log(another(3)); // Output: 3  // starts from its own initial value, unaffected by the first counter.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs before the actual query, guiding it toward the desired response style. By placing these demonstrations in the system or user messages, you can shape the model’s behavior without fine‑tuning. This technique works well for tasks like data extraction, code generation, or format conversion. The examples act as implicit instructions, helping the model infer the pattern you expect. Adjusting the number and clarity of examples lets you trade off between precision and token cost.

Code example (Python, requires the openai package and an API key set in the environment):  
import os, json, openai  

# Load API key from environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that extracts product names and prices from a text block  
messages = [  
    {"role": "system", "content": "You are an assistant that extracts product information and returns a JSON list of objects with 'name' and 'price' fields."},  
    {"role": "user", "content": "Example 1:\nInput: \"Buy the UltraPhone for $299 and the MegaTablet for $499.\"\nOutput: [{\"name\": \"UltraPhone\", \"price\": 299}, {\"name\": \"MegaTablet\", \"price\": 499}]"},  
    {"role": "user", "content": "Example 2:\nInput: \"Special offer: EcoLamp at $45, SolarCharger for $120.\"\nOutput: [{\"name\": \"EcoLamp\", \"price\": 45}, {\"name\": \"SolarCharger\", \"price\": 120}]"},  
    {"role": "user", "content": "Now extract from the following text:\n\"Limited time deal: SmartWatch $199, NoiseCancelling Headphones $250.\"\nOutput:"}  
]  

response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0.0,   # deterministic output for extraction tasks  
    max_tokens=200  
)  

# The model returns a string that should be valid JSON; parse it safely  
extracted = json.loads(response.choices[0].message.content.strip())  
print(json.dumps(extracted, indent=2))   # pretty‑print the result  
*/

