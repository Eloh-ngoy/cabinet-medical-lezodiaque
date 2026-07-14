use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

public function run(): void
{
$role = Role::firstOrCreate([
'name' => 'admin',
'guard_name' => 'web'
]);

$user = User::firstOrCreate(
['username' => 'admin'],
[
'email' => 'admin@lezodiaque.com',
'full_name' => 'Dr. Directeur Général LEZODIAQUE',
'matricule' => 'MED-001',
'password' => Hash::make('admin123'),
'must_change_password' => false,
]
);

$user->assignRole($role);
}