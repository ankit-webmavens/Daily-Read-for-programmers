<?php
// 2026-09-06 06:27:24

/* PHP
PHP PDO (PHP Data Objects) for Secure Database Interaction

Explanation:  
PHP PDO provides a consistent interface for accessing many different databases, allowing developers to write portable code. It supports prepared statements, which help prevent SQL injection by separating query structure from data. PDO also offers built-in error handling and transaction management, making it easier to maintain data integrity. By using named or positional placeholders, you can bind values safely and efficiently. The extension is object‑oriented, so you work with connections and statements as objects rather than procedural functions.

Code Example (MySQL connection, prepared SELECT, and result fetching):
<?php
// Set DSN (Data Source Name) with host, database name, charset
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';

// Database credentials
$username = 'dbuser';
$password = 'dbpass';

try {
    // Create a new PDO instance with error mode set to exceptions
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Prepare an SQL statement with a named placeholder
    $stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE status = :status');

    // Bind the placeholder to a value (e.g., active users)
    $status = 'active';
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch all matching rows
    $users = $stmt->fetchAll();

    // Iterate and display the results
    foreach ($users as $user) {
        echo "ID: {$user['id']} - Name: {$user['name']} - Email: {$user['email']}\n";
    }
} catch (PDOException $e) {
    // Handle any connection or query errors
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection

Explanation:  
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection. It resolves class instances automatically, allowing you to type‑hint dependencies in constructors or controller methods without manually instantiating them. By binding interfaces to concrete implementations, you can easily swap out classes, which promotes loose coupling and easier testing. The container also supports contextual bindings, singleton bindings, and automatic resolution of primitive values via the service provider. Understanding how to leverage the container improves code organization and adheres to the SOLID principles.

Code Example (app/Providers/AppServiceProvider.php):
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;
use App\Services\PaypalPaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    // Register bindings in the container
    public function register()
    {
        // Bind the PaymentGateway interface to a concrete implementation
        // Change StripePaymentGateway to PaypalPaymentGateway to switch providers
        $this->app->bind(PaymentGateway::class, function ($app) {
            // Here you could read config or environment to decide which class to return
            return new StripePaymentGateway(config('services.stripe.secret'));
        });
    }

    public function boot()
    {
        //
    }
}

// Example of a controller using dependency injection (app/Http/Controllers/OrderController.php)
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel automatically resolves the concrete class bound to PaymentGateway
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        // Use the injected payment gateway to process a payment
        $amount = $request->input('amount');
        $this->paymentGateway->charge($amount);

        return response()->json(['status' => 'payment successful']);
    }
}

// Interface definition (app/Contracts/PaymentGateway.php)
<?php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount);
}

// Concrete implementation (app/Services/StripePaymentGateway.php)
<?php
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    protected $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function charge(float $amount)
    {
        // Here you would integrate with Stripe's SDK
        // For demonstration, we'll just simulate a charge
        // echo "Charging \${$amount} with Stripe using API key {$this->apiKey}";
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and can improve readability by separating complex logic from the main query.  
Recursive CTEs allow you to perform hierarchical or graph traversals, such as generating a series of dates or navigating a parent‑child relationship.  
The recursion is controlled by an anchor member (the base case) and a recursive member that references the CTE itself.  
A recursive CTE must include a termination condition to avoid infinite loops, and most MySQL versions limit recursion depth to 1000 by default.  

Code Example:  
-- Define a recursive CTE to generate a calendar of dates for the next 7 days  
WITH RECURSIVE date_series AS (  
    -- Anchor member: start with today  
    SELECT CURDATE() AS dt  
    UNION ALL  
    -- Recursive member: add one day to the previous row until 7 rows are produced  
    SELECT DATE_ADD(dt, INTERVAL 1 DAY) FROM date_series WHERE dt < CURDATE() + INTERVAL 6 DAY  
)  
SELECT dt FROM date_series ORDER BY dt;  
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context. It allows inner functions to remember and use variables defined in an outer function after the outer function has finished executing. Closures are fundamental for creating private data, implementing function factories, and handling asynchronous callbacks. Understanding closures helps avoid common pitfalls with variable hoisting and memory leaks. They are created automatically by the JavaScript engine whenever a function references variables from an outer scope.

Code Example:
// Outer function creates a private variable and returns an inner function
function makeCounter() {
    let count = 0;                       // count is local to makeCounter
    return function() {                  // this inner function forms a closure
        count += 1;                       // it can access and modify count
        console.log('Current count:', count);
    };
}

// Create two independent counters
const counterA = makeCounter();           // each call gets its own closure
const counterB = makeCounter();

counterA(); // Output: Current count: 1
counterA(); // Output: Current count: 2
counterB(); // Output: Current count: 1   (independent from counterA)
*/

/* AI
Topic: Retrieval‑Augmented Generation (RAG) with the OpenAI API  

Explanation:  
Retrieval‑augmented generation combines a large language model with a external knowledge base, allowing the model to ground its answers in up‑to‑date or domain‑specific information.  
First, relevant documents are fetched from a vector store using similarity search; the retrieved texts are then appended to the user prompt.  
This approach improves factual accuracy and reduces hallucinations, especially for niche topics or rapidly changing data.  
The pattern is language‑model agnostic – you can swap OpenAI’s gpt‑4o for any compatible LLM.  
Implementing RAG in a few lines of Python gives you a powerful “search‑then‑answer” system without building a full‑scale retrieval pipeline.  

Code example (Python, using openai and faiss‑cpu):  

import os  
import json  
import numpy as np  
import faiss                        # Vector store for fast similarity search  
import openai                       # OpenAI API client  

# Load your OpenAI API key from environment  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# ----- Step 1: Build a simple vector index from a list of documents -----  
documents = [  
    "The Eiffel Tower is 324 meters tall and was completed in 1889.",  
    "Python's list comprehension provides a concise way to create lists.",  
    "The Great Barrier Reef is the world's largest coral reef system.",  
]  

def embed(text):  
    # Use OpenAI's embedding endpoint (text-embedding-3-large)  
    resp = openai.embeddings.create(input=[text], model="text-embedding-3-large")  
    return np.array(resp.data[0].embedding, dtype="float32")  

embeddings = np.vstack([embed(doc) for doc in documents])  
dimension = embeddings.shape[1]  
index = faiss.IndexFlatL2(dimension)          # L2 distance index  
index.add(embeddings)                         # Add document vectors to the index  

# ----- Step 2: Retrieve the most similar document for a query -----  
def retrieve(query, k=1):  
    q_vec = embed(query)                       # Embed the user query  
    distances, indices = index.search(q_vec.reshape(1, -1), k)  
    return [documents[i] for i in indices[0]]  

# ----- Step 3: Augment the prompt with retrieved context and call the LLM -----  
def ask(query):  
    relevant = retrieve(query)                 # Get top‑1 related doc  
    system_prompt = "You are a helpful assistant. Use the provided context to answer the question."  
    user_prompt = f"Context: {relevant[0]}\n\nQuestion: {query}"  
    response = openai.chat.completions.create(  
        model="gpt-4o-mini",  
        messages=[  
            {"role": "system", "content": system_prompt},  
            {"role": "user", "content": user_prompt}  
        ],  
        temperature=0.2  
    )  
    return response.choices[0].message.content.strip()  

# Example usage  
print(ask("How tall is the Eiffel Tower?"))   # Should answer using the retrieved context.  
*/

