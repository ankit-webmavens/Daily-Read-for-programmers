<?php
// 2026-10-31 07:27:30

/* PHP
Topic: PHP Traits

Explanation:
Traits allow you to reuse sets of methods across multiple classes without using inheritance.  
They are especially useful when different classes need the same functionality but do not share a parent class.  
A trait can contain methods, properties, and even abstract method declarations that the using class must implement.  
When a class uses a trait, the trait's methods become part of the class as if they were defined there.  
Conflicts between traits can be resolved with the "insteadof" and "as" operators, giving fine‑grained control over method precedence.

Code example (with comments):
<?php
// Define a reusable trait with common logging functionality
trait LoggerTrait {
    // Simple method to log a message with a timestamp
    public function log(string $message): void {
        $timestamp = date('Y-m-d H:i:s');
        echo "[{$timestamp}] {$message}\n";
    }

    // Abstract method that consuming classes must implement
    abstract protected function getLogPrefix(): string;
}

// First class that uses the trait
class User {
    use LoggerTrait;

    protected function getLogPrefix(): string {
        return "User";
    }

    public function create(string $username): void {
        // Business logic for creating a user...
        $this->log($this->getLogPrefix() . " created: {$username}");
    }
}

// Second class that also uses the same trait
class Order {
    use LoggerTrait;

    protected function getLogPrefix(): string {
        return "Order";
    }

    public function place(int $orderId): void {
        // Business logic for placing an order...
        $this->log($this->getLogPrefix() . " placed: #{$orderId}");
    }
}

// Demonstration
$user = new User();
$user->create('alice');

$order = new Order();
$order->place(12345);
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:  
Form Request classes encapsulate validation logic, keeping controllers clean and focused on handling the request. They automatically run before the controller method, returning a validated data array if the rules pass. You can define authorization logic to restrict who can make the request. Custom error messages and attribute names can be added for clearer feedback to users. Using Form Requests promotes reusability and makes testing validation rules straightforward.

Code example with comments:

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Change to false to deny all users or add custom logic.
        return true;
    }

    // Define the validation rules applied to the incoming data.
    public function rules()
    {
        return [
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    // (Optional) Provide custom error messages for the rules.
    public function messages()
    {
        return [
            'title.required'       => 'A title is required.',
            'body.required'        => 'Please provide the post content.',
            'category_id.exists'   => 'The selected category does not exist.',
        ];
    }
}

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    // The StorePostRequest is injected, so validation runs automatically.
    public function store(StorePostRequest $request)
    {
        // Retrieve only the validated fields.
        $validatedData = $request->validated();

        // Create the post using mass assignment.
        $post = Post::create($validatedData);

        // Redirect to the newly created post's page.
        return redirect()->route('posts.show', $post);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries in MySQL

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and can improve readability by separating complex subqueries from the main query logic.  
MySQL 8.0 introduced support for both non‑recursive and recursive CTEs, enabling hierarchical data processing such as organizational charts or tree traversals.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE itself until a termination condition is met.  
Because the CTE is materialized only for the duration of the statement, it does not persist in the database schema and has no impact on storage.  

Code example (recursive CTE that lists an employee hierarchy):

-- Define a CTE named emp_hierarchy that starts with the top‑level manager (manager_id IS NULL)
WITH RECURSIVE emp_hierarchy AS (
    SELECT 
        employee_id,
        employee_name,
        manager_id,
        1 AS level               -- base level for top manager
    FROM employees
    WHERE manager_id IS NULL    -- anchor member

    UNION ALL

    SELECT 
        e.employee_id,
        e.employee_name,
        e.manager_id,
        eh.level + 1 AS level    -- increment level for each recursion
    FROM employees e
    JOIN emp_hierarchy eh ON e.manager_id = eh.employee_id   -- recursive member
)
SELECT 
    employee_id,
    employee_name,
    manager_id,
    level
FROM emp_hierarchy
ORDER BY level, manager_id;   -- results show the hierarchy from top to bottom  
*/

/* JavaScript
Topic Name: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to the variables from its lexical scope even after that outer function has finished executing.  
Closures enable data privacy by encapsulating variables that cannot be accessed directly from outside the function.  
They are created every time a function is defined, not just when it is called.  
Closures are widely used for partial application, function factories, and maintaining state in asynchronous code.  
Understanding closures helps avoid common pitfalls related to variable hoisting and the behavior of the `this` keyword.  

Code Example:  
function makeCounter(start) {                     // outer function receives an initial value  
    let count = start;                           // this variable is private to the closure  

    return function increment(step = 1) {        // inner function forms a closure over `count`  
        count += step;                           // modifies the captured variable  
        return count;                            // returns the updated count  
    };                                            // end of inner function  

}                                                 // end of outer function  

const counterA = makeCounter(10); // counterA has its own private `count`  
console.log(counterA());          // 11 (default step of 1)  
console.log(counterA(5));         // 16 (adds 5)  

const counterB = makeCounter(0);  // separate closure with its own `count`  
console.log(counterB());          // 1  
console.log(counterB(2));         // 3   (counterB’s state is independent of counterA)  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a few example input‑output pairs to guide its behavior on new queries. By carefully selecting representative demonstrations, you can shape the model’s style, format, and reasoning pattern without fine‑tuning. This technique works well for tasks like classification, data extraction, or code generation where a few high‑quality examples convey the desired pattern. The prompt is built as a list of message objects, alternating between “system”, “user”, and “assistant” roles, ending with the new user request. Adjusting the temperature and max_tokens helps control creativity and output length.

Code example (Python, using the openai library):

import openai

# Set your API key (replace with your actual key or use env variable)
openai.api_key = "sk-YOUR_API_KEY"

def classify_sentiment(text):
    # Define a few‑shot prompt with two example classifications
    messages = [
        {"role": "system", "content": "You are an assistant that classifies sentiment of short sentences as Positive, Negative, or Neutral."},
        {"role": "user", "content": "I love the new design of this app!"},
        {"role": "assistant", "content": "Positive"},
        {"role": "user", "content": "The update broke my workflow, very frustrating."},
        {"role": "assistant", "content": "Negative"},
        # New query to classify
        {"role": "user", "content": text}
    ]

    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # Choose a suitable model
        messages=messages,
        temperature=0.0,              # Deterministic output for classification
        max_tokens=10,                # Limit token usage
        top_p=1.0,
        n=1
    )
    # Extract the assistant's reply
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
print(classify_sentiment("The customer support was okay, nothing special."))   # Expected: Neutral  
*/

