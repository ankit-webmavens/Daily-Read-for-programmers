<?php
// 2026-11-02 08:11:33

/* PHP
Topic: PDO Prepared Statements in PHP

Explanation:
PDO (PHP Data Objects) provides a consistent interface for accessing databases.  
Prepared statements separate the SQL query from its data, which prevents SQL injection attacks.  
They also improve performance when the same query is executed multiple times with different parameters.  
Using PDO you can bind values by name or position, and the driver handles proper escaping.  
This approach works with many databases (MySQL, PostgreSQL, SQLite, etc.) using the same code base.

Code example:
<?php
// Create a new PDO instance (replace DSN, username, and password with your own)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exceptions for easier debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Define an INSERT query with named placeholders
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
    // Bind values to the placeholders and execute
    $stmt->execute([
        ':username'  => $row['username'],
        ':email'     => $row['email'],
        ':created_at'=> date('Y-m-d H:i:s')
    ]);
    echo "Inserted user: " . $row['username'] . PHP_EOL;
}

// Fetch rows using a prepared SELECT statement with positional placeholders
$selectSql = 'SELECT id, username, email FROM users WHERE email LIKE ?';
$selectStmt = $pdo->prepare($selectSql);

// Find all users with email domain example.com
$selectStmt->execute(['%@example.com']);
$users = $selectStmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    echo "User ID {$user['id']}: {$user['username']} ({$user['email']})" . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Form Request Validation  

Explanation:  
Form Request Validation in Laravel separates validation logic from controller methods, keeping code clean and reusable. By generating a custom request class, you define authorization rules and validation rules in a single place. The framework automatically injects the validated data into the controller when the request is type‑hinted. Errors are redirected back to the form with the old input and error messages ready for display. This approach also allows you to add custom validation messages and rules without cluttering the controller.  

Code example (StorePostRequest.php):  

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Return true to allow all users, or add custom logic.
        return true;
    }

    // Define the validation rules for the incoming request.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'image'   => 'nullable|image|max:2048',
        ];
    }

    // Optional: customize the validation error messages.
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'body.required'  => 'Please provide the content of the post.',
        ];
    }
}

Controller usage (PostController.php):  

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    // Store a new blog post using the validated request.
    public function store(StorePostRequest $request): RedirectResponse
    {
        // The $request->validated() method returns only the fields that passed validation.
        $data = $request->validated();

        // If an image was uploaded, store it and add the path to the data array.
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('posts', 'public');
        }

        // Create the post record.
        Post::create($data);

        // Redirect back with a success flash message.
        return redirect()->route('posts.index')
                         ->with('status', 'Post created successfully!');
    }
}

Route definition (web.php):  

<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Route that points to the store method, expecting a POST request.
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries in MySQL  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve query readability by allowing you to break complex logic into named subqueries.  
MySQL 8.0 introduced support for both non‑recursive and recursive CTEs, enabling hierarchical data processing.  
Recursive CTEs repeatedly execute the CTE definition until a termination condition is met, which is useful for traversing trees or graphs.  
You define a CTE with the WITH clause, and you can give it column aliases for clarity.  

Code example (with comments):  
WITH RECURSIVE OrgHierarchy AS (  
    -- Anchor member: start with the top‑level manager (manager_id IS NULL)  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL  

    UNION ALL  

    -- Recursive member: join children to their parents and increment the level  
    SELECT e.employee_id, e.manager_id, e.employee_name, oh.level + 1  
    FROM employees e  
    JOIN OrgHierarchy oh ON e.manager_id = oh.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM OrgHierarchy  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is created when an inner function retains access to the variables of its outer (enclosing) function after that outer function has finished executing. This allows the inner function to “remember” the environment in which it was defined, enabling data encapsulation and private state. Closures are fundamental for patterns such as module creation, function factories, and partial application. They work because JavaScript functions form a lexical scope, preserving references to the variables rather than copying their values. Understanding closures helps avoid common pitfalls like unintended shared state in loops.  

Code example with comments:  

function makeCounter() {                     // outer function creates a private variable
    let count = 0;                           // this variable is not directly accessible outside
    return function() {                     // inner function forms a closure over `count`
        count++;                             // modifies the private variable each call
        console.log('Current count:', count);
    };
}

const increment = makeCounter();            // `increment` holds the closure
increment();                                 // prints: Current count: 1
increment();                                 // prints: Current count: 2
increment();                                 // prints: Current count: 3

// Even if we create another counter, its state is independent
const another = makeCounter();
another();                                   // prints: Current count: 1   (separate closure)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets a language model learn a new task from just a handful of examples supplied in the prompt. By carefully structuring the prompt—defining the role, presenting input‑output pairs, and clearly stating the desired format—you can achieve high accuracy without fine‑tuning. The technique works best when examples are concise, diverse, and representative of the target distribution. Adding explicit instructions about output format (e.g., JSON) reduces parsing errors. This approach is especially useful for rapid prototyping, data augmentation, or building lightweight AI assistants.  

Code example (Python, uses the openai package):  

import os, json, openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt for sentiment analysis  
system_prompt = "You are a helpful assistant that classifies the sentiment of short text as Positive, Negative, or Neutral."  

few_shot_examples = """\
User: I love the new design of the app!  
Assistant: Positive  

User: The update caused many crashes, very frustrating.  
Assistant: Negative  

User: The features are okay, nothing special.  
Assistant: Neutral  

User: """  # The new user input will be appended after this line  

user_input = "The customer support was quick and resolved my issue."  
full_prompt = few_shot_examples + user_input  

response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=[  
        {"role": "system", "content": system_prompt},  
        {"role": "user", "content": full_prompt}  
    ],  
    temperature=0.0,  
    max_tokens=10,  
    stop=None  
)  

# Extract and print the classification result  
sentiment = response.choices[0].message.content.strip()  
print("Sentiment:", sentiment)   # Expected output: Positive  
*/

