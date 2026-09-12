<?php
// 2026-09-12 06:24:29

/* PHP
Topic: PDO Prepared Statements for Secure Database Queries

Explanation:
PDO (PHP Data Objects) provides a consistent interface for accessing databases and supports prepared statements, which separate SQL code from data. This prevents SQL injection by sending the query structure to the server first, then binding user‑supplied values safely. Prepared statements can be reused with different parameters, improving performance for repeated queries. PDO also offers error handling via exceptions, making debugging easier. Using named or positional placeholders gives flexibility in how parameters are bound.

Code Example (MySQL connection, prepared SELECT, and fetch results):
<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// Create a new PDO instance (replace placeholders with real credentials)
$pdo = new PDO('mysql:host=localhost;dbname=sample_db;charset=utf8mb4', 'db_user', 'db_pass', $options);

// Prepare a SELECT statement with named placeholders
$sql = 'SELECT id, name, email FROM users WHERE status = :status AND created_at > :date';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders (automatic type handling)
$status = 'active';
$date   = '2023-01-01';
$stmt->bindParam(':status', $status);
$stmt->bindParam(':date', $date);

// Execute the prepared statement
$stmt->execute();

// Fetch all matching rows
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo "ID: {$user['id']} - Name: {$user['name']} - Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:
Form Request Validation separates validation logic from controllers, keeping them clean and focused on handling the request. You create a custom request class that contains the validation rules and authorization logic. Laravel automatically injects this class into your controller method, running the validation before the method body executes. If validation fails, a redirect response with error messages is generated automatically. This approach also allows you to reuse the same validation rules across multiple controllers or routes.

Code example with comments:

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request
    public function authorize()
    {
        // Typically you check permissions here; return true to allow all
        return true;
    }

    // Define the validation rules that apply to the request
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'tags'    => 'array',
            'tags.*'  => 'integer|exists:tags,id',
        ];
    }

    // Optional: customize the error messages
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'content.required' => 'Please provide the post content.',
        ];
    }
}

// Controller usage
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    // The StorePostRequest will be validated automatically before this method runs
    public function store(StorePostRequest $request)
    {
        // Since validation passed, we can safely create the post
        $post = Post::create($request->only(['title', 'content']));

        // Attach tags if any were provided
        if ($request->filled('tags')) {
            $post->tags()->attach($request->input('tags'));
        }

        // Return a response (could be a redirect or JSON)
        return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
They are defined using the WITH clause and can be recursive, enabling hierarchical or tree‑like data traversal.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.  

Code example (with comments):

WITH RECURSIVE OrgChart AS (               -- Define a recursive CTE named OrgChart
    SELECT employee_id, manager_id, name, 1 AS level   -- Anchor member: start with top‑level employees
    FROM employees
    WHERE manager_id IS NULL                     -- Top of hierarchy (no manager)

    UNION ALL                                   -- Combine anchor and recursive parts
    SELECT e.employee_id,
           e.manager_id,
           e.name,
           oc.level + 1 AS level                -- Increment level for each generation
    FROM employees e
    JOIN OrgChart oc ON e.manager_id = oc.employee_id   -- Recursive step: find subordinates
)
SELECT employee_id, manager_id, name, level
FROM OrgChart
ORDER BY level, manager_id, employee_id;          -- Result shows the hierarchy with depth levels.  
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
Closures enable data encapsulation, allowing private state that cannot be accessed directly from the outside.  
They are created every time a function is defined, capturing the lexical environment at that moment.  
Common uses include factory functions, module patterns, and preserving state in asynchronous callbacks.  
Understanding closures is essential for writing robust, memory‑efficient JavaScript code.

Code Example (with comments):
function makeCounter(initialValue) {          // outer function creates a private variable
    let count = initialValue;                // this variable is captured by the inner function
    return function() {                     // the returned function forms a closure
        count += 1;                          // it can read and modify 'count' even after makeCounter ends
        console.log(count);                 // each call shows the updated private state
    };
}
const counter = makeCounter(5);               // counter now holds the inner function
counter(); // prints 6
counter(); // prints 7
// Even though makeCounter has finished, 'count' lives on inside the closure.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies a language model with a small set of example input‑output pairs inside the prompt, guiding it to produce the desired style or format for new queries. This technique works well for tasks such as classification, transformation, or extracting structured data without fine‑tuning. By carefully crafting the examples and clearly separating them from the new user request, you can achieve high accuracy with just a single API call. The approach is inexpensive, fast to prototype, and works across many model versions. It also allows you to adjust behavior on the fly by adding or swapping examples.  

Code example (Python, using the openai package):  

import os  
import openai  

# Load your API key from environment variable for security  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that teaches the model how to convert informal sentences to polite formal English  
few_shot_prompt = """You are a helpful assistant that rewrites informal sentences into polite, formal language.  

Example 1:  
User: "Hey, can you send me that report?"  
Assistant: "Could you please send me the report at your earliest convenience?"  

Example 2:  
User: "I need this done ASAP."  
Assistant: "I would appreciate it if this could be completed as soon as possible."  

Now rewrite the following sentence:  

User: "Give me the stats right now."  
Assistant:"""  

response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",   # or any other chat model you have access to  
    messages=[  
        {"role": "system", "content": "You are a concise, polite rewriting assistant."},  
        {"role": "user", "content": few_shot_prompt}  
    ],  
    temperature=0.2,        # low temperature for deterministic output  
    max_tokens=100  
)  

# Extract and print the assistant’s rewrite  
rewrite = response["choices"][0]["message"]["content"].strip()  
print("Formal rewrite:", rewrite)  
*/

