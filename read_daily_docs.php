<?php
// 2026-11-23 09:30:40

/* PHP
Topic: PHP Generators (Yield)

Explanation:
- Generators provide a simple way to implement iterators without the overhead of building a full class that implements Iterator.
- By using the `yield` keyword, a function can return values one at a time, maintaining its state between calls.
- This is especially useful for processing large data sets or streams where loading everything into memory would be inefficient.
- Generators produce a Traversable object that can be used in foreach loops just like arrays.
- They also allow you to send data back into the generator with the `send()` method, enabling two‑way communication.

Code example (with comments):
<?php
function readLines(string $filename): Generator
{
    // Open the file for reading
    $handle = fopen($filename, 'r');
    if ($handle === false) {
        throw new RuntimeException("Cannot open file: $filename");
    }

    // Yield each line without loading the whole file into memory
    while (($line = fgets($handle)) !== false) {
        // Trim the newline character and yield the line
        yield trim($line);
    }

    // Close the file when the generator is exhausted
    fclose($handle);
}

// Usage example:
foreach (readLines('large_file.txt') as $lineNumber => $line) {
    // Process each line (e.g., print with line number)
    echo ($lineNumber + 1) . ": " . $line . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Form Request Validation

Explanation:  
Form Request classes encapsulate validation logic, keeping controllers clean and reusable.  
They are generated via Artisan and automatically injected into controller methods.  
You define rules and custom messages inside the request, and Laravel runs validation before the controller executes.  
If validation fails, a JSON response or redirect with errors is returned automatically.  
This approach promotes single‑responsibility principle and makes testing validation rules straightforward.

Code example:

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Typically return true or implement permission checks.
        return true;
    }

    // Define validation rules.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'tags'    => 'array',
            'tags.*'  => 'integer|exists:tags,id',
        ];
    }

    // Optional: custom error messages.
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'content.required' => 'Please provide the post content.',
        ];
    }
}

// In a controller
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        // Validation has already passed at this point.
        $validated = $request->validated();

        // Create the post using mass assignment.
        $post = Post::create($validated);

        // Attach tags if they were provided.
        if (isset($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by separating complex subqueries from the main query.  
Recursive CTEs allow you to query hierarchical or tree‑structured data, such as organization charts or bill‑of‑materials.  
The recursion works by defining an anchor member (the starting rows) and a recursive member that references the CTE itself.  
You can control the depth of recursion with the MAXRECURSION option (in MySQL 8.0+ the limit is set by the server variable).  

Code Example (MySQL 8.0+):
WITH RECURSIVE employee_hierarchy AS
(
    -- Anchor member: select the top‑level manager(s)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join each employee to their manager
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
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to its lexical scope even when the outer function has finished executing. It enables data encapsulation, allowing private variables that cannot be accessed directly from outside. Closures are created each time a function is defined, capturing the surrounding variables at that moment. They are essential for patterns such as factories, module pattern, and handling asynchronous callbacks. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state across invocations.

Code example:
// Outer function creates a private counter variable
function createCounter(initialValue) {
    let count = initialValue;               // This variable is private to the closure

    // Inner function forms a closure over 'count'
    return function(step = 1) {
        count += step;                      // Modifies the captured variable
        return count;                       // Returns the updated count
    };
}

// Create two independent counters
const counterA = createCounter(0);
const counterB = createCounter(10);

console.log(counterA()); // 1  (0 + 1)
console.log(counterA(5)); // 6  (1 + 5)
console.log(counterB()); // 11 (10 + 1)
console.log(counterB(2)); // 13 (11 + 2)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
1. Few‑shot prompting provides the model with several example input‑output pairs to guide its behavior without changing the underlying model.  
2. By placing these examples directly in the system or user messages, you can shape the style, format, and constraints of the generated response.  
3. This technique works well for tasks like data extraction, code generation, or rewriting text where a clear pattern is needed.  
4. The OpenAI Chat Completion API accepts a list of message objects, making it easy to prepend examples before the actual user query.  
5. Adjusting the number and quality of examples lets you trade off between prompt length (token cost) and result fidelity.  

Code example (Python, using the openai library):  

import os  
import openai  

# Set your OpenAI API key – normally from an environment variable for security  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that teaches the model to convert informal sentences to formal ones  
few_shot_examples = [  
    {"role": "system", "content": "You are an assistant that rewrites informal English into formal academic style."},  
    {"role": "user", "content": "Hey, can you tell me why the sky is blue?"},  
    {"role": "assistant", "content": "Could you please explain the physical reasons behind the blue coloration of the sky?"},  
    {"role": "user", "content": "I need help fixing my code, it's broken."},  
    {"role": "assistant", "content": "I require assistance in debugging my code, which is currently malfunctioning."}  
]  

# The actual query the user wants rewritten  
user_query = {"role": "user", "content": "What's the best way to learn Python quickly?"}  

# Combine examples and the new query into the message list  
messages = few_shot_examples + [user_query]  

# Call the Chat Completion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",      # Choose a model that supports chat completions  
    messages=messages,        # Provide the few‑shot context and user request  
    temperature=0.2           # Low temperature for more deterministic output  
)  

# Extract and print the assistant's formal rewrite  
formal_output = response.choices[0].message.content.strip()  
print(formal_output)   # Expected: "What is the most efficient methodology for acquiring proficiency in Python within a short timeframe?"  
*/

