<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Contracts\Services\UserServiceInterface;
use App\DTOs\RegisterDTO;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Create New User Action
 *
 * Fortify action for user registration using UserService.
 */
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Constructor with dependency injection.
     */
    public function __construct(
        protected UserServiceInterface $userService
    ) {
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Validate input (required by Fortify contract)
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        // Create DTO from validated input
        $registerData = new RegisterDTO(
            name: $input['name'],
            email: $input['email'],
            password: $input['password'],
            roleId: null // Will use default role (Karyawan)
        );

        // Create user via service (handles hashing, role assignment, events)
        $userDTO = $this->userService->createUser($registerData);

        // Return User model (required by Fortify contract)
        // We need to fetch the actual model since service returns DTO
        return User::findOrFail($userDTO->id);
    }
}
