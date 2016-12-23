# Arch Repositories
Laravel Package for Central repositories passing and receiving data from database. This package react as a bridge between Controller, Model and database. All the reusable code should located in one place and called when needed. 

# Package Installation
1) Package can be installed using composer, just require it :

    composer require arch/repositories
    
2) After install, add `ArchServiceProvider` to provider array in `config/app.php` as follow :

```Php
'providers' => [
  ............
  Arch\Repositories\ServiceProvider\ArchServiceProvider::class
];
```
    
# Canal, Repositories and Model Installation

Before everything can be used, we must create `Canal` and `Repositories` for the application and make use of trait class for the `Model`.

## 1) Generate Repositories

`Repositories` is a normal php class having all the functionalities to play with the `Laravel Query Builder` for database querying. You can find out all the available method at `Available method` section. Use below command for generating `repositories` :

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
 
 This command will create `Canal` file inside `App\Canal` directory on the fly. This class should contains all the reusable code. As example we have `UserModule` for simple/complex crud operation, user permission and anything else. Then, all the methods can be placed inside `UserModuleCanal`, and `UserModuleCanal` can call any of `Repositories`'s method inside of it. Later on, if we have two controller for the `Web` application and `Api` stuff with the same behaviour, we can just call the `UserModuleCanal` for both `controller` as they all share the same functionality.
 
## 3) Make use of traits inside Model

Last, include trait `Arch\Repositories\Tools\Instantiate` inside our `Model` that tied with the `Repositories` as follow :

```Php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Tools\Instantiate;

class User extends Model {
   use Instantiate;    
}
```

Follow these 3 steps for another module. Done!

# Usage Example

1) First, include `Canal` class inside `Controller`, eg : `UserController` :

```Php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Canal\UserModuleCanal as UserCanal; // Canal class

class UserController extends Controller
{
    private $userModule = null;
    
    public function __construct () {
        // instantiate and assign to class member
        $this->userModule = new UserCanal;
    }
    // retrieving all users
    public function index () {
    	
    	dd( $this->userModule->getAllUser() );

    }
}
```

2) Create reusable code inside `Canal` class, eg : `UserModuleCanal` :

```Php
<?php

namespace App\Canal;

use App\Repositories\UserRepositories as UserRepo; // include Repositories
use DB; // for creating custom query


class UserCanal {	
	
	// write method to call Repositories class
	public function getAllUser () {

		$user = new UserRepo; // Instantiate UserRepo object
		return $user->all();  // call UserRepo method and pass data back to Controller
        
	}

}
```

3) Simple example of Repositories, eg : `UserRepositories` :

```Php
<?php

namespace App\Repositories;

use Arch\Repositories\Shareables\BaseShareables as BaseAbstract;
use App\User; // Model tied with this repositories
use DB;       // custom database querying

class UserRepositories extends BaseAbstract {	
	
	public function __construct() {
		// Assign to parent Model for data fetching
		$this->model = User::getInstance();
	}
	
    // At here we didn't create all() method, basically we didnt
    // create any method inside this repositories 
    // as all the laravel Query method already
    // available inside this repositories
    // by include this BaseShareables class
    // unless you need complex query that BaseShareables didnt provided for you
    
    // You can do any custom query from database in here
    // by creating custom method, later on, we can call
    // this method inside `Canal` class
}
```

4) Map the route to that controller and you're good to go. 

# Supports

- Well, just open an issues

# Authors

- [Norlihazmey](https://github.com/metallurgical) <norlihazmey89@gmail.com>
- [Afiq Abdullah](https://github.com/AfiqAbdullah) <mohamadafiqabdullah@gmail.com>


    
    
