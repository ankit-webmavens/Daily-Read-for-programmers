<?php
// 2026-11-21 08:36:24

/* PHP
Topic: PHP Namespaces

Explanation:  
Namespaces in PHP allow you to group related classes, interfaces, functions, and constants under a single name, preventing naming collisions in large projects.  
They are defined using the `namespace` keyword at the top of a PHP file.  
When using namespaced code, you can either reference the full qualified name or import it with the `use` statement.  
Namespaces can be nested, creating a hierarchy similar to directory structures.  
They improve code organization, especially when integrating third‑party libraries or following PSR‑4 autoloading standards.  

Code example:  
<?php  
// Define a namespace for utility classes  
namespace MyApp\Utils;  

class StringHelper {  
    // Convert a string to title case (first letter of each word uppercase)  
    public static function toTitleCase(string $text): string {  
        return ucwords(strtolower($text));  
    }  
}  

// In another script, import and use the namespaced class  
use MyApp\Utils\StringHelper;  

echo StringHelper::toTitleCase('hello world from php'); // Outputs: Hello World From Php  
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or interacting with external APIs to a background process. By pushing jobs onto a queue, the main request can finish quickly, improving response time for users. Laravel provides a unified API for several queue drivers (database, Redis, SQS, etc.) and automatically retries failed jobs. Jobs are simple PHP classes that implement the handle method where the task logic resides. You can dispatch a job synchronously, to a specific queue, or with a delay, and Laravel’s worker processes will execute them in the background.

Code Example (Job class and dispatching):

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // The user instance that will receive the email

    // Constructor receives data needed for the job
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Build and send the welcome email
        Mail::to($this->user->email)->send(new \App\Mail\WelcomeMail($this->user));
    }
}

// Dispatching the job from a controller or any other place
use App\Jobs\SendWelcomeEmail;

// Assuming $newUser is the freshly created user model
SendWelcomeEmail::dispatch($newUser)          // Pushes the job onto the default queue
                 ->onQueue('emails')         // Optionally specify a named queue
                 ->delay(now()->addMinutes(5)); // Optional delay before execution

// To start processing jobs, run the worker in a terminal:
// php artisan queue:work --queue=emails

?>
*/

/* MySQL
Topic: MySQL Stored Procedures with Input and Output Parameters  

Explanation:  
Stored procedures are pre‑compiled SQL code that can be executed repeatedly with different arguments, improving performance and security. They allow you to encapsulate complex logic, use variables, control‑flow statements, and handle transactions in a single unit. Input parameters let callers supply values, while output parameters return results without using result sets. Using procedures reduces network round‑trips because multiple statements run on the server side. Proper error handling inside a procedure helps maintain data integrity.

Code Example:
DELIMITER $$

CREATE PROCEDURE GetCustomerStats (
    IN  p_country VARCHAR(50),          -- input: filter by country
    OUT p_totalCustomers INT,          -- output: total number of customers
    OUT p_avgOrderValue DECIMAL(10,2)  -- output: average order value
)
BEGIN
    -- Count customers in the specified country
    SELECT COUNT(*) INTO p_totalCustomers
    FROM customers
    WHERE country = p_country;

    -- Calculate average order value for those customers
    SELECT IFNULL(AVG(o.total_amount),0) INTO p_avgOrderValue
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    WHERE c.country = p_country;
END$$
DELIMITER ;

-- Call the procedure and retrieve output values
CALL GetCustomerStats('USA', @total, @avg);
SELECT @total AS total_customers, @avg AS average_order_value;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is created when an inner function retains access to variables from its outer (enclosing) function after that outer function has finished executing. This allows the inner function to remember the lexical environment in which it was defined, enabling data encapsulation and private state. Closures are fundamental for patterns like module design, callbacks, and partial application. They work because JavaScript functions form a scope chain that preserves references to outer variables. Understanding closures helps avoid common pitfalls such as unintended shared state in loops.

Code example with comments:  

function makeCounter() {  
    let count = 0;                     // count is local to makeCounter  
    return function() {                // the inner function forms a closure  
        count += 1;                     // it can modify count each call  
        return count;                   // expose the updated value  
    };                                 // the returned function keeps a reference to count  
}  

const counterA = makeCounter(); // each call to makeCounter gets its own closure  
const counterB = makeCounter();  

console.log(counterA()); // 1  
console.log(counterA()); // 2  
console.log(counterB()); // 1 (independent closure)  
console.log(counterA()); // 3   (counterA’s private state continues)  
*/

/* AI
Topic: Fine‑tuning a Small Transformer for Text Classification with Hugging Face Transformers  

Explanation:  
1. Fine‑tuning adapts a pre‑trained language model to a specific downstream task by training on a labeled dataset.  
2. Using a compact model such as “distilbert‑base‑uncased” keeps training fast and resource‑light while retaining strong performance.  
3. The Hugging Face 🤗 Transformers library provides high‑level Trainer utilities that handle tokenization, batching, and evaluation automatically.  
4. A typical workflow includes loading the dataset, preprocessing text into token IDs, defining a classification head, and launching training.  
5. After fine‑tuning, the model can be exported and served via the Hugging Face Inference API or integrated directly into applications.  

Code example (Python) with comments:  

import torch                                 # Core PyTorch library  
from datasets import load_dataset           # Utility to fetch standard datasets  
from transformers import AutoTokenizer, AutoModelForSequenceClassification, Trainer, TrainingArguments  

# Load a small sentiment dataset (e.g., IMDb)  
raw_dataset = load_dataset("imdb", split="train[:5%]")   # Use a tiny subset for demo  

# Initialize tokenizer for the chosen model  
tokenizer = AutoTokenizer.from_pretrained("distilbert-base-uncased")  

# Tokenization function that converts raw text to model inputs  
def tokenize(batch):  
    return tokenizer(batch["text"], padding="max_length", truncation=True, max_length=128)  

# Apply tokenization to the entire dataset  
tokenized_dataset = raw_dataset.map(tokenize, batched=True)  

# Rename the label column to “labels” as expected by the Trainer API  
tokenized_dataset = tokenized_dataset.rename_column("label", "labels")  
tokenized_dataset.set_format(type="torch", columns=["input_ids", "attention_mask", "labels"])  

# Load pre‑trained model with a classification head (2 classes for sentiment)  
model = AutoModelForSequenceClassification.from_pretrained("distilbert-base-uncased", num_labels=2)  

# Define training hyper‑parameters  
training_args = TrainingArguments(  
    output_dir="./results",            # Where to store checkpoints  
    num_train_epochs=2,                # Few epochs for quick demo  
    per_device_train_batch_size=8,  
    learning_rate=5e-5,  
    logging_steps=10,  
    evaluation_strategy="no",          # Skip evaluation to keep example short  
)  

# Create Trainer object that ties together model, data, and args  
trainer = Trainer(  
    model=model,  
    args=training_args,  
    train_dataset=tokenized_dataset,  
)  

# Start fine‑tuning  
trainer.train()  

# Save the fine‑tuned model for later inference  
trainer.save_model("./fine_tuned_distilbert")   # Directory now contains model + tokenizer  
*/

