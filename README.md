This is a simple LawFirm Application API.

Features:
1. Add/Edit/Delete/Read Case Type
2. Add Case Details, 
3. Edit Case Details
4. Delete Case Details
5. Update Stage of a case
6. Get all Cases
7. Get all cases by the case type


When Adding the case details, and you have filled the client email, first name,last name,
We check if the client email already exist, if it exist, we get the client id and create the case details, if the client does not exist, we create the client and get the id to create the case details.

8. Get Client Profile
9. Get All Clients
10. Get All Client cases
11. Search for clients by last name (this is a direct search)
12. Update Client Profile Picture

Postman API Documentation Link: https://documenter.getpostman.com/view/6890514/U16nMQQw

Any Questions should be directed to hello@danielozeh.com.ng OR danielozeh.com.ng

Make Sure you have composer installed globally on your machine.

For the purpose of this project, the env file is attached here.
This ReadMe isn't robust enough but it should get your project started.

<p> 
Clone The Project using this command: <b> git clone https://github.com/danielozeh/LawFirmX.git </b>
</p>

<p> Change Directory into the project: <b> cd LawFirmX </b> </p>

<p> Install The dependencies <b> composer install </b> </p>

<p> Create a Database, name it <b> lawfirm </b>  user: root, password should be empty</p>

<p> Migrate the database using the command: <b> php artisan migrate </b> </p>

<p> To run the script, use: <b> php artisan serve </b> (Default Port is at 8000) </p>

If you need email running, Please update the mail details in the env file.

The Scheduler will be running on the background.
Please check If the command is registered using: php artisan list
You can run the scheduler using php artisan profile:pending


Regards, 
Daniel Ozeh