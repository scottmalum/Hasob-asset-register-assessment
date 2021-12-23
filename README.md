## HASOB Laravel 8 API Service Architecture Template

API Service Application development should follow best practice using highly modular code completely decoupled to enable easy collaborative development. This template application is a guide to developing API services following these ideals.

This application is based on Laravel 8 and is accessible, powerful, and provides tools required for large, robust applications.

## Sections

This template application has several sections on how service applications should be developed.

-   **Code Structure**
-   **Modular Development**
-   **Test Driven Development**

### Code Structure

-   **Routes**

    -   Strict adherance to REST principles
    -   Activity functions

-   **Model**

    -   Created for each table in the design
    -   Models the database record, contains record specific functions.
    -   Contains modifiers of the database records

-   **Repository**

    -   See BaseRepository.php, all Repository objects inherit from here.
    -   Search for records from DB
    -   Get record from DB
    -   Store record into DB
    -   Remove record from DB
    -   Update record in DB
    -   Separates the model from the Database being used.
    -   Implements other useful functions such as record pagination, etc.

-   **Dependency Injection**

    -   Used to automatically inject objects into the Controller Method
    -   Required for Multitenant identification

-   **Multitenant Identification**

    -   Allows application to support by multiple organizations
    -   Using dependency injection, the application can be used in different organization contexts.

-   **Controller**

    -   One controller for each model
    -   TentantManager is injected into the Controller constructor method.
    -   Repository for the Model is injected into the Controller constructor method.
    -   Preference for thin controllers. Only Index, Store, Get, and Delete methods.
    -   Returns JSON to the client.

-   **Request Objects**

    -   One required for each API request.
    -   Extends Illuminate\Foundation\Http\FormRequest
    -   Requests sent from the client calling the API are wrapped in a Request object containing all the parameters for the request.
    -   This object is focused on handling various aspects of authorization and validation of requests being made to API
    -   Contains 3 important methods amongst others, authorize(), rules() and messages().
    -   The authorize() method should ensure the client is authorized to perform the request
    -   The rules() method returns the list of validation rules that should be used to valid the request
    -   The messages() method returns the messages to be used for the validation results
    -   By using this method, authorization and validation are removed from the controller code, and isolated specifically in request object only.
    -   See [Laravel8 FormRequest](https://laravel.com/docs/8.x/validation#form-request-validation)
    -   See [FormRequest Example](https://dev.to/secmohammed/laravel-form-request-tips-tricks-2p12)

-   **Events & Listeners**

    -   An Event is created for each Model database action (i.e. created, updated, and deleted)
    -   Event are fired when an action happens (i.e. created, updated, and deleted)
    -   Listener should be created for each action on each event.

-   **Factory**

    -   The factory should be created to generate fake data to be used for testing.
    -   Fake data are created using the Faker Helper of laravel.

-   **Seeder**
    -   Seeders are required to pre-seed the database with initial data needed for the functioning of the service.

### Modular Development

TBD

### Test Driven Development

Test Driven Development (TDD) allows us to test the service while developing the service. This is achieved using Unit Testing.

-   **APITest (one per API function)**

    -   This class extends TestCase and contains 1 test method for the POST, GET, DELETE, and PUT functions of each Model.
    -   Using the assert\* methods of the TestCase class, you can assert that the API is editing the database as required.

-   **RepositoryTest (one per repository object)**
    -   This class extends TestCase and contains 1 test method for Create, Read, Update, and Delete functions of each Model.
    -   Using the assert\* methods of the TestCase class, you can assert that the Repository is editing the database as required.
