<?php
// 2026-10-02 07:28:33

/* PHP
Topic: Using PDO Prepared Statements for Secure Database Access

Explanation:
PDO (PHP Data Objects) provides a consistent interface for accessing different databases.  
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
You can bind parameters by name or position, and PDO automatically handles quoting.  
Prepared statements also improve performance when executing the same query multiple times.  
Error handling can be managed with exceptions, making debugging easier.

Code example:
// Create a new PDO instance (replace placeholders with your DB credentials)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'username', 'password');
// Set error mode to exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare an INSERT statement with named placeholders
$stmt = $pdo->prepare('INSERT INTO users (username, email, age) VALUES (:username, :email, :age)');

// Bind values to the placeholders
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':age', $age, PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

// Check if the insert was successful
if ($stmt->rowCount() === 1) {
    echo 'User added successfully.';
} else {
    echo 'Failed to add user.';
}
*/

/* Laravel
Topic: Route Model Binding  

Explanation:  
Laravel's route model binding automatically injects Eloquent model instances into route callbacks or controller methods based on the URL parameters. When a route contains a parameter that matches a model's primary key, Laravel queries the database and supplies the model or aborts with a 404 if not found. There are two styles: implicit binding, which works out‑of‑the‑box when the parameter name and the type‑hinted variable match, and explicit binding, where you define custom resolution logic in the RouteServiceProvider. Implicit binding saves boilerplate by eliminating manual findOrFail calls. Explicit binding is useful for using alternative keys, applying global scopes, or binding non‑Eloquent classes.

Code example:  

// routes/web.php  
use App\Http\Controllers\PostController;  
Route::get('posts/{post}', [PostController::class, 'show']);  

// app/Http/Controllers/PostController.php  
namespace App\Http\Controllers;  
use App\Models\Post;  
class PostController extends Controller {  
    // Laravel injects the Post model instance automatically (implicit binding)  
    public function show(Post $post) {  
        // $post is already a fully loaded model; you can return it or pass to a view  
        return view('posts.show', compact('post'));  
    }  
}  

// app/Providers/RouteServiceProvider.php (for explicit binding)  
use Illuminate\Support\Facades\Route;  
use App\Models\User;  
public function boot() {  
    // Bind the {user} parameter to a User model using the 'username' column instead of id  
    Route::bind('user', function ($value) {  
        return User::where('username', $value)->firstOrFail();  
    });  
    parent::boot();  
}  

// routes/web.php (using explicit binding)  
use App\Http\Controllers\UserController;  
Route::get('profile/{user}', [UserController::class, 'profile']);  

// app/Http/Controllers/UserController.php  
namespace App\Http\Controllers;  
use App\Models\User;  
class UserController extends Controller {  
    public function profile(User $user) {  
        // $user is resolved via the explicit binding defined above  
        return view('users.profile', compact('user'));  
    }  
}  
*/

/* MySQL
Topic: MySQL Transactions and ACID Guarantees

Explanation:  
- A transaction is a logical unit of work that must be either fully completed or fully rolled back, ensuring data consistency.  
- MySQL implements the ACID properties (Atomicity, Consistency, Isolation, Durability) to protect transactional integrity.  
- By default, InnoDB tables support transactions; you can start a transaction with START TRANSACTION and end it with COMMIT or ROLLBACK.  
- Isolation levels (READ UNCOMMITTED, READ COMMITTED, REPEATABLE READ, SERIALIZABLE) control how concurrent transactions see each other's changes.  
- Proper use of transactions prevents problems such as lost updates, dirty reads, and phantom rows in multi‑user environments.  

Code Example (with inline comments):

START TRANSACTION;                     -- begin a new transaction
INSERT INTO accounts (user_id, balance) VALUES (101, 500);  -- add a new account
UPDATE accounts SET balance = balance - 200 WHERE user_id = 101;  -- debit the account
INSERT INTO transactions (account_id, amount, type) VALUES (101, -200, 'debit');  -- log the debit
/* Check a business rule: balance must not go negative */
SELECT balance FROM accounts WHERE user_id = 101;  -- retrieve current balance
-- if the balance is negative, roll back the whole transaction
ROLLBACK;                              -- abort all changes if rule violated
-- otherwise, finalize the changes
COMMIT;                                -- make all changes permanent

Note: Replace the ROLLBACK with COMMIT after confirming the balance is non‑negative. The isolation level can be set per session with SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ; before START TRANSACTION.
*/

/* JavaScript
Topic: Debouncing a Function to Optimize Event Handling  

Explanation:  
Debouncing limits how often a function can be executed by postponing its call until a specified wait time has elapsed since the last invocation. It is especially useful for performance‑critical events such as window resizing, scrolling, or keystroke handling where the native event may fire many times per second. The debounce wrapper returns a new function that tracks a timer; each call resets the timer, ensuring the original function runs only after the activity has paused. This technique reduces unnecessary calculations, DOM updates, or network requests, leading to smoother UI interactions. Implementing debounce manually helps developers understand closures and timer management in JavaScript.  

Code Example:  
function debounce(func, wait) {  
    let timeoutId = null;                     // holds the timer reference  
    return function(...args) {                // returned debounced function  
        const context = this;                  // preserve 'this' for later use  
        clearTimeout(timeoutId);               // cancel any pending execution  
        timeoutId = setTimeout(() => {         // schedule new execution after wait  
            func.apply(context, args);        // invoke original function with correct context and arguments  
        }, wait);                              // wait period in milliseconds  
    };  
}  

// Usage example: log the window width after resizing stops for 300 ms  
const handleResize = debounce(() => {  
    console.log('Window width:', window.innerWidth);  
}, 300);  

window.addEventListener('resize', handleResize);   // attach debounced handler to resize event
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of example input‑output pairs, guiding it to produce the desired format for new queries. By embedding these demonstrations directly in the system or user messages, you can control style, tone, and structure without fine‑tuning. This technique works well for tasks like data extraction, code generation, or custom Q&A. It is lightweight, requires only API calls, and adapts quickly to changing requirements. Properly chosen examples dramatically improve consistency and reduce hallucinations.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your API key from an environment variable or other secure source  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt: a system message that sets the role, followed by example user‑assistant exchanges  
messages = [  
    {"role": "system", "content": "You are a helpful assistant that extracts contact information from short email snippets and returns JSON with 'name', 'email', and 'phone' fields."},  
    # Example 1  
    {"role": "user", "content": "Hi, this is John Doe. You can reach me at john.doe@example.com or call 555‑123‑4567."},  
    {"role": "assistant", "content": '{"name": "John Doe", "email": "john.doe@example.com", "phone": "555-123-4567"}'},  
    # Example 2  
    {"role": "user", "content": "Hey there, Sara Smith here. My email: s.smith@company.org. Phone: +1 (800) 555‑0199."},  
    {"role": "assistant", "content": '{"name": "Sara Smith", "email": "s.smith@company.org", "phone": "+1 (800) 555-0199"}'},  
    # New query to process  
    {"role": "user", "content": "Hello, I’m Michael Lee. Contact: michael.lee@service.net, 212-555-0000."}  
]  

# Call the Chat Completion endpoint with temperature=0 for deterministic output  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0,  
    max_tokens=150  
)  

# Extract and print the assistant’s response (the JSON result)  
result = response.choices[0].message.content  
print("Extracted contact info:", result)  
*/

