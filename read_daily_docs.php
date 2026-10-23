<?php
// 2026-10-23 06:25:59

/* PHP
PHP PDO Prepared Statements

Explanation:
Prepared statements separate SQL code from data, preventing SQL injection attacks. 
They are compiled once by the database engine, which can improve performance for repeated queries. 
Placeholders (named or positional) are used in the SQL query and later bound to actual values. 
PDO supports multiple database drivers, making the code portable across MySQL, PostgreSQL, SQLite, etc. 
Error handling with exceptions helps to catch and debug issues efficiently. 

Example (MySQL connection and an INSERT using named placeholders):
<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO instance (replace credentials as needed)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', 'alice', PDO::PARAM_STR);
$stmt->bindValue(':email', 'alice@example.com', PDO::PARAM_STR);
$stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Get the ID of the newly inserted row
$lastId = $pdo->lastInsertId();
echo "New user ID: " . $lastId;
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:  
Form Request classes encapsulate validation logic, keeping controllers clean and reusable.  
They are generated with Artisan and automatically authorize the request before validation runs.  
You define rules as an associative array, and Laravel will redirect back with errors if validation fails.  
Custom messages and attribute names can be added for more user‑friendly feedback.  
The validated data can be retrieved via the validated() method, ensuring only approved fields are processed.

Code example with comments:

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Return true to allow all users; adjust for permission checks.
        return true;
    }

    // Define validation rules for the incoming request.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'category'=> 'required|exists:categories,id',
        ];
    }

    // Optional: custom error messages.
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'body.required'  => 'Please provide the content of the post.',
        ];
    }
}
?>

<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    // Store a new blog post using the Form Request for validation.
    public function store(StorePostRequest $request)
    {
        // The request has already been validated at this point.
        $validatedData = $request->validated();

        // Create the post using only the validated fields.
        $post = Post::create($validatedData);

        // Redirect or return response.
        return redirect()->route('posts.show', $post);
    }
}
?>
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTE) in MySQL

Explanation:
A recursive CTE allows you to query hierarchical or tree‑structured data by defining a query that references itself.  
It consists of two parts: the anchor member that provides the starting rows, and the recursive member that builds on those rows.  
MySQL evaluates the CTE repeatedly, appending results until the recursive member returns no new rows.  
This technique is useful for organizational charts, category trees, bill‑of‑materials, or any data with parent‑child relationships.  
The syntax is straightforward, but you must include a LIMIT or a termination condition to avoid infinite loops.

Code example (with inline comments):
WITH RECURSIVE employee_hierarchy AS (   -- start of the CTE definition
    SELECT employee_id, manager_id, employee_name, 1 AS level   -- anchor member: top‑level employees
    FROM employees
    WHERE manager_id IS NULL                                 -- top of the hierarchy
    UNION ALL
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1   -- recursive member: add subordinates
    FROM employees e
    JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id
)
SELECT employee_id, manager_id, employee_name, level
FROM employee_hierarchy
ORDER BY level, manager_id;   -- final query returns the full hierarchy ordered by level.
*/

/* JavaScript
Topic: Event Loop, Call Stack, and Task Queues  

Explanation:  
The JavaScript runtime uses a single‑threaded call stack to execute code line by line.  
When asynchronous operations (like promises, timers, or I/O) complete, their callbacks are placed in task queues.  
The event loop continuously checks the call stack; if it is empty, it pulls the next callback from the microtask queue (promises) first, then from the macrotask queue (setTimeout, I/O).  
Understanding this order is crucial for predicting when side effects occur and for avoiding race conditions.  
Microtasks run before the next rendering phase, while macrotasks run after, which influences UI updates and performance.  

Code example (with comments):

// A simple promise resolves immediately
const promise = Promise.resolve('Promise result');

// A setTimeout with 0 ms delay (macrotask)
setTimeout(() => {
    console.log('Timeout callback'); // runs after microtasks
}, 0);

// Microtask added by .then()
promise.then(value => {
    console.log(value); // runs before the timeout callback
});

// Synchronous log to show immediate execution
console.log('Synchronous log');

// Expected output order:
// Synchronous log
// Promise result
// Timeout callback   (because the microtask from the promise runs before the macrotask)  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets you give the model a handful of input‑output examples inside the prompt, teaching it the desired pattern without fine‑tuning. By carefully formatting the examples and separating them with clear delimiters, the model can infer the task and apply it to new inputs. This technique works well for classification, transformation, or extraction tasks where a full dataset for training is unavailable. The prompt should include a brief instruction, several labeled examples, and a final “User:” line for the unseen query. Adjusting temperature and max tokens helps balance creativity and deterministic output.

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def few_shot_completion(user_query):  
    # Construct a prompt that contains the task description and three examples  
    prompt = (  
        "You are a helpful assistant that extracts the city name from a travel sentence.\n\n"  
        "Example 1:\n"  
        "User: I will be flying to Paris next week.\n"  
        "Assistant: Paris\n\n"  
        "Example 2:\n"  
        "User: My next vacation is planned for Tokyo.\n"  
        "Assistant: Tokyo\n\n"  
        "Example 3:\n"  
        "User: Can you tell me where the conference is held? It's in Berlin.\n"  
        "Assistant: Berlin\n\n"  
        f"User: {user_query}\n"  
        "Assistant:"  
    )  

    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,          # deterministic output for extraction tasks  
        max_tokens=10,            # we only need a short answer  
        top_p=1.0,                # use full probability mass  
    )  

    # The model’s answer is in the first choice’s message content  
    answer = response.choices[0].message.content.strip()  
    return answer  

# Example usage  
print(few_shot_completion("I just booked a flight to Sydney for the holidays."))   # Expected: Sydney  
*/

