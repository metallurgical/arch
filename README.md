# Arch Repositories
Laravel Package for Central repositories passing and receiving data from database. This package react as a bridge between Controller, Model and database. All the reusable code should located in one place and called when needed. 

# Installation
1) Package can be installed using composer, just require it :

    composer require arch/repositories
    
2) After install, add `ArchServiceProvider` to provider array in `config/app.php` as follow :

    'providers' => [
      ............
      Arch\Repositories\ServiceProvider\ArchServiceProvider::class
    ];
    
# Usage

Before everything can be used, we must create `Canal` and `Repositories` for the application and make use of trait class for the `Model`.

## 1) Generate Repositories

Repositories is a normal php class having all the functionalities to play with the `Laravel Query Builder` for database querying. You can find out all the available method at `Available method` section. Use below command for generating `repositories` :

    php artisan shareable:repositories <RepositoriesName> --model=<modelName>
    
    eg : 
    
    php artisan shareable:repositories UserRepositories --model=User
    
 **RepositoriesName** - Repositories name
 
 **modelName** - Model name. This model were tied with repositories. 
 
 This command will create `Repositories` file inside `App\Repositories` directory on the fly. One repositories should have one model that tied with it. For ease of use, create repositories with descriptive name. `Eg : UserRepositories`, then we know that this repositories belongs to `User` model.
 
## 2) Generate Canal

Canal is a normal php class having all the reusable method that were created to call inside the `controller`. We didn't call the model directly inside `controller` instead we use this `Canal` class for that purpose to manage data passing/retreiving. This class should have methods that call `Repositories`'s method.

    php artisan shareable:canal <CanalName>
    
    eg : 
    
    php artisan shareable:canal UserModuleCanal
    
 **CanalName** - Canal name.
 
 This command will create `Canal` file inside `App\Canal` directory on the fly. This class should contains all the reusable code. As example we have `UserModule` for simple/complex crud operation, user permission and anything else. Then, all the methods can be placed inside `UserModuleCanal`, and `UserModuleCanal` can call any of `Repositories`'s method inside of it.
    
