<!-- resources/views/users/edit.blade.php -->
<h2>Edit User</h2>

<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
    </div>

    <div>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Leave blank to keep current password">
    </div>

    <div>
        <label for="role">Role:</label>
        <select name="role" id="role">
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit">Update</button>
</form>
