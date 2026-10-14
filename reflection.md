**Why does searching use GET while creating a ticket uses POST?**
Searching uses GET because it retrieves information without changing stored data, and the search parameters can be included in the URL.  Creating a ticket uses POST because it submits new data to the server to be saved.

**Why is server-side validation still necessary if the HTML form performs validation?**
Client-side validation can be bypassed (using an application like Postman or Bruno), so it cannot be trusted on its own. Server-side validation checks that the submitted data is valid before the application processes or saves it.

**What happens to an uploaded file before PHP moves it to the final upload directory?**
PHP temporarily stores the uploaded file in a temporary directory on the server.  After validating the file's size and type, the application uses move_uploaded_file() to move it to the final upload directory.

**Where could you use a similar request-processing pattern in your capstone project?**
I could use a similar request-processing pattern in Bogr (my capstone) when users create posts, upload images, or submit reports about inappropriate content.  The server would validate the submitted information, verify user permissions when necessary, and save the data to the database.  This would help ensure posts and reports are valid and that users can only modify their own content.