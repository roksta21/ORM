1. create a user and set attributes individually
``
$user_1 = new User();
$user_1->name = 'Samson';
$user_1->age = '24';
$user_1->save();
``

2. Create a user and save
``
$user_2 = User::create([
 	'name' => 'Peter',
 	'age' => '22'
]);
``

Fetch data from the saved database
``
$user_found = User::find(2);

$user_search = user::search(['name' => 'peter']);

$user_found->update(['name' => 'susan']);

$user_found->delete();
``
Define a primary key for a model
``
use ORM\ORM;

class Model extends ORM
{
	const PRIMARY = 'primary_key';
}
``

or the default key will default to id