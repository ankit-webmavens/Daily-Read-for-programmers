<?php
// 2026-09-27 07:02:49

/* PHP
Topic: PDO Prepared Statements

Explanation:
Prepared statements separate SQL code from data, protecting against SQL injection attacks. 
They allow the database engine to parse and compile the query once, then execute it multiple times with different parameters. 
Using PDO, you can bind values to placeholders, which are automatically escaped. 
This approach improves performance for repeated queries and enhances code readability. 
Prepared statements also make it easier to handle different data types safely.

Code Example:
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    $pdo = new PDO($dsn, $username, $password);
    // Enable exceptions for error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', $newUsername, PDO::PARAM_STR);
$stmt->bindValue(':email', $newEmail, PDO::PARAM_STR);

// Execute the prepared statement
if ($stmt->execute()) {
    echo "New user inserted with ID: " . $pdo->lastInsertId();
} else {
    echo "Error inserting user.";
}

// Example variables (in a real scenario these would come from user input)
$newUsername = 'johndoe';
$newEmail = 'john@example.com';
*/

/* Laravel
Laravel Route Model Binding  

Route model binding automatically injects model instances into your routes based on the URL parameters, eliminating manual queries and simplifying controller code. When a parameter name matches a route key, Laravel resolves it to the corresponding Eloquent model. You can customize the binding key or use implicit binding for default primary keys. This feature enhances readability and reduces boiler‑plate, especially for CRUD operations. It also gracefully handles missing records by returning a 404 response automatically.  

// In routes/web.php  
use App\Http\Controllers\PostController;  

Route::get('posts/{post}', [PostController::class, 'show']);  

// In App/Models/Post.php (optional: customize binding key)  
class Post extends Model  
{  
    // Use slug instead of id for binding  
    public function getRouteKeyName()  
    {  
        return 'slug';  
    }  
}  

// In App/Http/Controllers/PostController.php  
class PostController extends Controller  
{  
    // Laravel injects the Post model instance based on {post} parameter  
    public function show(Post $post)  
    {  
        // No need to query the database; $post is already loaded  
        return view('posts.show', compact('post'));  
    }  
}  
*/

/* MySQL
Topic: MySQL Stored Procedures – Parameters and Flow Control

Explanation:  
- A stored procedure is a pre‑compiled set of SQL statements that can be invoked repeatedly, improving performance and encapsulating business logic.  
- Parameters allow you to pass input values, receive output values, or both, making the procedure flexible for different data sets.  
- MySQL supports IN (default), OUT, and INOUT parameter modes, each controlling how data flows between the caller and the procedure.  
- Inside the procedure you can use flow‑control constructs such as IF, CASE, WHILE, and LOOP to implement conditional logic and iteration.  
- Proper use of DECLARE, SET, and SELECT INTO statements lets you manipulate local variables and return results without exposing raw tables.  

Code example with comments:

DELIMITER $$

CREATE PROCEDURE GetEmployeeStats (
    IN p_department_id INT,            -- input: department to filter
    OUT p_total_salary DECIMAL(15,2), -- output: sum of salaries
    INOUT p_employee_count INT        -- input/output: initial count, will be updated
)
BEGIN
    -- Local variable to hold intermediate sum
    DECLARE v_sum DECIMAL(15,2) DEFAULT 0;

    -- Calculate total salary for the given department
    SELECT SUM(salary) INTO v_sum
    FROM employees
    WHERE department_id = p_department_id;

    -- Assign the calculated sum to the OUT parameter
    SET p_total_salary = v_sum;

    -- Update the employee count: if caller passed 0, compute it; otherwise add to existing value
    IF p_employee_count = 0 THEN
        SELECT COUNT(*) INTO p_employee_count
        FROM employees
        WHERE department_id = p_department_id;
    ELSE
        SELECT COUNT(*) INTO @cnt
        FROM employees
        WHERE department_id = p_department_id;
        SET p_employee_count = p_employee_count + @cnt;
    END IF;
END$$

DELIMITER ;

-- Example call:
-- CALL GetEmployeeStats(3, @total, @cnt);
-- SELECT @total AS TotalSalary, @cnt AS EmployeeCount;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is created when an inner function retains access to the variables of its outer (enclosing) function after that outer function has finished executing. This allows the inner function to remember and manipulate those variables across multiple calls. Closures are useful for data privacy, creating function factories, and maintaining state without using global variables. They form the basis of many advanced patterns like module patterns and currying. Understanding closures helps you write more predictable and modular code.  

Code example with comments:  

function createCounter() {  
    let count = 0;                     // variable in the outer scope  

    return function increment() {     // inner function forms a closure  
        count++;                       // accesses and updates outer variable  
        console.log('Current count:', count);  
    };  
}  

const counter = createCounter();       // create a closure instance  
counter(); // prints: Current count: 1  
counter(); // prints: Current count: 2  

// Each call to createCounter() would produce an independent closure with its own count variable.  
*/

/* AI
Topic: Prompt Chaining with the OpenAI API for Structured Data Extraction  

Explanation:  
Prompt chaining breaks a complex request into a series of simpler prompts, letting the model focus on one sub‑task at a time. First, you ask the model to identify the relevant sections of a document, then you request a clean JSON representation of the extracted information. This approach improves consistency, reduces hallucinations, and makes post‑processing easier. By re‑using the same model with different system messages, you can build a lightweight pipeline without writing custom parsers. The technique works with any GPT‑3.5‑Turbo or GPT‑4 model accessible via the OpenAI API.

Code example (Python, using openai library):

import os
import openai

# Set your OpenAI API key in the environment or replace with a string
openai.api_key = os.getenv("OPENAI_API_KEY")

def call_model(messages, model="gpt-4o-mini"):
    """Send a list of messages to the OpenAI chat completion endpoint."""
    response = openai.ChatCompletion.create(
        model=model,
        messages=messages,
        temperature=0.0,          # deterministic output for extraction
    )
    return response.choices[0].message.content.strip()

def extract_sections(text):
    """First chain step: ask the model to list sections that contain contact info."""
    system = {"role": "system", "content": "You are an assistant that extracts structural cues from raw text."}
    user = {"role": "user", "content": f"""Identify the paragraph(s) in the following text that contain a person's name, email, and phone number. Return only the exact paragraph(s) without any commentary.

Text:
\"\"\"{text}\"\"\""""}
    return call_model([system, user])

def format_to_json(section):
    """Second chain step: convert the extracted paragraph into a JSON object."""
    system = {"role": "system", "content": "You output only valid JSON, no extra text."}
    user = {"role": "user", "content": f"""Extract the name, email, and phone number from the paragraph below and present them as a JSON object with keys: name, email, phone.

Paragraph:
\"\"\"{section}\"\"\""""}
    return call_model([system, user])

# Example raw document
raw_text = """
Dear Hiring Team,

My name is Alex Rivera. You can reach me at alex.rivera@example.com or call me at (555) 123‑4567.
I am excited to apply for the software engineer position.

Thanks,
Alex
"""

# Run the prompt chain
section = extract_sections(raw_text)
json_result = format_to_json(section)

print("Extracted Section:")
print(section)
print("\nJSON Output:")
print(json_result)
*/

